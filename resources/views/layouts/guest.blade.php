<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Transfer System') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">

        {{-- Left branding panel --}}
        <div class="hidden lg:flex lg:w-5/12 bg-gradient-to-br from-green-800 via-green-700 to-emerald-600 flex-col justify-between p-12 text-white">
            <div>
                <a href="/" class="flex items-center gap-3 mb-16">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-sm leading-tight">Transfer Management System</p>
                        <p class="text-green-200 text-xs leading-tight">Government Health Sector</p>
                    </div>
                </a>

                <h1 class="text-3xl font-bold leading-snug mb-4">
                    Streamlining Healthcare<br>Workforce Transfers
                </h1>
                <p class="text-green-100 text-sm leading-relaxed max-w-xs">
                    A digital platform that brings transparency, fairness, and efficiency to the transfer of nurses and doctors across Tanzania's health facilities.
                </p>
            </div>

            <div class="space-y-4">
                @foreach([
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'Transparent approval workflow'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'text' => 'Role-based access for all stakeholders'],
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'text' => 'Real-time request tracking'],
                ] as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-sm text-green-100">{{ $item['text'] }}</span>
                    </div>
                @endforeach

                <div class="pt-4 border-t border-white/20 text-xs text-green-300">
                    &copy; {{ date('Y') }} Ministry of Health — Tanzania
                </div>
            </div>
        </div>

        {{-- Right form panel --}}
        <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 bg-gray-50">
            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 text-center">
                <div class="w-12 h-12 rounded-xl bg-green-600 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-800">Transfer Management System</p>
                <p class="text-xs text-gray-400">Government Health Sector</p>
            </div>

            <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-gray-400 text-center">
                <a href="/" class="hover:text-green-600 transition">← Back to home</a>
            </p>
        </div>
    </div>
</body>
</html>
