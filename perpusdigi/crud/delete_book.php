<?php
// crud/delete_book.php
require_once __DIR__ . "/../config/config.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: ../pages/admin/books.php?msg=deleted");
        exit;
    } else {
        die("Gagal hapus: " . $conn->error);
    }
}
