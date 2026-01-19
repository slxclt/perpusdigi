<?php
// crud/update_book.php
require_once __DIR__ . "/../config/config.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = intval($_POST['book_id']);
    $judul = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $tahun = $_POST['tahun_terbit'] ?? null;
    $stok = intval($_POST['stok'] ?? 0);

    $stmt = $conn->prepare("UPDATE books SET judul=?, penulis=?, tahun_terbit=?, stok=? WHERE book_id=?");
    $stmt->bind_param("ssiii", $judul, $penulis, $tahun, $stok, $book_id);
    if ($stmt->execute()) {
        header("Location: ../pages/admin/books.php?msg=updated");
        exit;
    } else {
        die("Gagal update buku: " . $conn->error);
    }
}
