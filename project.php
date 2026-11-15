<?php require 'config.php';session_start();
$adm=!empty($_SESSION['adm']);$d=db();maint();
$q=$d->prepare("SELECT * FROM projects WHERE ".(isset($_GET['slug'])?'slug=?':'id=?')." AND (status='published' OR ?=1)");
$q->execute([$_GET['slug']??(int)($_GET['id']??0),(int)$adm]);$p=$q->fetch();
if(!$p)fail(404,'Karya yang Anda cari tidak ada atau belum dipublikasikan. Coba lihat karya lainnya.');
$B=rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\').'/';
if(!$adm){track('pageview','karya/'.($p['slug']?:$p['id']));pview($p['id'],$p['title']);}
$p=$d->query("SELECT * FROM projects WHERE id=".(int)$p['id'])->fetch();
$s=array_column($d->query("SELECT k,v FROM settings")->fetchAll(),'v','k')+['photo'=>'','cv'=>'','nick'=>'Iwa'];
$md=$d->prepare("SELECT file FROM project_media WHERE project_id=? ORDER BY sort_order,id");$md->execute([$p['id']]);
$media=array_merge($p['cover']?[$p['cover']]:[],$md->fetchAll(PDO::FETCH_COLUMN));
$lq=$d->prepare("SELECT COUNT(*) FROM likes WHERE pid=? AND vid=?");$lq->execute([$p['id'],vid()]);$liked=(bool)$lq->fetchColumn();
$all=$d->query("SELECT id,title,slug FROM projects WHERE status='published' ORDER BY sort_order,id DESC")->fetchAll();
$ix=array_search($p['id'],array_column($all,'id'));$pr=($ix!==false&&$ix>0)?$all[$ix-1]:null;$nx=($ix!==false&&isset($all[$ix+1]))?$all[$ix+1]:null;
$url=base().purl($p);$ds=$p['summary']?:$p['description'];
$imgs=array_values(array_filter($media,fn($f)=>!isvid($f)));$img=$imgs?base().'uploads/'.rawurlencode($imgs[0]):(($s['photo']&&!isvid($s['photo']))?base().'uploads/'.rawurlencode($s['photo']):'');
preg_match('/(?:youtu\.be\/|v=|embed\/)([\w-]{11})/',$p['video']??'',$m);$yt=$m?'https://www.youtube-nocookie.com/embed/'.$m[1]:'';
$ld=['@context'=>'https://schema.org','@type'=>'CreativeWork','name'=>$p['title'],'description'=>$ds,'url'=>$url,'image'=>$img,'genre'=>$p['category'],'keywords'=>$p['tags'],'dateCreated'=>$p['year'],'author'=>['@type'=>'Person','name'=>$s['name']]];
$eu=rawurlencode($url);$et=rawurlencode($p['title'].' - '.$s['name']);
$sh=['WhatsApp'=>"https://wa.me/?text=$et%20$eu",'X'=>"https://twitter.com/intent/tweet?text=$et&url=$eu",'LinkedIn'=>"https://www.linkedin.com/sharing/share-offsite/?url=$eu",'Facebook'=>"https://www.facebook.com/sharer/sharer.php?u=$eu",'Telegram'=>"https://t.me/share/url?url=$eu&text=$et"];
?><!doctype html><html lang="id" class="dark" data-b="<?=e($B)?>"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($p['title'])?> | <?=e($s['name'])?></title>
<?=meta($p['title'].' | '.$s['name'],$ds,$img,$url,'article')?>
<script type="application/ld+json"><?=json_encode(array_filter($ld),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG)?></script>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;800&family=Permanent+Marker&family=DM+Mono&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script><script src="<?=$B?>assets/tw.js"></script><link rel="stylesheet" href="<?=$B?>assets/site.css"></head>
<body class="s1 font-d antialiased overflow-x-hidden">
<?php $hl='Detail karya';require 'nav.php'?>
<section class="s1 relative overflow-hidden pt-28 pb-24 lg:pt-36"><div class="gb absolute inset-0"></div><div class="absolute -top-24 right-0 w-96 h-96 rounded-full blur-3xl opacity-30 bg-lav"></div>
<div class="relative max-w-6xl mx-auto px-5"><a href="<?=$B?>#karya" class="btn2 !py-1.5 text-sm mag">← Semua karya</a>
<p class="font-m mk mt-8"><?=e($p['category'])?><?=$p['year']?' / '.e($p['year']):''?></p>
<h1 class="font-extrabold uppercase tracking-tight leading-[.95] text-[clamp(2.2rem,8vw,6rem)] mt-2 break-words"><?=e($p['title'])?></h1>
<p class="mu text-xl max-w-3xl mt-4"><?=e($p['summary'])?></p>
<div class="flex flex-wrap items-center gap-3 mt-7">
<button class="lk chip <?=$liked?'on':''?>" data-p="<?=$p['id']?>" onclick="like(<?=$p['id']?>)" aria-label="Like"><svg class="w-4 h-4 inline"><use href="#ht"/></svg> <b><?=(int)$p['likes']?></b></button>
<span class="mu"><svg class="w-4 h-4 inline"><use href="#eye"/></svg> <?=(int)$p['views']?> views</span>
<div class="relative"><button class="btn2" onclick="share()">Bagikan ↗</button>
<div id="shm" class="hidden absolute z-20 mt-2 w-56 card p-2 grid gap-1 text-sm"><?php foreach($sh as $n=>$u):?><a data-sh="<?=$n?>" target="_blank" rel="noopener" href="<?=e($u)?>" class="sw px-3 py-2 rounded-lg"><?=$n?></a><?php endforeach?><button id="cpb" onclick="cp_()" class="sw px-3 py-2 rounded-lg text-left">Salin tautan</button></div></div>
<?php if($p['link']):?><a class="btn mag" target="_blank" rel="noopener" href="<?=e($p['link'])?>">Buka proyek <i>↗</i></a><?php endif?></div></div></section>

