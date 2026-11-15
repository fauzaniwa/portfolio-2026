<?php require 'config.php';session_start();
$f=db()->query("SELECT v FROM settings WHERE k='cv'")->fetchColumn();$p=__DIR__.'/uploads/'.basename($f?:'x');
if(!$f||!is_file($p)){http_response_code(404);exit('CV belum diunggah.');}
if(empty($_SESSION['adm']))track('cv_download');
header('Content-Type: application/pdf');header('Content-Disposition: attachment; filename="CV-Muhammad-Fauzan-Aztera.pdf"');header('Content-Length: '.filesize($p));readfile($p);
