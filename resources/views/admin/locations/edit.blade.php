<x-app-layout>
    <x-slot name="header">Edit Location</x-slot>

    <div class="mt-2 max-w-lg">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Edit: {{ $location->name }}</h2>
            </div>
            <form method="POST" action="{{ route('locations.update', $location) }}" class="px-6 py-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Location Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $location->name) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District <span class="text-red-500">*</span></label>
                    <input type="text" id="district" name="district" value="{{ old('district', $location->district) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500 @error('district') border-red-400 @enderror">
                    @error('district') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region <span class="text-red-500">*</span></label>
                    <input type="text" id="region" name="region" value="{{ old('region', $location->region) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500 @error('region') border-red-400 @enderror">
                    @error('region') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                        Update Location
                    </button>
                    <a href="{{ route('locations.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
