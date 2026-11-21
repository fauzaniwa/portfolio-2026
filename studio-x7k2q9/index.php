<?php
// Konsol host: kelola semua akun, pantau perkembangan, atur platform. Hanya untuk role admin.
require __DIR__.'/../config.php';require __DIR__.'/../auth.php';require __DIR__.'/../charts.php';
session_set_cookie_params(['httponly'=>true,'samesite'=>'Strict','path'=>'/']);session_start();
header('X-Robots-Tag: noindex, nofollow');
function tk(){return $_SESSION['t']??=bin2hex(random_bytes(16));}
function ok(){if(!hash_equals($_SESSION['t']??'',$_POST['t']??''))die('Token tidak valid');}
function qv($s){return db()->query($s)->fetchColumn();}
function qa($s){return db()->query($s)->fetchAll();}
function ago($t){if(!$t)return 'belum pernah';$d=time()-strtotime($t);return $d<60?'baru saja':($d<3600?floor($d/60).' mnt lalu':($d<86400?floor($d/3600).' jam lalu':floor($d/86400).' hari lalu'));}
function act($id,$a,$l,$c='ab'){return "<form method=post class=inline><input type=hidden name=t value='".tk()."'><input type=hidden name=act value=$a><input type=hidden name=id value=$id><button class='$c'>$l</button></form>";}
function wipe($id){$d=db();$f=[];
 foreach(['projects'=>'cover','clients'=>'logo'] as $t=>$c)foreach($d->query("SELECT $c x FROM $t WHERE user_id=$id") as $r)$f[]=$r['x'];
 foreach($d->query("SELECT m.file x FROM project_media m JOIN projects p ON p.id=m.project_id WHERE p.user_id=$id") as $r)$f[]=$r['x'];
 foreach($d->query("SELECT v x FROM settings WHERE user_id=$id AND k IN('photo','photo_about','cv')") as $r)$f[]=$r['x'];
 foreach($f as $x)if($x)@unlink(__DIR__.'/../uploads/'.basename($x));
 $d->exec("DELETE m FROM project_media m JOIN projects p ON p.id=m.project_id WHERE p.user_id=$id");$d->exec("DELETE l FROM likes l JOIN projects p ON p.id=l.pid WHERE p.user_id=$id");
 foreach(['projects','clients','achievements','skills','services','history','events','settings'] as $t)$d->exec("DELETE FROM $t WHERE user_id=$id");$d->exec("DELETE FROM users WHERE id=$id");
 $dir=__DIR__."/../backups/u$id";foreach(glob("$dir/*")?:[] as $x)@unlink($x);@rmdir($dir);}
$H='<!doctype html><html lang=id><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1"><meta name=robots content=noindex><title>Konsol host</title><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;800&family=Permanent+Marker&family=DM+Mono&display=swap" rel=stylesheet><script src="https://cdn.tailwindcss.com"></script><script src="../assets/tw.js"></script><link rel=stylesheet href="../assets/site.css"><link rel=stylesheet href="../assets/admin.css"><body class="s1 font-d antialiased">';
if(isset($_GET['out'])){session_destroy();header('Location: ./');exit;}
if(empty($_SESSION['uid'])||($_SESSION['role']??'')!=='admin')do_login(true);
$U=(int)$_SESSION['uid'];$ME=qa("SELECT * FROM users WHERE id=$U")[0]??null;
if(!$ME||$ME['role']!=='admin'||$ME['status']!=='active'){session_destroy();header('Location: ./');exit;}
$t=in_array($_GET['t']??'',['users','settings'])?$_GET['t']:'overview';

