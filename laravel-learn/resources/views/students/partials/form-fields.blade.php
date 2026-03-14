@php
    $fieldClasses = 'mt-2 w-full rounded-[22px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10';
    $labelClasses = 'text-xs font-semibold uppercase tracking-[0.24em] text-slate-500';
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="{{ $labelClasses }}" for="name">Full name</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $student->name) }}"
            class="{{ $fieldClasses }}"
            placeholder="Alya Prameswari"
            @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
        >
        @error('name')
            <p id="name-error" class="mt-2 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="{{ $labelClasses }}" for="email">Email address</label>
        <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email', $student->email) }}"
            class="{{ $fieldClasses }}"
            placeholder="alya@campus.test"
            @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
        >
        @error('email')
            <p id="email-error" class="mt-2 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="{{ $labelClasses }}" for="phone">Phone number</label>
        <input
            id="phone"
            name="phone"
            type="text"
            value="{{ old('phone', $student->phone) }}"
            class="{{ $fieldClasses }}"
            placeholder="+62 812 0000 0000"
            @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror
        >
        @error('phone')
            <p id="phone-error" class="mt-2 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="{{ $labelClasses }}" for="major">Major</label>
        <input
            id="major"
            name="major"
            type="text"
            value="{{ old('major', $student->major) }}"
            class="{{ $fieldClasses }}"
            placeholder="Computer Science"
            @error('major') aria-invalid="true" aria-describedby="major-error" @enderror
        >
        @error('major')
            <p id="major-error" class="mt-2 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="{{ $labelClasses }}" for="enrollment_year">Enrollment year</label>
        <select
            id="enrollment_year"
            name="enrollment_year"
            class="{{ $fieldClasses }}"
            @error('enrollment_year') aria-invalid="true" aria-describedby="enrollment_year-error" @enderror
        >
            <option value="">Select year</option>
            @foreach ($enrollmentYears as $year)
                <option value="{{ $year }}" @selected((string) old('enrollment_year', $student->enrollment_year) === (string) $year)>{{ $year }}</option>
            @endforeach
        </select>
        @error('enrollment_year')
            <p id="enrollment_year-error" class="mt-2 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="{{ $labelClasses }}" for="date_of_birth">Date of birth</label>
        <input
            id="date_of_birth"
            name="date_of_birth"
            type="date"
            value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
            class="{{ $fieldClasses }}"
            @error('date_of_birth') aria-invalid="true" aria-describedby="date_of_birth-error" @enderror
        >
        @error('date_of_birth')
            <p id="date_of_birth-error" class="mt-2 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="{{ $labelClasses }}" for="status">Directory status</label>
        <select
            id="status"
            name="status"
            class="{{ $fieldClasses }}"
            @error('status') aria-invalid="true" aria-describedby="status-error" @enderror
        >
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $student->status ?: 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <p id="status-error" class="mt-2 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
    <p class="max-w-lg text-sm leading-6 text-slate-500">Every save runs server-side validation and keeps the previous input in place if anything needs correction.</p>
    <div class="flex flex-wrap gap-3">
        <a href="{{ $student->exists ? route('students.show', $student) : route('students.index') }}" class="inline-flex cursor-pointer items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:border-slate-300 hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex cursor-pointer items-center justify-center rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-slate-800">
            {{ $submitLabel }}
        </button>
    </div>
</div>
