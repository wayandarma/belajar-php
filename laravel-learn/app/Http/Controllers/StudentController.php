<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $status = (string) $request->string('status');

        $students = Student::query()
            ->search($search)
            ->withStatus($status)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('students.index', [
            'students' => $students,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
            'metrics' => $this->metrics(),
            'recentStudents' => Student::query()->latest()->limit(4)->get(),
            'topMajors' => $this->topMajors(),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function create(): View
    {
        return view('students.create', $this->formViewData(new Student));
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = Student::create($request->validated());

        return to_route('students.show', $student)
            ->with('success', 'Student profile created successfully.');
    }

    public function show(Student $student): View
    {
        return view('students.show', [
            'student' => $student,
            'metrics' => $this->metrics(),
            'relatedStudents' => Student::query()
                ->where('major', $student->major)
                ->whereKeyNot($student->getKey())
                ->latest()
                ->limit(3)
                ->get(),
        ]);
    }

    public function edit(Student $student): View
    {
        return view('students.edit', $this->formViewData($student));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return to_route('students.show', $student)
            ->with('success', 'Student profile updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $studentName = $student->name;

        $student->delete();

        return to_route('students.index')
            ->with('success', "{$studentName} was removed from the directory.");
    }

    /**
     * @return array<string, array{label: string, value: string}>
     */
    private function metrics(): array
    {
        $currentYear = (int) now()->format('Y');

        return [
            'total' => [
                'label' => 'Total students',
                'value' => number_format(Student::query()->count()),
            ],
            'active' => [
                'label' => 'Active roster',
                'value' => number_format(Student::query()->where('status', 'active')->count()),
            ],
            'new_this_year' => [
                'label' => 'New this year',
                'value' => number_format(Student::query()->where('enrollment_year', $currentYear)->count()),
            ],
            'inactive' => [
                'label' => 'Inactive records',
                'value' => number_format(Student::query()->where('status', 'inactive')->count()),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formViewData(Student $student): array
    {
        return [
            'student' => $student,
            'statusOptions' => $this->statusOptions(),
            'enrollmentYears' => range((int) now()->addYear()->format('Y'), 2000),
        ];
    }

    /**
     * @return Collection<int, Student>
     */
    private function topMajors(): Collection
    {
        return Student::query()
            ->select('major')
            ->selectRaw('count(*) as total')
            ->groupBy('major')
            ->orderByDesc('total')
            ->limit(4)
            ->get();
    }

    /**
     * @return array<string, string>
     */
    private function statusOptions(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }
}
