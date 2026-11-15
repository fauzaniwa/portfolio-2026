<?php
require __DIR__.'/../config.php';
session_set_cookie_params(['httponly'=>true,'samesite'=>'Strict','path'=>'/']);session_start();
header('X-Robots-Tag: noindex, nofollow');
const IMG=['jpg','jpeg','png','webp','gif'];
function tk(){return $_SESSION['t']??=bin2hex(random_bytes(16));}
function ok(){if(!hash_equals($_SESSION['t']??'',$_POST['t']??''))die('Token tidak valid');}
function qv($s){return db()->query($s)->fetchColumn();}
function qa($s){return db()->query($s)->fetchAll();}
function ini1($s){return strtoupper(function_exists('mb_substr')?mb_substr($s,0,1):substr($s,0,1));}
function up($f,$ex){if(!$f||($f['error']??1)!==0)return;$x=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));if(!in_array($x,$ex))return;
 if($x=='pdf'?file_get_contents($f['tmp_name'],false,null,0,4)!=='%PDF':!@getimagesize($f['tmp_name']))return;
 $n=bin2hex(random_bytes(8)).".$x";move_uploaded_file($f['tmp_name'],__DIR__."/../uploads/$n");return $n;}
function ago($t){$d=time()-strtotime($t);return $d<60?'baru saja':($d<3600?floor($d/60).' mnt lalu':($d<86400?floor($d/3600).' jam lalu':floor($d/86400).' hari lalu'));}
$H='<!doctype html><html lang=id><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1"><meta name=robots content=noindex><title>CMS Portfolio</title><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;800&family=Permanent+Marker&family=DM+Mono&display=swap" rel=stylesheet><script src="https://cdn.tailwindcss.com"></script><script src="../assets/tw.js"></script><link rel=stylesheet href="../assets/site.css"><link rel=stylesheet href="../assets/admin.css"><body class="s1 font-d antialiased">';
if(isset($_GET['out'])){session_destroy();header('Location: ./');exit;}
if(empty($_SESSION['adm'])){$err='';
 if($_POST){ok();
  if(($_SESSION['n']??0)>=5&&time()-($_SESSION['nt']??0)<300)$err='Terlalu banyak percobaan. Coba lagi 5 menit.';
  elseif(ADMIN_HASH&&$_POST['u']===ADMIN_USER&&password_verify($_POST['p'],ADMIN_HASH)){session_regenerate_id(true);$_SESSION['adm']=1;header('Location: ./');exit;}
  else{$_SESSION['n']=($_SESSION['n']??0)+1;$_SESSION['nt']=time();$err='Username atau password salah.';}}
 echo $H.'<div class="gb fixed inset-0"></div><form method=post class="relative panel max-w-sm mx-auto mt-28 space-y-4 !p-8"><p class="font-h text-xl mk">Halo, Iwa</p><h1 class="font-extrabold text-4xl">Masuk<span class="mk">.</span></h1>'.($err?"<p class='text-cor text-sm'>$err</p>":'').(ADMIN_HASH?'':'<p class="text-sm text-amber-400">Isi ADMIN_HASH di config.php dulu.</p>').'<input type=hidden name=t value="'.tk().'"><input name=u placeholder=Username class="inp" autocomplete=username><input name=p type=password placeholder=Password class="inp" autocomplete=current-password><button class="btn w-full justify-center">Masuk <i>→</i></button></form>';exit;}

