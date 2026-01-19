<?php
// crud/create_book.php
require_once __DIR__ . "/../config/config.php";
session_start();

// hanya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $tahun = $_POST['tahun_terbit'] ?? null;
    $stok = intval($_POST['stok'] ?? 0);
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            if ($_FILES['image']['size'] <= 2000000) { // 2MB max
                $upload_dir = __DIR__ . "/../assets/uploads/books/";
                
                // Create directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                // Generate unique filename
                $new_filename = uniqid() . "." . $ext;
                $destination = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image_path = "/perpusdigi/assets/uploads/books/" . $new_filename;
                }
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO books (judul, penulis, tahun_terbit, stok, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiis", $judul, $penulis, $tahun, $stok, $image_path);
    if ($stmt->execute()) {
        header("Location: ../pages/admin/books.php?msg=added");
        exit;
    } else {
        die("Gagal menambah buku: " . $conn->error);
    }
}