if($_POST){ok();$a=$_POST['act']??'';$id=(int)($_POST['id']??0);$tg=$id?(qa("SELECT * FROM users WHERE id=$id")[0]??null):null;$root=$tg&&(int)$tg['id']===1;
 if($a=='approve'&&$tg){db()->exec("UPDATE users SET status='active' WHERE id=$id");$_SESSION['f']='Akun '.$tg['username'].' disetujui.';}
 elseif($a=='activate'&&$tg){db()->exec("UPDATE users SET status='active' WHERE id=$id");$_SESSION['f']='Akun '.$tg['username'].' diaktifkan.';}
 elseif($a=='suspend'&&$tg&&!$root){db()->exec("UPDATE users SET status='suspended' WHERE id=$id");$_SESSION['f']='Akun '.$tg['username'].' dinonaktifkan. Situsnya tidak lagi tampil.';}
 elseif($a=='resetpw'&&$tg){$pw=substr(bin2hex(random_bytes(6)),0,10);db()->prepare("UPDATE users SET pass=? WHERE id=?")->execute([password_hash($pw,PASSWORD_DEFAULT),$id]);$_SESSION['f']='Sandi sementara '.$tg['username'].': '.$pw.' (catat sekarang, tidak ditampilkan lagi).';}
 elseif($a=='delete'&&$tg&&!$root){wipe($id);$_SESSION['f']='Akun '.$tg['username'].' dan seluruh datanya dihapus.';}
 elseif($a=='imp'&&$tg&&$tg['status']==='active'&&$id!==$U){$_SESSION['imp']=$U;$_SESSION['uid']=$id;$_SESSION['role']=$tg['role'];header('Location: ../app/');exit;}
 elseif($a=='create'){$un=strtolower(trim($_POST['u']??''));$em=strtolower(trim($_POST['e']??''));$pw=$_POST['p']??'';
  if(!uvalid($un)||!filter_var($em,FILTER_VALIDATE_EMAIL)||strlen($pw)<8)$_SESSION['f']='Data akun tidak valid (username 3-30 karakter, email benar, sandi minimal 8).';
  else{$x=db()->prepare("SELECT COUNT(*) FROM users WHERE username=? OR email=?");$x->execute([$un,$em]);
   if($x->fetchColumn())$_SESSION['f']='Username atau email sudah dipakai.';
   else{mkuser($un,$em,$pw,'user','active',isset(THEMES[$_POST['theme']??''])?$_POST['theme']:'arcade');$_SESSION['f']='Akun '.$un.' dibuat.';}}}
 elseif($a=='plat'){foreach(['name'=>$_POST['name']??'','signup'=>in_array($_POST['signup']??'',['open','approval','closed'],true)?$_POST['signup']:'open','default_theme'=>isset(THEMES[$_POST['default_theme']??''])?$_POST['default_theme']:'arcade'] as $k=>$v)db()->prepare("REPLACE INTO platform(k,v) VALUES(?,?)")->execute([$k,substr($v,0,80)]);$_SESSION['f']='Pengaturan platform disimpan.';}
 header('Location: ?t='.$t);exit;}

$grp=[['overview','▦','Ringkasan'],['users','☺','Akun pengguna'],['settings','⚙','Pengaturan platform']];
$tt=['overview'=>['Ringkasan platform','Perkembangan seluruh akun dalam satu tampilan'],'users'=>['Akun pengguna','Pantau, setujui, atau kelola setiap akun'],'settings'=>['Pengaturan platform','Atur siapa yang boleh mendaftar dan tema awal']][$t];
echo $H;?>
<div class="lay">
<aside class="sb s2"><div class="brand font-extrabold text-2xl mb-2"><?=e(strtolower(plat('name','host')))?><span class="mk">.</span> <span class="font-h text-sm mk">konsol</span></div>
<p class="grp">Platform</p><?php foreach($grp as [$k,$i,$l]):?><a class="nv2 <?=$k==$t?'on':''?>" href="?t=<?=$k?>"><span><?=$i?></span><?=$l?><?php if($k=='users'):?><span class="cn"><?=qv("SELECT COUNT(*) FROM users")?></span><?php endif?></a><?php endforeach?>
<div class="md:mt-auto flex md:flex-col gap-1"><a class="nv2" href="../app/"><span>◈</span>Kelola situs saya</a><a class="nv2" target="_blank" href="../"><span>◉</span>Lihat situs utama</a><button id="th" class="nv2 text-left"><span>◐</span>Ganti tema</button><a class="nv2" href="?out=1"><span>⏻</span>Keluar</a></div></aside>
<main class="s1 flex-1 min-w-0 relative"><div class="gb absolute inset-x-0 top-0 h-72 pointer-events-none opacity-40"></div><div class="relative max-w-6xl mx-auto p-5 md:p-9">
<div class="flex flex-wrap items-end gap-4 mb-7"><div class="flex-1 min-w-[14rem]"><p class="font-h text-lg mk">Halo, <?=e($ME['username'])?></p><h1 class="font-extrabold text-4xl"><?=e($tt[0])?></h1><p class="mu"><?=e($tt[1])?></p></div>
<?php if($t=='overview'):?><div class="flex gap-2"><?php foreach([7,14,30] as $d):?><a class="chip <?=(($_GET['r']??14)==$d)?'on':''?>" href="?t=overview&r=<?=$d?>"><?=$d?> hari</a><?php endforeach?></div>
<?php elseif($t=='users'):?><button class="btn mag" data-open="#nw">+ Tambah akun</button><?php endif?></div>