$T=[
'projects'=>['Karya','Portofolio yang tampil di beranda dan punya halaman detail sendiri',[
 'title'=>['Judul karya','text','Tampil sebagai judul kartu dan halaman detail','Informasi utama'],
 'summary'=>['Ringkasan singkat','text:140','Satu kalimat yang tampil di kartu karya'],
 'category'=>['Kategori','seg:Game Dev,VR/AR,UI/UX,Project Management,Animation,Web','Dipakai untuk filter di beranda'],
 'description'=>['Deskripsi lengkap','textarea','Ceritakan peran Anda, tantangan, dan hasilnya'],
 'year'=>['Tahun','short',''],'slug'=>['Alamat halaman','short','Kosongkan agar dibuat dari judul'],
 'tags'=>['Tag','text','Pisahkan dengan koma, misalnya: Unity, C#, WebGL'],
 'link'=>['Tautan proyek','text','URL game, demo, atau Behance (opsional)','Tautan dan media'],
 'video'=>['Video YouTube','text','Tempel tautan YouTube (opsional)'],
 'cover'=>['Foto sampul','file','Tampil di kartu, dan jadi gambar preview saat tautan dibagikan'],
 'status'=>['Status','seg:published|Terbit di situs,draft|Draft (disembunyikan)','Draft hanya terlihat lewat Preview situs','Publikasi'],
 'sort_order'=>['Urutan','number','Angka kecil tampil lebih dulu']]],
'services'=>['Layanan','Peran yang tampil sebagai #01 sampai #04 di beranda',[
 'title'=>['Nama layanan','text','Samakan dengan kategori karya agar bisa memfilter','Informasi'],'description'=>['Deskripsi singkat','textarea',''],'sort_order'=>['Urutan','number','']]],
'clients'=>['Klien','Brand dan institusi yang pernah bekerja sama dengan Anda',[
 'name'=>['Nama klien','text','','Informasi'],'project'=>['Jenis kerja sama','text','Misalnya: Freelance interactive'],'url'=>['Tautan','text','Website klien (opsional)'],
 'logo'=>['Logo','file','Kosongkan untuk menampilkan nama klien sebagai teks','Logo'],'sort_order'=>['Urutan','number','','Tampilan']]],
'achievements'=>['Prestasi','Penghargaan dan kompetisi yang pernah diraih',[
 'title'=>['Nama prestasi','text','Misalnya: Juara 1 Cipta Game Berbasis Budaya','Informasi'],'org'=>['Penyelenggara','text',''],'year'=>['Tahun','short',''],'sort_order'=>['Urutan','number','']]],
'skills'=>['Keahlian','Daftar kemampuan yang dikelompokkan di bagian Tentang',[
 'name'=>['Nama keahlian','text','','Informasi'],'category'=>['Kelompok','text','Misalnya: Game & XR, Desain, Web, Manajemen'],'sort_order'=>['Urutan','number','']]],
'history'=>['Pendidikan & Pekerjaan','Riwayat yang tampil di bagian Tentang',[
 'title'=>['Posisi atau jenjang','text','','Informasi'],'type'=>['Jenis','seg:Pendidikan,Pekerjaan',''],'org'=>['Institusi atau perusahaan','text',''],'years'=>['Periode','short','Misalnya: 2021 - 2025'],
 'description'=>['Keterangan','textarea',''],'sort_order'=>['Urutan','number','Yang paling atas dianggap "terakhir"']]],
'settings'=>['Pengaturan','Profil, foto, CV, dan kontak',[
 'name'=>['Nama lengkap','text','','Profil'],'nick'=>['Nama panggilan','short',''],'tagline'=>['Tagline','textarea','Kalimat pembuka di bagian hero dan deskripsi saat dibagikan'],'about'=>['Tentang saya','textarea',''],
 'photo'=>['Foto hero (header)','file','Foto utama di bagian atas beranda. Disarankan rasio 4:5','Foto'],'photo_about'=>['Foto profil (Tentang)','file','Foto berbeda untuk kartu polaroid di bagian Tentang'],
 'cv'=>['File CV (PDF)','pdf','Dipakai oleh semua tombol Unduh CV','Dokumen'],
 'email'=>['Email','text','','Kontak dan sosial media'],'whatsapp'=>['WhatsApp','short','Nomor dengan kode negara, misalnya 628123456789'],'instagram'=>['Instagram','short','Username tanpa @'],'linkedin'=>['LinkedIn','text','URL lengkap profil'],'github'=>['GitHub','short','Username']]]];
$t=$_GET['t']??'dashboard';if($t!='dashboard'&&!isset($T[$t]))$t='dashboard';

function drop($n,$v,$pdf=false){
 $pv=$pdf?'<div class=pdf>PDF'.($v?'<small>'.e($v).'</small>':'').'</div>':($v?"<img data-zoom src='../uploads/".e($v)."' alt=''>":'＋');
 return "<label class=drop data-drop><input type=file name=$n accept='".($pdf?'application/pdf':'image/*')."' hidden><div class=dz>$pv</div><div class=dt><b>".($pdf?'Pilih file PDF':'Tarik foto ke sini')."</b><span>atau klik untuk memilih. ".($pdf?'Hanya PDF.':'JPG, PNG, atau WEBP.')."</span><em data-fn>".($v?'Sudah ada file. Pilih lagi untuk mengganti.':'Belum ada file dipilih')."</em></div></label>";}
function galfld($id){$h="<div class='fld sm:col-span-2'><label class=lbl>Galeri foto</label><div class=gal>";
 foreach(($id?qa("SELECT * FROM project_media WHERE project_id=".(int)$id." ORDER BY sort_order,id"):[]) as $m)$h.="<label class=th><img data-zoom src='../uploads/".e($m['file'])."' alt=''><input type=checkbox name=rm[] value={$m['id']} hidden><span class=rm>Hapus</span></label>";
 return $h."</div><label class='drop slim' data-drop data-multi><input type=file name='media[]' multiple accept='image/*' hidden><div class=dt><b>Tambah foto galeri</b><span>Pilih banyak foto sekaligus atau tarik ke sini. Tidak ada batas jumlah.</span><em data-fn>Klik foto untuk memperbesar. Tombol Hapus menandai foto yang akan dihapus.</em></div></label></div>";}
