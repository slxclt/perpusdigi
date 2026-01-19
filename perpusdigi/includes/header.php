<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>PerpusDigi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="http://localhost/perpusdigi/assets/css/library.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
  <div class="container">
    <a class="navbar-brand" href="/perpusdigi/index.php">📚PerpusDigi</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="/perpusdigi/auth/logout.php">Logout</a></li>
          
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/perpusdigi/auth/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
        </body>
        <script>
function openModal() {
  document.querySelector('.modal-overlay').classList.add('show-modal');
  document.querySelector('.modal').classList.add('show-modal');
}

function closeModal() {
  document.querySelector('.modal-overlay').classList.remove('show-modal');
  document.querySelector('.modal').classList.remove('show-modal');
}
</script>