<?php if($t=='overview'):
$n=in_array($_GET['r']??'',['7','14','30'])?(int)$_GET['r']:14;$ds=[];for($i=$n-1;$i>=0;$i--)$ds[]=date('Y-m-d',strtotime("-$i day"));
$S0="created>=CURDATE()-INTERVAL ".($n-1)." DAY";$P0="created>=CURDATE()-INTERVAL ".(2*$n-1)." DAY AND created<CURDATE()-INTERVAL ".($n-1)." DAY";$S0e=str_replace('created','e.created',$S0);
$pv=[];$uq=[];foreach(qa("SELECT DATE(created) d,COUNT(*) c,COUNT(DISTINCT vid) u FROM events WHERE type='pageview' AND $S0 GROUP BY d") as $r){$pv[$r['d']]=(int)$r['c'];$uq[$r['d']]=(int)$r['u'];}
$sa=array_map(fn($d)=>$pv[$d]??0,$ds);$sb=array_map(fn($d)=>$uq[$d]??0,$ds);
$st=array_column(qa("SELECT status,COUNT(*) c FROM users GROUP BY status"),'c','status');
$vc=array_sum($sa);$vp=(int)qv("SELECT COUNT(*) FROM events WHERE type='pageview' AND $P0");$vu=(int)qv("SELECT COUNT(DISTINCT vid) FROM events WHERE type='pageview' AND $S0");
$su=array_column(qa("SELECT DATE(created) d,COUNT(*) c FROM users WHERE $S0 GROUP BY d"),'c','d');$sn=array_map(fn($d)=>(int)($su[$d]??0),$ds);
if($vp>0){$pc=round(($vc-$vp)/$vp*100);$dl=$pc>=0?['up','▲ '.$pc.'%']:['dn','▼ '.abs($pc).'%'];}elseif($vc>0)$dl=['up','baru'];else $dl=['z','—'];
$K=[['Total akun',array_sum($st),'Semua akun terdaftar','☺','#D6FF3D',null],['Akun aktif',(int)($st['active']??0),'Situsnya tampil ke publik','✓','#B7A4FF',null],['Menunggu persetujuan',(int)($st['pending']??0),'Perlu tindakan Anda','⏳','#FFB8D9',null],['Dinonaktifkan',(int)($st['suspended']??0),'Situs disembunyikan','⏸','#FF4D5E',null],
 ['Kunjungan semua situs',$vc,'Total kunjungan seluruh akun','◉','#D6FF3D',$dl],['Pengunjung unik',$vu,'Orang berbeda yang datang','☺','#B7A4FF',null],['Total karya',(int)qv("SELECT COUNT(*) FROM projects"),'Karya yang diunggah semua akun','◈','#FFB8D9',null],['Pendaftar baru',array_sum($sn),'Akun baru di periode ini','✦','#FF4D5E',null]];?>
