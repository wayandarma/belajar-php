<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$title = 'Edit Data Siswa';
$activePage = 'home';
$errors = [];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    flash('danger', 'ID siswa tidak valid.');
    redirect('index.php');
}

$existingStudent = find_student($pdo, $id);

if ($existingStudent === null) {
    flash('danger', 'Data siswa tidak ditemukan.');
    redirect('index.php');
}

$student = normalize_student_input($existingStudent);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = normalize_student_input($_POST);
    $errors = validate_student($student);

    if ($errors === []) {
        $statement = $pdo->prepare(
            'UPDATE students
             SET name = :name, email = :email, phone = :phone, course = :course
             WHERE id = :id'
        );

        $statement->execute([
            'name' => $student['name'],
            'email' => $student['email'],
            'phone' => $student['phone'],
            'course' => $student['course'],
            'id' => $id,
        ]);

        flash('success', 'Data siswa berhasil diperbarui.');
        redirect('index.php');
    }
}

require __DIR__ . '/partials/header.php';
?>

<section class="row g-4 align-items-start">
    <div class="col-lg-8">
        <div class="mb-4">
            <span class="hero-kicker">Update</span>
            <h1 class="display-6 fw-bold mt-3 mb-2">Edit data siswa</h1>
            <p class="text-secondary mb-0">
                Halaman ini menunjukkan cara mengambil satu record dengan `SELECT`, lalu mengubahnya memakai query `UPDATE`.
            </p>
        </div>

        <form method="post" novalidate>
            <?php
            $submitLabel = 'Update data';
            $submitHelper = 'PHP mengirim nilai baru ke query UPDATE berdasarkan ID siswa yang sedang diedit.';
            require __DIR__ . '/partials/form.php';
            ?>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="learning-card p-4">
            <h2 class="h5 mb-3">Catatan penting</h2>
            <ul class="text-secondary mb-0">
                <li>ID diambil dari query string seperti `edit.php?id=1`.</li>
                <li>Query pakai prepared statement supaya lebih aman.</li>
                <li>Form memakai nilai lama agar user mudah mengedit.</li>
                <li>Setelah sukses, halaman diarahkan lagi ke daftar data.</li>
            </ul>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>

