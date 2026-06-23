<x-app-layout>
    <x-slot name="header">Locations</x-slot>

    <div class="mt-2">
        <div class="mb-4 flex justify-end">
            <a href="{{ route('locations.create') }}"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Location
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Locations (Districts / Regions)</h2>
            </div>
            @if($locations->isEmpty())
                <p class="px-6 py-10 text-center text-sm text-gray-400">No locations registered.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Name</th>
                                <th class="px-6 py-3 text-left">District</th>
                                <th class="px-6 py-3 text-left">Region</th>
                                <th class="px-6 py-3 text-left">Facilities</th>
                                <th class="px-6 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($locations as $location)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $location->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $location->district }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $location->region }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $location->facilities_count }}</td>
                                    <td class="px-6 py-3 flex gap-3">
                                        <a href="{{ route('locations.edit', $location) }}"
                                           class="text-green-600 hover:text-green-800 text-xs font-medium">Edit</a>
                                        <form method="POST" action="{{ route('locations.destroy', $location) }}"
                                              onsubmit="return confirm('Delete this location?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $locations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
