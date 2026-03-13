<?php

declare(strict_types=1);

$student = $student ?? [
    'name' => '',
    'email' => '',
    'phone' => '',
    'course' => '',
];

$errors = $errors ?? [];
$submitLabel = $submitLabel ?? 'Simpan';
$submitHelper = $submitHelper ?? 'Data akan langsung tersimpan ke SQLite lokal.';
?>
<div class="card border-0 shadow-sm app-card">
    <div class="card-body p-4 p-lg-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h2 class="h4 mb-1">Form Siswa</h2>
                <p class="text-secondary mb-0">Isi form di bawah untuk latihan menerima data dari input HTML ke PHP.</p>
            </div>
            <span class="badge text-bg-light border px-3 py-2">Native PHP</span>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label for="name" class="form-label">Nama lengkap</label>
                <input
                    type="text"
                    class="form-control form-control-lg <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                    id="name"
                    name="name"
                    value="<?= e($student['name']) ?>"
                    placeholder="Contoh: Dinda Pratama"
                >
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?= e($errors['name']) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control form-control-lg <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                    id="email"
                    name="email"
                    value="<?= e($student['email']) ?>"
                    placeholder="nama@email.com"
                >
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= e($errors['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">Nomor telepon</label>
                <input
                    type="text"
                    class="form-control form-control-lg <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                    id="phone"
                    name="phone"
                    value="<?= e($student['phone']) ?>"
                    placeholder="08xx-xxxx-xxxx"
                >
                <?php if (isset($errors['phone'])): ?>
                    <div class="invalid-feedback"><?= e($errors['phone']) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="course" class="form-label">Kelas atau topik</label>
                <input
                    type="text"
                    class="form-control form-control-lg <?= isset($errors['course']) ? 'is-invalid' : '' ?>"
                    id="course"
                    name="course"
                    value="<?= e($student['course']) ?>"
                    placeholder="Contoh: PHP Dasar"
                >
                <?php if (isset($errors['course'])): ?>
                    <div class="invalid-feedback"><?= e($errors['course']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4 pt-3 border-top">
            <p class="text-secondary mb-0"><?= e($submitHelper) ?></p>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-light px-4">Kembali</a>
                <button type="submit" class="btn btn-primary px-4"><?= e($submitLabel) ?></button>
            </div>
        </div>
    </div>
</div>