function fld($n,$d,$v){[$l,$ty,$h]=$d;$k=explode(':',$ty,2);$wd=in_array($k[0],['short','number'])?'':'sm:col-span-2';
 $o="<div class='fld $wd'><label class=lbl for=f_$n>$l</label>";
 if($k[0]=='textarea')$o.="<textarea id=f_$n name=$n rows=6 class=inp>".e($v)."</textarea>";
 elseif($k[0]=='seg'){$op=explode(',',$k[1]);$v=$v!==''?$v:explode('|',$op[0])[0];$o.='<div class=seg>';foreach($op as $x){$p=explode('|',$x);$o.="<label><input type=radio name=$n value='".e($p[0])."'".($p[0]==$v?' checked':'')."><span>".e($p[1]??$p[0])."</span></label>";}$o.='</div>';}
 elseif($k[0]=='file'||$k[0]=='pdf')$o.=drop($n,$v,$k[0]=='pdf');
 else{$ml=ctype_digit($k[1]??'')?" maxlength=$k[1] data-c":'';$o.="<input id=f_$n name=$n type=".($k[0]=='number'?'number':'text')." value='".e($v)."' class=inp$ml".(in_array($n,['title','name'])?' required':'').">";}
 return $o.($h?"<p class=hint>$h</p>":'').'</div>';}
function live($r){$cv=$r['cover']??'';return "<aside class=live><p class=eyebrow>Pratinjau kartu</p><div class='lc cutc mt-2'><div class=lc-i>".($cv?"<img src='../uploads/".e($cv)."' alt=''>":'')."</div><b data-lt class='block mt-2 text-lg leading-tight'>".e($r['title']??'')."</b><small data-lc class=opacity-70>".e($r['category']??'')."</small><p data-ls class='text-sm opacity-80 mt-1'>".e($r['summary']??'')."</p></div><p class=hint>Beginilah karya tampil di beranda. Berubah otomatis saat Anda mengetik.</p></aside>";}
function frm($t,$id){global $T;[$nm,$sb,$F]=$T[$t];
 $r=$t=='settings'?array_column(qa("SELECT k,v FROM settings"),'v','k'):($id?(qa("SELECT * FROM $t WHERE id=".(int)$id)[0]??[]):[]);
 $S=[];$cur='';foreach($F as $n=>$d){$cur=$d[3]??$cur;$S[$cur][]=fld($n,$d,$r[$n]??'');if($t=='projects'&&$n=='cover')$S[$cur][]=galfld($id);}
 $b='';foreach($S as $g=>$l)$b.="<section class=fs><h3>$g</h3><div class='grid sm:grid-cols-2 gap-4'>".implode('',$l)."</div></section>";
 $lv=$t=='projects'?live($r):'';$f0=array_key_first($F);
 $ti=$t=='settings'?'Pengaturan situs':($id&&!empty($r[$f0])?$r[$f0]:'Tambah '.$nm);
 return "<form method=post enctype=multipart/form-data class=sheet-in><input type=hidden name=t value='".tk()."'><input type=hidden name=id value=$id><header class=sh-h><div class=flex-1><p class=eyebrow>".($t=='settings'?'Kelola':($id?'Ubah '.$nm:'Data baru'))."</p><h2 class='font-extrabold text-2xl'>".e($ti)."</h2></div>".($t=='settings'?'':"<button type=button data-close class=ib2 aria-label=Tutup>✕</button>")."</header><div class='sh-b".($lv?' has-live':'')."'><div>$b</div>$lv</div><footer class=sh-f>".($t=='settings'?'':'<button type=button data-close class=btn2>Batal</button>')."<button class=btn>Simpan <i>✓</i></button></footer></form>";}

if($_POST){ok();
 if(isset($_POST['del'])&&$t!='settings'&&isset($T[$t])){$i=(int)$_POST['del'];
  if($t=='projects'){db()->prepare("DELETE FROM project_media WHERE project_id=?")->execute([$i]);db()->prepare("DELETE FROM likes WHERE pid=?")->execute([$i]);}
  db()->prepare("DELETE FROM $t WHERE id=?")->execute([$i]);$_SESSION['f']='Data dihapus.';}
 elseif($t=='settings'){foreach($T['settings'][2] as $k=>$d){
   if(in_array($d[1],['file','pdf'])){if($n=up($_FILES[$k]??0,$d[1]=='pdf'?['pdf']:IMG))db()->prepare("REPLACE INTO settings(k,v) VALUES(?,?)")->execute([$k,$n]);}
   else db()->prepare("REPLACE INTO settings(k,v) VALUES(?,?)")->execute([$k,$_POST[$k]??'']);}$_SESSION['f']='Pengaturan disimpan.';}
 elseif(isset($T[$t])){$id=(int)($_POST['id']??0);$c=[];$v=[];
  if($t=='projects'){$sg=slug($_POST['slug']?:$_POST['title'])?:'karya';$q=db()->prepare("SELECT COUNT(*) FROM projects WHERE slug=? AND id<>?");$q->execute([$sg,$id]);if($q->fetchColumn())$sg.='-'.(time()%10000);$_POST['slug']=$sg;}
  foreach($T[$t][2] as $n=>$d){
   if($d[1]=='file'){if($nm=up($_FILES[$n]??0,IMG)){$c[]=$n;$v[]=$nm;}}
   else{$c[]=$n;$v[]=$d[1]=='number'?(int)($_POST[$n]??0):($_POST[$n]??'');}}
  if($id){$q="UPDATE $t SET ".implode(',',array_map(fn($x)=>"$x=?",$c))." WHERE id=?";$v[]=$id;}
  else $q="INSERT INTO $t(".implode(',',$c).") VALUES(".implode(',',array_fill(0,count($c),'?')).")";
  db()->prepare($q)->execute($v);$pid=$id?:(int)db()->lastInsertId();
  if($t=='projects'){
   foreach($_POST['rm']??[] as $m){$m=(int)$m;$fn=qv("SELECT file FROM project_media WHERE id=$m AND project_id=$pid");if($fn){@unlink(__DIR__.'/../uploads/'.basename($fn));db()->exec("DELETE FROM project_media WHERE id=$m");}}
   foreach($_FILES['media']['name']??[] as $k=>$z){$f=['name'=>$z,'tmp_name'=>$_FILES['media']['tmp_name'][$k],'error'=>$_FILES['media']['error'][$k]];
    if($nm=up($f,IMG))db()->prepare("INSERT INTO project_media(project_id,file) VALUES(?,?)")->execute([$pid,$nm]);}}
  $_SESSION['f']='Tersimpan.';}
 header("Location: ?t=$t");exit;}
