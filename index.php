<?php
// 1. Koneksi Database
$conn = mysqli_connect('localhost', 'root', '', 'pemweb_1');

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Tambah Data
if (isset($_POST['tambah'])) {
    $nim   = $_POST['nim'];
    $nama  = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $email = $_POST['email'];

    mysqli_query($conn, "INSERT INTO mahasiswa VALUES ('$nim', '$nama', '$prodi', '$email')");
    header("Location: index.php");
    exit();
}

// 3. Hapus Data
if (isset($_GET['hapus'])) {
    $nim = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE nim = '$nim'");
    header("Location: index.php");
    exit();
}

// 4. Update Data
if (isset($_POST['update'])) {
    $nim   = $_POST['nim'];
    $nama  = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $email = $_POST['email'];

    mysqli_query($conn, "UPDATE mahasiswa SET nama='$nama', prodi='$prodi', email='$email' WHERE nim='$nim'");
    header("Location: index.php");
    exit();
}

// Cek apakah lagi mau edit data
$data_edit = null;
if (isset($_GET['edit'])) {
    $nim = $_GET['edit'];
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
    $data_edit = mysqli_fetch_assoc($query);
}

// 5. Ambil Semua Data (Read)
$data_mahasiswa = mysqli_query($conn, "SELECT * FROM mahasiswa");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa - Pemweb</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f7f9fa;   
            color: #333;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        h2, h3 {
            margin-top: 0;
            color: #111;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 25px 0;
        }

        /* Form Styling */
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #444;
        }

        input[type="text"], input[type="email"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #0066cc;
        }

        input[readonly] {
            background-color: #f1f1f1;
            color: #777;
        }

        .form-actions {
            grid-column: span 2;
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }

        button, .btn-batal {
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        button[type="submit"] {
            background-color: #0066cc;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #0052a3;
        }

        .btn-batal {
            background-color: #e0e0e0;
            color: #333;
            display: inline-block;
            line-height: normal;
        }

        .btn-batal:hover {
            background-color: #d0d0d0;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #222;
        }

        tr:hover {
            background-color: #fafafa;
        }

        /* Action Links inside Table */
        .action-links a {
            color: #0066cc;
            text-decoration: none;
            margin-right: 10px;
            font-weight: 500;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .action-links a.hapus {
            color: #d93838;
        }

        .action-links a.hapus:hover {
            color: #b52b2b;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manajemen Data Mahasiswa</h2>
    <p style="color: #666; font-size: 14px; margin-top: -5px;">152024078 - Rafli Maulana Ahmad <code></p>

    <hr>

    <!-- Form Tambah / Edit -->
    <h3><?= $data_edit ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa Baru' ?></h3>
    <form action="" method="POST">
        <div class="form-group">
            <label>NIM</label>
            <input type="text" name="nim" value="<?= $data_edit['nim'] ?? '' ?>" <?= $data_edit ? 'readonly' : '' ?> required placeholder="Contoh: 152024001">
        </div>

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= $data_edit['nama'] ?? '' ?>" required placeholder="Masukkan nama...">
        </div>

        <div class="form-group">
            <label>Program Studi</label>
            <input type="text" name="prodi" value="<?= $data_edit['prodi'] ?? '' ?>" placeholder="Contoh: Informatika">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= $data_edit['email'] ?? '' ?>" placeholder="nama@mhs.itenas.ac.id">
        </div>

        <div class="form-actions">
            <?php if ($data_edit): ?>
                <button type="submit" name="update">Simpan Perubahan</button>
                <a href="index.php" class="btn-batal">Batal</a>
            <?php else: ?>
                <button type="submit" name="tambah">Tambah Data</button>
            <?php endif; ?>
        </div>
    </form>

    <hr>

    <!-- Tabel Tampil Data -->
    <h3>Daftar Mahasiswa</h3>
    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Program Studi</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data_mahasiswa)): ?>
            <tr>
                <td><?= htmlspecialchars($row['nim']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['prodi']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td class="action-links">
                    <a href="index.php?edit=<?= $row['nim'] ?>">Edit</a>
                    <a href="index.php?hapus=<?= $row['nim'] ?>" class="page-hapus hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>