<p class="mu text-sm mb-3">Angka dihitung untuk <?=$n?> hari terakhir (kecuali total akun).</p>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4"><?php foreach($K as [$lb,$val,$hn,$ico,$col,$dd]):?><div class="kpi"><div class="ico" style="background:<?=$col?>"><?=$ico?></div><div><p class="kl"><?=$lb?></p><p class="kv" data-n="<?=$val?>"><?=$val?></p></div><p class="kd"><?php if($dd):?><span class="dl <?=$dd[0]?>"><?=$dd[1]?></span><?php endif?><?=$hn?></p></div><?php endforeach?></div>
<div class="grid lg:grid-cols-3 gap-4 mt-4"><div class="panel lg:col-span-2"><h3>Tren kunjungan seluruh platform</h3><p class="sub">Garis tebal: kunjungan per hari. Putus-putus: pengunjung unik.</p><?=lineChart($ds,$sa,$sb)?></div>
<div class="panel"><h3>Tema yang dipakai</h3><p class="sub">Pilihan tema semua akun.</p><?php $th=array_column(qa("SELECT theme,COUNT(*) c FROM users GROUP BY theme"),'c','theme');$dc=['var(--c1)','var(--c2)','#FF6A2B'];$lab=[];foreach($th as $k=>$v)$lab[$k]=THEMES[$k][0]??$k;
if($th):?><div class="flex flex-wrap items-center gap-4"><?=donut($th,$dc)?><ul class="text-sm space-y-1.5"><?php $i=0;foreach($th as $k=>$v):?><li><span style="color:<?=$dc[$i++%3]?>">●</span> <?=e($lab[$k])?> <b><?=$v?></b></li><?php endforeach?></ul></div><?php endif?></div></div>
<div class="grid lg:grid-cols-3 gap-4 mt-4"><div class="panel"><h3>Pendaftar per hari</h3><p class="sub">Akun baru di <?=$n?> hari terakhir.</p><div class="h-32 flex gap-1 items-end"><?php $mx=max(1,max($sn));foreach($sn as $i=>$v):?><div class="flex-1 h-full flex flex-col justify-end" title="<?=$ds[$i]?>: <?=$v?> akun"><div class="rounded-t" style="height:<?=round($v/$mx*100)?>%;min-height:2px;background:var(--c1)"></div></div><?php endforeach?></div></div>
<div class="panel"><h3>Situs paling ramai</h3><p class="sub">Berdasarkan kunjungan.</p><?php $ts=qa("SELECT u.username,COUNT(*) c FROM events e JOIN users u ON u.id=e.user_id WHERE e.type='pageview' AND $S0e GROUP BY e.user_id ORDER BY c DESC LIMIT 6");$m0=max(1,(int)($ts[0]['c']??1));foreach($ts as $r)echo hb($r['username'],(int)$r['c'],$m0);if(!$ts)echo '<p class="mu text-sm">Belum ada data.</p>'?></div>
<div class="panel"><h3>Akun terbaru</h3><p class="sub">Pendaftar paling baru.</p><?php foreach(qa("SELECT username,status,created FROM users ORDER BY id DESC LIMIT 6") as $r):?><div class="flex justify-between text-sm py-1.5 ln"><span class="truncate"><?=e($r['username'])?> <span class="badge"><?=e($r['status'])?></span></span><span class="mu text-xs shrink-0"><?=ago($r['created'])?></span></div><?php endforeach?></div></div>
<div class="panel mt-4"><h3>Aktivitas terbaru lintas akun</h3><p class="sub">12 kejadian terakhir di semua situs.</p><?php foreach(qa("SELECT e.type,e.ref,e.created,u.username FROM events e LEFT JOIN users u ON u.id=e.user_id ORDER BY e.id DESC LIMIT 12") as $r):?><div class="flex gap-3 py-2 ln items-center text-sm"><span class="badge ok shrink-0"><?=e($r['username']?:'?')?></span><span class="flex-1 truncate"><b><?=e($r['type'])?></b> <span class="mu"><?=e($r['ref'])?></span></span><span class="mu text-xs shrink-0"><?=ago($r['created'])?></span></div><?php endforeach?></div>

