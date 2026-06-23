<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ old('role', '') }}' }">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" x-model="role" required
                    class="block mt-1 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm text-sm">
                <option value="">— Select your role —</option>
                <option value="nurse_doctor"     {{ old('role') === 'nurse_doctor'      ? 'selected' : '' }}>Nurse / Doctor</option>
                <option value="facility_admin"   {{ old('role') === 'facility_admin'    ? 'selected' : '' }}>Facility Administrator</option>
                <option value="district_officer" {{ old('role') === 'district_officer'  ? 'selected' : '' }}>District Health Officer</option>
                <option value="region_officer"   {{ old('role') === 'region_officer'    ? 'selected' : '' }}>Regional Health Officer</option>
                <option value="ministry_official"{{ old('role') === 'ministry_official' ? 'selected' : '' }}>Ministry Official</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Title / Designation -->
        <div class="mt-4">
            <x-input-label for="title" :value="__('Title / Designation')" />
            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')"
                          required placeholder="e.g. Senior Nurse, Medical Doctor, District Health Officer" />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <!-- Facility (for nurse_doctor and facility_admin) -->
        <div class="mt-4" x-show="role === 'nurse_doctor' || role === 'facility_admin'" x-cloak>
            <x-input-label for="facility_id" :value="__('Current Facility')" />
            <select id="facility_id" name="facility_id"
                    class="block mt-1 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm text-sm">
                <option value="">— Select facility —</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                        {{ $facility->name }}
                        @if($facility->location) ({{ $facility->location->district }}, {{ $facility->location->region }}) @endif
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('facility_id')" class="mt-2" />
        </div>

        <!-- District (for district_officer) -->
        <div class="mt-4" x-show="role === 'district_officer'" x-cloak>
            <x-input-label for="district" :value="__('District')" />
            <x-text-input id="district" class="block mt-1 w-full" type="text" name="district" :value="old('district')"
                          placeholder="e.g. Kinondoni" />
            <x-input-error :messages="$errors->get('district')" class="mt-2" />
        </div>

        <!-- Region (for district_officer and region_officer) -->
        <div class="mt-4" x-show="role === 'district_officer' || role === 'region_officer'" x-cloak>
            <x-input-label for="region" :value="__('Region')" />
            <x-text-input id="region" class="block mt-1 w-full" type="text" name="region" :value="old('region')"
                          placeholder="e.g. Dar es Salaam" />
            <x-input-error :messages="$errors->get('region')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-password-input id="password" name="password" autocomplete="new-password" class="mt-1" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-password-input id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="mt-1" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ms-4">{{ __('Register') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
