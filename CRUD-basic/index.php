<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$title = 'Data Siswa';
$activePage = 'home';
$flash = pull_flash();

$students = $pdo->query('SELECT * FROM students ORDER BY id DESC')->fetchAll();
$totalStudents = count($students);
$latestStudent = $students[0] ?? null;

require __DIR__ . '/partials/header.php';
?>

<section class="hero-panel mb-4 mb-lg-5">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <span class="hero-kicker">Belajar PHP dari dasar</span>
            <h1 class="hero-title mt-3 mb-3">Simple CRUD dengan Bootstrap terbaru.</h1>
            <p class="hero-copy mb-4">
                Project ini memperlihatkan alur inti PHP native: menerima request, memproses data form,
                berbicara ke database dengan PDO, lalu merender HTML kembali ke browser.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="create.php" class="btn btn-primary btn-lg px-4">Tambah Siswa</a>
                <a href="#belajar" class="btn btn-light btn-lg px-4">Lihat Konsep Dasar</a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stats-card p-4">
                <p class="text-secondary text-uppercase fw-semibold small mb-2">Ringkasan</p>
                <div class="display-6 fw-bold mb-3"><?= $totalStudents ?></div>
                <p class="mb-1 fw-semibold">Total data siswa</p>
                <p class="text-secondary mb-0">
                    <?= $latestStudent ? 'Data terbaru dibuat pada ' . e(format_datetime($latestStudent['created_at'])) : 'Belum ada data.' ?>
                </p>
            </div>
        </div>
    </div>
</section>

<?php if ($flash): ?>
    <div class="alert alert-<?= e(alert_class($flash['type'])) ?> shadow-sm border-0 mb-4">
        <?= e($flash['message']) ?>
    </div>
<?php endif; ?>

<div id="belajar" class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="learning-card h-100 p-4">
            <h2 class="h5 mb-3">Konsep PHP yang dipakai</h2>
            <ul class="mb-0 text-secondary">
                <li>Variabel untuk menyimpan data hasil query.</li>
                <li>Array untuk menampung satu atau banyak record siswa.</li>
                <li>Condition untuk validasi dan notifikasi.</li>
                <li>Loop `foreach` untuk merender tabel HTML.</li>
                <li>Function untuk helper seperti validasi dan redirect.</li>
            </ul>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="learning-card h-100 p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                <div>
                    <h2 class="h5 mb-3">Flow CRUD</h2>
                    <p class="text-secondary mb-0">
                        `Create` memakai form + `INSERT`, `Read` memakai `SELECT`, `Update` memakai form edit + `UPDATE`,
                        dan `Delete` memakai request `POST` ke file khusus hapus.
                    </p>
                </div>
                <div class="text-md-end">
                    <span class="badge text-bg-light border px-3 py-2">PDO + SQLite</span>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="app-card table-shell">
    <div class="card border-0">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h2 class="h4 mb-1">Daftar Siswa</h2>
                    <p class="text-secondary mb-0">Data di bawah berasal dari tabel `students` di SQLite lokal.</p>
                </div>
                <a href="create.php" class="btn btn-primary">Tambah data baru</a>
            </div>

            <?php if ($students === []): ?>
                <div class="empty-state">
                    <p class="mb-3">Belum ada data. Tambahkan record pertama untuk mulai latihan CRUD.</p>
                    <a href="create.php" class="btn btn-primary">Buat data pertama</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Siswa</th>
                                <th>Kontak</th>
                                <th>Kelas</th>
                                <th>Dibuat</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td class="fw-semibold">#<?= (int) $student['id'] ?></td>
                                    <td>
                                        <div class="record-name"><?= e($student['name']) ?></div>
                                        <div class="record-meta"><?= e($student['email']) ?></div>
                                    </td>
                                    <td><?= e($student['phone']) ?></td>
                                    <td>
                                        <span class="badge text-bg-light border px-3 py-2"><?= e($student['course']) ?></span>
                                    </td>
                                    <td><?= e(format_datetime($student['created_at'])) ?></td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="edit.php?id=<?= (int) $student['id'] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                            <form action="delete.php" method="post" onsubmit="return confirm('Hapus data ini?');">
                                                <input type="hidden" name="id" value="<?= (int) $student['id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