<?php elseif($t=='users'):
$us=qa("SELECT * FROM users ORDER BY id");$pj=[];foreach(qa("SELECT user_id,COUNT(*) c,COALESCE(SUM(views),0) v FROM projects GROUP BY user_id") as $r)$pj[$r['user_id']]=$r;
$vs=array_column(qa("SELECT user_id,COUNT(*) c FROM events WHERE type='pageview' AND created>=CURDATE()-INTERVAL 29 DAY GROUP BY user_id"),'c','user_id');
$sp=[];foreach(qa("SELECT user_id,DATE(created) d,COUNT(*) c FROM events WHERE type='pageview' AND created>=CURDATE()-INTERVAL 13 DAY GROUP BY user_id,d") as $r)$sp[$r['user_id']][$r['d']]=(int)$r['c'];
$cn=[];foreach(['clients','services','achievements'] as $tb)foreach(qa("SELECT user_id,COUNT(*) c FROM $tb GROUP BY user_id") as $r)$cn[$tb][$r['user_id']]=(int)$r['c'];
$fl=[];foreach(qa("SELECT user_id,k FROM settings WHERE k IN('tagline','about','photo','cv') AND v<>''") as $r)$fl[$r['user_id']][$r['k']]=1;
$last=array_column(qa("SELECT user_id,MAX(created) m FROM events GROUP BY user_id"),'m','user_id');
$d14=[];for($i=13;$i>=0;$i--)$d14[]=date('Y-m-d',strtotime("-$i day"));?>
<div class="flex flex-wrap gap-3 mb-5 items-center"><input id="q" class="inp max-w-xs" placeholder="Cari username atau email..."><div class="flex gap-2" id="stf"><?php foreach([''=>'Semua','active'=>'Aktif','pending'=>'Menunggu','suspended'=>'Nonaktif'] as $k=>$l):?><button class="chip <?=$k===''?'on':''?>" data-st="<?=$k?>"><?=$l?></button><?php endforeach?></div></div>
<div class="grid xl:grid-cols-2 gap-4"><?php foreach($us as $r):$i=(int)$r['id'];$pc=(int)($pj[$i]['c']??0);
 $pr=round((count($fl[$i]??[])+($pc>0)+(($cn['services'][$i]??0)>0)+(($cn['clients'][$i]??0)>0)+(($cn['achievements'][$i]??0)>0))/9*100);
 $sr=array_map(fn($d)=>$sp[$i][$d]??0,$d14);$url=$i===1?'../':'../index.php?u='.urlencode($r['username']);?>
<article class="item !items-start flex-wrap" data-q="<?=e(strtolower($r['username'].' '.$r['email']))?>" data-s="<?=e($r['status'])?>"><div class="thumb"><?=e(strtoupper(substr($r['username'],0,1)))?></div>
<div class="min-w-0 flex-1"><h3 class="font-extrabold truncate"><?=e($r['username'])?> <span class="badge <?=$r['status']==='active'?'ok':''?>"><?=e($r['status'])?></span><?php if($r['role']==='admin'):?> <span class="badge">host</span><?php endif?></h3>
<p class="mu text-sm truncate"><?=e($r['email'])?> / tema <?=e(THEMES[$r['theme']][0]??$r['theme'])?></p>
<div class="flex flex-wrap gap-1.5 mt-2"><span class="badge"><?=$pc?> karya</span><span class="badge"><?=(int)($pj[$i]['v']??0)?> views</span><span class="badge"><?=(int)($vs[$i]??0)?> kunjungan 30h</span><span class="badge">login <?=ago($r['last_login'])?></span><span class="badge">aktif <?=ago($last[$i]??null)?></span></div>
<div class="flex items-center gap-3 mt-3"><div class="flex-1"><div class="h-2 rounded-full overflow-hidden" style="background:var(--bd)"><i class="block h-full rounded-full" style="width:<?=$pr?>%;background:var(--c1)"></i></div><p class="text-xs mu mt-1">Kelengkapan profil <?=$pr?>%</p></div><div class="w-24 h-7"><?=spark($sr)?></div></div>
<div class="flex flex-wrap gap-1.5 mt-3"><a class="ab" target="_blank" href="<?=e($url)?>">Lihat situs</a>
<?php if($r['status']==='pending')echo act($i,'approve','Setujui');elseif($r['status']!=='active')echo act($i,'activate','Aktifkan');elseif($i!==1)echo act($i,'suspend','Nonaktifkan');
if($r['status']==='active'&&$i!==$U)echo act($i,'imp','Masuk sebagai');echo act($i,'resetpw','Reset sandi');if($i!==1):?><button type="button" class="ab rd" data-del="<?=$i?>" data-name="<?=e($r['username'])?>">Hapus</button><?php endif?></div></div></article>
<?php endforeach?></div>

