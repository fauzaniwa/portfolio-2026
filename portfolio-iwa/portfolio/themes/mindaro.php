<?php // Tema Mindaro: cerah, kuning dan biru, coretan tangan, kartu putih membulat
$ic=['<circle cx="24" cy="24" r="13"/><path d="M18 27q6 6 12 0M18 20v2M30 20v2"/>','<path d="M24 41V22M24 30c-9 0-13-5-13-13 9 0 13 4 13 13zM24 26c0-9 4-13 12-13 0 9-4 13-12 13z"/>','<path d="M24 41S8 30 8 19a8 8 0 0 1 16-2 8 8 0 0 1 16 2c0 11-16 22-16 22z"/>','<circle cx="24" cy="24" r="7"/><path d="M24 5v7M24 36v7M5 24h7M36 24h7M10 10l5 5M33 33l5 5M38 10l-5 5M15 33l-5 5"/>'];
$stack=array_slice(array_column($S,'title'),0,3);$sc=['var(--a)','var(--y)','var(--w)'];$flat=array_merge(...array_values($sk?:[[]]));
?><!doctype html><html lang="id" data-th="mindaro" data-dm="l" data-b="<?=e($B)?>" data-u="<?=e(sq()?$SITE['username']:'')?>"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($ttl)?></title>
<?=meta($ttl,$s['tagline'],(($og=array_values(array_filter([$s['photo'],$s['photo_about']],fn($f)=>$f&&!isvid($f))))?base().'uploads/'.rawurlencode($og[0]):''),base())?>
<script src="https://cdn.tailwindcss.com"></script><script src="<?=$B?>assets/tw.js"></script><link rel="stylesheet" href="<?=$B?>assets/site.css"><link rel="stylesheet" href="<?=$B?>assets/themes.css"><?=theme_head($SITE,'mindaro')?></head>
<body class="s1 font-d antialiased overflow-x-hidden">
<?php if($pv):?><div class="fixed bottom-3 left-3 z-50 bg-amber-400 text-black text-sm rounded-full px-4 py-1">Mode preview (draft tampil). <a class="underline" href="<?=$B?>app/">CMS</a></div><?php endif?>
<?php require __DIR__.'/../nav.php'?>

<section id="home" data-sec class="s1 relative overflow-hidden pt-28 lg:pt-32"><div class="relative max-w-6xl mx-auto px-5 grid lg:grid-cols-12 gap-10 items-center pb-14">
<div class="lg:col-span-7"><h1 class="font-extrabold leading-[1.03] text-[clamp(2.6rem,8.5vw,5.6rem)]">Halo, saya <?=e($s['nick'])?><br><span class="font-h mk" style="font-weight:400"><span id="role"><?=e($S[0]['title']??'Portofolio')?></span><span class="cur">|</span></span></h1>
<p class="mt-5 text-lg max-w-xl mu"><?=e($s['tagline'])?></p>
<div class="mt-7 flex flex-wrap gap-3"><a href="#karya" class="btn mag">Lihat karya <i>→</i></a><a href="<?=e(lnk($B.'download.php'))?>" class="btn2 mag">▶ Unduh CV</a></div>
<div class="mt-10 grid grid-cols-3 gap-4 max-w-md"><?php foreach(['Karya'=>count($P),'Klien'=>count($C),'Views'=>$tv] as $l=>$n):?><div class="border-l-2 pl-4 first:border-0 first:pl-0" style="border-color:var(--bd)"><div class="font-extrabold text-3xl" data-n="<?=$n?>"><?=$n?></div><div class="mu text-sm"><?=$l?></div></div><?php endforeach?></div></div>
<div class="lg:col-span-5 relative mx-auto w-full max-w-sm"><div class="relative overflow-hidden aspect-[4/5]" style="border-radius:50% 50% 2rem 2rem/34% 34% 2rem 2rem;background:var(--a)"><?=$ph?></div>
<?php if($s['sticker']):?><p class="font-h absolute -left-3 top-4 text-xl leading-tight -rotate-6 max-w-[8rem] mk"><?=e($s['sticker'])?></p><?php endif?>
<?php if($s['bubble']):?><div class="font-h absolute -right-2 -top-3 text-lg leading-tight rotate-6 px-4 py-3 rounded-[2rem] border-[3px] max-w-[9rem] text-center" style="border-color:var(--fg);background:var(--bg)"><?=e($s['bubble'])?></div><?php endif?>
<div class="absolute -right-3 bottom-2 grid gap-1.5 justify-items-end"><?php foreach($stack as $i=>$r):?><span class="px-4 py-1.5 rounded-lg font-bold text-sm border-2" style="background:<?=$sc[$i]?>;color:var(--b);border-color:var(--b);transform:rotate(<?=[-3,2,-2][$i]?>deg) translateX(<?=[0,-10,6][$i]?>px)"><?=e($r)?></span><?php endforeach?></div></div></div></section>