if(isset($_GET['m'],$_GET['id'])&&isset($T[$t])&&$t!='settings'){echo frm($t,(int)$_GET['id']);exit;}

function spark($a){$n=count($a);if($n<2)return'';$mx=max(1,max($a));$p=[];foreach($a as $i=>$v)$p[]=round($i/($n-1)*80,1).','.round(24-$v/$mx*22,1);return "<svg viewBox='0 0 80 26' preserveAspectRatio='none'><polyline points='".implode(' ',$p)."' fill=none stroke-width=2 stroke-linecap=round stroke-linejoin=round style='stroke:var(--c1)'/></svg>";}
function hb($l,$v,$mx,$x=''){return "<div class=hb><span class=truncate title='".e($l)."'>".e($l)."</span><div><i style='width:".round($v/max(1,$mx)*100)."%'></i></div><b>$v$x</b></div>";}
function donut($rows,$cols){$tt=max(1,array_sum($rows));$r=52;$c=2*M_PI*$r;$o=0;$s='';foreach(array_values($rows) as $i=>$v){$l=$v/$tt*$c;$s.="<circle r=$r cx=70 cy=70 fill=none stroke-width=18 style='stroke:{$cols[$i%count($cols)]}' stroke-dasharray='$l ".($c-$l)."' stroke-dashoffset='".(-$o)."' transform='rotate(-90 70 70)'/>";$o+=$l;}
 return "<svg viewBox='0 0 140 140' class='w-40 h-40 shrink-0'>$s<text x=70 y=75 text-anchor=middle font-size=22 font-weight=800 style='fill:var(--fg)'>".array_sum($rows)."</text></svg>";}
function lineChart($lab,$a,$b){$W=640;$H=230;$px=34;$py=18;$n=count($lab);$mx=(int)(ceil(max(4,max($a),max($b))/4)*4);
 $x=fn($i)=>round($px+($W-$px-10)*($n>1?$i/($n-1):.5),1);$y=fn($v)=>round($H-$py-12-($H-2*$py-12)*$v/$mx,1);$g='';
 for($i=0;$i<=4;$i++){$v=$mx*$i/4;$g.="<line x1=$px x2=".($W-10)." y1=".$y($v)." y2=".$y($v)." style='stroke:var(--bd)'/><text x=".($px-6)." y=".($y($v)+4)." text-anchor=end font-size=10 style='fill:var(--mu)'>".round($v)."</text>";}
 $path=function($s)use($x,$y){$o='';foreach($s as $i=>$v)$o.=($i?'L':'M').$x($i).','.$y($v);return $o;};
 $area=$path($a).'L'.$x($n-1).','.$y(0).'L'.$x(0).','.$y(0).'Z';$xl='';$st=max(1,(int)ceil($n/8));
 foreach($lab as $i=>$l)if($i%$st==0||$i==$n-1)$xl.="<text x=".$x($i)." y=".($H-4)." text-anchor=middle font-size=10 style='fill:var(--mu)'>".date('d/m',strtotime($l))."</text>";
 $hit='';$w=($W-$px)/max($n,1);foreach($lab as $i=>$l)$hit.="<rect class=hit x=".round($x($i)-$w/2,1)." y=0 width=".round($w,1)." height=$H fill=transparent><title>".date('d M',strtotime($l)).": {$a[$i]} kunjungan, {$b[$i]} pengunjung unik</title></rect><circle cx=".$x($i)." cy=".$y($a[$i])." r=3 style='fill:var(--c1)'/>";
 return "<svg viewBox='0 0 $W $H' class=w-full>$g<path d='$area' style='fill:var(--c1);opacity:.12'/><path d='".$path($a)."' fill=none stroke-width=3 stroke-linejoin=round style='stroke:var(--c1)'/><path d='".$path($b)."' fill=none stroke-width=2.5 stroke-dasharray='5 4' style='stroke:var(--c2)'/>$xl$hit</svg>";}

