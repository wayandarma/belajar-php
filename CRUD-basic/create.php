<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$title = 'Tambah Data Siswa';
$activePage = 'create';
$errors = [];
$student = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'course' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        flash('danger', 'Token CSRF tidak valid. Silakan coba lagi.');
        redirect('create.php');
    }

    $student = normalize_student_input($_POST);
    $errors = validate_student($student);

    if ($errors === []) {
        $statement = $pdo->prepare(
            'INSERT INTO students (name, email, phone, course, created_at)
             VALUES (:name, :email, :phone, :course, :created_at)'
        );

        $statement->execute([
            'name' => $student['name'],
            'email' => $student['email'],
            'phone' => $student['phone'],
            'course' => $student['course'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        flash('success', 'Data siswa berhasil ditambahkan.');
        redirect('index.php');
    }
}

require __DIR__ . '/partials/header.php';
?>

<section class="row g-4 align-items-start">
    <div class="col-lg-8">
        <div class="mb-4">
            <span class="hero-kicker">Create</span>
            <h1 class="display-6 fw-bold mt-3 mb-2">Tambah data siswa baru</h1>
            <p class="text-secondary mb-0">
                Di halaman ini kita latihan menerima data `POST`, memvalidasi input, lalu menyimpan record baru ke database.
            </p>
        </div>

        <form method="post" novalidate>
            <?php
            $submitLabel = 'Simpan data';
            $submitHelper = 'Saat tombol simpan ditekan, PHP menjalankan query INSERT ke tabel students.';
            require __DIR__ . '/partials/form.php';
            ?>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="learning-card p-4">
            <h2 class="h5 mb-3">Yang terjadi di balik layar</h2>
            <ol class="text-secondary mb-0">
                <li>Browser mengirim form dengan method `POST`.</li>
                <li>PHP membaca isi form melalui `$_POST`.</li>
                <li>Function `validate_student()` memeriksa input.</li>
                <li>PDO menjalankan query `INSERT` bila valid.</li>
                <li>User diarahkan balik ke halaman daftar data.</li>
            </ol>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>

