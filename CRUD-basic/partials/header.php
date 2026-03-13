p<?php

declare(strict_types=1);

$title = $title ?? 'Belajar PHP CRUD';
$activePage = $activePage ?? '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="index.php">
                <span class="brand-mark">P</span>
                <span>Belajar PHP CRUD</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <div class="navbar-nav ms-auto gap-lg-2">
                    <a class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="index.php">Data Siswa</a>
                    <a class="nav-link <?= $activePage === 'create' ? 'active' : '' ?>" href="create.php">Tambah Data</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4 py-lg-5">
        <div class="container">

