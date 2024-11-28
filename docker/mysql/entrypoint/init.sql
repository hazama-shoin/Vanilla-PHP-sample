USE app;
CREATE TABLE users (
    id INT AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE COMMENT 'メールアドレス',
    name VARCHAR(100) NOT NULL COMMENT '会員名',
    password VARCHAR(255) NOT NULL COMMENT 'パスワード',
    avatar_id VARCHAR(255) COMMENT 'アバターID',
    is_admin BOOLEAN NOT NULL DEFAULT false COMMENT '管理者フラグ',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at DATETIME COMMENT '更新日時',
    deleted_at DATETIME COMMENT '削除日時',
    PRIMARY KEY (id)
);
INSERT INTO users (email, name, password, is_admin) VALUES ('admin@test.com', '管理者 太郎', '$2y$10$Qgm3faUwXtiRWHF3vCOfIuGovS0trGMx0BFDB6sdwWcRNr5c31M6W', true);
CREATE TABLE deleted_users LIKE users;
ALTER TABLE deleted_users DROP INDEX email;
