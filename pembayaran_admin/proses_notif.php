<?php
require_once '../db/conn.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) die('ID tidak valid');

if ($action == 'set') {
    // Ambil data pembayaran
    $pq = mysqli_query($conn, "SELECT p.*, s.email, p.id_keluar FROM tb_pembayaran_supplier p JOIN tb_supplier s ON p.id_supplier = s.id_supplier WHERE p.id_pembayaran = $id") or die(mysqli_error($conn));
    $prow = mysqli_fetch_assoc($pq);

    // Generate invoice file via cetak_invoice.php (capture output)
    $invoice_name = isset($prow['invoice_number']) && !empty($prow['invoice_number']) ? $prow['invoice_number'] : 'invoice_' . $prow['id_keluar'];
    $invoice_dir = __DIR__ . "/../invoices";
    if (!is_dir($invoice_dir)) mkdir($invoice_dir, 0755, true);
    $invoice_file = $invoice_dir . '/' . $invoice_name . '.pdf';

    ob_start();
    $_GET['id_keluar'] = $prow['id_keluar'];
    include __DIR__ . '/../pembayaran_supplier_admin/cetak_invoice.php';
    $pdf_content = ob_get_clean();
    file_put_contents($invoice_file, $pdf_content);

    // Update payment record to indicate notification (email) is available (no SMTP send)
    $col_check = mysqli_query($conn, "SHOW COLUMNS FROM tb_pembayaran_supplier LIKE 'notifikasi_sent'");
    if (mysqli_num_rows($col_check) == 0) {
        mysqli_query($conn, "ALTER TABLE tb_pembayaran_supplier ADD COLUMN notifikasi_sent TINYINT(1) DEFAULT 0") or die(mysqli_error($conn));
    }
    $col_sent_at = mysqli_query($conn, "SHOW COLUMNS FROM tb_pembayaran_supplier LIKE 'sent_at'");
    if (mysqli_num_rows($col_sent_at) == 0) {
        mysqli_query($conn, "ALTER TABLE tb_pembayaran_supplier ADD COLUMN sent_at DATETIME NULL") or die(mysqli_error($conn));
    }

    mysqli_query($conn, "UPDATE tb_pembayaran_supplier SET notifikasi_sent = 1, sent_at = NOW() WHERE id_pembayaran = $id") or die(mysqli_error($conn));

    // Create email_logs table if not exists, then insert a log entry to show invoice saved but email disabled
    $create_logs_sql = "CREATE TABLE IF NOT EXISTS email_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_pembayaran INT NULL,
        recipient VARCHAR(255) NOT NULL,
        invoice_number VARCHAR(100) NULL,
        sent_at DATETIME NULL,
        success TINYINT(1) NOT NULL DEFAULT 0,
        response_text TEXT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    mysqli_query($conn, $create_logs_sql) or die(mysqli_error($conn));

    $to = isset($prow['email']) ? $prow['email'] : '';
    $stmt = "INSERT INTO email_logs (id_pembayaran, recipient, invoice_number, sent_at, success, response_text)
             VALUES ('" . intval($id) . "', '" . mysqli_real_escape_string($conn, $to) . "', '" . mysqli_real_escape_string($conn, $invoice_name) . "', NOW(), 0, 'Email sending disabled by user; invoice saved to server')";
    mysqli_query($conn, $stmt) or die(mysqli_error($conn));

    header('Location: index.php');
} elseif ($action == 'unset') {
    mysqli_query($conn, "UPDATE tb_pembayaran_supplier SET notifikasi_sent = 0 WHERE id_pembayaran = $id") or die(mysqli_error($conn));
    header('Location: index.php');
} else {
    die('Aksi tidak dikenal');
}

?>