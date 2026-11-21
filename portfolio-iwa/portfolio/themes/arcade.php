<?php
$pal=['var(--pink)','var(--lime)','var(--lav)','var(--ink)'];
$mq='';foreach($C as $c)$mq.='<span class="flex items-center gap-3 shrink-0 text-2xl font-extrabold">'.($c['logo']?mt($c['logo'],'h-8',$c['name']):e($c['name'])).'<i class="not-italic" style="color:var(--ac)">✦</i></span>';
?><!doctype html><html lang="id" data-th="arcade" data-dm="d" data-b="<?=e($B)?>" data-u="<?=e(sq()?$SITE['username']:'')?>"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($ttl)?></title><?=meta($ttl,$s['tagline'],(($og=array_values(array_filter([$s['photo'],$s['photo_about']],fn($f)=>$f&&!isvid($f))))?base().'uploads/'.rawurlencode($og[0]):''),base())?>
<script src="https://cdn.tailwindcss.com"></script>
<script src="<?=$B?>assets/tw.js"></script>
<link rel="stylesheet" href="<?=$B?>assets/site.css"><link rel="stylesheet" href="<?=$B?>assets/themes.css"><?=theme_head($SITE,'arcade')?></head>
<body class="s1 font-d antialiased overflow-x-hidden">
<?php require __DIR__.'/../nav.php'?>
<?php if($pv):?><div class="fixed bottom-3 left-3 z-50 bg-amber-400 text-black text-sm rounded-full px-4 py-1">Mode preview (draft tampil). <a class="underline" href="<?=$B?>app/">CMS</a></div><?php endif?>


<section id="home" data-sec class="s1 relative overflow-hidden pt-28 pb-24 lg:pt-36 lg:pb-32">
<div class="absolute -top-24 -left-24 w-96 h-96 rounded-full blur-3xl opacity-40" style="background:var(--org)" data-px="30"></div>
<div class="absolute top-1/3 right-0 w-80 h-80 rounded-full blur-3xl opacity-30 bg-lav" data-px="-40"></div><div class="gb absolute inset-0"></div>
<div class="relative max-w-6xl mx-auto px-5 grid lg:grid-cols-12 gap-12 items-center">
<div class="lg:col-span-7"><p class="font-h text-2xl mk">Hey, saya <?=e($s['nick'])?> <span class="wig">✦</span></p>
<h1 class="mt-3 font-extrabold uppercase tracking-tight leading-[.95] text-[clamp(2.5rem,9.5vw,6.8rem)] break-words min-h-[2em]"><span id="role"><?=e($S[0]['title']??'Portofolio')?></span><span class="cur mk">|</span></h1>
<svg viewBox="0 0 300 14" class="w-64 sm:w-96 -mt-4"><path class="dr" d="M3 9C60 2 110 13 170 6S260 10 297 5" fill="none" style="stroke:var(--lime)" stroke-width="6" stroke-linecap="round"/></svg>
<p class="mt-6 text-xl font-bold"><?=e($s['name'])?></p><p class="mu mt-2 max-w-xl text-lg"><?=e($s['tagline'])?></p>
<div class="mt-8 flex flex-wrap gap-3"><a href="#karya" class="btn mag">Lihat karya <i>↗</i></a><a href="<?=e(lnk($B.'download.php'))?>" class="btn2 mag">Unduh CV ↓</a></div></div>
<div class="lg:col-span-5 relative mx-auto w-full max-w-sm"><div class="blob absolute inset-3 bg-lav" data-px="14"></div>
<div class="relative cutc aspect-[4/5] overflow-hidden"><?=$ph?></div>
<div class="fl absolute -left-3 top-10 bg-cream text-ink rounded-xl p-3 shadow-xl"><div class="text-xs">Total kunjungan</div><b class="text-2xl" data-n="<?=$pvw?>"><?=$pvw?></b></div>
<div class="fl2 absolute -right-2 bottom-16 bg-lime text-ink rounded-xl p-3 rotate-3 shadow-xl"><div class="text-xs">Views karya</div><b class="text-2xl" data-n="<?=$tv?>"><?=$tv?></b></div>
<?php if($s['sticker']):?><div class="absolute -bottom-4 left-4 font-h bg-cor text-white px-3 py-1 -rotate-3 text-sm"><?=e($s['sticker'])?></div><?php endif?></div></div>
<div class="hidden xl:flex absolute right-5 top-1/2 -translate-y-1/2 flex-col gap-2"><?php foreach($soc as $k=>$pre) if(!empty($s[$k])):?><a data-ct="<?=$k?>" target="_blank" rel="noopener" href="<?=e($pre.$s[$k])?>" class="w-9 h-9 grid place-items-center rounded-full border text-xs font-m hover:bg-lime hover:text-ink transition" style="border-color:var(--bd)"><?=$ab[$k]?></a><?php endif?></div></section>

