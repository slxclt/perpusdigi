<?php
// pages/siswa/history.php
require_once __DIR__ . "/../../config/config.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT br.borrow_id, b.judul, br.tanggal_pinjam, br.tanggal_kembali, br.status 
    FROM borrow_records br 
    JOIN books b ON br.book_id = b.book_id 
    WHERE br.user_id = ? 
    ORDER BY br.borrow_id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Riwayat Peminjaman | PerpusDigi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="http://localhost/perpusdigi/assets/css/siswa.css" rel="stylesheet">
    <link rel="icon" href="http://localhost/perpusdigi/assets/img/book.png" type="image/x-icon">

 
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar d-flex justify-content-between align-items-center">
    <a href="#" class="brand">📘 PerpusDigi Siswa</a>
    <div class="nav-actions d-flex align-items-center">
      <span class="me-3">Halo, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Siswa'); ?></span>
      <form action="../../auth/logout.php" method="post" class="m-0">
        <button type="submit" class="btn btn-light btn-sm">Logout</button>
      </form>
    </div>
  </nav>

  <!-- Konten -->
  <div class="container-custom">
    <h3>Riwayat Peminjaman Saya</h3>
    <div class="table-responsive">
      <table class="table align-middle text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($res->num_rows > 0): ?>
            <?php while($r = $res->fetch_assoc()): ?>
              <tr>
                <td><?php echo $r['borrow_id']; ?></td>
                <td><?php echo htmlspecialchars($r['judul']); ?></td>
                <td><?php echo $r['tanggal_pinjam']; ?></td>
                <td><?php echo $r['tanggal_kembali'] ?: '-'; ?></td>
                <td>
                  <?php if ($r['status'] === 'Dipinjam'): ?>
                    <span class="badge bg-warning text-dark">Dipinjam</span>
                  <?php elseif ($r['status'] === 'Dikembalikan'): ?>
                    <span class="badge bg-success">Dikembalikan</span>
                  <?php else: ?>
                    <span class="badge bg-secondary"><?php echo $r['status']; ?></span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-muted">Belum ada riwayat peminjaman.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="text-center mt-4">
      <button class="btn-dashboard" onclick="window.location.href='/perpusdigi/pages/siswa/dashboard.php'">⬅ Kembali ke Dashboard</button>
    </div>
  </div>

</body>
</html>
