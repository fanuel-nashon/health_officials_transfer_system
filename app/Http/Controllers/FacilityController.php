<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = \App\Models\Facility::with('location')->orderBy('name')->paginate(20);

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        $locations = \App\Models\Location::orderBy('name')->get();

        return view('admin.facilities.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'location_id' => ['nullable', 'exists:locations,id'],
        ]);

        Facility::create($validated);

        return redirect()->route('facilities.index')->with('success', 'Facility created successfully.');
    }

    public function show(Facility $facility)
    {
        $facility->load(['location', 'employeeRecords.user', 'officialRecords.user']);

        return view('admin.facilities.show', compact('facility'));
    }

    public function edit(Facility $facility)
    {
        $locations = \App\Models\Location::orderBy('name')->get();

        return view('admin.facilities.edit', compact('facility', 'locations'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'location_id' => ['nullable', 'exists:locations,id'],
        ]);

        $facility->update($validated);

        return redirect()->route('facilities.index')->with('success', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()->route('facilities.index')->with('success', 'Facility deleted.');
    }
}
