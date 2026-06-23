<x-app-layout>
    <x-slot name="header">New Transfer Request</x-slot>

    <div class="mt-2 max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Submit Transfer Application</h2>
                <p class="text-sm text-gray-500 mt-1">Fill in the details below to submit your transfer request.</p>
            </div>

            <form method="POST" action="{{ route('transfers.store') }}" class="px-6 py-6 space-y-5">
                @csrf

                {{-- Current facility (read-only) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Facility</label>
                    <div class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600">
                        @if($fromFacility)
                            {{ $fromFacility->name }}
                            @if($fromFacility->location)
                                <span class="text-gray-400"> — {{ $fromFacility->location->district }}, {{ $fromFacility->location->region }}</span>
                            @endif
                        @else
                            <span class="text-red-500">Not set. Contact administrator.</span>
                        @endif
                    </div>
                </div>

                {{-- Destination facility --}}
                <div>
                    <label for="to_facility_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Requested Destination Facility <span class="text-red-500">*</span>
                    </label>
                    <select id="to_facility_id" name="to_facility_id" required
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500 @error('to_facility_id') border-red-400 @enderror">
                        <option value="">— Select a facility —</option>
                        @foreach($facilities as $facility)
                            @if($fromFacility && $facility->id == $fromFacility->id)
                                @continue
                            @endif
                            <option value="{{ $facility->id }}" {{ old('to_facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                                @if($facility->location)
                                    ({{ $facility->location->district }}, {{ $facility->location->region }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('to_facility_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Reason --}}
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                        Reason for Transfer <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" name="reason" rows="5" required
                              placeholder="Explain why you are requesting this transfer (minimum 20 characters)..."
                              class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500 @error('reason') border-red-400 @enderror">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">Provide a clear and honest reason. This will be reviewed by facility administrators and officers.</p>
                </div>

                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                        Submit Request
                    </button>
                    <a href="{{ route('transfers.index') }}"
                       class="text-sm text-gray-500 hover:text-gray-700 transition">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
