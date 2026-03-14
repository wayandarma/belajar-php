@extends('layouts.dashboard')

@section('title', 'Student Dashboard')
@section('summary', 'Monitor enrollment health, update student records, and move through the directory from a single operational workspace.')

@section('content')
    @php
        $largestMajorCount = max(1, (int) ($topMajors->max('total') ?? 1));
    @endphp

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
        <div class="space-y-6">
            <section class="relative overflow-hidden rounded-[32px] bg-slate-950 px-6 py-8 text-white shadow-2xl shadow-slate-950/15 sm:px-8">
                <div class="absolute inset-y-0 right-0 hidden w-1/2 bg-[radial-gradient(circle_at_top,rgba(37,99,235,0.3),transparent_55%)] lg:block"></div>
                <div class="absolute -right-10 bottom-0 h-40 w-40 rounded-full bg-orange-500/20 blur-3xl"></div>
                <div class="absolute left-1/3 top-0 h-32 w-32 rounded-full bg-blue-500/20 blur-3xl"></div>

                <div class="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-slate-200">Student operations hub</span>
                        <h2 class="font-heading mt-5 text-3xl font-semibold tracking-tight sm:text-4xl">Data-dense enough for operations, calm enough for daily use.</h2>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">Search the directory, check enrollment movement, and keep student profiles consistent without leaving the dashboard.</p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('students.create') }}" class="inline-flex cursor-pointer items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition-colors duration-200 hover:bg-slate-100">
                                Create student profile
                            </a>
                            <a href="#directory-table" class="inline-flex cursor-pointer items-center justify-center rounded-full border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition-colors duration-200 hover:bg-white/10">
                                Review directory
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($metrics as $metric)
                            <div class="rounded-[24px] border border-white/10 bg-white/5 px-5 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">{{ $metric['label'] }}</p>
                                <p class="font-heading mt-3 text-3xl font-semibold text-white">{{ $metric['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-2 2xl:grid-cols-4">
                @foreach ($metrics as $key => $metric)
                    <article class="surface-panel dashboard-ring rounded-[28px] border border-white/70 px-5 py-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $metric['label'] }}</p>
                                <p class="font-heading mt-3 text-3xl font-semibold text-slate-950">{{ $metric['value'] }}</p>
                            </div>
                            <span @class([
                                'inline-flex h-12 w-12 items-center justify-center rounded-2xl',
                                'bg-blue-500/10 text-blue-700' => $key === 'total',
                                'bg-emerald-500/10 text-emerald-700' => $key === 'active',
                                'bg-orange-500/10 text-orange-700' => $key === 'new_this_year',
                                'bg-slate-900/10 text-slate-700' => $key === 'inactive',
                            ])>
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                    @if ($key === 'total')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5V4h-5m-5 16h5V10h-5m-5 10h5V14H7m-5 6h5V8H2v12Z" />
                                    @elseif ($key === 'active')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 11-18 0 9 9 0 0118 0Z" />
                                    @elseif ($key === 'new_this_year')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6v12m6-6H6" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 9.75 9 15.75m0-6 6 6m6-3a9 9 0 11-18 0 9 9 0 0118 0Z" />
                                    @endif
                                </svg>
                            </span>
                        </div>
                    </article>
                @endforeach
            </section>

            <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Directory filters</p>
                        <h3 class="font-heading mt-3 text-2xl font-semibold text-slate-950">Search, segment, and keep context across pagination.</h3>
                    </div>
                    <a href="{{ route('students.index') }}" class="inline-flex cursor-pointer items-center text-sm font-semibold text-slate-500 transition-colors duration-200 hover:text-slate-900">Reset filters</a>
                </div>

                <form action="{{ route('students.index') }}" method="GET" class="mt-6 grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px_auto]">
                    <label class="block">
                        <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Search</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ $filters['search'] }}"
                            placeholder="Name, email, or major"
                            class="mt-2 w-full rounded-[22px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
                    </label>

                    <label class="block">
                        <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Status</span>
                        <select name="status" class="mt-2 w-full rounded-[22px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                            <option value="">All statuses</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex items-end">
                        <button type="submit" class="inline-flex w-full cursor-pointer items-center justify-center rounded-[22px] bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition-colors duration-200 hover:bg-slate-800">
                            Apply filters
                        </button>
                    </div>
                </form>
            </section>

            <section id="directory-table" class="surface-panel dashboard-ring overflow-hidden rounded-[32px] border border-white/70">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Directory table</p>
                        <h3 class="font-heading mt-2 text-2xl font-semibold text-slate-950">Student records</h3>
                    </div>
                    <p class="text-sm text-slate-500">{{ $students->total() }} record{{ $students->total() === 1 ? '' : 's' }} matched the current view.</p>
                </div>

                @if ($students->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left">
                            <thead class="bg-slate-50/80">
                                <tr class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    <th class="px-6 py-4">Student</th>
                                    <th class="px-6 py-4">Major</th>
                                    <th class="px-6 py-4">Enrollment</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white/80 text-sm text-slate-700">
                                @foreach ($students as $student)
                                    <tr class="transition-colors duration-200 hover:bg-slate-50/80">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-semibold text-white">{{ $student->initials() }}</span>
                                                <div class="min-w-0">
                                                    <p class="font-heading truncate text-base font-semibold text-slate-950">{{ $student->name }}</p>
                                                    <p class="truncate text-sm text-slate-500">{{ $student->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <p class="font-medium text-slate-900">{{ $student->major }}</p>
                                            <p class="text-slate-500">{{ $student->phone ?: 'No phone listed' }}</p>
                                        </td>
                                        <td class="px-6 py-5">
                                            <p class="font-medium text-slate-900">{{ $student->enrollment_year }}</p>
                                            <p class="text-slate-500">{{ $student->date_of_birth?->format('d M Y') ?: 'Birth date not added' }}</p>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span @class([
                                                'inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                                                'bg-emerald-500/10 text-emerald-700' => $student->isActive(),
                                                'bg-slate-900/10 text-slate-700' => ! $student->isActive(),
                                            ])>
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('students.show', $student) }}" class="inline-flex cursor-pointer items-center rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition-colors duration-200 hover:border-slate-300 hover:bg-slate-50">Open</a>
                                                <a href="{{ route('students.edit', $student) }}" class="inline-flex cursor-pointer items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition-colors duration-200 hover:border-blue-300 hover:bg-blue-100">Edit</a>
                                                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex cursor-pointer items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition-colors duration-200 hover:border-rose-300 hover:bg-rose-100">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-slate-200 px-6 py-5">
                        {{ $students->links() }}
                    </div>
                @else
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-950 text-white">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.75 9.75h4.5m-7.5 6h10.5m2.25-9.75v11.25A2.25 2.25 0 0117.25 19.5H6.75A2.25 2.25 0 014.5 17.25V6.75A2.25 2.25 0 016.75 4.5h10.5A2.25 2.25 0 0119.5 6.75Z" />
                            </svg>
                        </div>
                        <h3 class="font-heading mt-6 text-2xl font-semibold text-slate-950">No students match this view.</h3>
                        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">Try a broader search, clear the status filter, or create a new student profile to start the directory.</p>
                        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('students.index') }}" class="inline-flex cursor-pointer items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:border-slate-300 hover:bg-slate-50">Clear filters</a>
                            <a href="{{ route('students.create') }}" class="inline-flex cursor-pointer items-center justify-center rounded-full bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-slate-800">Create student</a>
                        </div>
                    </div>
                @endif
            </section>
        </div>

        <aside class="space-y-6">
            <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Recent additions</p>
                <h3 class="font-heading mt-2 text-2xl font-semibold text-slate-950">Latest records</h3>

                <div class="mt-6 space-y-4">
                    @forelse ($recentStudents as $student)
                        <a href="{{ route('students.show', $student) }}" class="flex cursor-pointer items-center gap-4 rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4 transition-colors duration-200 hover:border-slate-300 hover:bg-white">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-500/10 text-sm font-semibold text-blue-700">{{ $student->initials() }}</span>
                            <span class="min-w-0">
                                <span class="font-heading block truncate text-sm font-semibold text-slate-950">{{ $student->name }}</span>
                                <span class="block truncate text-sm text-slate-500">{{ $student->major }}</span>
                            </span>
                        </a>
                    @empty
                        <p class="rounded-[24px] border border-dashed border-slate-200 px-4 py-6 text-sm leading-6 text-slate-500">Recent student activity will appear here once the directory has records.</p>
                    @endforelse
                </div>
            </section>

            <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Major distribution</p>
                <h3 class="font-heading mt-2 text-2xl font-semibold text-slate-950">Top programs</h3>

                <div class="mt-6 space-y-5">
                    @forelse ($topMajors as $major)
                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-heading text-sm font-semibold text-slate-950">{{ $major->major }}</p>
                                <p class="text-sm text-slate-500">{{ $major->total }} student{{ $major->total === 1 ? '' : 's' }}</p>
                            </div>
                            <div class="mt-3 h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-gradient-to-r from-blue-600 to-orange-500" style="width: {{ max(18, ((int) $major->total / $largestMajorCount) * 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-[24px] border border-dashed border-slate-200 px-4 py-6 text-sm leading-6 text-slate-500">Program insights appear automatically once the directory starts collecting students.</p>
                    @endforelse
                </div>
            </section>

            <section class="metric-gradient dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Ops principle</p>
                <h3 class="font-heading mt-2 text-2xl font-semibold text-slate-950">Make every edit obvious and reversible.</h3>
                <p class="mt-4 text-sm leading-7 text-slate-600">Each record has a dedicated detail page, status stays visible in the table, and destructive actions stay separate from edit controls.</p>
            </section>
        </aside>
    </div>
@endsection
