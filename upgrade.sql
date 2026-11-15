USE portfolio;
ALTER TABLE projects ADD video VARCHAR(255),ADD views INT DEFAULT 0,ADD likes INT DEFAULT 0;
CREATE TABLE IF NOT EXISTS project_media(id INT AUTO_INCREMENT PRIMARY KEY,project_id INT,file VARCHAR(80),sort_order INT DEFAULT 0,INDEX(project_id));
CREATE TABLE IF NOT EXISTS events(id BIGINT AUTO_INCREMENT PRIMARY KEY,type VARCHAR(30),ref VARCHAR(100),pid INT NULL,vid CHAR(16),device VARCHAR(10),referrer VARCHAR(150),created DATETIME DEFAULT CURRENT_TIMESTAMP,INDEX(type,created),INDEX(pid));
CREATE TABLE IF NOT EXISTS likes(pid INT,vid CHAR(16),PRIMARY KEY(pid,vid));
CREATE TABLE IF NOT EXISTS services(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(60),description VARCHAR(255),sort_order INT DEFAULT 0);
INSERT INTO services(title,description,sort_order) VALUES
('Game Dev','Game edukasi, gamifikasi, dan proyek komersial dengan Unity (C#, WebGL).',1),
('VR/AR','Pengalaman imersif AR/VR yang interaktif untuk edukasi dan brand.',2),
('UI/UX','Antarmuka mobile dan web yang jelas, enak dipakai, dan memecahkan masalah nyata.',3),
('Project Management','Memimpin belasan kreator di DangDang Studio dari ide sampai rilis.',4);