<section id="klien" data-sec class="s2 sl"><div class="max-w-6xl mx-auto px-5 flex items-center gap-8"><p class="font-semibold shrink-0 w-32 hidden sm:block leading-tight">Dipercaya brand dan institusi</p>
<div class="overflow-hidden flex-1"><div class="mq"><?php for($i=0;$i<2;$i++)echo '<div class="flex gap-12 pr-12">'.$mq.'</div>'?></div></div></div></section>

<section id="layanan" data-sec class="s1 sl r"><div class="max-w-6xl mx-auto px-5"><h2 class="font-extrabold text-4xl sm:text-5xl rv">Apa yang saya kerjakan</h2>
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-10"><?php foreach($S as $i=>$v):?><div class="rv"><button data-r="<?=e($v['title'])?>" class="sw w-full h-full text-left p-5 border-t-4" style="border-color:var(--fg)"><span class="font-m mk">#0<?=$i+1?></span><h3 class="text-2xl font-extrabold mt-2"><?=e($v['title'])?></h3><p class="mu mt-2"><?=e($v['description'])?></p></button></div><?php endforeach?></div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 text-center"><?php foreach(['Karya'=>count($P),'Klien'=>count($C),'Prestasi'=>count($A),'Likes'=>$tl] as $l=>$n):?><div class="rv"><div class="text-5xl sm:text-6xl font-extrabold mk" data-n="<?=$n?>"><?=$n?></div><div class="mu"><?=$l?></div></div><?php endforeach?></div></div></section>

<section id="karya" data-sec class="s2 sl"><div class="max-w-6xl mx-auto px-5"><h2 class="font-extrabold text-4xl sm:text-5xl rv">Karya pilihan</h2>
<div class="flex flex-wrap gap-2 mt-6" id="flt"><button class="chip on" data-c="">Semua</button><?php foreach($cats as $c):?><button class="chip" data-c="<?=e($c)?>"><?=e($c)?></button><?php endforeach?></div>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-8">
<?php foreach($P as $i=>$p):$bg=$pal[$i%4];$fg=$i%4==3?'var(--cream)':'var(--ink)';$cv=$p['media'][0]??'';?>
<div class="rv pj" data-c="<?=e($p['category'])?>"><a href="<?=e($B.purl($p))?>" class="tilt cutc block p-3 h-full" style="background:<?=$bg?>;color:<?=$fg?>">
<div class="aspect-[4/3] rounded-xl overflow-hidden" style="background:linear-gradient(135deg,var(--acl),var(--cor))"><?php if($cv):?><?=mt($cv,'w-full h-full object-cover',$p['title'])?><?php endif?></div>
<div class="p-2 pt-4 flex items-start justify-between gap-2"><div><h3 class="text-xl font-extrabold leading-tight"><?=e($p['title'])?></h3><p class="text-sm opacity-70"><?=e($p['category'])?><?=$p['year']?' / '.e($p['year']):''?><?=$p['status']=='draft'?' (draft)':''?></p></div><i class="ar shrink-0">↗</i></div>
<p class="text-sm opacity-80 px-2"><?=e($p['summary'])?></p>
<div class="flex items-center gap-4 px-2 pt-3 text-sm"><span><svg class="w-4 h-4 inline"><use href="#eye"/></svg> <b class="vw" data-p="<?=$p['id']?>"><?=$p['views']?></b></span>
<button class="lk <?=$p['liked']?'on':''?>" data-p="<?=$p['id']?>" onclick="event.preventDefault();event.stopPropagation();like(<?=$p['id']?>)" aria-label="Like"><svg class="w-4 h-4 inline"><use href="#ht"/></svg> <b><?=$p['likes']?></b></button></div></a></div>
<?php endforeach; if(!$P):?><p class="mu">Belum ada karya yang dipublikasikan.</p><?php endif?></div></div></section>

