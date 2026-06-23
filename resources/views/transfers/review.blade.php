<x-app-layout>
    <x-slot name="header">Review Transfer #{{ $transfer->id }}</x-slot>

    <div class="mt-2 max-w-3xl space-y-5">

        {{-- Applicant & Transfer Info --}}
        <div class="grid grid-cols-2 gap-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-600 mb-3">Applicant</h3>
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $transfer->user->name }}</dd></div>
                    <div><dt class="text-gray-500">Title</dt><dd>{{ optional($transfer->user->employeeRecord)->title ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">Email</dt><dd>{{ $transfer->user->email }}</dd></div>
                </dl>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-600 mb-3">Transfer Route</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500">From</dt>
                        <dd class="font-medium">{{ optional($transfer->fromFacility)->name ?? '—' }}</dd>
                        <dd class="text-xs text-gray-400">{{ optional(optional($transfer->fromFacility)->location)->district }}, {{ optional(optional($transfer->fromFacility)->location)->region }}</dd>
                    </div>
                    <div class="pt-1">
                        <dt class="text-gray-500">To</dt>
                        <dd class="font-medium">{{ optional($transfer->toFacility)->name ?? '—' }}</dd>
                        <dd class="text-xs text-gray-400">{{ optional(optional($transfer->toFacility)->location)->district }}, {{ optional(optional($transfer->toFacility)->location)->region }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Reason --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">Applicant's Reason</h3>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $transfer->reason }}</p>
        </div>

        {{-- Previous comments --}}
        @if($transfer->facility_comment || $transfer->district_comment || $transfer->region_comment)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-600 mb-3">Previous Review Comments</h3>
            <div class="space-y-3">
                @if($transfer->facility_comment)
                    <div class="text-sm"><span class="font-medium text-gray-600">Facility Admin:</span> <span class="text-gray-700 italic">"{{ $transfer->facility_comment }}"</span></div>
                @endif
                @if($transfer->district_comment)
                    <div class="text-sm"><span class="font-medium text-gray-600">District Officer:</span> <span class="text-gray-700 italic">"{{ $transfer->district_comment }}"</span></div>
                @endif
                @if($transfer->region_comment)
                    <div class="text-sm"><span class="font-medium text-gray-600">Region Officer:</span> <span class="text-gray-700 italic">"{{ $transfer->region_comment }}"</span></div>
                @endif
            </div>
        </div>
        @endif

        {{-- Review form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-600 mb-4">Your Decision</h3>

            <form method="POST" action="{{ route('transfers.process-review', $transfer) }}" class="space-y-4">
                @csrf

                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
                        Comment / Remarks <span class="text-red-500">*</span>
                    </label>
                    <textarea id="comment" name="comment" rows="4" required
                              placeholder="Provide your official remarks on this transfer request..."
                              class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500 @error('comment') border-red-400 @enderror">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @if($errors->any())
                    <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">
                        @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                    </div>
                @endif

                <div class="flex items-center gap-3 pt-1">
                    @role('ministry_official')
                    <button type="submit" name="action" value="approve"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                        Approve (Final)
                    </button>
                    @else
                    <button type="submit" name="action" value="approve"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                        Recommend / Forward
                    </button>
                    @endrole

                    <button type="submit" name="action" value="reject"
                            onclick="return confirm('Are you sure you want to reject this transfer?')"
                            class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                        Reject
                    </button>

                    <a href="{{ route('transfers.show', $transfer) }}"
                       class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
