@php
    $navigation = [
        [
            'label' => 'Dashboard',
            'caption' => 'Student operations',
            'route' => route('students.index'),
            'active' => request()->routeIs('students.index', 'students.show'),
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 4.5h6.75v6.75H3.75V4.5Zm9.75 0h6.75v10.5H13.5V4.5Zm-9.75 9.75h6.75v5.25H3.75v-5.25Zm9.75 3h6.75v2.25H13.5v-2.25Z" />',
        ],
        [
            'label' => 'Register student',
            'caption' => 'Create a new profile',
            'route' => route('students.create'),
            'active' => request()->routeIs('students.create', 'students.edit'),
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6v12m6-6H6" />',
        ],
    ];
@endphp

<aside class="hidden w-80 shrink-0 lg:block">
    <div class="surface-panel dashboard-ring sticky top-6 flex h-[calc(100vh-3rem)] flex-col rounded-[32px] border border-white/70 px-6 py-6">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-white shadow-lg shadow-slate-950/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.5 3 9l9 4.5L21 9 12 4.5Zm0 9 6.75-3.375V15L12 18.75 5.25 15v-4.875L12 13.5Z" />
                </svg>
            </div>
            <div>
                <p class="font-heading text-lg font-semibold text-slate-950">Atlas Student Desk</p>
                <p class="text-sm text-slate-500">Admissions and records workspace</p>
            </div>
        </div>

        <div class="mt-8 rounded-[28px] bg-slate-950 px-5 py-5 text-white">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Control center</p>
            <h2 class="font-heading mt-3 text-2xl font-semibold leading-tight">Sharper visibility for every student record.</h2>
            <p class="mt-3 text-sm leading-6 text-slate-300">Track enrollment, keep directory details current, and move between student records without losing context.</p>
        </div>

        <nav class="mt-8 space-y-3">
            @foreach ($navigation as $item)
                <a
                    href="{{ $item['route'] }}"
                    @class([
                        'group flex cursor-pointer items-center gap-4 rounded-[24px] border px-4 py-4 transition-colors duration-200',
                        'border-slate-950 bg-slate-950 text-white shadow-lg shadow-slate-950/10' => $item['active'],
                        'border-transparent bg-white/40 text-slate-700 hover:border-slate-200 hover:bg-white/80' => ! $item['active'],
                    ])
                >
                    <span @class([
                        'inline-flex h-11 w-11 items-center justify-center rounded-2xl transition-colors duration-200',
                        'bg-white/10 text-white' => $item['active'],
                        'bg-slate-100 text-slate-700 group-hover:bg-slate-950 group-hover:text-white' => ! $item['active'],
                    ])>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">{!! $item['icon'] !!}</svg>
                    </span>
                    <span class="min-w-0">
                        <span class="font-heading block text-sm font-semibold">{{ $item['label'] }}</span>
                        <span @class([
                            'block text-xs',
                            'text-slate-300' => $item['active'],
                            'text-slate-500' => ! $item['active'],
                        ])>{{ $item['caption'] }}</span>
                    </span>
                </a>
            @endforeach
        </nav>

        <div class="mt-auto rounded-[28px] border border-slate-200 bg-white/75 px-5 py-5">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Operating note</p>
            <p class="font-heading mt-3 text-lg font-semibold text-slate-950">Clean forms, predictable routes, readable records.</p>
            <p class="mt-2 text-sm leading-6 text-slate-600">Validation errors return with preserved input, and every record opens in a dedicated detail view for safer edits.</p>
        </div>
    </div>
</aside>
