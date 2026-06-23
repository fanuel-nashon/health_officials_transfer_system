<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Transfers;
use App\Models\User;
use App\Notifications\TransferStatusUpdatedNotification;
use App\Notifications\TransferSubmittedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function index(Request $request): View
    {
        $user  = $request->user();
        $query = Transfers::with(['user', 'fromFacility.location', 'toFacility.location']);

        if ($user->hasRole('nurse_doctor')) {
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole('facility_admin')) {
            $facilityId = optional($user->officialRecord)->facility_id;
            $query->where('from_facility_id', $facilityId);
        } elseif ($user->hasRole('district_officer')) {
            $district = optional($user->officialRecord)->district;
            $query->whereHas('fromFacility.location', fn ($q) => $q->where('district', $district));
        } elseif ($user->hasRole('region_officer')) {
            $region = optional($user->officialRecord)->region;
            $query->whereHas('fromFacility.location', fn ($q) => $q->where('region', $region));
        }

        $transfers = $query->latest()->paginate(15);

        return view('transfers.index', compact('transfers'));
    }

    public function create(Request $request): View
    {
        $user        = $request->user();
        $record      = $user->employeeRecord;
        $fromFacility= $record ? $record->facility : null;
        $facilities  = Facility::with('location')->orderBy('name')->get();

        return view('transfers.create', compact('fromFacility', 'facilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'to_facility_id' => ['required', 'exists:facilities,id'],
            'reason'         => ['required', 'string', 'min:20', 'max:2000'],
        ]);

        $record = $user->employeeRecord;
        if (! $record) {
            return back()->withErrors(['error' => 'Your employee record is not set up. Contact the administrator.']);
        }

        if ($record->facility_id == $validated['to_facility_id']) {
            return back()->withErrors(['to_facility_id' => 'Destination facility must be different from your current facility.'])->withInput();
        }

        $activeTransfer = $user->transfers()->where('level_status', 'pending')->first();
        if ($activeTransfer) {
            return back()->withErrors(['error' => 'You already have a pending transfer request.'])->withInput();
        }

        $transfer = Transfers::create([
            'user_id'          => $user->id,
            'from_facility_id' => $record->facility_id,
            'to_facility_id'   => $validated['to_facility_id'],
            'reason'           => $validated['reason'],
            'level'            => 'facility',
            'level_status'     => 'pending',
        ]);

        // Notify facility admins at the applicant's facility
        $transfer->load(['fromFacility', 'toFacility', 'user']);
        User::role('facility_admin')
            ->whereHas('officialRecord', fn ($q) => $q->where('facility_id', $record->facility_id))
            ->each(fn ($admin) => $admin->notify(new TransferSubmittedNotification($transfer)));

        return redirect()->route('transfers.index')->with('success', 'Transfer request submitted successfully.');
    }

    public function show(Transfers $transfer): View
    {
        $user = auth()->user();

        if ($user->hasRole('nurse_doctor') && $transfer->user_id !== $user->id) {
            abort(403);
        }

        $transfer->load(['user.employeeRecord', 'fromFacility.location', 'toFacility.location']);

        return view('transfers.show', compact('transfer'));
    }

    public function review(Transfers $transfer): View
    {
        $user = auth()->user();
        $this->authorizeReview($user, $transfer);

        $transfer->load(['user.employeeRecord', 'fromFacility.location', 'toFacility.location']);

        return view('transfers.review', compact('transfer'));
    }

    public function processReview(Request $request, Transfers $transfer): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeReview($user, $transfer);

        $validated = $request->validate([
            'action'  => ['required', 'in:approve,reject'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $action  = $validated['action'];
        $comment = $validated['comment'];

        $currentLevel = $transfer->level;

        if ($user->hasRole('facility_admin')) {
            $transfer->facility_comment = $comment;
            if ($action === 'approve') {
                $transfer->level        = 'district';
                $transfer->level_status = 'pending';
            } else {
                $transfer->level_status = 'rejected';
            }
        } elseif ($user->hasRole('district_officer')) {
            $transfer->district_comment = $comment;
            if ($action === 'approve') {
                $transfer->level        = 'region';
                $transfer->level_status = 'pending';
            } else {
                $transfer->level_status = 'rejected';
            }
        } elseif ($user->hasRole('region_officer')) {
            $transfer->region_comment = $comment;
            if ($action === 'approve') {
                $transfer->level        = 'ministry';
                $transfer->level_status = 'pending';
            } else {
                $transfer->level_status = 'rejected';
            }
        } elseif ($user->hasRole('ministry_official')) {
            $transfer->ministry_comment = $comment;
            $transfer->level_status     = $action === 'approve' ? 'approved' : 'rejected';
        }

        $transfer->save();
        $transfer->load(['fromFacility', 'toFacility', 'user']);

        // Notify the applicant of their transfer status
        $notifyAction = match (true) {
            $transfer->level_status === 'approved'                           => 'approved',
            $transfer->level_status === 'rejected'                           => 'rejected',
            default                                                           => 'forwarded',
        };
        $transfer->user->notify(new TransferStatusUpdatedNotification($transfer, $notifyAction, $currentLevel, $comment));

        // Notify the next level reviewers when forwarded
        if ($notifyAction === 'forwarded') {
            $this->notifyNextReviewers($transfer);
        }

        $msg = $action === 'approve'
            ? 'Transfer forwarded / approved successfully.'
            : 'Transfer rejected.';

        return redirect()->route('transfers.index')->with('success', $msg);
    }

    private function notifyNextReviewers(Transfers $transfer): void
    {
        $nextLevel = $transfer->level;
        $region    = optional(optional($transfer->fromFacility)->location)->region;
        $district  = optional(optional($transfer->fromFacility)->location)->district;

        $reviewers = match ($nextLevel) {
            'district' => User::role('district_officer')
                ->whereHas('officialRecord', fn ($q) => $q->where('district', $district))
                ->get(),
            'region'   => User::role('region_officer')
                ->whereHas('officialRecord', fn ($q) => $q->where('region', $region))
                ->get(),
            'ministry' => User::role('ministry_official')->get(),
            default    => collect(),
        };

        $reviewers->each(fn ($reviewer) => $reviewer->notify(new TransferSubmittedNotification($transfer)));
    }

    private function authorizeReview($user, Transfers $transfer): void
    {
        if ($user->hasRole('facility_admin')) {
            $facilityId = optional($user->officialRecord)->facility_id;
            if ($transfer->level !== 'facility' || $transfer->level_status !== 'pending' || $transfer->from_facility_id != $facilityId) {
                abort(403);
            }
        } elseif ($user->hasRole('district_officer')) {
            if ($transfer->level !== 'district' || $transfer->level_status !== 'pending') {
                abort(403);
            }
            $district = optional($user->officialRecord)->district;
            if (optional(optional($transfer->fromFacility)->location)->district !== $district) {
                abort(403);
            }
        } elseif ($user->hasRole('region_officer')) {
            if ($transfer->level !== 'region' || $transfer->level_status !== 'pending') {
                abort(403);
            }
            $region = optional($user->officialRecord)->region;
            if (optional(optional($transfer->fromFacility)->location)->region !== $region) {
                abort(403);
            }
        } elseif ($user->hasRole('ministry_official')) {
            if ($transfer->level !== 'ministry' || $transfer->level_status !== 'pending') {
                abort(403);
            }
        } elseif (! $user->hasRole('admin')) {
            abort(403);
        }
    }
}