<section id="layanan" data-sec class="s1 pb-20"><div class="max-w-6xl mx-auto px-5"><div class="rounded-[2rem] p-6 sm:p-9" style="background:var(--w);color:var(--b)">
<h2 class="font-extrabold text-3xl sm:text-4xl rv">Apa yang saya kerjakan</h2>
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-8"><?php foreach($S as $i=>$v):?><div class="rv"><button data-r="<?=e($v['title'])?>" class="hvl group w-full h-full text-center p-4 rounded-2xl"><svg viewBox="0 0 48 48" class="w-14 h-14 mx-auto group-hover:scale-110 transition" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><?=$ic[$i%4]?></svg><h3 class="font-extrabold text-lg mt-3"><?=e($v['title'])?></h3><p class="text-sm opacity-75 mt-1"><?=e($v['description'])?></p></button></div><?php endforeach?></div></div></div></section>
<div class="scl s2 ups1" aria-hidden="true"></div>

<section id="karya" data-sec class="s2 pb-16"><div class="max-w-6xl mx-auto px-5">
<div class="flex flex-wrap items-end justify-between gap-4"><h2 class="font-extrabold text-3xl sm:text-4xl rv">Jelajahi karya saya</h2><div class="flex flex-wrap gap-2" id="flt"><button class="chip on" data-c="">Semua</button><?php foreach($cats as $c):?><button class="chip" data-c="<?=e($c)?>"><?=e($c)?></button><?php endforeach?></div></div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8"><?php foreach($P as $i=>$p):$cv=$p['media'][0]??'';?>
<div class="rv pj" data-c="<?=e($p['category'])?>"><a href="<?=e($B.purl($p))?>" class="tilt block relative overflow-hidden rounded-3xl aspect-[3/4]" style="background:linear-gradient(160deg,var(--a),var(--b))"><?php if($cv):?><?=mt($cv,'absolute inset-0 w-full h-full object-cover',$p['title'])?><?php endif?>
<button class="lk absolute top-3 right-3 z-10 rounded-full px-2.5 py-1 text-xs font-bold <?=$p['liked']?'on':''?>" style="background:var(--w);color:var(--b)" data-p="<?=$p['id']?>" onclick="event.preventDefault();event.stopPropagation();like(<?=$p['id']?>)" aria-label="Like"><svg class="w-3.5 h-3.5 inline"><use href="#ht"/></svg> <b><?=$p['likes']?></b></button>
<div class="absolute inset-x-0 bottom-0 p-3 flex items-center gap-2" style="background:color-mix(in srgb,var(--b) 82%,transparent);color:var(--y)"><div class="min-w-0 flex-1"><b class="block truncate"><?=e($p['title'])?></b><small class="opacity-80"><?=e($p['category'])?><?=$p['status']=='draft'?' (draft)':''?></small></div><i class="ar shrink-0">↗</i></div></a></div>
<?php endforeach; if(!$P):?><p class="opacity-80 col-span-full">Belum ada karya yang dipublikasikan.</p><?php endif?></div></div></section>

