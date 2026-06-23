<aside class="w-64 bg-green-900 text-green-50 flex flex-col min-h-screen shrink-0">
    {{-- Brand --}}
    <div class="px-6 py-5 border-b border-green-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-green-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-semibold leading-tight">Transfer System</div>
                <div class="text-xs text-green-400 leading-tight">Health Ministry</div>
            </div>
        </a>
    </div>

    {{-- User info --}}
    <div class="px-6 py-4 border-b border-green-800">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-green-700 flex items-center justify-center text-xs font-bold text-white shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <div class="text-sm font-medium truncate">{{ Auth::user()->name }}</div>
                <div class="text-xs text-green-400 truncate">{{ Auth::user()->role_display }}</div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @php $current = request()->routeIs(...) @endphp

        <p class="px-3 mb-1 text-xs font-semibold text-green-600 uppercase tracking-wider">Navigation</p>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('dashboard') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Nurse/Doctor links --}}
        @role('nurse_doctor')
        <a href="{{ route('transfers.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('transfers.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            My Transfers
        </a>
        <a href="{{ route('transfers.create') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('transfers.create') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Request
        </a>
        @endrole

        {{-- Review roles --}}
        @role('facility_admin|district_officer|region_officer|ministry_official')
        <a href="{{ route('transfers.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('transfers.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            All Transfers
        </a>
        @endrole

        @can('view_reports')
        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('reports.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Reports
        </a>
        @endcan

        {{-- Admin links --}}
        @role('admin')
        <div class="mt-4">
            <p class="px-3 mb-1 text-xs font-semibold text-green-600 uppercase tracking-wider">Administration</p>
        </div>
        <a href="{{ route('admin.users') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('admin.users*') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Users
        </a>
        <a href="{{ route('facilities.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('facilities.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Facilities
        </a>
        <a href="{{ route('locations.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('locations.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Locations
        </a>
        <a href="{{ route('transfers.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('transfers.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            All Transfers
        </a>
        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition {{ request()->routeIs('reports.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Reports
        </a>
        @endrole
    </nav>

    {{-- Account --}}
    <div class="px-3 py-4 border-t border-green-800 space-y-1">
        <p class="px-3 mb-1 text-xs font-semibold text-green-600 uppercase tracking-wider">Account</p>
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-green-200 hover:bg-green-800 hover:text-white transition">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-sm text-green-200 hover:bg-red-600 hover:text-white transition text-left">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
