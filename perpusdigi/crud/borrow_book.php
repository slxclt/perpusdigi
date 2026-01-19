<?php
// crud/borrow_book.php
require_once __DIR__ . "/../config/config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_id = intval($_POST['book_id']);
    $tanggal_pinjam = date('Y-m-d');

    // cek stok
    $stmt = $conn->prepare("SELECT stok FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($stok);
    if ($stmt->fetch()) {
        if ($stok <= 0) {
            $_SESSION['flash_error'] = "Buku tidak tersedia (stok habis).";
            header("Location: ../pages/siswa/books.php");
            exit;
        }
    } else {
        $_SESSION['flash_error'] = "Buku tidak ditemukan.";
        header("Location: ../pages/siswa/books.php");
        exit;
    }
    $stmt->close();

    // buat record peminjaman
    $status = 'dipinjam';
    $stmt = $conn->prepare("INSERT INTO borrow_records (user_id, book_id, tanggal_pinjam, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $book_id, $tanggal_pinjam, $status);
    if ($stmt->execute()) {
        // kurangi stok
        $stmt2 = $conn->prepare("UPDATE books SET stok = stok - 1 WHERE book_id = ?");
        $stmt2->bind_param("i", $book_id);
        $stmt2->execute();
        $stmt2->close();

        $_SESSION['flash_success'] = "Berhasil meminjam buku.";
        header("Location: ../pages/siswa/books.php");
        exit;
    } else {
        die("Gagal meminjam: " . $conn->error);
    }
}
