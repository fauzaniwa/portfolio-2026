<?php
// Halaman error mandiri. Preview: error.php?c=404 (juga 403, 500, 503, dll).
if(is_file(__DIR__.'/config.php'))require_once __DIR__.'/config.php';
if(!function_exists('e')){function e($s){return htmlspecialchars((string)$s,ENT_QUOTES);}}
$M=[
 400=>['Permintaan tidak valid','Server tidak bisa memahami permintaan Anda. Coba muat ulang halaman atau kembali ke halaman sebelumnya.','Alamatnya aneh nih','#FFB8D9'],
 403=>['Akses ditolak','Anda tidak punya izin untuk membuka halaman ini. Kalau merasa ini keliru, hubungi saya lewat beranda.','Pintunya terkunci','#B7A4FF'],
 404=>['Halaman tidak ditemukan','Alamat yang Anda tuju tidak ada, sudah dipindahkan, atau karyanya sedang disembunyikan. Mungkin ada salah ketik.','Level ini belum dibuat','#D6FF3D'],
 405=>['Metode tidak diizinkan','Cara akses ini tidak didukung oleh halaman tersebut. Silakan buka lewat tautan biasa.','Tombolnya salah','#FFB8D9'],
 500=>['Server sedang bermasalah','Ada yang rusak di sisi kami. Ini bukan salah Anda. Coba muat ulang sebentar lagi.','Game crash','#FF4D5E'],
 502=>['Gerbang bermasalah','Server sedang tidak merespons dengan benar. Biasanya pulih dalam hitungan menit.','Sinyal putus','#FF4D5E'],
 503=>['Sedang dipoles','Situs sedang dalam pemeliharaan. Biasanya tidak lama. Halaman ini akan memuat ulang otomatis.','Lagi di-patch','#FF6A2B']];
