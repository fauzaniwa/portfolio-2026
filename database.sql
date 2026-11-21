CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4;
USE portfolio;
CREATE TABLE settings(user_id INT NOT NULL DEFAULT 1,k VARCHAR(40),v TEXT,PRIMARY KEY(user_id,k));
CREATE TABLE projects(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL DEFAULT 1,title VARCHAR(150),category VARCHAR(40),summary VARCHAR(255),description TEXT,link VARCHAR(255),tags VARCHAR(255),year VARCHAR(10),cover VARCHAR(80),video VARCHAR(255),slug VARCHAR(120),views INT DEFAULT 0,likes INT DEFAULT 0,status VARCHAR(12) DEFAULT 'published',sort_order INT DEFAULT 0);
CREATE TABLE clients(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL DEFAULT 1,name VARCHAR(100),project VARCHAR(150),url VARCHAR(255),logo VARCHAR(80),sort_order INT DEFAULT 0);
CREATE TABLE achievements(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL DEFAULT 1,title VARCHAR(200),org VARCHAR(150),year VARCHAR(10),sort_order INT DEFAULT 0);
CREATE TABLE skills(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL DEFAULT 1,name VARCHAR(60),category VARCHAR(40),sort_order INT DEFAULT 0);
INSERT INTO settings(k,v) VALUES
('name','Muhammad Fauzan Aztera'),('nick','Iwa'),
('tagline','Game developer, VR/AR, UI/UX, dan project manager yang membawa budaya Nusantara ke media interaktif.'),
('about','Saya Iwa, lulusan DKV UPI (IPK 3,86, 3,5 tahun) yang berbasis di Pekanbaru dan Bandung. Saya Founder & CEO PT Dang Creative Media (DangDang Studio), memimpin belasan kreator membangun game edukasi, pengalaman AR/VR, animasi, dan platform web. Saya menjembatani estetika visual dengan logika teknis, dan senang mengangkat budaya Melayu Riau ke medium modern.'),
('email',''),('whatsapp',''),('instagram',''),('linkedin',''),('github','');
INSERT INTO projects(title,category,summary,description,tags,year,sort_order) VALUES
('Pacu Jalur','Game Dev','Game edukasi budaya pemenang nasional.','Game berbasis Unity yang mengangkat tradisi Pacu Jalur. Juara 1 Kompetisi Cipta Game Berbasis Budaya IP-Nesia, Kementerian Kebudayaan.','Unity,C#,WebGL','2024',1),
('Art of Trying','Animation','Animasi pendek 2D/3D.','Animasi pendek tentang proses dan ketekunan.','Animasi,Motion','2024',2),
('Legenda Putri Naga','Animation','Animasi legenda Nusantara.','Adaptasi legenda Putri Naga dalam animasi.','Animasi,Budaya','2025',3);
INSERT INTO clients(name,project,sort_order) VALUES('Gojek','Freelance interactive',1),('IQOS','Freelance interactive',2),('Oriflame','Freelance interactive',3),('Signature','Freelance interactive',4);
INSERT INTO achievements(title,org,year,sort_order) VALUES
('Juara 1 Cipta Game Berbasis Budaya IP-Nesia','Kementerian Kebudayaan','2024',1),
('Juara 2 Cipta Game Edukasi Pajak','Direktorat Jenderal Pajak','2024',2),
('Juara 3 Hackathon UNESCO x Nakara','UNESCO x Nakara','2023',3),
('Juara 3 Desain UI/UX Mobile App','Cuxion','2023',4),
('Finalis Indie Game Ignite','Compfest','2023',5);
INSERT INTO skills(name,category,sort_order) VALUES
('Unity (C#)','Game & XR',1),('WebGL','Game & XR',2),('AR/VR','Game & XR',3),('Game design','Game & XR',4),
('Figma','Desain',5),('UI/UX','Desain',6),('Animasi 2D/3D','Desain',7),('Motion graphics','Desain',8),
('Laravel','Web',9),('PHP','Web',10),('Node.js','Web',11),('MySQL','Web',12),('Tailwind CSS','Web',13),
('Memimpin tim kreatif','Manajemen',14),('Perencanaan proyek','Manajemen',15);

CREATE TABLE IF NOT EXISTS project_media(id INT AUTO_INCREMENT PRIMARY KEY,project_id INT,file VARCHAR(80),sort_order INT DEFAULT 0,INDEX(project_id));
CREATE TABLE IF NOT EXISTS events(id BIGINT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL DEFAULT 1,type VARCHAR(30),ref VARCHAR(100),pid INT NULL,vid CHAR(16),device VARCHAR(10),referrer VARCHAR(150),created DATETIME DEFAULT CURRENT_TIMESTAMP,INDEX(type,created),INDEX(pid));
CREATE TABLE IF NOT EXISTS likes(pid INT,vid CHAR(16),PRIMARY KEY(pid,vid));
CREATE TABLE IF NOT EXISTS services(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL DEFAULT 1,title VARCHAR(60),description VARCHAR(255),sort_order INT DEFAULT 0);
INSERT INTO services(title,description,sort_order) VALUES
('Game Dev','Game edukasi, gamifikasi, dan proyek komersial dengan Unity (C#, WebGL).',1),
('VR/AR','Pengalaman imersif AR/VR yang interaktif untuk edukasi dan brand.',2),
('UI/UX','Antarmuka mobile dan web yang jelas, enak dipakai, dan memecahkan masalah nyata.',3),
('Project Management','Memimpin belasan kreator di DangDang Studio dari ide sampai rilis.',4);

CREATE TABLE IF NOT EXISTS history(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL DEFAULT 1,type VARCHAR(20),title VARCHAR(150),org VARCHAR(150),years VARCHAR(60),description VARCHAR(255),sort_order INT DEFAULT 0);
INSERT INTO history(type,title,org,years,description,sort_order) VALUES
('Pendidikan','S1 Desain Komunikasi Visual','Universitas Pendidikan Indonesia (FPSD)','Lulus dalam 3,5 tahun','IPK 3,86. Ketua UKM Isola Digital Media dan Head of Multimedia Finder 7th DKV UPI.',1),
('Pendidikan','SMA Negeri 11 Pekanbaru','Pekanbaru','','Ketua MPK, Wakil Ketua Jurnalistik, basket, dan Olimpiade Informatika.',2),
('Pekerjaan','Founder, CEO & Director','PT Dang Creative Media (DangDang Studio)','Desember 2025 - sekarang','Memimpin studio kreatif digital dengan belasan tim kreatif.',1),
('Pekerjaan','Game Designer (magang)','PT Kinema Systrans Multimedia','','',2),
('Pekerjaan','Penata Layanan Operasional','Kementerian Kebudayaan','','',3);
UPDATE projects SET slug=LOWER(REPLACE(title,' ','-')) WHERE slug IS NULL OR slug='';

CREATE TABLE IF NOT EXISTS users(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(30) UNIQUE,email VARCHAR(120) UNIQUE,pass VARCHAR(255),role VARCHAR(10) DEFAULT 'user',status VARCHAR(12) DEFAULT 'active',theme VARCHAR(20) DEFAULT 'arcade',palette TEXT,created DATETIME DEFAULT CURRENT_TIMESTAMP,last_login DATETIME NULL);
CREATE TABLE IF NOT EXISTS platform(k VARCHAR(40) PRIMARY KEY,v VARCHAR(255));
