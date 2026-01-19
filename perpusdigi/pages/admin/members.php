<?php
// pages/admin/members.php
require_once __DIR__ . "/../../config/config.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$members = $conn->query("SELECT user_id, username, nama, role FROM users ORDER BY user_id DESC");
include __DIR__ . "/../../includes/header.php";
?>
<link href="http://localhost/perpusdigi/assets/css/admin.css" rel="stylesheet">
  <link rel="icon" href="http://localhost/perpusdigi/assets/img/book.png" type="image/x-icon">

<h3>Daftar Anggota</h3>
<div class="table-responsive-custom mt-3">
<table class="table custom-table table-striped">
  <thead><tr><th>#</th><th>Nama</th><th>Username</th><th>Role</th></tr></thead>
  <tbody>
    <?php while($m = $members->fetch_assoc()): ?>
      <tr>
        <td><?php echo $m['user_id']; ?></td>
        <td><?php echo htmlspecialchars($m['nama']); ?></td>
        <td><?php echo htmlspecialchars($m['username']); ?></td>
        <td><?php echo htmlspecialchars($m['role']); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
    </div>
    <br>
  <button class="btn btn-success" onclick="window.location.href='/perpusdigi/pages/admin/dashboard.php'">Kembali ke Dashboard</button>

<?php include __DIR__ . "/../../includes/footer.php"; ?>
