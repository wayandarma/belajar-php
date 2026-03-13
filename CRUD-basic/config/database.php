<?php

declare(strict_types=1);

function database_connection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $databaseDirectory = __DIR__ . '/../data';

    if (!is_dir($databaseDirectory) && !mkdir($databaseDirectory, 0775, true) && !is_dir($databaseDirectory)) {
        throw new RuntimeException('Database directory could not be created.');
    }

    $databasePath = $databaseDirectory . '/crud.sqlite';

    $pdo = new PDO('sqlite:' . $databasePath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    initialise_database($pdo);

    return $pdo;
}

function initialise_database(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS students (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT NOT NULL,
            course TEXT NOT NULL,
            created_at TEXT NOT NULL
        )'
    );

    $studentCount = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();

    if ($studentCount > 0) {
        return;
    }

    // Seed first-run data so the CRUD has visible content immediately.
    $seedStudents = [
        [
            'name' => 'Alya Ramadhani',
            'email' => 'alya@example.com',
            'phone' => '0812-1111-2222',
            'course' => 'PHP Dasar',
        ],
        [
            'name' => 'Bima Saputra',
            'email' => 'bima@example.com',
            'phone' => '0813-3333-4444',
            'course' => 'MySQL Dasar',
        ],
        [
            'name' => 'Citra Mahesa',
            'email' => 'citra@example.com',
            'phone' => '0814-5555-6666',
            'course' => 'Bootstrap Layout',
        ],
    ];

    $statement = $pdo->prepare(
        'INSERT INTO students (name, email, phone, course, created_at)
         VALUES (:name, :email, :phone, :course, :created_at)'
    );

    foreach ($seedStudents as $student) {
        $statement->execute([
            'name' => $student['name'],
            'email' => $student['email'],
            'phone' => $student['phone'],
            'course' => $student['course'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

