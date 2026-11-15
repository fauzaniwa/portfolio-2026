USE portfolio;
ALTER TABLE projects ADD slug VARCHAR(120);
CREATE TABLE IF NOT EXISTS history(id INT AUTO_INCREMENT PRIMARY KEY,type VARCHAR(20),title VARCHAR(150),org VARCHAR(150),years VARCHAR(60),description VARCHAR(255),sort_order INT DEFAULT 0);
INSERT INTO history(type,title,org,years,description,sort_order) VALUES
('Pendidikan','S1 Desain Komunikasi Visual','Universitas Pendidikan Indonesia (FPSD)','Lulus dalam 3,5 tahun','IPK 3,86. Ketua UKM Isola Digital Media dan Head of Multimedia Finder 7th DKV UPI.',1),
('Pendidikan','SMA Negeri 11 Pekanbaru','Pekanbaru','','Ketua MPK, Wakil Ketua Jurnalistik, basket, dan Olimpiade Informatika.',2),
('Pekerjaan','Founder, CEO & Director','PT Dang Creative Media (DangDang Studio)','Desember 2025 - sekarang','Memimpin studio kreatif digital dengan belasan tim kreatif.',1),
('Pekerjaan','Game Designer (magang)','PT Kinema Systrans Multimedia','','',2),
('Pekerjaan','Penata Layanan Operasional','Kementerian Kebudayaan','','',3);
UPDATE projects SET slug=LOWER(REPLACE(title,' ','-')) WHERE slug IS NULL OR slug='';
