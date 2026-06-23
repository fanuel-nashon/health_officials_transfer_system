<x-app-layout>
    <x-slot name="header">Create New User</x-slot>

    <div class="mt-2 max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
             x-data="{ role: '' }">

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="role" name="role" x-model="role" required
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">— Select role —</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}" {{ old('role') === $r->name ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $r->name)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title / Designation</label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" required
                           placeholder="e.g. Senior Nurse, Facility Administrator, District Health Officer"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Facility (nurse_doctor / facility_admin) --}}
                <div x-show="role === 'nurse_doctor' || role === 'facility_admin'" x-cloak>
                    <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-1">Facility</label>
                    <select id="facility_id" name="facility_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">— Select facility —</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                                @if($facility->location) ({{ $facility->location->district }}, {{ $facility->location->region }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('facility_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- District (district_officer) --}}
                <div x-show="role === 'district_officer'" x-cloak>
                    <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District</label>
                    <input id="district" name="district" type="text" value="{{ old('district') }}"
                           placeholder="e.g. Kinondoni"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                    @error('district') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Region (district_officer / region_officer) --}}
                <div x-show="role === 'district_officer' || role === 'region_officer'" x-cloak>
                    <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                    <input id="region" name="region" type="text" value="{{ old('region') }}"
                           placeholder="e.g. Dar es Salaam"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                    @error('region') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <x-password-input id="password" name="password" autocomplete="new-password" :hint="true" required />
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <x-password-input id="password_confirmation" name="password_confirmation" autocomplete="new-password" required />
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('admin.users') }}" class="text-sm text-gray-500 hover:text-gray-700">← Cancel</a>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
