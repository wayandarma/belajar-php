@php
    $pageTitle = trim($__env->yieldContent('title', 'Student Dashboard'));
    $pageSummary = trim($__env->yieldContent('summary', 'Coordinate admissions, directory updates, and record quality from one place.'));
@endphp

<header class="surface-panel dashboard-ring sticky top-4 z-20 rounded-[28px] border border-white/70 px-5 py-4 sm:px-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Academic operations</p>
            <div class="mt-2 flex flex-wrap items-center gap-3">
                <h1 class="font-heading text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">{{ $pageTitle }}</h1>
                <span class="inline-flex items-center rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-700">Updated {{ now()->format('d M Y') }}</span>
            </div>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ $pageSummary }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('students.index') }}" class="inline-flex cursor-pointer items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:border-slate-300 hover:bg-slate-50">
                Directory
            </a>
            <a href="{{ route('students.create') }}" class="inline-flex cursor-pointer items-center justify-center rounded-full bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-slate-800">
                New Student
            </a>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap gap-3 lg:hidden">
        <a href="{{ route('students.index') }}" class="inline-flex cursor-pointer items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:border-slate-300 hover:bg-slate-50">Dashboard</a>
        <a href="{{ route('students.create') }}" class="inline-flex cursor-pointer items-center rounded-full border border-slate-950 bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-slate-800">Register</a>
    </div>
</header>
