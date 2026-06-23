<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transfer Management System — Government Health Sector</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-white text-gray-800">

    {{-- Navbar --}}
    <header class="border-b border-gray-100 bg-white sticky top-0 z-50 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-green-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800 leading-tight">Transfer Management System</p>
                    <p class="text-xs text-gray-400 leading-tight">Government Health Sector — Tanzania</p>
                </div>
            </div>
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-600 hover:text-green-700 font-medium transition">
                        Log In
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                        Register
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-green-800 via-green-700 to-emerald-600 text-white py-24 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block bg-white/10 border border-white/20 text-green-100 text-xs font-semibold px-3 py-1 rounded-full mb-6 uppercase tracking-widest">
                Ministry of Health — Tanzania
            </span>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-5">
                Nurses &amp; Doctors<br>Transfer Management System
            </h1>
            <p class="text-green-100 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                A centralized digital platform for submitting, reviewing, and tracking transfer requests of healthcare professionals across government health facilities.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}"
                       class="bg-white text-green-700 hover:bg-green-50 font-semibold px-8 py-3.5 rounded-xl transition text-sm">
                        Create an Account
                    </a>
                    <a href="{{ route('login') }}"
                       class="border border-white/40 hover:bg-white/10 text-white font-medium px-8 py-3.5 rounded-xl transition text-sm">
                        Sign In
                    </a>
                @else
                    <a href="{{ route('dashboard') }}"
                       class="bg-white text-green-700 hover:bg-green-50 font-semibold px-8 py-3.5 rounded-xl transition text-sm">
                        Go to Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Stats strip --}}
    <div class="bg-green-900 text-white">
        <div class="max-w-6xl mx-auto px-6 py-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @foreach([
                ['label' => 'Health Facilities', 'value' => 'Nationwide'],
                ['label' => 'Transfer Requests', 'value' => 'Digitized'],
                ['label' => 'Approval Levels', 'value' => '4 Tiers'],
                ['label' => 'Real-time Tracking', 'value' => 'Always On'],
            ] as $stat)
                <div>
                    <p class="text-xl font-bold text-green-300">{{ $stat['value'] }}</p>
                    <p class="text-xs text-green-400 mt-0.5">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Features --}}
    <section class="py-20 px-6 bg-gray-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Everything You Need</h2>
                <p class="text-gray-500 max-w-xl mx-auto text-sm">Designed for nurses, doctors, and health administrators across Tanzania's government health sector.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Online Applications', 'desc' => 'Submit transfer requests from any device. No paper forms, no lost documents.'],
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Multi-level Approvals', 'desc' => 'Structured workflow: Facility → District → Region → Ministry. Every step tracked.'],
                    ['icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'title' => 'Real-time Status', 'desc' => 'Applicants track their request at every approval stage, from submission to final decision.'],
                    ['icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Reports & Analytics', 'desc' => 'Generate workforce distribution reports to support evidence-based planning.'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Role-based Access', 'desc' => 'Each user sees only what they need. Secure, role-controlled access at every level.'],
                    ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'title' => 'Workforce Equity', 'desc' => 'Reduce rural-urban staffing imbalances through transparent, data-driven transfers.'],
                ] as $f)
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-20 px-6 bg-white">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">How the Transfer Process Works</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto">A transparent, step-by-step workflow from submission to final approval.</p>
            </div>
            <div class="grid md:grid-cols-4 gap-6">
                @foreach([
                    ['step' => '01', 'title' => 'Submit Request', 'desc' => 'Nurse or Doctor fills an online transfer form with reason and requested destination.'],
                    ['step' => '02', 'title' => 'Facility Review', 'desc' => 'Facility Administrator reviews and either recommends or rejects the application.'],
                    ['step' => '03', 'title' => 'District & Region', 'desc' => 'District and Regional Officers review and forward the transfer upward.'],
                    ['step' => '04', 'title' => 'Ministry Approval', 'desc' => 'Ministry Official gives the final approval or rejection decision.'],
                ] as $step)
                    <div class="text-center">
                        <div class="w-14 h-14 rounded-2xl bg-green-600 text-white flex items-center justify-center text-lg font-bold mx-auto mb-4">
                            {{ $step['step'] }}
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2 text-sm">{{ $step['title'] }}</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Roles --}}
    <section class="py-20 px-6 bg-green-50">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Who Uses the System?</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-5">
                @foreach([
                    ['role' => 'Nurses & Doctors', 'desc' => 'Submit transfer requests, upload documents, and track status in real time.', 'badge' => 'Applicant'],
                    ['role' => 'Facility Administrators', 'desc' => 'Review requests from their facility and recommend or reject at the first level.', 'badge' => 'Reviewer L1'],
                    ['role' => 'District & Region Officers', 'desc' => 'Oversee transfers across their district or region and forward to the next level.', 'badge' => 'Reviewer L2 & L3'],
                    ['role' => 'Ministry Officials', 'desc' => 'Grant final approvals and monitor nationwide workforce distribution.', 'badge' => 'Final Approver'],
                    ['role' => 'System Administrator', 'desc' => 'Manage users, facilities, and locations. Full system oversight.', 'badge' => 'Admin'],
                    ['role' => 'Any Device, Anywhere', 'desc' => 'Web-based system accessible from computers, tablets, and smartphones.', 'badge' => 'Accessible'],
                ] as $r)
                    <div class="bg-white rounded-2xl p-5 border border-green-100 shadow-sm">
                        <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-0.5 rounded-full mb-3">{{ $r['badge'] }}</span>
                        <h3 class="font-semibold text-gray-800 mb-1.5 text-sm">{{ $r['role'] }}</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $r['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-6 bg-gradient-to-r from-green-700 to-emerald-600 text-white text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold mb-4">Ready to get started?</h2>
            <p class="text-green-100 mb-8 text-sm leading-relaxed">
                Register your account to access the system. Nurses, doctors, and health officials are all welcome.
            </p>
            @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}"
                       class="bg-white text-green-700 hover:bg-green-50 font-semibold px-8 py-3.5 rounded-xl transition text-sm">
                        Create Account
                    </a>
                    <a href="{{ route('login') }}"
                       class="border border-white/40 hover:bg-white/10 text-white font-medium px-8 py-3.5 rounded-xl transition text-sm">
                        Sign In
                    </a>
                </div>
            @else
                <a href="{{ route('dashboard') }}"
                   class="bg-white text-green-700 hover:bg-green-50 font-semibold px-8 py-3.5 rounded-xl transition text-sm inline-block">
                    Go to Dashboard
                </a>
            @endguest
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-green-900 text-green-300 py-8 px-6 text-center text-sm">
        <p>&copy; {{ date('Y') }} Nurses &amp; Doctors Transfer Management System — Government Health Sector, Tanzania.</p>
        <p class="text-green-500 text-xs mt-1">Designed to improve transparency and equity in healthcare workforce management.</p>
    </footer>

</body>
</html>
