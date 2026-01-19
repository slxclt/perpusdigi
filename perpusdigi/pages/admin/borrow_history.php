<?php
// pages/admin/borrow_history.php
require_once __DIR__ . "/../../config/config.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$sql = "SELECT br.borrow_id, u.nama, b.judul, br.tanggal_pinjam, br.tanggal_kembali, br.status 
        FROM borrow_records br 
        JOIN users u ON br.user_id = u.user_id
        JOIN books b ON br.book_id = b.book_id
        ORDER BY br.borrow_id DESC";
$res = $conn->query($sql);
include __DIR__ . "/../../includes/header.php";
?>
<link href="http://localhost/perpusdigi/assets/css/admin.css" rel="stylesheet">
  <link rel="icon" href="http://localhost/perpusdigi/assets/img/book.png" type="image/x-icon">

<h3>Riwayat Peminjaman</h3>
<?php if(!empty($_SESSION['flash_success'])): ?><div class="alert alert-success"><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div><?php endif; ?>
<table class="table">
  <thead><tr><th>#</th><th>Nama</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
  <tbody>
    <?php while($r = $res->fetch_assoc()): ?>
      <tr>
        <td><?php echo $r['borrow_id']; ?></td>
        <td><?php echo htmlspecialchars($r['nama']); ?></td>
        <td><?php echo htmlspecialchars($r['judul']); ?></td>
        <td><?php echo $r['tanggal_pinjam']; ?></td>
        <td><?php echo $r['tanggal_kembali'] ?? '-'; ?></td>
        <td><?php echo $r['status']; ?></td>
        <td>
          <?php if($r['status'] === 'dipinjam'): ?>
            <form method="post" action="/perpusdigi/crud/return_book.php" style="display:inline">
              <input type="hidden" name="borrow_id" value="<?php echo $r['borrow_id']; ?>">
              <button class="btn btn-sm btn-success" onclick="return confirm('Tandai sebagai dikembalikan?')">Kembalikan</button>
            </form>
          <?php else: ?>
            <span class="text-success">Sudah dikembalikan</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<br>
  <button class="btn btn-success" onclick="window.location.href='/perpusdigi/pages/admin/dashboard.php'">Kembali ke Dashboard</button>

<?php include __DIR__ . "/../../includes/footer.php"; ?>