$grp=['Ringkasan'=>['dashboard'],'Konten'=>['projects','services','clients','achievements','skills','history'],'Situs'=>['settings']];
$ic=['dashboard'=>'▦','projects'=>'◈','services'=>'✦','clients'=>'◍','achievements'=>'★','skills'=>'⌘','history'=>'☰','settings'=>'⚙'];
$tt=$t=='dashboard'?['Dashboard','Ringkasan aktivitas situs portfolio Anda']:$T[$t];
echo $H;?>
<div class="lay">
<aside class="sb s2"><div class="brand font-extrabold text-2xl mb-2">iwa<span class="mk">.</span> <span class="font-h text-sm mk">cms</span></div>
<?php foreach($grp as $g=>$ks):?><p class="grp"><?=$g?></p><?php foreach($ks as $k):?><a class="nv2 <?=$k==$t?'on':''?>" href="?t=<?=$k?>"><span><?=$ic[$k]?></span><?=$k=='dashboard'?'Dashboard':e($T[$k][0])?><?php if(!in_array($k,['dashboard','settings'])):?><span class="cn"><?=qv("SELECT COUNT(*) FROM $k")?></span><?php endif?></a><?php endforeach;endforeach?>
<div class="md:mt-auto flex md:flex-col gap-1"><a class="nv2" target="_blank" href="../?preview=1"><span>◉</span>Preview situs</a><button id="th" class="nv2 text-left"><span>◐</span>Ganti tema</button><a class="nv2" href="?out=1"><span>⏻</span>Keluar</a></div></aside>
<main class="s1 flex-1 min-w-0 relative"><div class="gb absolute inset-x-0 top-0 h-72 pointer-events-none opacity-40"></div>
<div class="relative max-w-6xl mx-auto p-5 md:p-9">
<div class="flex flex-wrap items-end gap-4 mb-7"><div class="flex-1 min-w-[14rem]"><p class="font-h text-lg mk">Halo, Iwa</p><h1 class="font-extrabold text-4xl"><?=e($tt[0])?></h1><p class="mu"><?=e($tt[1])?></p></div>
<?php if($t=='dashboard'):?><div class="flex gap-2"><?php foreach([7,14,30] as $d):?><a class="chip <?=(($_GET['r']??14)==$d)?'on':''?>" href="?t=dashboard&r=<?=$d?>"><?=$d?> hari</a><?php endforeach?></div>
<?php elseif($t!='settings'&&!isset($_GET['id'])):?><a class="btn mag" data-edit href="?t=<?=$t?>&id=0">+ Tambah <?=e($tt[0])?></a><?php endif?></div>

<?php if($t=='dashboard'):
$n=in_array($_GET['r']??'',['7','14','30'])?(int)$_GET['r']:14;$ds=[];for($i=$n-1;$i>=0;$i--)$ds[]=date('Y-m-d',strtotime("-$i day"));
$S0="created>=CURDATE()-INTERVAL ".($n-1)." DAY";$P0="created>=CURDATE()-INTERVAL ".(2*$n-1)." DAY AND created<CURDATE()-INTERVAL ".($n-1)." DAY";
$cur=[];foreach(qa("SELECT type,DATE(created) d,COUNT(*) c FROM events WHERE $S0 GROUP BY type,d") as $r)$cur[$r['type']][$r['d']]=(int)$r['c'];
$prv=array_column(qa("SELECT type,COUNT(*) c FROM events WHERE $P0 GROUP BY type"),'c','type');
$uq=array_column(qa("SELECT DATE(created) d,COUNT(DISTINCT vid) c FROM events WHERE type='pageview' AND $S0 GROUP BY d"),'c','d');
$uc=(int)qv("SELECT COUNT(DISTINCT vid) FROM events WHERE type='pageview' AND $S0");$up=(int)qv("SELECT COUNT(DISTINCT vid) FROM events WHERE type='pageview' AND $P0");
$ser=fn($ty)=>array_map(fn($d)=>$cur[$ty][$d]??0,$ds);$su=array_map(fn($d)=>(int)($uq[$d]??0),$ds);
$K=[['Kunjungan situs','pageview','Berapa kali situs dibuka','◉','#D6FF3D'],['Pengunjung unik',null,'Jumlah orang berbeda yang datang','☺','#B7A4FF'],['Karya dilihat','project_view','Halaman detail karya yang dibuka','◈','#FFB8D9'],['Like','like','Karya yang disukai pengunjung','♥','#FF4D5E'],['Unduh CV','cv_download','Berapa kali CV Anda diunduh','↓','#D6FF3D'],['Dibagikan','share','Karya yang dibagikan ke media sosial','↗','#B7A4FF'],['Klik kontak','contact','Klik ke Instagram, WhatsApp, email, dll.','✉','#FFB8D9'],['Foto diperbesar','gallery','Galeri foto yang dibuka lebih besar','▣','#FF4D5E']];?>
<p class="mu text-sm mb-3">Angka dihitung untuk <?=$n?> hari terakhir. Label ▲ ▼ membandingkan dengan <?=$n?> hari sebelumnya.</p>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
<?php foreach($K as [$lb,$ty,$hn,$ico,$col]):$sx=$ty?$ser($ty):$su;$c=$ty?array_sum($sx):$uc;$pv=$ty?(int)($prv[$ty]??0):$up;
 if($pv>0){$pc=round(($c-$pv)/$pv*100);$dl=$pc>=0?['up','▲ '.$pc.'%']:['dn','▼ '.abs($pc).'%'];}elseif($c>0)$dl=['up','baru'];else $dl=['z','—'];?>
