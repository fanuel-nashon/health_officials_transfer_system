<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Transfers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Transfers::with(['user', 'fromFacility.location', 'toFacility.location']);

        if ($user->hasRole('facility_admin')) {
            $facilityId = optional($user->officialRecord)->facility_id;
            $query->where('from_facility_id', $facilityId);
        } elseif ($user->hasRole('district_officer')) {
            $district = optional($user->officialRecord)->district;
            $query->whereHas('fromFacility.location', fn ($q) => $q->where('district', $district));
        } elseif ($user->hasRole('region_officer')) {
            $region = optional($user->officialRecord)->region;
            $query->whereHas('fromFacility.location', fn ($q) => $q->where('region', $region));
        }

        $transfers = $query->latest()->get();

        $stats = [
            'total'    => $transfers->count(),
            'pending'  => $transfers->where('level_status', 'pending')->count(),
            'approved' => $transfers->filter->isFinallyApproved()->count(),
            'rejected' => $transfers->filter->isRejected()->count(),
        ];

        $byLevel = [
            'facility' => $transfers->where('level', 'facility')->where('level_status', 'pending')->count(),
            'district' => $transfers->where('level', 'district')->where('level_status', 'pending')->count(),
            'region'   => $transfers->where('level', 'region')->where('level_status', 'pending')->count(),
            'ministry' => $transfers->where('level', 'ministry')->where('level_status', 'pending')->count(),
        ];

        return view('reports.index', compact('transfers', 'stats', 'byLevel'));
    }
}
