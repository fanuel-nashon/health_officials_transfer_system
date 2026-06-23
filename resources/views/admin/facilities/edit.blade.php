<x-app-layout>
    <x-slot name="header">Edit Facility</x-slot>

    <div class="mt-2 max-w-lg">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Edit: {{ $facility->name }}</h2>
            </div>
            <form method="POST" action="{{ route('facilities.update', $facility) }}" class="px-6 py-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Facility Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $facility->name) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <select id="location_id" name="location_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">— None —</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id', $facility->location_id) == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} ({{ $location->district }}, {{ $location->region }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                        Update Facility
                    </button>
                    <a href="{{ route('facilities.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