<section class="s2 sl"><div class="max-w-6xl mx-auto px-5">
<?php if($yt):?><div class="aspect-video rounded-2xl overflow-hidden bg-black mb-5 rv"><iframe class="w-full h-full" src="<?=e($yt)?>" title="Video <?=e($p['title'])?>" allowfullscreen loading="lazy"></iframe></div><?php endif?>
<?php if($media):?><div class="columns-1 sm:columns-2 gap-4"><?php $k=0;foreach($media as $f):if(isvid($f)):?><div class="rv mb-4 break-inside-avoid overflow-hidden rounded-2xl bg-black"><?=mt($f,'w-full',$p['title'],['b'=>$B,'ctl'=>1])?></div><?php else:?><button class="rv pj block w-full mb-4 break-inside-avoid overflow-hidden rounded-2xl" onclick="lb(<?=$k?>)" aria-label="Perbesar foto <?=++$k?>"><?=mt($f,'w-full',$p['title'].' '.$k,['b'=>$B])?></button><?php endif;endforeach?></div>
<?php elseif(!$yt):?><div class="aspect-video rounded-2xl" style="background:linear-gradient(135deg,#6B3DF5,#FF4D5E)"></div><?php endif?></div></section>

<section class="s1 sl r"><div class="max-w-6xl mx-auto px-5 grid md:grid-cols-3 gap-10">
<div class="md:col-span-2"><h2 class="font-extrabold text-3xl rv">Tentang karya ini</h2><div class="mu text-lg leading-relaxed mt-4 max-w-prose"><?=nl2br(e($p['description']?:$p['summary']))?></div></div>
<aside class="rv"><div class="cutc p-5" style="background:var(--cd)"><p class="font-m text-xs mk">Kategori</p><p class="font-bold mb-3"><?=e($p['category'])?></p><?php if($p['year']):?><p class="font-m text-xs mk">Tahun</p><p class="font-bold mb-3"><?=e($p['year'])?></p><?php endif?>
<?php if($p['tags']):?><p class="font-m text-xs mk mb-1">Tag</p><div class="flex flex-wrap gap-2"><?php foreach(array_filter(array_map('trim',explode(',',$p['tags']))) as $tg):?><span class="chip !font-normal"><?=e($tg)?></span><?php endforeach?></div><?php endif?></div></aside>
<div class="md:col-span-3 grid sm:grid-cols-2 gap-4"><?php foreach([['← Sebelumnya',$pr],['Berikutnya →',$nx]] as [$lbl,$x]) if($x):?><a href="<?=e($B.purl($x))?>" class="sw cutc p-5 block"><span class="font-m text-xs mk"><?=$lbl?></span><span class="block font-extrabold text-2xl mt-1"><?=e($x['title'])?></span></a><?php endif?></div></div></section>

<footer class="s2 sl"><div class="max-w-6xl mx-auto px-5 flex flex-wrap justify-between items-center gap-4"><p class="mu text-sm">© <?=date('Y')?> <?=e($s['name'])?></p><div class="flex gap-3"><a href="<?=$B?>download.php" class="btn">Unduh CV <i>↓</i></a><a href="<?=$B?>#kontak" class="btn2">Kontak</a></div></div></footer>

<dialog id="lbx" class="p-0 bg-transparent max-w-none max-h-none w-screen h-screen"><div class="relative w-full h-full grid place-items-center" style="background:#000d"><img id="lim" alt="" class="max-h-[90vh] max-w-[94vw] object-contain"><button id="lp" class="nv" aria-label="Sebelumnya">‹</button><button id="ln" class="nv" style="left:auto;right:.5rem" aria-label="Berikutnya">›</button></div></dialog>
<script src="<?=$B?>assets/site.js"></script>
<script>
const PID=<?=(int)$p['id']?>,MD=<?=json_encode($imgs)?>,U=<?=json_encode($url)?>,L=$('#lbx'),SM=$('#shm');let li=0;
async function share(){if(navigator.share&&matchMedia('(pointer:coarse)').matches){try{await navigator.share({title:document.title,url:U});tr('share','native',PID)}catch(e){}return}SM.classList.toggle('hidden')}
$$('[data-sh]').forEach(a=>a.onclick=()=>tr('share',a.dataset.sh,PID));
async function cp_(){try{await navigator.clipboard.writeText(U)}catch(e){const t=document.createElement('textarea');t.value=U;document.body.append(t);t.select();document.execCommand('copy');t.remove()}$('#cpb').textContent='Tersalin!';tr('share','salin',PID);setTimeout(()=>$('#cpb').textContent='Salin tautan',1800)}
function lb(i){li=(i+MD.length)%MD.length;$('#lim').src='<?=$B?>uploads/'+encodeURIComponent(MD[li]);if(!L.open){L.showModal();tr('gallery','',PID)}}
$$('video[controls]').forEach(v=>v.addEventListener('play',()=>tr('video',v.getAttribute('src').split('/').pop().split('#')[0],PID),{once:true}));
$('#lp').onclick=e=>{e.stopPropagation();lb(li-1)};$('#ln').onclick=e=>{e.stopPropagation();lb(li+1)};L.onclick=()=>L.close();
addEventListener('keydown',e=>{if(!L.open)return;if(e.key=='ArrowLeft')lb(li-1);if(e.key=='ArrowRight')lb(li+1)});
let tx=0;L.ontouchstart=e=>tx=e.touches[0].clientX;L.ontouchend=e=>{const d=e.changedTouches[0].clientX-tx;if(Math.abs(d)>50)lb(li+(d<0?1:-1))};
</script></body></html>