<?php else:?>
<form method="post" class="panel space-y-5 max-w-2xl"><input type="hidden" name="t" value="<?=tk()?>"><input type="hidden" name="act" value="plat">
<div><label class="lbl" for="pn">Nama platform</label><input id="pn" name="name" class="inp" value="<?=e(plat('name',''))?>" placeholder="Misalnya: DangDang Folio"><p class="hint">Tampil di halaman pendaftaran.</p></div>
<div><label class="lbl">Pendaftaran akun</label><div class="seg"><?php foreach(['open'=>'Terbuka (langsung aktif)','approval'=>'Perlu persetujuan saya','closed'=>'Ditutup'] as $k=>$l):?><label><input type="radio" name="signup" value="<?=$k?>" <?=plat('signup','open')===$k?'checked':''?>><span><?=$l?></span></label><?php endforeach?></div><p class="hint">Mode "Perlu persetujuan" menahan akun baru sampai Anda setujui di tab Akun pengguna.</p></div>
<div><label class="lbl">Tema awal akun baru</label><div class="seg"><?php foreach(THEMES as $k=>$X):?><label><input type="radio" name="default_theme" value="<?=$k?>" <?=plat('default_theme','arcade')===$k?'checked':''?>><span><?=e($X[0])?></span></label><?php endforeach?></div></div>
<button class="btn">Simpan <i>✓</i></button></form><?php endif?>
</div></main></div>

<dialog id="nw" class="mini s1" style="max-width:28rem"><div class="flex justify-between items-start"><div><p class="eyebrow">Akun baru</p><h3 class="font-extrabold text-2xl">Tambah akun</h3></div><button type="button" data-close class="ib2" aria-label="Tutup">✕</button></div>
<form method="post" class="mt-4 space-y-3"><input type="hidden" name="t" value="<?=tk()?>"><input type="hidden" name="act" value="create">
<div><label class="lbl" for="nu">Username</label><input id="nu" name="u" class="inp" required></div><div><label class="lbl" for="ne">Email</label><input id="ne" name="e" type="email" class="inp" required></div>
<div><label class="lbl" for="np">Kata sandi sementara</label><input id="np" name="p" class="inp" minlength="8" required></div>
<div><label class="lbl">Tema</label><div class="seg"><?php foreach(THEMES as $k=>$X):?><label><input type="radio" name="theme" value="<?=$k?>" <?=$k==='arcade'?'checked':''?>><span><?=e($X[0])?></span></label><?php endforeach?></div></div>
<div class="flex justify-end gap-2"><button type="button" data-close class="btn2">Batal</button><button class="btn">Buat akun <i>✓</i></button></div></form></dialog>
<dialog id="cf" class="mini s1"><form method="post"><input type="hidden" name="t" value="<?=tk()?>"><input type="hidden" name="act" value="delete"><input type="hidden" name="id" id="cdel"><h3 class="font-extrabold text-xl">Hapus akun ini?</h3><p class="mu mt-2">Akun "<b id="cnm"></b>" beserta semua karya, media, dan statistiknya akan dihapus permanen.</p><div class="flex justify-end gap-2 mt-5"><button type="button" data-close class="btn2">Batal</button><button class="btn !bg-cor !text-white">Ya, hapus</button></div></form></dialog>
<?php if(!empty($_SESSION['f'])):?><div id="toast" role="status"><?=e($_SESSION['f'])?></div><?php unset($_SESSION['f']);endif?>
<script>
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
$('#th').onclick=()=>{localStorage[TK]=document.documentElement.classList.toggle('dark')?'d':'l'};
document.addEventListener('click',e=>{const T=e.target;const o=T.closest('[data-open]');if(o){$(o.dataset.open).showModal();return}
 const x=T.closest('[data-close]');if(x){x.closest('dialog').close();return}
 const d=T.closest('[data-del]');if(d){$('#cnm').textContent=d.dataset.name;$('#cdel').value=d.dataset.del;$('#cf').showModal();return}
 if(T.tagName=='DIALOG')T.close()},true);
let sf='';const ap=()=>{const v=($('#q')||{value:''}).value.toLowerCase();$$('.item').forEach(i=>i.hidden=!i.dataset.q.includes(v)||(sf&&i.dataset.s!=sf))};
const q=$('#q');if(q)q.oninput=ap;$$('#stf .chip').forEach(b=>b.onclick=()=>{sf=b.dataset.st;$$('#stf .chip').forEach(c=>c.classList.toggle('on',c==b));ap()});
$$('[data-n]').forEach(x=>{const n=+x.dataset.n;if(n<2)return;let s=null;const f=t=>{s??=t;const k=Math.min((t-s)/1200,1);x.textContent=Math.round(n*(1-Math.pow(1-k,3)));if(k<1)requestAnimationFrame(f)};requestAnimationFrame(f)});
setTimeout(()=>$('#toast')?.remove(),6000);
</script>