<section id="tentang" data-sec class="s1 sl r"><div class="max-w-6xl mx-auto px-5 grid md:grid-cols-5 gap-12 items-center">
<div class="md:col-span-2 relative mx-auto max-w-xs w-full rv"><div class="bg-white text-ink p-3 pb-12 shadow-2xl -rotate-3 hover:rotate-0 transition duration-500"><div class="aspect-square overflow-hidden relative"><?=$ph2?></div><p class="font-h text-center mt-3"><?=e($s['location'])?></p></div>
<span class="absolute -top-3 left-1/2 -translate-x-1/2 w-24 h-6 rotate-3" style="background:color-mix(in srgb,var(--pink) 80%,transparent)"></span><div class="absolute -bottom-5 -right-3 bg-lime text-ink font-h p-3 rotate-6 w-36 text-sm shadow-lg"><?=e(implode(', ',array_slice(array_column($S,'title'),0,4))?:'Portofolio')?></div></div>
<div class="md:col-span-3"><h2 class="font-extrabold text-4xl sm:text-5xl rv">Hey, saya <span class="font-h mk"><?=e($s['nick'])?></span></h2><p class="mu mt-4 text-lg leading-relaxed max-w-prose"><?=nl2br(e($s['about']))?></p>
<div class="grid sm:grid-cols-2 gap-4 mt-8"><?php foreach($Hs as $ty=>$l) if($l):$f=array_shift($l);?><div class="rv"><div class="cutc p-5 h-full" style="background:var(--cd)"><p class="font-m text-xs mk"><?=$ty?> terakhir</p><h3 class="font-extrabold text-xl mt-1"><?=e($f['title'])?></h3><p><?=e($f['org'])?></p><p class="mu text-sm"><?=e($f['years'])?></p><p class="mu text-sm mt-2"><?=e($f['description'])?></p>
<?php if($l):?><details class="mt-3 text-sm"><summary class="cursor-pointer mk font-semibold">Riwayat sebelumnya</summary><?php foreach($l as $o):?><p class="mt-2"><b><?=e($o['title'])?></b>, <?=e($o['org'])?> <span class="mu"><?=e($o['years'])?></span></p><?php endforeach?></details><?php endif?></div></div><?php endif?></div>
<?php foreach($sk as $g=>$l):?><div class="mt-6"><h3 class="font-bold mb-2"><?=e($g)?></h3><div class="flex flex-wrap gap-2"><?php foreach($l as $n):?><span class="chip !font-normal hover:-rotate-3 hover:scale-110 cursor-default"><?=e($n)?></span><?php endforeach?></div></div><?php endforeach?>
<div class="mt-8 flex flex-wrap gap-3"><a href="<?=e(lnk($B.'download.php'))?>" class="btn mag">Unduh CV <i>↓</i></a><a href="#kontak" class="btn2 mag">Hubungi saya</a></div></div></div></section>

<section id="prestasi" data-sec class="s2 sl"><div class="max-w-6xl mx-auto px-5"><h2 class="font-extrabold text-4xl sm:text-5xl rv">Prestasi</h2>
<div class="grid md:grid-cols-2 gap-4 mt-10"><?php foreach($A as $a):?><div class="rv"><div class="sw cutc p-5 flex gap-4 items-center h-full"><span class="font-h text-3xl mk"><?=e($a['year'])?></span><div><div class="font-bold leading-snug"><?=e($a['title'])?></div><div class="mu text-sm"><?=e($a['org'])?></div></div></div></div><?php endforeach?></div></div></section>

<footer id="kontak" data-sec class="s1 sl r"><div class="max-w-6xl mx-auto px-5 grid md:grid-cols-3 gap-10 items-center">
<div class="md:col-span-2"><p class="font-h text-4xl sm:text-6xl leading-tight mk rv"><?=e($s['cta']?:'Mari buat sesuatu yang keren!')?></p>
<div class="flex flex-wrap gap-3 mt-8"><?php foreach($soc as $k=>$pre) if(!empty($s[$k])):?><a data-ct="<?=$k?>" class="btn2 mag" target="_blank" rel="noopener" href="<?=e($pre.$s[$k])?>"><?=ucfirst($k)?></a><?php endif?><a href="<?=e(lnk($B.'download.php'))?>" class="btn mag">Unduh CV <i>↓</i></a></div></div>
<div class="justify-self-center"><svg viewBox="0 0 120 120" class="spin w-36 h-36"><defs><path id="cp" d="M60 60m-46 0a46 46 0 1 1 92 0a46 46 0 1 1-92 0"/></defs><text class="font-m" font-size="10.5" fill="currentColor" letter-spacing="2"><textPath href="#cp"><?=e(strtoupper(implode(' ✦ ',array_column($S,'title'))?:'PORTOFOLIO').' ✦')?></textPath></text></svg></div></div>
<p class="max-w-6xl mx-auto px-5 mu text-sm mt-14">© <?=date('Y')?> <?=e($s['name'])?>.<?=$s['location']?' Dibuat di '.e($s['location']).'.':''?><?php if(plat('signup','open')!=='closed'):?> <a class="underline" href="<?=$B?>daftar.php">Buat portfolio Anda</a><?php endif?></p></footer>


<script src="<?=$B?>assets/site.js"></script>
<script>
const R=<?=json_encode(array_column($S,'title')?:['Portofolio'])?>;
(function(){const el=$('#role');if(RM)return;let i=0,j=0,dl=0;(function t(){const w=R[i];el.textContent=w.slice(0,j);if(!dl&&j<w.length){j++;setTimeout(t,90)}else if(!dl){dl=1;setTimeout(t,1600)}else if(j>0){j--;setTimeout(t,45)}else{dl=0;i=(i+1)%R.length;setTimeout(t,200)}})()})();
function filt(c){$$('.pj').forEach(b=>b.hidden=!!c&&b.dataset.c!=c);$$('#flt .chip').forEach(b=>b.classList.toggle('on',b.dataset.c==c));tr('filter',c||'Semua')}
$$('#flt .chip').forEach(b=>b.onclick=()=>filt(b.dataset.c));
$$('[data-r]').forEach(b=>b.onclick=()=>{filt(b.dataset.r);tr('role',b.dataset.r);location.hash='karya'});
</script></body></html>
