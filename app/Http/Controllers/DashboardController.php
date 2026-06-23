<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Transfers;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return view('admin.index', [
                'totalUsers'      => User::count(),
                'totalFacilities' => Facility::count(),
                'totalTransfers'  => Transfers::count(),
                'pendingCount'    => Transfers::where('level_status', 'pending')->count(),
                'approvedCount'   => Transfers::where('level', 'ministry')->where('level_status', 'approved')->count(),
                'rejectedCount'   => Transfers::where('level_status', 'rejected')->count(),
                'recentTransfers' => Transfers::with(['user', 'fromFacility', 'toFacility'])->latest()->limit(10)->get(),
            ]);
        }

        if ($user->hasRole('nurse_doctor')) {
            $transfers = $user->transfers()->with(['fromFacility', 'toFacility'])->latest()->get();

            return view('nurse.dashboard', [
                'transfers'    => $transfers,
                'activeCount'  => $transfers->filter->isActive()->count(),
                'approvedCount'=> $transfers->filter->isFinallyApproved()->count(),
                'rejectedCount'=> $transfers->filter->isRejected()->count(),
            ]);
        }

        if ($user->hasRole('facility_admin')) {
            $facilityId = optional($user->officialRecord)->facility_id;
            $pending    = Transfers::with(['user', 'toFacility'])
                ->where('level', 'facility')
                ->where('level_status', 'pending')
                ->where('from_facility_id', $facilityId)
                ->latest()->get();
            $all        = Transfers::with(['user', 'fromFacility', 'toFacility'])
                ->where('from_facility_id', $facilityId)
                ->latest()->limit(20)->get();

            return view('facility-admin.dashboard', [
                'pending'        => $pending,
                'recentTransfers'=> $all,
                'pendingCount'   => $pending->count(),
            ]);
        }

        if ($user->hasRole('district_officer')) {
            $district = optional($user->officialRecord)->district;
            $pending  = Transfers::with(['user', 'fromFacility.location', 'toFacility'])
                ->where('level', 'district')
                ->where('level_status', 'pending')
                ->whereHas('fromFacility.location', fn ($q) => $q->where('district', $district))
                ->latest()->get();

            return view('district.dashboard', [
                'pending'      => $pending,
                'pendingCount' => $pending->count(),
                'district'     => $district,
            ]);
        }

        if ($user->hasRole('region_officer')) {
            $region  = optional($user->officialRecord)->region;
            $pending = Transfers::with(['user', 'fromFacility.location', 'toFacility'])
                ->where('level', 'region')
                ->where('level_status', 'pending')
                ->whereHas('fromFacility.location', fn ($q) => $q->where('region', $region))
                ->latest()->get();

            return view('region.dashboard', [
                'pending'      => $pending,
                'pendingCount' => $pending->count(),
                'region'       => $region,
            ]);
        }

        if ($user->hasRole('ministry_official')) {
            $pending  = Transfers::with(['user', 'fromFacility', 'toFacility'])
                ->where('level', 'ministry')->where('level_status', 'pending')->latest()->get();
            $approved = Transfers::where('level', 'ministry')->where('level_status', 'approved')->count();
            $rejected = Transfers::where('level_status', 'rejected')->count();

            return view('ministry.dashboard', [
                'pending'        => $pending,
                'pendingCount'   => $pending->count(),
                'approvedCount'  => $approved,
                'rejectedCount'  => $rejected,
                'totalTransfers' => Transfers::count(),
            ]);
        }

        return view('dashboard');
    }
}
