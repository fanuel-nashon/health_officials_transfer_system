<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Transfer System') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <script>
            tailwind.config = {
                theme: { extend: { fontFamily: { sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex h-screen overflow-hidden">
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main area --}}
            <div class="flex-1 flex flex-col min-w-0 overflow-auto">
                {{-- Top bar --}}
                <header class="bg-white shadow-sm border-b border-gray-200 shrink-0">
                    <div class="flex items-center justify-between px-6 py-3">
                        <div>
                            @isset($header)
                                <h1 class="text-lg font-semibold text-gray-800">{{ $header }}</h1>
                            @endisset
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-400">{{ now()->format('l, d M Y') }}</span>

                            {{-- Notification Bell --}}
                            @php $unread = Auth::user()->unreadNotifications->count(); @endphp
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.outside="open = false"
                                        class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if($unread > 0)
                                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                    @endif
                                </button>

                                {{-- Dropdown --}}
                                <div x-show="open" x-transition
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                        <span class="text-sm font-semibold text-gray-700">Notifications</span>
                                        @if($unread > 0)
                                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                                @csrf
                                                <button class="text-xs text-green-600 hover:text-green-800">Mark all read</button>
                                            </form>
                                        @endif
                                    </div>

                                    @php $notifications = Auth::user()->notifications()->latest()->limit(8)->get(); @endphp

                                    @if($notifications->isEmpty())
                                        <p class="px-4 py-6 text-center text-sm text-gray-400">No notifications yet.</p>
                                    @else
                                        <ul class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                                            @foreach($notifications as $n)
                                                <li>
                                                    <a href="{{ route('notifications.read', $n->id) }}"
                                                       class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition {{ $n->read_at ? '' : 'bg-green-50' }}">
                                                        <div class="shrink-0 mt-0.5">
                                                            <div class="w-2 h-2 rounded-full mt-1 {{ $n->read_at ? 'bg-gray-300' : 'bg-green-500' }}"></div>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $n->data['title'] }}</p>
                                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $n->data['body'] }}</p>
                                                            <p class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Flash messages --}}
                <div class="px-6 pt-4">
                    @if(session('success'))
                        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif
                </div>

                {{-- Page content --}}
                <main class="flex-1 px-6 pb-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
