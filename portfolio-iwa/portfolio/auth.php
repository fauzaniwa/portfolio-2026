<?php // Login bersama untuk CMS pengguna dan konsol host (butuh $H, tk(), ok(), qv())
function do_login($adminOnly=false){global $H;seed();$err='';
 if($_POST){ok();
  if(($_SESSION['n']??0)>=5&&time()-($_SESSION['nt']??0)<300)$err='Terlalu banyak percobaan. Coba lagi 5 menit.';
  else{$id=strtolower(trim($_POST['u']??''));$q=db()->prepare("SELECT * FROM users WHERE username=? OR email=?");$q->execute([$id,$id]);$u=$q->fetch();
   if($u&&password_verify($_POST['p']??'',$u['pass'])&&(!$adminOnly||$u['role']==='admin')){
    if($u['status']==='pending')$err='Akun Anda masih menunggu persetujuan.';
    elseif($u['status']!=='active')$err='Akun Anda dinonaktifkan. Hubungi pemilik platform.';
    else{session_regenerate_id(true);$_SESSION['uid']=(int)$u['id'];$_SESSION['role']=$u['role'];$_SESSION['n']=0;db()->prepare("UPDATE users SET last_login=NOW() WHERE id=?")->execute([$u['id']]);header('Location: ./');exit;}}
   else{$_SESSION['n']=($_SESSION['n']??0)+1;$_SESSION['nt']=time();$err='Username atau password salah.';}}}
 $setup=!qv("SELECT COUNT(*) FROM users");
 echo $H.'<div class="gb fixed inset-0"></div><form method=post class="relative panel max-w-sm mx-auto mt-28 space-y-4 !p-8"><p class="font-h text-xl mk">'.($adminOnly?'Konsol host':'Selamat datang').'</p><h1 class="font-extrabold text-4xl">Masuk<span class="mk">.</span></h1>'
 .($err?"<p class='text-cor text-sm'>$err</p>":'').($setup&&!ADMIN_HASH?'<p class="text-sm text-amber-400">Isi ADMIN_HASH di config.php untuk membuat akun host pertama.</p>':'')
 .'<input type=hidden name=t value="'.tk().'"><input name=u placeholder="Username atau email" class="inp" autocomplete=username><input name=p type=password placeholder=Password class="inp" autocomplete=current-password><button class="btn w-full justify-center">Masuk <i>→</i></button>'
 .(!$adminOnly&&plat('signup','open')!=='closed'?'<p class="text-sm mu text-center">Belum punya akun? <a class="mk font-bold underline" href="../daftar.php">Daftar gratis</a></p>':'').'</form>';exit;}
