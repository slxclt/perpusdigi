<?php
// pages/admin/books.php
require_once __DIR__ . "/../../config/config.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

// ambil daftar buku
$books = $conn->query("SELECT * FROM books ORDER BY book_id DESC");

include __DIR__ . "/../../includes/header.php";
?>
<link href="/perpusdigi/assets/css/admin.css?v=<?php echo time(); ?>" rel="stylesheet">
<link rel="icon" href="http://localhost/perpusdigi/assets/img/book.png" type="image/x-icon">


<div class="d-flex justify-content-between align-items-center">
  <h3>Kelola Buku</h3>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Buku</button>
</div>

<?php if(isset($_GET['msg'])): ?>
  <div class="alert alert-success mt-2">
    Operasi berhasil: <?php echo htmlspecialchars($_GET['msg']); ?>
  </div>
<?php endif; ?>

<div class="table-responsive-custom mt-3">
  <table class="table custom-table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Judul</th>
      <th>Penulis</th>
      <th>Tahun</th>
      <th>Stok</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php while($b = $books->fetch_assoc()): ?>
      <tr>
        <td><?php echo $b['book_id']; ?></td>
        <td><?php echo htmlspecialchars($b['judul']); ?></td>
        <td><?php echo htmlspecialchars($b['penulis']); ?></td>
        <td><?php echo $b['tahun_terbit']; ?></td>
        <td><?php echo $b['stok']; ?></td>
        <td>
          <!-- Edit: buka modal -->
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $b['book_id']; ?>">Edit</button>
          <a class="btn btn-sm btn-danger" href="/perpusdigi/crud/delete_book.php?id=<?php echo $b['book_id']; ?>" onclick="return confirm('Hapus buku?')">Hapus</a>
        </td>
      </tr>

      <!--Edit Buku -->
<div class="modal fade" id="editModal<?php echo $b['book_id']; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content custom-modal">

      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-semibold">Edit Buku 📘</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="post" action="/perpusdigi/crud/update_book.php">
        <div class="modal-body pt-2">
          <input type="hidden" name="book_id" value="<?php echo $b['book_id']; ?>">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Judul Buku</label>
              <input type="text" name="judul" value="<?php echo htmlspecialchars($b['judul']); ?>" class="form-control fancy-input" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Penulis</label>
              <input type="text" name="penulis" value="<?php echo htmlspecialchars($b['penulis']); ?>" class="form-control fancy-input">
            </div>

            <div class="col-md-6">
              <label class="form-label">Tahun Terbit</label>
              <input type="number" name="tahun_terbit" value="<?php echo $b['tahun_terbit']; ?>" class="form-control fancy-input">
            </div>

            <div class="col-md-6">
              <label class="form-label">Stok Buku</label>
              <input type="number" name="stok" value="<?php echo $b['stok']; ?>" min="0" class="form-control fancy-input">
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 mt-2">
          <button type="button" class="btn btn-outline-light cancel-btn" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-gradient">Simpan Perubahan</button>
        </div>
      </form>

    </div>
  </div>
</div>
    <?php endwhile; ?>
  </tbody>
  </table>
</div>
<!-- Modal Tambah Buku -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content custom-modal">

      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-semibold">Tambah Buku Baru 📚</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="post" action="/perpusdigi/crud/create_book.php" enctype="multipart/form-data">
        <div class="modal-body pt-2">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Judul Buku</label>
              <input type="text" name="judul" class="form-control fancy-input" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Penulis</label>
              <input type="text" name="penulis" class="form-control fancy-input">
            </div>

            <div class="col-md-6">
              <label class="form-label">Tahun Terbit</label>
              <input type="number" name="tahun_terbit" class="form-control fancy-input">
            </div>

            <div class="col-md-6">
              <label class="form-label">Stok Buku</label>
              <input type="number" name="stok" class="form-control fancy-input" min="0" value="1">
            </div>
            
            <div class="col-12">
              <label class="form-label">Gambar Buku</label>
              <input type="file" name="image" class="form-control fancy-input" accept="image/*">
              <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 mt-2">
          <button type="button" class="btn btn-outline-light cancel-btn" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-gradient">Tambah Buku</button>
        </div>
      </form>

    </div>
  </div>
</div>
<script>
// Jika backdrop tetap muncul, hapus otomatis saat modal ditampilkan
document.addEventListener('shown.bs.modal', function () {
  const backdrop = document.querySelector('.modal-backdrop');
  if (backdrop) backdrop.remove();
});
</script>
<br>
  <button class="btn btn-success" onclick="window.location.href='/perpusdigi/pages/admin/dashboard.php'">Kembali ke Dashboard</button>
  <!-- Tombol mode gelap -->
<button class="theme-toggle" id="themeToggle">🌙 Mode Gelap</button>

<script>
  const toggle = document.getElementById("themeToggle");
  const html = document.documentElement;
  const savedTheme = localStorage.getItem("theme");

  if (savedTheme) {
    html.setAttribute("data-theme", savedTheme);
    toggle.textContent = savedTheme === "dark" ? "☀️" : "🌙";
  }

  toggle.addEventListener("click", () => {
    const current = html.getAttribute("data-theme");
    const next = current === "dark" ? "light" : "dark";
    html.setAttribute("data-theme", next);
    localStorage.setItem("theme", next);
    toggle.textContent = next === "dark" ? "☀️" : "🌙";
  });
</script>

<?php include __DIR__ . "/../../includes/footer.php"; ?>
