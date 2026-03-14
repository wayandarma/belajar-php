@extends('layouts.dashboard')

@section('title', 'Edit Student')
@section('summary', 'Update academic details, contact information, and directory status without losing the surrounding student context.')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
        <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6 sm:px-8 sm:py-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Record maintenance</p>
                    <h2 class="font-heading mt-3 text-3xl font-semibold text-slate-950">Refine {{ $student->name }}’s profile.</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Changes apply instantly to the directory table, dashboard metrics, and the individual student detail view.</p>
                </div>

                <a href="{{ route('students.show', $student) }}" class="inline-flex cursor-pointer items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:border-slate-300 hover:bg-slate-50">
                    View profile
                </a>
            </div>

            <form action="{{ route('students.update', $student) }}" method="POST" class="mt-8">
                @csrf
                @method('PUT')

                @include('students.partials.form-fields', ['submitLabel' => 'Save changes'])
            </form>
        </section>

        <aside class="space-y-6">
            <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Current snapshot</p>
                <div class="mt-5 flex items-center gap-4">
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-slate-950 text-base font-semibold text-white">{{ $student->initials() }}</span>
                    <div class="min-w-0">
                        <p class="font-heading truncate text-xl font-semibold text-slate-950">{{ $student->name }}</p>
                        <p class="truncate text-sm text-slate-500">{{ $student->email }}</p>
                    </div>
                </div>

                <dl class="mt-6 space-y-4 text-sm text-slate-600">
                    <div class="flex items-center justify-between gap-3 rounded-[22px] border border-slate-200 bg-white/70 px-4 py-3">
                        <dt>Status</dt>
                        <dd @class([
                            'inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-emerald-500/10 text-emerald-700' => $student->isActive(),
                            'bg-slate-900/10 text-slate-700' => ! $student->isActive(),
                        ])>{{ ucfirst($student->status) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3 rounded-[22px] border border-slate-200 bg-white/70 px-4 py-3">
                        <dt>Major</dt>
                        <dd class="font-medium text-slate-900">{{ $student->major }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3 rounded-[22px] border border-slate-200 bg-white/70 px-4 py-3">
                        <dt>Enrollment</dt>
                        <dd class="font-medium text-slate-900">{{ $student->enrollment_year }}</dd>
                    </div>
                </dl>
            </section>

            <section class="metric-gradient dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Editing principle</p>
                <h3 class="font-heading mt-2 text-2xl font-semibold text-slate-950">Keep identity fields stable.</h3>
                <p class="mt-4 text-sm leading-7 text-slate-600">Preserving the same email for the current student is allowed, while the unique validation rule still protects the directory from duplicates.</p>
            </section>
        </aside>
    </div>
@endsection