<div class="kpi"><div class="ico" style="background:<?=$col?>"><?=$ico?></div><div><p class="kl"><?=$lb?></p><p class="kv" data-n="<?=$c?>"><?=$c?></p></div><?=spark($sx)?><p class="kd"><span class="dl <?=$dl[0]?>"><?=$dl[1]?></span><?=$hn?></p></div>
<?php endforeach?></div>

<div class="grid lg:grid-cols-3 gap-4 mt-4">
<div class="panel lg:col-span-2"><h3>Tren kunjungan</h3><p class="sub">Garis tebal = jumlah kunjungan per hari. Garis putus-putus = orang berbeda (unik). Arahkan kursor untuk detail.</p><?=lineChart($ds,$ser('pageview'),$su)?>
<p class="text-xs mu mt-2"><span style="color:var(--c1)">━</span> Kunjungan &nbsp; <span style="color:var(--c2)">╍</span> Pengunjung unik</p></div>
<div class="panel"><h3>Perangkat</h3><p class="sub">Pengunjung membuka situs dari apa?</p>
<?php $dv=array_column(qa("SELECT device,COUNT(*) c FROM events WHERE type='pageview' AND $S0 GROUP BY device ORDER BY c DESC"),'c','device');$dc=['var(--c1)','var(--c2)','#FF6A2B'];
if($dv):?><div class="flex flex-wrap items-center gap-4"><?=donut($dv,$dc)?><ul class="text-sm space-y-1.5"><?php $i=0;foreach($dv as $k=>$v):?><li><span style="color:<?=$dc[$i++%3]?>">●</span> <?=ucfirst($k)?> <b><?=$v?></b></li><?php endforeach?></ul></div><?php else:?><p class="mu text-sm">Belum ada data.</p><?php endif?></div></div>

<div class="grid lg:grid-cols-3 gap-4 mt-4">
<div class="panel lg:col-span-2"><h3>Karya terpopuler</h3><p class="sub">Diurutkan dari jumlah views sepanjang waktu.</p>
<?php $tp=qa("SELECT title,views,likes,(SELECT COUNT(*) FROM events e WHERE e.pid=projects.id AND e.type='share') sh FROM projects ORDER BY views DESC LIMIT 6");$mxv=max(1,(int)($tp[0]['views']??1));foreach($tp as $r)echo hb($r['title'],(int)$r['views'],$mxv,'').'<p class="mu text-xs -mt-1 mb-1 pl-1">'.(int)$r['likes'].' like, '.(int)$r['sh'].' kali dibagikan</p>';if(!$tp)echo '<p class="mu text-sm">Belum ada karya.</p>'?></div>
<div class="panel"><h3>Sumber kunjungan</h3><p class="sub">Dari mana pengunjung datang?</p>
<?php $rf=qa("SELECT IF(referrer='','Langsung / tidak diketahui',referrer) r,COUNT(*) c FROM events WHERE type='pageview' AND $S0 GROUP BY r ORDER BY c DESC LIMIT 6");$mr=max(1,(int)($rf[0]['c']??1));foreach($rf as $r)echo hb($r['r'],(int)$r['c'],$mr);if(!$rf)echo '<p class="mu text-sm">Belum ada data.</p>'?></div></div>

<div class="grid lg:grid-cols-3 gap-4 mt-4">
<div class="panel lg:col-span-2"><h3>Jam tersibuk</h3><p class="sub">Semakin terang kotaknya, semakin banyak yang berkunjung pada hari dan jam itu.</p>
<?php $hm=array_fill(0,7,array_fill(0,24,0));foreach(qa("SELECT DAYOFWEEK(created) d,HOUR(created) h,COUNT(*) c FROM events WHERE type='pageview' AND $S0 GROUP BY d,h") as $r)$hm[($r['d']+5)%7][(int)$r['h']]=(int)$r['c'];$hx=max(1,max(array_map('max',$hm)));?>
<div class="overflow-x-auto"><div class="hm min-w-[34rem] mb-1"><span></span><?php for($h=0;$h<24;$h++):?><span><?=$h%6==0?$h:''?></span><?php endfor?></div>
<div class="hm min-w-[34rem]"><?php foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $di=>$dn):?><span><?=$dn?></span><?php for($h=0;$h<24;$h++):$c=$hm[$di][$h];?><i style="opacity:<?=$c?round(.2+.8*$c/$hx,2):.06?>" title="<?=$dn?> <?=sprintf('%02d',$h)?>:00, <?=$c?> kunjungan"></i><?php endfor;endforeach?></div></div></div>
<div class="panel"><h3>Sampai di mana mereka scroll?</h3><p class="sub">Jumlah pengunjung yang mencapai tiap bagian situs.</p>
<?php $fo=['home'=>'Beranda','klien'=>'Klien','layanan'=>'Layanan','karya'=>'Karya','tentang'=>'Tentang','prestasi'=>'Prestasi','kontak'=>'Kontak'];$fc=array_column(qa("SELECT ref,COUNT(DISTINCT vid) c FROM events WHERE type='section' AND $S0 GROUP BY ref"),'c','ref');$f0=max(1,(int)max($fc?:[1]));foreach($fo as $k=>$l)echo hb($l,(int)($fc[$k]??0),$f0)?></div></div>