$code=(int)($code??($_GET['c']??404));$code=[401=>403,504=>502,429=>503,408=>400][$code]??$code;
if(!isset($M[$code]))$code=$code>=500?500:($code==404?404:400);
[$ttl,$dsc,$tag,$col]=$M[$code];if(!empty($GLOBALS['emsg']))$dsc=$GLOBALS['emsg'];
if(!headers_sent()){http_response_code($code);header('X-Robots-Tag: noindex');if($code==503)header('Retry-After: 1800');}
$dr=rtrim(str_replace('\\','/',(string)realpath($_SERVER['DOCUMENT_ROOT']??'')),'/');$dd=str_replace('\\','/',__DIR__);
$B=($dr&&strpos($dd,$dr)===0)?rtrim(substr($dd,strlen($dr)),'/').'/':'/';
$ru=$_SERVER['REDIRECT_URL']??$_SERVER['REQUEST_URI']??'/';$sg=[];
if($code==404&&function_exists('db')){try{
 $sg=db()->query("SELECT id,title,slug,category FROM projects WHERE status='published' ORDER BY sort_order,id DESC LIMIT 3")->fetchAll();
 if(session_status()!=PHP_SESSION_ACTIVE)@session_start();
 if(empty($_SESSION['adm'])&&!preg_match('/\.(ico|png|jpe?g|webp|svg|map|txt|xml)$/i',$ru))track('404',$ru);
}catch(Throwable $x){}}
$ds=str_split(str_pad((string)$code,3,'0'));
?><!doctype html><html lang="id" class="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title><?=$code?> | <?=e($ttl)?></title><?php if($code==503):?><meta http-equiv="refresh" content="60"><?php endif?>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;800&family=Permanent+Marker&family=DM+Mono&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script><script src="<?=$B?>assets/tw.js"></script><link rel="stylesheet" href="<?=$B?>assets/site.css">
<style>
.ol{color:transparent;-webkit-text-stroke:3px var(--fg)}
.ob{display:inline-grid;place-items:center;aspect-ratio:1;width:.95em;margin:0 .03em;color:#0B0B0F;cursor:pointer}
.hop{animation:hop .6s}@keyframes hop{40%{transform:translateY(-.22em) scale(1.08,.92)}70%{transform:scale(.95,1.05)}}
.gl:hover .ol{animation:gl .25s steps(2) 3}@keyframes gl{50%{transform:translate(4px,-3px);text-shadow:-4px 0 #FF4D5E,4px 0 #D6FF3D}}
.hz{height:14px;background:repeating-linear-gradient(-45deg,#D6FF3D 0 16px,#0B0B0F 16px 32px)}
.st{animation:fl 4s ease-in-out infinite}
</style></head>
<body class="s1 font-d antialiased overflow-x-hidden min-h-screen flex flex-col">
<header class="px-5 py-4 flex items-center justify-between relative z-10"><a href="<?=$B?>" class="font-extrabold text-xl">iwa<span class="mk">.</span></a><button id="th" class="w-9 h-9 rounded-full border" style="border-color:var(--bd)" aria-label="Ganti tema">◐</button></header>
<main class="relative flex-1 grid place-items-center px-5 py-8 overflow-hidden"><div class="gb absolute inset-0"></div>
<span class="st absolute top-[12%] left-[8%] text-3xl mk" aria-hidden="true">✦</span><span class="st absolute bottom-[18%] right-[10%] text-4xl font-h mk" style="animation-delay:1s" aria-hidden="true">?!</span><span class="st absolute top-[20%] right-[18%] text-2xl" style="color:<?=$col?>;animation-delay:2s" aria-hidden="true">✦</span>
<div class="relative text-center max-w-2xl">
<div class="gl font-extrabold leading-none select-none text-[clamp(6rem,26vw,15rem)] flex items-center justify-center" aria-hidden="true"><span class="ol"><?=$ds[0]?></span><span id="hp" class="inline-block"><span class="ob blob" style="background:<?=$col?>"><?=$ds[1]?></span></span><span class="ol"><?=$ds[2]?></span></div>
<p class="font-h text-3xl mk mt-2"><?=e($tag)?></p>
<h1 class="font-extrabold text-3xl sm:text-5xl mt-2"><?=e($ttl)?></h1>
<p class="mu text-lg mt-4"><?=e($dsc)?></p>
<?php if($code==404):?><p class="inline-block mt-5 font-m text-sm px-3 py-1.5 rounded-lg max-w-full truncate" style="background:var(--cd);border:1px solid var(--bd)"><span class="mk">GET</span> <?=e($ru)?> <span class="mu">&rarr; 404</span></p><?php endif?>
<?php if(!empty($GLOBALS['edbg'])):?><pre class="mt-5 text-left text-xs p-3 rounded-lg overflow-auto" style="background:var(--cd)"><?=e($GLOBALS['edbg'])?></pre><?php endif?>
<div class="flex flex-wrap gap-3 mt-8 justify-center"><a href="<?=$B?>" class="btn">Kembali ke beranda <i>↗</i></a>
<?php if($code>=500):?><button onclick="location.reload()" class="btn2">Coba lagi</button><?php else:?><button onclick="history.length>1?history.back():location.href='<?=e($B)?>'" class="btn2">Halaman sebelumnya</button><?php endif?>
<?php if($code==404):?><a href="<?=$B?>#karya" class="btn2">Lihat karya</a><?php endif?></div>
<?php if($sg):?><div class="mt-12 text-left"><p class="font-m text-xs mk mb-3 text-center">Mungkin Anda mencari</p><div class="grid sm:grid-cols-3 gap-3"><?php foreach($sg as $p):?><a href="<?=e($B.purl($p))?>" class="sw cutc p-4 block"><span class="font-m text-xs mk"><?=e($p['category'])?></span><span class="block font-extrabold leading-tight mt-1"><?=e($p['title'])?></span></a><?php endforeach?></div></div><?php endif?>
</div></main>
<footer><div class="hz"></div><div class="bg-lime text-ink font-extrabold uppercase py-2 overflow-hidden"><div class="mq"><?php for($i=0;$i<2;$i++):?><div class="flex gap-8 pr-8 whitespace-nowrap"><?php for($j=0;$j<8;$j++):?><span>Error <?=$code?> ✦ <?=e($tag)?> ✦</span><?php endfor?></div><?php endfor?></div></div></footer>
<script>
document.getElementById('th').onclick=()=>{const d=document.documentElement.classList.toggle('dark');localStorage.t=d?'d':'l'};
const hp=document.getElementById('hp');hp.onclick=()=>{hp.classList.remove('hop');void hp.offsetWidth;hp.classList.add('hop')};
if(matchMedia('(hover:hover)').matches&&!matchMedia('(prefers-reduced-motion:reduce)').matches)onmousemove=e=>{hp.style.translate=`${(e.clientX/innerWidth-.5)*30}px ${(e.clientY/innerHeight-.5)*30}px`};
</script></body></html>
