<?php

namespace App\Http\Controllers;

use App\Models\EmployeeRecords;
use App\Models\Facility;
use App\Models\OfficialRecords;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function users(): View
    {
        $users = User::with('roles')->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function createUser(): View
    {
        $roles      = Role::whereNotIn('name', ['admin'])->orderBy('name')->get();
        $facilities = Facility::with('location')->orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'facilities'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $allowedRoles = ['nurse_doctor', 'facility_admin', 'district_officer', 'region_officer', 'ministry_official'];

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'role'        => ['required', 'in:' . implode(',', $allowedRoles)],
            'title'       => ['required', 'string', 'max:255'],
            'facility_id' => ['required_if:role,nurse_doctor', 'required_if:role,facility_admin', 'nullable', 'exists:facilities,id'],
            'district'    => ['required_if:role,district_officer', 'nullable', 'string', 'max:255'],
            'region'      => ['required_if:role,district_officer', 'required_if:role,region_officer', 'nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        if ($validated['role'] === 'nurse_doctor') {
            EmployeeRecords::create([
                'user_id'     => $user->id,
                'title'       => $validated['title'],
                'facility_id' => $validated['facility_id'],
            ]);
        } else {
            OfficialRecords::create([
                'user_id'     => $user->id,
                'title'       => $validated['title'],
                'facility_id' => $validated['facility_id'] ?? null,
                'district'    => $validated['district'] ?? null,
                'region'      => $validated['region'] ?? null,
            ]);
        }

        return redirect()->route('admin.users')->with('success', "User {$user->name} created successfully.");
    }

    public function showUser(User $user): View
    {
        $user->load(['roles', 'employeeRecord.facility', 'officialRecord.facility', 'transfers']);
        $roles = Role::all();

        return view('admin.users.show', compact('user', 'roles'));
    }

    public function assignRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->syncRoles([$validated['role']]);

        return back()->with('success', 'Role assigned successfully.');
    }
}
