<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header("Location: {$path}");
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function pull_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return $flash;
}

function normalize_student_input(array $input): array
{
    return [
        'name' => trim((string) ($input['name'] ?? '')),
        'email' => trim((string) ($input['email'] ?? '')),
        'phone' => trim((string) ($input['phone'] ?? '')),
        'course' => trim((string) ($input['course'] ?? '')),
    ];
}

function validate_student(array $student): array
{
    $errors = [];

    if ($student['name'] === '') {
        $errors['name'] = 'Nama wajib diisi.';
    }

    if ($student['email'] === '') {
        $errors['email'] = 'Email wajib diisi.';
    } elseif (!filter_var($student['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email belum valid.';
    }

    if ($student['phone'] === '') {
        $errors['phone'] = 'Nomor telepon wajib diisi.';
    }

    if ($student['course'] === '') {
        $errors['course'] = 'Kelas atau topik wajib diisi.';
    }

    return $errors;
}

function find_student(PDO $pdo, int $id): ?array
{
    $statement = $pdo->prepare('SELECT * FROM students WHERE id = :id LIMIT 1');
    $statement->execute(['id' => $id]);

    $student = $statement->fetch();

    return $student ?: null;
}

function count_students(PDO $pdo): int
{
    return (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
}

function format_datetime(?string $value): string
{
    if (!$value) {
        return '-';
    }

    try {
        return (new DateTimeImmutable($value))->format('d/m/Y H:i');
    } catch (Exception) {
        return $value;
    }
}

function alert_class(string $type): string
{
    $allowedTypes = ['success', 'danger', 'warning', 'info'];

    return in_array($type, $allowedTypes, true) ? $type : 'info';
}

