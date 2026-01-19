<?php
// pages/siswa/books.php
require_once __DIR__ . "/../../config/config.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../auth/login.php");
    exit;
}

// Pencarian
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $conn->prepare("SELECT * FROM books WHERE judul LIKE ? OR penulis LIKE ? ORDER BY book_id DESC");
    $like = "%$q%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $books = $stmt->get_result();
} else {
    $books = $conn->query("SELECT * FROM books ORDER BY book_id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Buku | PerpusDigi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="http://localhost/perpusdigi/assets/css/siswa.css" rel="stylesheet">
    <link rel="icon" href="http://localhost/perpusdigi/assets/img/book.png" type="image/x-icon">

</head>
<body>
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
    <h3>Daftar Buku</h3>

    <?php if(!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['flash_success'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form method="get" class="mb-4">
      <div class="input-group">
        <input name="q" placeholder="Cari judul atau penulis..." class="form-control" value="<?php echo htmlspecialchars($q); ?>">
        <button class="btn btn-search">Cari</button>
      </div>
    </form>

    <!-- Tabel Buku -->
    <div class="table-responsive">
      <div class="row row-cols-1 row-cols-md-3 g-4">
          <?php if ($books->num_rows > 0): ?>
            <?php while($b = $books->fetch_assoc()): ?>
              <div class="col">
                <div class="card h-100">
                  <?php if ($b['image_path']): ?>
                    <img src="<?php echo htmlspecialchars($b['image_path']); ?>" class="card-img-top" alt="Cover buku" style="height: 250px; object-fit: cover;">
                  <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                      <span class="text-muted">Tidak ada gambar</span>
                    </div>
                  <?php endif; ?>
                  <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($b['judul']); ?></h5>
                    <p class="card-text">
                      <strong>Penulis:</strong> <?php echo htmlspecialchars($b['penulis']); ?><br>
                      <strong>Tahun:</strong> <?php echo $b['tahun_terbit']; ?><br>
                      <strong>Stok:</strong> <?php echo $b['stok']; ?>
                    </p>
                    
                    <?php if($b['stok'] > 0): ?>
                      <form method="post" action="/perpusdigi/crud/borrow_book.php" class="mt-2">
                        <input type="hidden" name="book_id" value="<?php echo $b['book_id']; ?>">
                        <button class="btn-pinjam btn-sm w-100">Pinjam Buku</button>
                      </form>
                    <?php else: ?>
                      <div class="mt-2">
                        <span class="badge bg-danger">Stok Habis</span>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info">Tidak ada buku ditemukan.</div>
            </div>
          <?php endif; ?>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="text-center mt-4">
      <button class="btn-dashboard" onclick="window.location.href='/perpusdigi/pages/siswa/dashboard.php'">⬅ Kembali ke Dashboard</button>
    </div>
  </div>

</body>
</html>
