<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Student Dashboard') · {{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen antialiased">
        <div class="relative isolate min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-56 bg-[radial-gradient(circle_at_top,rgba(15,23,42,0.08),transparent_62%)]"></div>
            <div class="relative mx-auto flex min-h-screen max-w-[1600px] gap-4 px-4 py-4 sm:px-6 lg:px-8 lg:py-6">
                @include('layouts.partials.sidebar')

                <div class="flex min-w-0 flex-1 flex-col gap-6">
                    @include('layouts.partials.header')

                    @if (session('success'))
                        <div class="surface-panel dashboard-ring rounded-[28px] border border-white/70 px-5 py-4 text-sm text-slate-700">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.25 7.25a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.295 6.543-6.545a1 1 0 011.415 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-heading text-sm font-semibold text-slate-900">Directory updated</p>
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <main class="pb-6">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
