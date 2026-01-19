<?php
require_once __DIR__ . "/../config/config.php";
session_start();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($username === '' || $password === '' || $role === '') {
        $errors[] = "Semua field wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, password, role, nama FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $stmt->bind_result($user_id, $hash, $db_role, $nama);

        if ($stmt->fetch() && password_verify($password, $hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $db_role;
            $_SESSION['nama'] = $nama;

            if ($db_role === 'admin') {
                header("Location: ../welcome/dashboard.php");
            } else {
                header("Location: ../pages/siswa/dashboard.php"); 
            }
            exit;
        } else {
            $errors[] = "Username, password, atau role salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | PerpusDigi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="icon" href="http://localhost/perpusdigi/assets/img/book.png" type="image/x-icon">
  <style>
    :root {
      --blue1: #2563eb;
      --blue2: #4f46e5;
      --light-bg: rgba(255,255,255,0.7);
      --dark-bg: rgba(15,23,42,0.85);
      --light-text: #1e293b;
      --dark-text: #f8fafc;
      --transition-speed: 0.5s;
    }

    * {
      transition: background var(--transition-speed) ease, 
                  color var(--transition-speed) ease,
                  border var(--transition-speed) ease;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: url("http://localhost/perpusdigi/assets/img/library.jpg") no-repeat center center/cover;
      background-attachment: fixed;
      display: flex;
      flex-direction: column;
      height: 100vh;
      margin: 0;
      overflow: hidden;
      color: var(--light-text);
      position: relative;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInUp 1s ease forwards;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* overlay */
    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(255,255,255,0.55);
      backdrop-filter: blur(6px);
      z-index: 0;
      transition: background var(--transition-speed) ease;
    }

    body.dark::before {
      background: rgba(15,23,42,0.85);
    }

    /* Navbar */
    .navbar {
      position: relative;
      z-index: 2;
      background: rgba(255,255,255,0.2);
      backdrop-filter: blur(10px);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid rgba(255,255,255,0.3);
      color: var(--light-text);
      opacity: 0;
      transform: translateY(-30px);
      animation: slideDown 1s 0.3s ease forwards;
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    body.dark .navbar {
      background: rgba(15,23,42,0.5);
      border-bottom: 1px solid rgba(255,255,255,0.1);
      color: var(--dark-text);
    }

    .navbar .brand {
      font-weight: 600;
      font-size: 1.3rem;
      color: inherit;
      text-decoration: none;
    }

    .toggle-dark {
      background: rgba(255,255,255,0.25);
      border: 1px solid rgba(255,255,255,0.4);
      border-radius: 50%;
      color: #fff;
      width: 42px;
      height: 42px;
      font-size: 1.3rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .toggle-dark:hover { transform: scale(1.1); }

    /* Login box */
    .login-container {
      z-index: 2;
      margin: auto;
      background: var(--light-bg);
      padding: 2rem 2.5rem;
      border-radius: 18px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
      max-width: 400px;
      width: 90%;
      backdrop-filter: blur(8px);
      color: var(--light-text);
      opacity: 0;
      transform: scale(0.9);
      animation: scaleUp 0.9s 0.4s ease forwards;
    }

    @keyframes scaleUp {
      to { opacity: 1; transform: scale(1); }
    }

    body.dark .login-container {
      background: var(--dark-bg);
      color: var(--dark-text);
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .login-container h3 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }

    input, select {
      background: rgba(255,255,255,0.8);
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      padding: 10px;
      color: var(--light-text);
      width: 100%;
    }

    body.dark input,
    body.dark select {
      background: rgba(30,41,59,0.9);
      border: 1px solid #475569;
      color: var(--dark-text);
    }

    input:focus, select:focus {
      border-color: var(--blue2);
      outline: none;
      box-shadow: 0 0 6px var(--blue2);
    }

    label {
      color: inherit;
      font-weight: 500;
    }

    .btn-login {
      background: linear-gradient(90deg, var(--blue1), var(--blue2));
      color: white;
      border: none;
      border-radius: 12px;
      padding: 0.75rem;
      font-weight: 600;
      width: 100%;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(79,70,229,0.3);
    }

    .btn-register {
      display: block;
      text-align: center;
      margin-top: 1rem;
      color: var(--blue2);
      text-decoration: none;
    }

    body.dark .btn-register {
      color: #a5b4fc;
    }
    .select-wrapper {
  position: relative;
}

.select-wrapper::after {
  content: "▾";
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  color: var(--light-text);
  transition: color var(--transition-speed);
}

body.dark .select-wrapper::after {
  color: var(--dark-text);
}

select {
  appearance: none;
  -webkit-appearance: none;
  background: rgba(255, 255, 255, 0.85);
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  padding: 10px 40px 10px 12px;
  font-weight: 500;
  color: var(--light-text);
  transition: all 0.3s ease;
  cursor: pointer;
}

select:hover {
  background: rgba(255, 255, 255, 0.95);
  transform: scale(1.02);
}

select:focus {
  border-color: var(--blue2);
  box-shadow: 0 0 8px var(--blue2);
}

body.dark select {
  background: rgba(30, 41, 59, 0.9);
  border: 1px solid #475569;
  color: var(--dark-text);
}

body.dark select:hover {
  background: rgba(51, 65, 85, 0.95);
}

  </style>
</head>
<body>
  
  <nav class="navbar">
    <a href="#" class="brand">📚 PerpusDigi</a>
    <button class="toggle-dark" id="toggleDark" title="Ganti Mode">🌙</button>
  </nav>

  <div class="login-container">
    <h3>Masuk ke Akun</h3>
    <?php if($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul>
      </div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" required>
      </div>
      <div class="mb-3">
  <label class="form-label">Role</label>
  <div class="select-wrapper">
    <select name="role" required>
      <option value="">🔽 Pilih Role Anda</option>
      <option value="admin">👑 Admin</option>
      <option value="siswa">🎓 Siswa</option>
    </select>
  </div>
</div>
      <button class="btn-login" type="submit">Masuk</button>
      <a href="register.php" class="btn-register">Buat Akun Baru</a>
    </form>
  </div>

  <script>
    const toggleBtn = document.getElementById('toggleDark');
    const body = document.body;
    const dark = localStorage.getItem('darkMode') === 'true';
    if (dark) body.classList.add('dark');
    toggleBtn.textContent = dark ? '☀️' : '🌙';

    toggleBtn.addEventListener('click', () => {
      body.classList.toggle('dark');
      const active = body.classList.contains('dark');
      localStorage.setItem('darkMode', active);
      toggleBtn.textContent = active ? '☀️' : '🌙';
    });
  </script>
</body>
</html>