<section id="tentang" data-sec class="s2 pb-16"><div class="max-w-6xl mx-auto px-5"><div class="s1 rounded-[2rem] p-6 sm:p-10 grid md:grid-cols-5 gap-10 items-center overflow-hidden">
<div class="md:col-span-3"><h2 class="font-extrabold text-3xl sm:text-4xl rv">Tentang <span class="font-h mk" style="font-weight:400"><?=e($s['nick'])?></span></h2><p class="mu mt-4 text-lg leading-relaxed"><?=nl2br(e($s['about']))?></p>
<ul class="mt-5 grid sm:grid-cols-2 gap-2.5"><?php foreach(array_slice($flat,0,8) as $n):?><li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full grid place-items-center text-xs shrink-0" style="background:var(--b);color:var(--y)">✓</span><?=e($n)?></li><?php endforeach?></ul>
<div class="mt-7 flex flex-wrap gap-3"><a href="<?=e(lnk($B.'download.php'))?>" class="btn mag">Unduh CV <i>↓</i></a><a href="#kontak" class="btn2 mag">Hubungi saya</a></div></div>
<div class="md:col-span-2 relative h-72 sm:h-80"><div class="absolute left-0 top-2 w-[58%] bg-white p-2 pb-8 shadow-xl -rotate-6 hover:rotate-0 transition duration-500"><div class="aspect-square overflow-hidden relative"><?=$ph?></div></div>
<div class="absolute right-0 bottom-0 w-[58%] bg-white p-2 pb-8 shadow-xl rotate-6 hover:rotate-0 transition duration-500" style="color:var(--b)"><div class="aspect-square overflow-hidden relative"><?=$ph2?></div><p class="font-h text-center text-sm mt-1"><?=e($s['location'])?></p></div></div>
<?php if(array_filter($Hs)):?><div class="md:col-span-5 grid sm:grid-cols-2 gap-4"><?php foreach($Hs as $ty=>$l) if($l):$f=array_shift($l);?><div class="rv rounded-2xl p-5" style="background:var(--w);color:var(--b)"><p class="font-h text-lg"><?=$ty?> terakhir</p><h3 class="font-extrabold text-xl"><?=e($f['title'])?></h3><p><?=e($f['org'])?></p><p class="text-sm opacity-70"><?=e($f['years'])?></p><p class="text-sm opacity-80 mt-2"><?=e($f['description'])?></p>
<?php if($l):?><details class="mt-3 text-sm"><summary class="cursor-pointer font-bold">Riwayat sebelumnya</summary><?php foreach($l as $o):?><p class="mt-2"><b><?=e($o['title'])?></b>, <?=e($o['org'])?> <span class="opacity-70"><?=e($o['years'])?></span></p><?php endforeach?></details><?php endif?></div><?php endif?></div><?php endif?>
</div></div></section>

<section id="klien" data-sec class="s2 pb-12"><div class="max-w-6xl mx-auto px-5"><h2 class="font-extrabold text-2xl sm:text-3xl rv">Dipercaya oleh</h2>
<div class="flex flex-wrap gap-3 mt-6"><?php foreach($C as $c):?><div class="rv rounded-2xl px-5 py-3 flex items-center gap-3" style="background:var(--w);color:var(--b)"><?=$c['logo']?mt($c['logo'],'h-8',$c['name']):'<b class="text-lg">'.e($c['name']).'</b>'?><small class="opacity-70"><?=e($c['project'])?></small></div><?php endforeach; if(!$C):?><p class="opacity-80">Belum ada klien.</p><?php endif?></div></div></section>

