-- Migration: Add notifikasi_sent, sent_at and email_logs table

ALTER TABLE tb_pembayaran_supplier
  ADD COLUMN IF NOT EXISTS notifikasi_sent TINYINT(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS sent_at DATETIME NULL;

CREATE TABLE IF NOT EXISTS email_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_pembayaran INT NULL,
  recipient VARCHAR(255) NOT NULL,
  invoice_number VARCHAR(100) NULL,
  sent_at DATETIME NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  response_text TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
