<?php
require_once __DIR__ . "/../../config/config.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$count_books = $conn->query("SELECT COUNT(*) as c FROM books")->fetch_assoc()['c'];
$count_users = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='siswa'")->fetch_assoc()['c'];
$count_borrows = $conn->query("SELECT COUNT(*) as c FROM borrow_records")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin | PerpusDigi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="http://localhost/perpusdigi/assets/css/dashboard.css" rel="stylesheet">
    <link rel="icon" href="http://localhost/perpusdigi/assets/img/book.png" type="image/x-icon">

</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <a href="#" class="brand">📚 PerpusDigi Admin</a>
    <div class="nav-actions ms-auto d-flex align-items-center">
      <span class="me-3 text-light">Halo, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
      <form action="../../auth/logout.php" method="post" class="m-0">
      <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
      </form>
    </div>
  </nav>

  <!-- Dashboard Container -->
  <div class="app-container fade-in">
    <h3 class="mb-4 text-center">Dashboard Admin</h3>
    <div class="row justify-content-center">
      <!-- Buku -->
      <div class="col-md-4 mb-3">
        <div class="card-custom">
          <h5>Buku</h5>
          <div class="card-content">
            <p class="display-6"><?php echo $count_books; ?></p>
            <img src="http://localhost/perpusdigi/assets/img/book.gif" alt="Icon Buku" class="icon-img">
          </div>
          <a href="books.php" class="btn btn-primary w-100">Kelola Buku</a>
        </div>
      </div>

      <!-- Anggota -->
      <div class="col-md-4 mb-3">
        <div class="card-custom">
          <h5>Anggota</h5>
          <div class="card-content">
            <p class="display-6"><?php echo $count_users; ?></p>
            <img src="http://localhost/perpusdigi/assets/img/user.gif" alt="Icon Anggota" class="icon-img">
          </div>
          <a href="members.php" class="btn btn-primary w-100">Daftar Anggota</a>
        </div>
      </div>

      <!-- Riwayat -->
      <div class="col-md-4 mb-3">
        <div class="card-custom">
          <h5>Riwayat</h5>
          <div class="card-content">
            <p class="display-6"><?php echo $count_borrows; ?></p>
            <img src="http://localhost/perpusdigi/assets/img/history.gif" alt="Icon Riwayat" class="icon-img">
          </div>
          <a href="borrow_history.php" class="btn btn-primary w-100">Riwayat Peminjaman</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