<div class="panel mt-4"><h3>Aktivitas terbaru</h3><p class="sub">15 kejadian terakhir di situs Anda.</p>
<?php $lb=['pageview'=>['Membuka situs','◉'],'section'=>['Melihat bagian','↓'],'project_view'=>['Membuka karya','◈'],'like'=>['Menyukai karya','♥'],'unlike'=>['Batal menyukai','♡'],'filter'=>['Memfilter karya','☰'],'role'=>['Klik layanan','✦'],'cv_download'=>['Mengunduh CV','↓'],'share'=>['Membagikan karya','↗'],'gallery'=>['Memperbesar foto','▣'],'contact'=>['Klik kontak','✉']];
foreach(qa("SELECT e.type,e.ref,e.device,e.created,p.title FROM events e LEFT JOIN projects p ON p.id=e.pid ORDER BY e.id DESC LIMIT 15") as $r):$l=$lb[$r['type']]??[$r['type'],'•'];?>
<div class="flex gap-3 py-2 ln items-center"><span class="ico2"><?=$l[1]?></span><p class="flex-1 min-w-0 text-sm truncate"><b><?=$l[0]?></b> <span class="mu"><?=e($r['title']?:$r['ref'])?></span></p><span class="mu text-xs shrink-0"><?=e($r['device'])?> / <?=ago($r['created'])?></span></div>
<?php endforeach?></div>

<?php elseif($t=='settings'||isset($_GET['id'])):?><div class="panel !p-0 overflow-hidden"><?=frm($t,(int)($_GET['id']??0))?></div>
<?php else:
$LI=['projects'=>['title','category','cover'],'services'=>['title','description'],'clients'=>['name','project','logo'],'achievements'=>['title','org'],'skills'=>['name','category'],'history'=>['title','org']];
[$tf,$sf]=$LI[$t];$im=$LI[$t][2]??null;$rows=qa("SELECT * FROM $t ORDER BY sort_order,id");?>
<div class="flex gap-3 mb-5 flex-wrap items-center"><input id="q" class="inp max-w-xs" placeholder="Cari di <?=e(strtolower($tt[0]))?>..."><span class="mu text-sm"><?=count($rows)?> data. Klik foto untuk memperbesar.</span></div>
<?php if(!$rows):?><div class="panel text-center py-12"><p class="font-extrabold text-xl">Belum ada data</p><p class="mu mb-4">Mulai dengan menambahkan data pertama Anda.</p><a class="btn" data-edit href="?t=<?=$t?>&id=0">+ Tambah <?=e($tt[0])?></a></div><?php endif?>
<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4"><?php foreach($rows as $r):?>
<article class="item" data-q="<?=e(strtolower($r[$tf].' '.($r[$sf]??'')))?>"><div class="thumb"><?php if($im&&$r[$im]):?><img data-zoom src="../uploads/<?=e($r[$im])?>" alt=""><?php else:?><?=e(ini1($r[$tf]))?><?php endif?></div>
<div class="min-w-0 flex-1"><h3 class="font-extrabold truncate"><?=e($r[$tf])?></h3><p class="mu text-sm truncate"><?=e($r[$sf]??'')?></p>
<div class="flex flex-wrap gap-1.5 mt-1.5"><?php if($t=='projects'):?><span class="badge <?=$r['status']=='published'?'ok':''?>"><?=$r['status']=='published'?'Terbit':'Draft'?></span><span class="badge"><?=(int)$r['views']?> views</span><span class="badge"><?=(int)$r['likes']?> like</span><?php elseif($t=='history'):?><span class="badge ok"><?=e($r['type'])?></span><?php else:?><span class="badge">urutan <?=(int)$r['sort_order']?></span><?php endif?></div></div>
<div class="flex flex-col gap-1.5"><a class="ab" data-edit href="?t=<?=$t?>&id=<?=$r['id']?>">Ubah</a><?php if($t=='projects'):?><a class="ab" target="_blank" href="../<?=e(purl($r))?>">Lihat</a><?php endif?><button class="ab rd" data-del="<?=$r['id']?>" data-name="<?=e($r[$tf])?>">Hapus</button></div></article>
<?php endforeach?></div><?php endif?>
</div></main></div>

