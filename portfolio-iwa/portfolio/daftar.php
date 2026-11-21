<?php require 'config.php';session_start();
seed();$mode=plat('signup','open');if($mode==='closed')fail(403,'Pendaftaran akun sedang ditutup oleh pemilik platform.');
function tk(){return $_SESSION['t']??=bin2hex(random_bytes(16));}
function ok(){if(!hash_equals($_SESSION['t']??'',$_POST['t']??''))die('Token tidak valid');}
$err='';$done=0;$th0=plat('default_theme','arcade');
if($_POST){ok();$un=strtolower(trim($_POST['u']??''));$em=strtolower(trim($_POST['e']??''));$pw=$_POST['p']??'';$th=isset(THEMES[$_POST['theme']??''])?$_POST['theme']:$th0;
 if(time()-($_SESSION['su']??0)<20)$err='Terlalu cepat. Coba lagi sebentar.';
 elseif(!uvalid($un))$err='Username 3-30 karakter: huruf kecil, angka, tanda - atau _, dan bukan kata yang dicadangkan.';
 elseif(!filter_var($em,FILTER_VALIDATE_EMAIL))$err='Email tidak valid.';
 elseif(strlen($pw)<8)$err='Kata sandi minimal 8 karakter.';
 else{$x=db()->prepare("SELECT COUNT(*) FROM users WHERE username=? OR email=?");$x->execute([$un,$em]);
  if($x->fetchColumn())$err='Username atau email sudah terdaftar.';
  else{$_SESSION['su']=time();$st=$mode==='approval'?'pending':'active';$id=mkuser($un,$em,$pw,'user',$st,$th);
   if($st==='active'){session_regenerate_id(true);$_SESSION['uid']=$id;$_SESSION['role']='user';header('Location: app/');exit;}$done=1;}}}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Daftar | <?=e(plat('name','Portfolio'))?></title>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;800&family=Permanent+Marker&family=DM+Mono&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script><script src="assets/tw.js"></script><link rel="stylesheet" href="assets/site.css"><link rel="stylesheet" href="assets/admin.css"></head>
<body class="s1 font-d antialiased min-h-screen"><div class="gb fixed inset-0"></div>
<main class="relative max-w-md mx-auto px-5 py-14"><a href="./" class="font-extrabold text-xl">← <?=e(plat('name','Portfolio'))?></a>
<?php if($done):?><div class="panel mt-8 !p-8"><p class="font-h text-xl mk">Terima kasih!</p><h1 class="font-extrabold text-3xl mt-1">Pendaftaran diterima</h1><p class="mu mt-3">Akun Anda menunggu persetujuan pemilik platform. Setelah disetujui, Anda bisa masuk lewat halaman CMS.</p><a class="btn mt-6" href="app/">Ke halaman masuk <i>→</i></a></div>
<?php else:?><form method="post" class="panel mt-8 space-y-4 !p-8"><p class="font-h text-xl mk">Buat portfolio Anda</p><h1 class="font-extrabold text-4xl">Daftar<span class="mk">.</span></h1>
<?php if($err):?><p class="text-cor text-sm"><?=e($err)?></p><?php endif?>
<input type="hidden" name="t" value="<?=tk()?>">
<div><label class="lbl" for="u">Username</label><input id="u" name="u" class="inp" value="<?=e($_POST['u']??'')?>" placeholder="namaanda" autocomplete="username" required><p class="hint">Alamat situs Anda: <?=e($_SERVER['HTTP_HOST'])?>/u/<b>username</b></p></div>
<div><label class="lbl" for="e">Email</label><input id="e" name="e" type="email" class="inp" value="<?=e($_POST['e']??'')?>" autocomplete="email" required></div>
<div><label class="lbl" for="p">Kata sandi</label><input id="p" name="p" type="password" class="inp" minlength="8" autocomplete="new-password" required></div>
<div><label class="lbl">Tema awal</label><div class="seg"><?php foreach(THEMES as $k=>$X):?><label><input type="radio" name="theme" value="<?=$k?>" <?=$k===$th0?'checked':''?>><span><?=e($X[0])?></span></label><?php endforeach?></div><p class="hint">Bisa diganti dan dikustomisasi kapan saja di CMS.</p></div>
<button class="btn w-full justify-center">Buat akun <i>→</i></button>
<p class="text-sm mu text-center">Sudah punya akun? <a class="mk font-bold underline" href="app/">Masuk</a><?=$mode==='approval'?' · Akun baru perlu disetujui dulu.':''?></p></form><?php endif?></main></body></html>
