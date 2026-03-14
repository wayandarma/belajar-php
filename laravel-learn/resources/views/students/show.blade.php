@extends('layouts.dashboard')

@section('title', 'Student Profile')
@section('summary', 'Review a single student record with clear academic, contact, and status details before editing or removing it.')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-[32px] bg-slate-950 px-6 py-8 text-white shadow-2xl shadow-slate-950/15 sm:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-start gap-5">
                        <span class="inline-flex h-20 w-20 shrink-0 items-center justify-center rounded-[28px] bg-white/10 text-2xl font-semibold text-white">{{ $student->initials() }}</span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-300">Student profile</p>
                            <h2 class="font-heading mt-3 text-3xl font-semibold sm:text-4xl">{{ $student->name }}</h2>
                            <p class="mt-2 text-sm text-slate-300">{{ $student->email }}</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-slate-200">{{ $student->major }}</span>
                                <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-slate-200">Enrollment {{ $student->enrollment_year }}</span>
                                <span @class([
                                    'inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                                    'bg-emerald-500/15 text-emerald-200' => $student->isActive(),
                                    'bg-white/10 text-slate-200' => ! $student->isActive(),
                                ])>{{ ucfirst($student->status) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('students.edit', $student) }}" class="inline-flex cursor-pointer items-center justify-center rounded-full bg-white px-4 py-2.5 text-sm font-semibold text-slate-950 transition-colors duration-200 hover:bg-slate-100">
                            Edit profile
                        </a>
                        <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex cursor-pointer items-center justify-center rounded-full border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-white/10">
                                Delete record
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <article class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Academic details</p>
                    <dl class="mt-6 space-y-5">
                        <div class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">
                            <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Major</dt>
                            <dd class="font-heading mt-2 text-lg font-semibold text-slate-950">{{ $student->major }}</dd>
                        </div>
                        <div class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">
                            <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Enrollment year</dt>
                            <dd class="font-heading mt-2 text-lg font-semibold text-slate-950">{{ $student->enrollment_year }}</dd>
                        </div>
                        <div class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">
                            <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Directory status</dt>
                            <dd class="mt-3">
                                <span @class([
                                    'inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                                    'bg-emerald-500/10 text-emerald-700' => $student->isActive(),
                                    'bg-slate-900/10 text-slate-700' => ! $student->isActive(),
                                ])>{{ ucfirst($student->status) }}</span>
                            </dd>
                        </div>
                    </dl>
                </article>

                <article class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Contact details</p>
                    <dl class="mt-6 space-y-5">
                        <div class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">
                            <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Email</dt>
                            <dd class="mt-2 text-base font-medium text-slate-950">{{ $student->email }}</dd>
                        </div>
                        <div class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">
                            <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Phone</dt>
                            <dd class="mt-2 text-base font-medium text-slate-950">{{ $student->phone ?: 'No phone number provided' }}</dd>
                        </div>
                        <div class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">
                            <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Date of birth</dt>
                            <dd class="mt-2 text-base font-medium text-slate-950">{{ $student->date_of_birth?->format('d F Y') ?: 'No date of birth provided' }}</dd>
                        </div>
                    </dl>
                </article>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Directory metrics</p>
                <div class="mt-6 grid gap-4">
                    @foreach ($metrics as $metric)
                        <div class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $metric['label'] }}</p>
                            <p class="font-heading mt-3 text-2xl font-semibold text-slate-950">{{ $metric['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Same major cohort</p>
                <h3 class="font-heading mt-2 text-2xl font-semibold text-slate-950">Related students</h3>

                <div class="mt-6 space-y-4">
                    @forelse ($relatedStudents as $relatedStudent)
                        <a href="{{ route('students.show', $relatedStudent) }}" class="flex cursor-pointer items-center gap-4 rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4 transition-colors duration-200 hover:border-slate-300 hover:bg-white">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-500/10 text-sm font-semibold text-orange-700">{{ $relatedStudent->initials() }}</span>
                            <span class="min-w-0">
                                <span class="font-heading block truncate text-sm font-semibold text-slate-950">{{ $relatedStudent->name }}</span>
                                <span class="block truncate text-sm text-slate-500">{{ $relatedStudent->email }}</span>
                            </span>
                        </a>
                    @empty
                        <p class="rounded-[24px] border border-dashed border-slate-200 px-4 py-6 text-sm leading-6 text-slate-500">No other students in this major are currently visible in the directory.</p>
                    @endforelse
                </div>
            </section>
        </aside>
    </div>
@endsection
