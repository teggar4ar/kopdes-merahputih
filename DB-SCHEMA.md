CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    nik VARCHAR(16) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    profile_picture_url VARCHAR(255) NULL,
    ktp_image_url VARCHAR(255) NULL,
    account_status ENUM('pending_verification', 'active', 'rejected', 'inactive') NOT NULL DEFAULT 'pending_verification',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL -- [Baru] Untuk Soft Deletes
);

CREATE TABLE savings_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    transaction_type ENUM('setor', 'tarik') NOT NULL,
    savings_type ENUM('pokok', 'wajib', 'sukarela') NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    description TEXT NULL,
    transaction_proof_url VARCHAR(255) NULL, -- Untuk bukti setoran
    transaction_date TIMESTAMP NOT NULL,
    processed_by_admin_id BIGINT UNSIGNED NULL, -- Admin yang memproses
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (processed_by_admin_id) REFERENCES users(id)
);

CREATE TABLE loans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    principal_amount DECIMAL(15, 2) NOT NULL,
    interest_rate FLOAT NOT NULL, -- Suku bunga saat pengajuan (terkunci)
    duration_months INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed', 'disbursed') NOT NULL DEFAULT 'pending',
    reason TEXT NULL,
    application_date TIMESTAMP NOT NULL,
    approval_date TIMESTAMP NULL,
    approved_by_admin_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL, -- [Baru] Untuk Soft Deletes
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (approved_by_admin_id) REFERENCES users(id)
);

CREATE TABLE loan_installments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    loan_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    payment_date TIMESTAMP NOT NULL,
    transaction_proof_url VARCHAR(255) NOT NULL, -- Bukti pembayaran angsuran
    processed_by_admin_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (loan_id) REFERENCES loans(id),
    FOREIGN KEY (processed_by_admin_id) REFERENCES users(id)
);

CREATE TABLE announcements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL, -- [Baru] Untuk Soft Deletes
    FOREIGN KEY (admin_user_id) REFERENCES users(id)
);

CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description VARCHAR(255) NULL
);

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL, -- User yang melakukan aksi
    action VARCHAR(255) NOT NULL, -- Contoh: 'loan.approved', 'savings.deposit'
    description TEXT NOT NULL, -- Deskripsi lengkap aksi
    log_time TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Index untuk tabel users
ALTER TABLE users ADD INDEX users_email_index (email);
ALTER TABLE users ADD INDEX users_nik_index (nik);
ALTER TABLE users ADD INDEX users_account_status_index (account_status);

-- Index untuk tabel savings_transactions (SANGAT PENTING)
ALTER TABLE savings_transactions ADD INDEX savings_user_date_index (user_id, transaction_date);

-- Index untuk tabel loans
ALTER TABLE loans ADD INDEX loans_user_id_index (user_id);
ALTER TABLE loans ADD INDEX loans_status_index (status);

-- Index untuk tabel loan_installments
ALTER TABLE loan_installments ADD INDEX installments_loan_id_index (loan_id);

-- Index untuk tabel announcements
ALTER TABLE announcements ADD INDEX announcements_created_at_index (created_at);

-- Index untuk tabel audit_logs
ALTER TABLE audit_logs ADD INDEX audit_logs_user_action_index (user_id, action);