<dialog id="md" class="sheet s1"><div class="mc"></div></dialog>
<dialog id="cf" class="mini s1"><form method="post" action="?t=<?=e($t)?>"><input type="hidden" name="t" value="<?=tk()?>"><input type="hidden" name="del" id="cdel"><h3 class="font-extrabold text-xl">Hapus data ini?</h3><p class="mu mt-2">"<b id="cnm"></b>" akan dihapus permanen dan tidak bisa dikembalikan.</p><div class="flex justify-end gap-2 mt-5"><button type="button" data-close class="btn2">Batal</button><button class="btn !bg-cor !text-white">Ya, hapus</button></div></form></dialog>
<dialog id="zm" class="zoom"><img id="zi" alt="Pratinjau"></dialog>
<?php if(!empty($_SESSION['f'])):?><div id="toast" role="status"><?=e($_SESSION['f'])?></div><?php unset($_SESSION['f']);endif?>
<script>
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)],esc=s=>s.replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
$('#th').onclick=()=>{localStorage.t=document.documentElement.classList.toggle('dark')?'d':'l'};
const md=$('#md'),cf=$('#cf'),zm=$('#zm');
async function openForm(u){const r=await fetch(u+(u.includes('?')?'&':'?')+'m=1');md.querySelector('.mc').innerHTML=await r.text();md.style.width=md.querySelector('.has-live')?'min(100vw,64rem)':'min(100vw,38rem)';md.showModal();init(md)}
document.addEventListener('click',e=>{const T=e.target;
 const z=T.closest('img[data-zoom]');if(z){e.preventDefault();e.stopPropagation();$('#zi').src=z.src;zm.showModal();return}
 if(T.closest('#zm')){zm.close();return}
 const a=T.closest('[data-edit]');if(a){e.preventDefault();openForm(a.href);return}
 const x=T.closest('[data-close]');if(x){x.closest('dialog').close();return}
 const d=T.closest('[data-del]');if(d){$('#cnm').textContent=d.dataset.name;$('#cdel').value=d.dataset.del;cf.showModal();return}
 if(T.tagName=='DIALOG')T.close()},true);
function live(r){const l=r.querySelector('.live');if(!l)return;const g=n=>r.querySelector(`[name=${n}]`),c=r.querySelector('[name=category]:checked');
 l.querySelector('[data-lt]').textContent=g('title').value||'Judul karya';l.querySelector('[data-lc]').textContent=c?c.value:'';l.querySelector('[data-ls]').textContent=g('summary').value||'Ringkasan singkat karya'}
function init(r){
 r.addEventListener('input',()=>live(r));r.addEventListener('change',()=>live(r));live(r);
 r.querySelectorAll('[data-c]').forEach(i=>{const s=document.createElement('span');s.className='cnt';i.after(s);const u=()=>s.textContent=i.value.length+'/'+i.maxLength;i.addEventListener('input',u);u()});
 r.querySelectorAll('[data-drop]').forEach(dz=>{const inp=dz.querySelector('input'),multi=dz.dataset.multi!==undefined,fn=dz.querySelector('[data-fn]');let dt=new DataTransfer();
  const single=f=>{const b=dz.querySelector('.dz');if(f.type.startsWith('image/')){b.innerHTML='';const im=new Image();im.src=URL.createObjectURL(f);im.dataset.zoom='';b.append(im);const l=r.querySelector('.lc-i');if(l&&inp.name=='cover'){l.innerHTML='';const i2=new Image();i2.src=im.src;l.append(i2)}}else b.innerHTML='<div class=pdf>PDF<small>'+esc(f.name)+'</small></div>';fn.textContent=f.name+' ('+Math.round(f.size/1024)+' KB). Pratinjau di atas.'};
  const render=()=>{const g=dz.parentNode.querySelector('.gal');g.querySelectorAll('.new').forEach(n=>n.remove());[...dt.files].forEach((f,i)=>{const d=document.createElement('div');d.className='th new';const im=new Image();im.src=URL.createObjectURL(f);im.dataset.zoom='';const b=document.createElement('button');b.type='button';b.textContent='Batal';b.onclick=()=>{const n=new DataTransfer();[...dt.files].forEach((x,k)=>k!=i&&n.items.add(x));dt=n;render()};d.append(im,b);g.append(d)});inp.files=dt.files;fn.textContent=dt.files.length?dt.files.length+' foto baru siap diunggah.':'Klik foto untuk memperbesar. Tombol Hapus menandai foto yang akan dihapus.'};
  inp.addEventListener('change',()=>{const fs=[...inp.files];if(multi){fs.forEach(f=>dt.items.add(f));render()}else if(fs[0])single(fs[0])});
  dz.addEventListener('dragover',e=>{e.preventDefault();dz.classList.add('over')});dz.addEventListener('dragleave',()=>dz.classList.remove('over'));
  dz.addEventListener('drop',e=>{e.preventDefault();dz.classList.remove('over');const fs=[...e.dataTransfer.files];if(multi){fs.filter(f=>f.type.startsWith('image/')).forEach(f=>dt.items.add(f));render()}else if(fs[0]){const t=new DataTransfer();t.items.add(fs[0]);inp.files=t.files;single(fs[0])}})})}
init(document);
const q=$('#q');if(q)q.oninput=e=>{const v=e.target.value.toLowerCase();$$('.item').forEach(i=>i.hidden=!i.dataset.q.includes(v))};
$$('[data-n]').forEach(x=>{const n=+x.dataset.n;if(n<2)return;let s=null;const f=t=>{s??=t;const k=Math.min((t-s)/1200,1);x.textContent=Math.round(n*(1-Math.pow(1-k,3)));if(k<1)requestAnimationFrame(f)};requestAnimationFrame(f)});
setTimeout(()=>$('#toast')?.remove(),3400);
</script>
