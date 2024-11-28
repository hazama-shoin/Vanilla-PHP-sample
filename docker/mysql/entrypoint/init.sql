USE app;
CREATE TABLE users (
    id INT AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar_id VARCHAR(255),
    is_admin BOOLEAN NOT NULL DEFAULT false,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME,
    deleted_at DATETIME,
    PRIMARY KEY (id)
);
INSERT INTO users (email, name, password, is_admin) VALUES ('admin@test.com', '管理者 太郎', '$2y$10$Qgm3faUwXtiRWHF3vCOfIuGovS0trGMx0BFDB6sdwWcRNr5c31M6W', true);
CREATE TABLE deleted_users LIKE users;
ALTER TABLE deleted_users DROP INDEX email;
