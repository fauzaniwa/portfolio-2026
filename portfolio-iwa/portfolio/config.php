<?php
// Isi sesuai hosting Anda
const DB=['host'=>'localhost','name'=>'portfolio','user'=>'root','pass'=>''];
const ADMIN_USER='iwa';
// Buat hash: php -r "echo password_hash('PASSWORD_ANDA',PASSWORD_DEFAULT);"
const ADMIN_HASH='';
function db(){static $p;return $p??=new PDO('mysql:host='.DB['host'].';dbname='.DB['name'].';charset=utf8mb4',DB['user'],DB['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES);}
function vid(){if(empty($_COOKIE['vid'])||!preg_match('/^[a-f0-9]{16}$/',$_COOKIE['vid'])){$v=bin2hex(random_bytes(8));setcookie('vid',$v,['expires'=>time()+31536000,'path'=>'/','samesite'=>'Lax','httponly'=>true]);$_COOKIE['vid']=$v;}return $_COOKIE['vid'];}

const SITE_URL='';  // contoh: https://domainanda.com (kosongkan = otomatis)
const PRETTY=false; // true bila mod_rewrite aktif: tautan jadi /karya/nama-karya
function base(){if(SITE_URL)return rtrim(SITE_URL,'/').'/';return ((($_SERVER['HTTPS']??'off')!='off')?'https':'http').'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\').'/';}
function slug($s){return trim(preg_replace('/[^a-z0-9]+/','-',strtolower($s)),'-');}
function meta($t,$ds,$img,$url,$type='website'){$ds=trim(preg_replace('/\s+/',' ',$ds));$ds=function_exists('mb_substr')?mb_substr($ds,0,200):substr($ds,0,200);$o='';
 foreach(['og:type'=>$type,'og:title'=>$t,'og:description'=>$ds,'og:url'=>$url,'og:site_name'=>$GLOBALS['sn']??'','og:locale'=>'id_ID','og:image'=>$img,'twitter:card'=>$img?'summary_large_image':'summary','twitter:title'=>$t,'twitter:description'=>$ds,'twitter:image'=>$img] as $k=>$v)if($v)$o.='<meta '.(strpos($k,'og')===0?'property':'name').'="'.$k.'" content="'.e($v).'">'."\n";
 return $o.'<link rel="canonical" href="'.e($url).'"><meta name="description" content="'.e($ds).'">';}
function pview($id,$t){$q=db()->prepare("SELECT COUNT(*) FROM events WHERE type='project_view' AND pid=? AND vid=? AND created>NOW()-INTERVAL 30 MINUTE");$q->execute([$id,vid()]);
 if(!$q->fetchColumn())db()->prepare("UPDATE projects SET views=views+1 WHERE id=?")->execute([$id]);track('project_view',$t,$id);}
const DEBUG=false; // true saat pengembangan: pesan error tampil di halaman 500
function fail($code,$msg=null){if(!headers_sent()){http_response_code($code);if($code==503)header('Retry-After: 1800');}$GLOBALS['emsg']=$msg;require __DIR__.'/error.php';exit;}
set_exception_handler(function($e){static $n=0;error_log((string)$e);if($n++)exit('Terjadi kesalahan.');$GLOBALS['edbg']=DEBUG?$e->getMessage():null;fail(500);});

function isvid($f){return in_array(strtolower(pathinfo((string)$f,PATHINFO_EXTENSION)),['mp4','webm','ogv','m4v','mov'],true);}
// Render foto atau video. Opsi: b=prefix path, z=zoom, ctl=kontrol video, still=video diam, eager=tanpa lazy
function mt($f,$c='',$alt='',$o=[]){$u=e(($o['b']??($GLOBALS['B']??'')).'uploads/'.rawurlencode($f));$c=e($c);$z=!empty($o['z'])?' data-zoom':'';
 if(!isvid($f))return '<img src="'.$u.'" alt="'.e($alt).'" class="'.$c.'"'.(empty($o['eager'])?' loading="lazy"':'').$z.'>';
 if(!empty($o['ctl']))return '<video src="'.$u.'#t=0.001" class="'.$c.'" controls playsinline preload="metadata"'.$z.'></video>';
 return '<video src="'.$u.'#t=0.001" class="'.$c.'" muted loop playsinline preload="metadata"'.(empty($o['still'])?' data-auto':'').$z.'></video>';}

$SITE=null;
const CONSOLE='studio-x7k2q9'; // nama folder konsol host (samakan jika folder diganti nama)
const THEMES=[
 'arcade'=>['Neo Arcade','Gelap dan berani dengan aksen lime dan ungu. Cocok untuk game dev, motion, dan kreator digital.',['lime'=>['Aksen utama','#D6FF3D'],'lav'=>['Aksen sekunder','#B7A4FF'],'cor'=>['Aksen kejutan','#FF4D5E'],'ink'=>['Warna gelap','#0B0B0F'],'cream'=>['Warna terang','#F1EDE4'],'acl'=>['Aksen di latar terang','#6B3DF5']],'Bricolage Grotesque','Permanent Marker','d'],
 'mindaro'=>['Mindaro','Cerah dan ramah dengan kuning, biru, dan coretan tangan. Cocok untuk edukasi, wellness, dan personal brand.',['y'=>['Latar terang','#FFF27A'],'b'=>['Warna utama','#4A47D1'],'a'=>['Aksen','#B9B6FF'],'w'=>['Kartu','#FFFDF2']],'Outfit','Caveat Brush','l']];
const FONTS_D=['Bricolage Grotesque','Outfit','Plus Jakarta Sans','Poppins','Space Grotesk','DM Sans'];
const FONTS_H=['Permanent Marker','Caveat Brush','Gochi Hand','Patrick Hand'];
function plat($k,$d=''){static $c=null;if($c===null){try{$c=array_column(db()->query("SELECT k,v FROM platform")->fetchAll(),'v','k');}catch(Throwable $e){$c=[];}}return $c[$k]??$d;}
function seed(){$d=db();if($d->query("SELECT COUNT(*) FROM users")->fetchColumn()||!ADMIN_HASH)return;$d->prepare("INSERT INTO users(id,username,email,pass,role,status,theme) VALUES(1,?,?,?,'admin','active','arcade')")->execute([ADMIN_USER,ADMIN_USER.'@local.test',ADMIN_HASH]);}
function owner(){global $SITE;return !empty($_SESSION['uid'])&&$SITE&&((int)$_SESSION['uid']===(int)$SITE['id']||($_SESSION['role']??'')==='admin');}
function resolve(){global $SITE;if($SITE)return $SITE;if(session_status()!=PHP_SESSION_ACTIVE)@session_start();seed();$u=strtolower((string)($_GET['u']??''));
 $q=db()->prepare($u?"SELECT * FROM users WHERE username=?":"SELECT * FROM users WHERE id=1");$q->execute($u?[$u]:[]);$SITE=$q->fetch()?:null;
 if(!$SITE)fail(404,$u?'Situs ini tidak ditemukan.':'Situs belum disiapkan. Masuk ke CMS untuk menyelesaikan instalasi.');
 if($SITE['status']!=='active'&&!owner())fail(404,'Situs ini belum aktif atau sedang dinonaktifkan.');
 if(!isset(THEMES[$SITE['theme']]))$SITE['theme']='arcade';return $SITE;}
function maint(){resolve();global $SITE;if(owner())return;$q=db()->prepare("SELECT v FROM settings WHERE user_id=? AND k='maintenance'");$q->execute([$SITE['id']]);if($q->fetchColumn()==='on')fail(503);}
function isHost(){global $SITE;return !$SITE||(int)$SITE['id']===1;}
function sq(){global $SITE;return isHost()?'':'u='.rawurlencode($SITE['username']);}
function purl($p){global $SITE;if(PRETTY&&!empty($p['slug']))return (isHost()?'':'u/'.rawurlencode($SITE['username']).'/').'karya/'.rawurlencode($p['slug']);
 $q=sq();return 'project.php?'.($q?$q.'&':'').(!empty($p['slug'])?'slug='.rawurlencode($p['slug']):'id='.(int)$p['id']);}
function home($B=''){global $SITE;return isHost()?$B:$B.(PRETTY?'u/'.rawurlencode($SITE['username']).'/':'index.php?'.sq());}
function lnk($f){$q=sq();return $f.($q?'?'.$q:'');}
function theme_head($u,$th){$X=THEMES[$th];$p=json_decode((string)($u['palette']??''),true)?:[];$v='';
 foreach($X[2] as $k=>$d){$c=$p['c'][$k]??'';$v.="--$k:".(is_string($c)&&preg_match('/^#[0-9a-f]{6}$/i',$c)?$c:$d[1]).';';}
 $fd=in_array($p['fd']??'',FONTS_D,true)?$p['fd']:$X[3];$fh=in_array($p['fh']??'',FONTS_H,true)?$p['fh']:$X[4];
 return '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family='.urlencode($fd).':wght@400;600;800&amp;family='.urlencode($fh).'&amp;family=DM+Mono&amp;display=swap"><style>:root{'.$v."--fd:'$fd';--fh:'$fh'}</style>";}
function uvalid($un){return preg_match('/^[a-z0-9][a-z0-9_-]{2,29}$/',$un)&&!in_array($un,['admin','app','api','u','karya','assets','uploads','backups','themes','error','daftar','masuk','index','project','download','studio','host','www','root','static','config','nav','auth','charts'],true);}
function mkuser($un,$em,$pw,$role='user',$st='active',$th='arcade'){$d=db();$d->prepare("INSERT INTO users(username,email,pass,role,status,theme) VALUES(?,?,?,?,?,?)")->execute([$un,$em,password_hash($pw,PASSWORD_DEFAULT),$role,$st,$th]);$id=(int)$d->lastInsertId();
 foreach(['name'=>$un,'nick'=>ucfirst($un),'tagline'=>'Selamat datang di portofolio saya.','about'=>'Ceritakan tentang diri Anda di sini lewat menu Pengaturan di CMS.'] as $k=>$v)$d->prepare("INSERT INTO settings(user_id,k,v) VALUES(?,?,?)")->execute([$id,$k,$v]);return $id;}
function track($type,$ref='',$pid=null){$ua=$_SERVER['HTTP_USER_AGENT']??'';if(preg_match('/bot|crawl|spider|slurp/i',$ua))return;
 $dv=preg_match('/iPad|Tablet/i',$ua)?'tablet':(preg_match('/Mobi|Android|iPhone/i',$ua)?'mobile':'desktop');
 $r=parse_url($_SERVER['HTTP_REFERER']??'',PHP_URL_HOST)?:'';if($r==($_SERVER['HTTP_HOST']??''))$r='';
 db()->prepare("INSERT INTO events(user_id,type,ref,pid,vid,device,referrer) VALUES(?,?,?,?,?,?,?)")->execute([(int)($GLOBALS['SITE']['id']??1),$type,substr($ref,0,100),$pid,vid(),$dv,substr($r,0,150)]);}
