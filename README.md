# Belajar PHP Native: Simple CRUD

Project ini dibuat untuk belajar PHP dari dasar dengan target pertama:

- paham flow request -> PHP -> database -> HTML
- mengenal CRUD (`Create`, `Read`, `Update`, `Delete`)
- memakai Bootstrap terbaru untuk UI
- memakai SQLite supaya setup lokal cepat

Bootstrap yang dipakai saat ini adalah **v5.3.8** via CDN resmi.

## Stack

- PHP 8.x
- SQLite (`pdo_sqlite`)
- Bootstrap 5.3.8

## Cara Menjalankan

Jalankan built-in server PHP dari root project:

```bash
php -S localhost:8000
```

Lalu buka:

```text
http://localhost:8000
```

Tidak perlu membuat database manual. Saat project pertama kali dibuka, file SQLite akan dibuat otomatis di folder `data/`.

## Struktur File

```text
.
├── assets/css/app.css        # Custom styling di atas Bootstrap
├── bootstrap.php             # Entry helper: session + koneksi database
├── config/database.php       # Koneksi PDO + init tabel + seed data awal
├── create.php                # Form tambah data
├── data/                     # File SQLite lokal
├── delete.php                # Proses hapus data
├── edit.php                  # Form edit data
├── functions.php             # Helper umum
├── index.php                 # Halaman daftar data
└── partials/                 # Potongan layout dan form
```

## Konsep Dasar PHP yang Dipakai

### 1. Variabel

Variabel dipakai untuk menyimpan nilai:

```php
$title = 'Data Siswa';
$students = $pdo->query('SELECT * FROM students ORDER BY id DESC')->fetchAll();
```

### 2. Array

Array dipakai untuk menyimpan sekumpulan data:

```php
$student = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'course' => '',
];
```

### 3. Condition

Condition dipakai untuk mengambil keputusan:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // proses form
}
```

### 4. Function

Function dipakai supaya logic bisa dipakai ulang:

```php
function validate_student(array $student): array
{
    $errors = [];

    if ($student['name'] === '') {
        $errors['name'] = 'Nama wajib diisi.';
    }

    return $errors;
}
```

### 5. Loop

Loop dipakai untuk menampilkan banyak data ke HTML:

```php
<?php foreach ($students as $student): ?>
    <tr>
        <td><?= $student['name'] ?></td>
    </tr>
<?php endforeach; ?>
```

### 6. Superglobal

PHP punya variable bawaan seperti:

- `$_GET` untuk data dari URL
- `$_POST` untuk data dari form
- `$_SERVER` untuk info request
- `$_SESSION` untuk data sesi seperti flash message

## Flow CRUD di Project Ini

### Create

File: `create.php`

1. User isi form.
2. PHP baca data dari `$_POST`.
3. PHP validasi input.
4. Jika valid, PDO menjalankan `INSERT`.
5. User diarahkan ke `index.php`.

### Read

File: `index.php`

1. PHP menjalankan query `SELECT * FROM students`.
2. Hasil query dimasukkan ke array `$students`.
3. `foreach` dipakai untuk mencetak baris tabel.

### Update

File: `edit.php`

1. PHP ambil `id` dari URL.
2. PHP ambil satu data siswa dari database.
3. Form diisi dengan data lama.
4. Saat disubmit, PHP menjalankan `UPDATE`.

### Delete

File: `delete.php`

1. Tombol hapus mengirim `POST`.
2. PHP cek `id`.
3. PDO menjalankan `DELETE`.
4. User kembali ke halaman daftar data.

## Kenapa Pakai SQLite Dulu?

Untuk belajar dasar PHP, kita belum perlu ribet set up MySQL. Dengan SQLite:

- tidak perlu install server database tambahan
- file database cukup satu file `.sqlite`
- syntax SQL tetap relevan
- lebih cepat untuk belajar fondasi CRUD

Kalau nanti kamu sudah nyaman, project ini gampang dipindah ke MySQL karena koneksi tetap memakai PDO.

## Catatan Belajar Berikutnya

Setelah paham project ini, urutan bagus untuk lanjut adalah:

1. pahami perbedaan `GET` vs `POST`
2. pahami validasi form lebih dalam
3. pahami prepared statement dan SQL injection
4. pindahkan logic CRUD ke function atau class
5. migrasi dari SQLite ke MySQL

## Sumber Bootstrap

- Dokumentasi resmi Bootstrap: https://getbootstrap.com/docs/5.3/getting-started/introduction/
- Halaman download resmi Bootstrap: https://getbootstrap.com/docs/5.3/getting-started/download/
