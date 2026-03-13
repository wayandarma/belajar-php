<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    flash('warning', 'Aksi hapus hanya boleh melalui form POST.');
    redirect('index.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    flash('danger', 'ID siswa tidak valid.');
    redirect('index.php');
}

$student = find_student($pdo, $id);

if ($student === null) {
    flash('danger', 'Data yang ingin dihapus tidak ditemukan.');
    redirect('index.php');
}

$statement = $pdo->prepare('DELETE FROM students WHERE id = :id');
$statement->execute(['id' => $id]);

flash('success', 'Data siswa berhasil dihapus.');
redirect('index.php');

