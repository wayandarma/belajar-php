@extends('layouts.dashboard')

@section('title', 'Register Student')
@section('summary', 'Create a clean student profile with enrollment details, academic context, and a clear directory status from the start.')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
        <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6 sm:px-8 sm:py-8">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">New directory record</p>
                <h2 class="font-heading mt-3 text-3xl font-semibold text-slate-950">Capture the essentials once, then let the dashboard carry the context.</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Profiles created here feed the directory, detail view, and filter system immediately after save.</p>
            </div>

            <form action="{{ route('students.store') }}" method="POST" class="mt-8">
                @csrf

                @include('students.partials.form-fields', ['submitLabel' => 'Create student profile'])
            </form>
        </section>

        <aside class="space-y-6">
            <section class="surface-panel dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Profile checklist</p>
                <h3 class="font-heading mt-2 text-2xl font-semibold text-slate-950">What to confirm before saving</h3>

                <ul class="mt-6 space-y-4 text-sm leading-6 text-slate-600">
                    <li class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">Use the student’s institutional email if possible so the record stays unique.</li>
                    <li class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">Set the current directory status correctly so index filters remain meaningful.</li>
                    <li class="rounded-[24px] border border-slate-200 bg-white/70 px-4 py-4">Enrollment year drives dashboard metrics, so confirm it before publishing the profile.</li>
                </ul>
            </section>

            <section class="metric-gradient dashboard-ring rounded-[32px] border border-white/70 px-6 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Operational benefit</p>
                <h3 class="font-heading mt-2 text-2xl font-semibold text-slate-950">Server-side validation keeps the directory trustworthy.</h3>
                <p class="mt-4 text-sm leading-7 text-slate-600">If a field fails validation, the form returns with the user’s input intact and announces the exact fields that need attention.</p>
            </section>
        </aside>
    </div>
@endsection
