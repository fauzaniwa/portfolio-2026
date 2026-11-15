<?php
// Isi sesuai hosting Anda
const DB=['host'=>'localhost','name'=>'portfolio','user'=>'root','pass'=>''];
const ADMIN_USER='iwa';
// Buat hash: php -r "echo password_hash('PASSWORD_ANDA',PASSWORD_DEFAULT);"
const ADMIN_HASH='';
function db(){static $p;return $p??=new PDO('mysql:host='.DB['host'].';dbname='.DB['name'].';charset=utf8mb4',DB['user'],DB['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES);}
function vid(){if(empty($_COOKIE['vid'])||!preg_match('/^[a-f0-9]{16}$/',$_COOKIE['vid'])){$v=bin2hex(random_bytes(8));setcookie('vid',$v,['expires'=>time()+31536000,'path'=>'/','samesite'=>'Lax','httponly'=>true]);$_COOKIE['vid']=$v;}return $_COOKIE['vid'];}
function track($type,$ref='',$pid=null){$ua=$_SERVER['HTTP_USER_AGENT']??'';if(preg_match('/bot|crawl|spider|slurp/i',$ua))return;
 $dv=preg_match('/iPad|Tablet/i',$ua)?'tablet':(preg_match('/Mobi|Android|iPhone/i',$ua)?'mobile':'desktop');
 $r=parse_url($_SERVER['HTTP_REFERER']??'',PHP_URL_HOST)?:'';if($r==($_SERVER['HTTP_HOST']??''))$r='';
 db()->prepare("INSERT INTO events(type,ref,pid,vid,device,referrer) VALUES(?,?,?,?,?,?)")->execute([$type,substr($ref,0,100),$pid,vid(),$dv,substr($r,0,150)]);}

const SITE_URL='';  // contoh: https://domainanda.com (kosongkan = otomatis)
const PRETTY=false; // true bila mod_rewrite aktif: tautan jadi /karya/nama-karya
function base(){if(SITE_URL)return rtrim(SITE_URL,'/').'/';return ((($_SERVER['HTTPS']??'off')!='off')?'https':'http').'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\').'/';}
function slug($s){return trim(preg_replace('/[^a-z0-9]+/','-',strtolower($s)),'-');}
function purl($p){return !empty($p['slug'])?(PRETTY?'karya/'.$p['slug']:'project.php?slug='.$p['slug']):'project.php?id='.$p['id'];}
function meta($t,$ds,$img,$url,$type='website'){$ds=trim(preg_replace('/\s+/',' ',$ds));$ds=function_exists('mb_substr')?mb_substr($ds,0,200):substr($ds,0,200);$o='';
 foreach(['og:type'=>$type,'og:title'=>$t,'og:description'=>$ds,'og:url'=>$url,'og:site_name'=>'Muhammad Fauzan Aztera','og:locale'=>'id_ID','og:image'=>$img,'twitter:card'=>$img?'summary_large_image':'summary','twitter:title'=>$t,'twitter:description'=>$ds,'twitter:image'=>$img] as $k=>$v)if($v)$o.='<meta '.(strpos($k,'og')===0?'property':'name').'="'.$k.'" content="'.e($v).'">'."\n";
 return $o.'<link rel="canonical" href="'.e($url).'"><meta name="description" content="'.e($ds).'">';}
function pview($id,$t){$q=db()->prepare("SELECT COUNT(*) FROM events WHERE type='project_view' AND pid=? AND vid=? AND created>NOW()-INTERVAL 30 MINUTE");$q->execute([$id,vid()]);
 if(!$q->fetchColumn())db()->prepare("UPDATE projects SET views=views+1 WHERE id=?")->execute([$id]);track('project_view',$t,$id);}
const DEBUG=false; // true saat pengembangan: pesan error tampil di halaman 500
function fail($code,$msg=null){if(!headers_sent()){http_response_code($code);if($code==503)header('Retry-After: 1800');}$GLOBALS['emsg']=$msg;require __DIR__.'/error.php';exit;}
function maint(){if(!empty($_SESSION['adm']))return;try{$v=db()->query("SELECT v FROM settings WHERE k='maintenance'")->fetchColumn();}catch(Throwable $e){return;}if($v==='on')fail(503);}
set_exception_handler(function($e){static $n=0;error_log((string)$e);if($n++)exit('Terjadi kesalahan.');$GLOBALS['edbg']=DEBUG?$e->getMessage():null;fail(500);});

function isvid($f){return in_array(strtolower(pathinfo((string)$f,PATHINFO_EXTENSION)),['mp4','webm','ogv','m4v','mov'],true);}
// Render foto atau video. Opsi: b=prefix path, z=zoom, ctl=kontrol video, still=video diam, eager=tanpa lazy
function mt($f,$c='',$alt='',$o=[]){$u=e(($o['b']??'').'uploads/'.rawurlencode($f));$c=e($c);$z=!empty($o['z'])?' data-zoom':'';
 if(!isvid($f))return '<img src="'.$u.'" alt="'.e($alt).'" class="'.$c.'"'.(empty($o['eager'])?' loading="lazy"':'').$z.'>';
 if(!empty($o['ctl']))return '<video src="'.$u.'#t=0.001" class="'.$c.'" controls playsinline preload="metadata"'.$z.'></video>';
 return '<video src="'.$u.'#t=0.001" class="'.$c.'" muted loop playsinline preload="metadata"'.(empty($o['still'])?' data-auto':'').$z.'></video>';}