<section id="prestasi" data-sec class="s2 pb-20"><div class="max-w-6xl mx-auto px-5"><h2 class="font-extrabold text-3xl sm:text-4xl rv">Prestasi</h2>
<div class="grid md:grid-cols-3 gap-4 mt-6"><?php foreach($A as $a):?><div class="rv rounded-2xl p-5" style="background:var(--w);color:var(--b)"><div class="flex items-center gap-3"><span class="w-10 h-10 rounded-full grid place-items-center font-bold" style="background:var(--a)">★</span><div><b><?=e($a['year'])?></b><div class="text-sm" style="color:#F5A524">★★★★★</div></div></div><p class="mt-3 font-semibold leading-snug"><?=e($a['title'])?></p><p class="text-sm opacity-70"><?=e($a['org'])?></p></div><?php endforeach; if(!$A):?><p class="opacity-80">Belum ada prestasi.</p><?php endif?></div></div></section>

<div class="scl s1 ups2" aria-hidden="true"></div>
<footer id="kontak" data-sec class="s1 pt-8 overflow-hidden"><div class="max-w-6xl mx-auto px-5 grid md:grid-cols-5 gap-8 items-center">
<div class="md:col-span-3"><h2 class="font-extrabold text-3xl sm:text-5xl rv"><?=e($s['cta']?:'Mari berkolaborasi hari ini')?></h2><p class="mu mt-3">Punya proyek atau ide? Hubungi saya lewat tautan berikut.</p>
<div class="flex flex-wrap gap-3 mt-6"><?php foreach($soc as $k=>$pre) if(!empty($s[$k])):?><a data-ct="<?=$k?>" class="btn2 mag" target="_blank" rel="noopener" href="<?=e($pre.$s[$k])?>"><?=ucfirst($k)?></a><?php endif?><a href="<?=e(lnk($B.'download.php'))?>" class="btn mag">Unduh CV <i>↓</i></a></div></div>
<div class="md:col-span-2 flex justify-center"><svg viewBox="0 0 120 120" class="w-40 h-40 fl" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M30 80a18 18 0 0 1 2-35 22 22 0 0 1 42-6 20 20 0 0 1 16 41z"/><circle cx="48" cy="62" r="2.5" fill="currentColor"/><circle cx="68" cy="62" r="2.5" fill="currentColor"/><path d="M52 71q8 7 16 0M45 90v18M75 90v18M38 108h14M68 108h14"/></svg></div></div>
<p class="max-w-6xl mx-auto px-5 mu text-sm mt-10">© <?=date('Y')?> <?=e($s['name'])?>.<?=$s['location']?' '.e($s['location']).'.':''?><?php if(plat('signup','open')!=='closed'):?> <a class="underline" href="<?=$B?>daftar.php">Buat portfolio Anda</a><?php endif?></p>
<div class="flex justify-center gap-4 mt-8 translate-y-1/2" aria-hidden="true"><?php for($i=0;$i<9;$i++):?><i class="w-24 h-24 rounded-full shrink-0" style="background:var(--fg)"></i><?php endfor?></div><div class="h-12"></div></footer>

<script src="<?=$B?>assets/site.js"></script>
<script>
const R=<?=json_encode(array_column($S,'title')?:['Portofolio'])?>;
(function(){const el=$('#role');if(RM)return;let i=0,j=0,dl=0;(function t(){const w=R[i];el.textContent=w.slice(0,j);if(!dl&&j<w.length){j++;setTimeout(t,90)}else if(!dl){dl=1;setTimeout(t,1600)}else if(j>0){j--;setTimeout(t,45)}else{dl=0;i=(i+1)%R.length;setTimeout(t,200)}})()})();
function filt(c){$$('.pj').forEach(b=>b.hidden=!!c&&b.dataset.c!=c);$$('#flt .chip').forEach(b=>b.classList.toggle('on',b.dataset.c==c));tr('filter',c||'Semua')}
$$('#flt .chip').forEach(b=>b.onclick=()=>filt(b.dataset.c));
$$('[data-r]').forEach(b=>b.onclick=()=>{filt(b.dataset.r);tr('role',b.dataset.r);location.hash='karya'});
</script></body></html>
