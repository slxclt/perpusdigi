<?php
// crud/return_book.php
require_once __DIR__ . "/../config/config.php";
session_start();

// hanya admin dapat menandai kembali OR peminjam sendiri (opsional)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['borrow_id'])) {
    $borrow_id = intval($_POST['borrow_id']);
    $tanggal_kembali = date('Y-m-d');
    $status = 'dikembalikan';

    // Cari record
    $stmt = $conn->prepare("SELECT book_id FROM borrow_records WHERE borrow_id = ? AND status = 'dipinjam'");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    $stmt->bind_result($book_id);
    if ($stmt->fetch()) {
        $stmt->close();
        // update status dan tanggal kembali
        $stmt2 = $conn->prepare("UPDATE borrow_records SET status=?, tanggal_kembali=? WHERE borrow_id=?");
        $stmt2->bind_param("ssi", $status, $tanggal_kembali, $borrow_id);
        if ($stmt2->execute()) {
            // tambah stok buku
            $stmt3 = $conn->prepare("UPDATE books SET stok = stok + 1 WHERE book_id = ?");
            $stmt3->bind_param("i", $book_id);
            $stmt3->execute();
            $stmt3->close();

            $_SESSION['flash_success'] = "Buku berhasil dikembalikan.";
            header("Location: ../pages/admin/borrow_history.php");
            exit;
        } else {
            die("Gagal update peminjaman: " . $conn->error);
        }
    } else {
        $_SESSION['flash_error'] = "Record tidak ditemukan atau sudah dikembalikan.";
        header("Location: ../pages/admin/borrow_history.php");
        exit;
    }
}
