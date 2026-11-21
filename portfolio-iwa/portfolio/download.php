<?php require 'config.php';session_start();
maint();$q=db()->prepare("SELECT v FROM settings WHERE user_id=? AND k='cv'");$q->execute([$SITE['id']]);$f=$q->fetchColumn();$p=__DIR__.'/uploads/'.basename($f?:'x');
if(!$f||!is_file($p)){fail(404,'CV belum tersedia saat ini. Silakan hubungi saya lewat tombol kontak di beranda.');}
if(!owner())track('cv_download');
header('Content-Type: application/pdf');header('Content-Disposition: attachment; filename="CV-'.preg_replace('/[^a-z0-9_-]/i','',$SITE['username']).'.pdf"');header('Content-Length: '.filesize($p));readfile($p);
