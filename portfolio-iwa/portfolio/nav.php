<?php $B=$B??'';$hl=$hl??'Beranda';$hp=!empty($ONH)?'':home($B);
$soc=$soc??['instagram'=>'https://instagram.com/','linkedin'=>'','github'=>'https://github.com/','whatsapp'=>'https://wa.me/','email'=>'mailto:'];
$nl=['home'=>'Beranda','layanan'=>'Layanan','karya'=>'Karya','tentang'=>'Tentang','klien'=>'Klien','prestasi'=>'Prestasi','kontak'=>'Kontak'];?>
<svg width="0" height="0" class="absolute"><symbol id="eye" viewBox="0 0 24 24"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" style="fill:none;stroke:currentColor;stroke-width:2"/><circle cx="12" cy="12" r="3" fill="currentColor"/></symbol><symbol id="ht" viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a4.5 4.5 0 018-2.8A4.5 4.5 0 0120 10c0 5.5-8 11-8 11z"/></symbol></svg>
<div id="cu"></div>
<header id="hd" class="fixed inset-x-0 top-0 z-40"><div id="pg" class="h-1 bg-lime origin-left" style="transform:scaleX(0)"></div>
<div class="flex items-center gap-4 px-5 py-2.5 backdrop-blur" style="background:color-mix(in srgb,var(--bg) 80%,transparent);border-bottom:1px solid var(--bd)">
<a href="<?=e($hp)?>#home" class="font-extrabold text-xl"><?=e(strtolower($s['nick']))?><span class="mk">.</span></a>
<div class="hidden sm:flex items-center gap-2 font-m text-xs mu mx-auto"><i class="w-2 h-2 rounded-full bg-lime animate-pulse"></i><span id="hs"><?=e($hl)?></span><span>//</span><span id="hp">00%</span></div>
<button id="th" class="ml-auto sm:ml-0 w-9 h-9 rounded-full border" style="border-color:var(--bd)" aria-label="Ganti tema">◐</button>
<button id="mb" class="btn !py-2 mag" aria-haspopup="dialog">Menu <i>≡</i></button></div></header>
<div id="ov" class="s1" role="dialog" aria-label="Menu" aria-hidden="true"><div class="gb absolute inset-0 pointer-events-none"></div>
<div class="relative max-w-6xl mx-auto px-5 py-4 min-h-full flex flex-col"><div class="flex justify-between items-center"><span class="font-extrabold text-xl"><?=e(strtolower($s['nick']))?><span class="mk">.</span></span><button id="mx" class="btn2" aria-label="Tutup menu">Tutup ✕</button></div>
<nav class="my-auto py-10"><?php $i=0;foreach($nl as $h=>$l):?><a class="ol sw" href="<?=e($hp)?>#<?=$h?>" style="animation-delay:<?=.15+$i*.05?>s"><span class="font-m text-sm mk">#0<?=++$i?></span><?=$l?></a><?php endforeach?></nav>
<div class="flex flex-wrap gap-3 pb-4"><a href="<?=e(lnk($B.'download.php'))?>" class="btn">Unduh CV <i>↓</i></a><?php foreach($soc as $k=>$pre) if(!empty($s[$k])):?><a data-ct="<?=$k?>" class="btn2" target="_blank" rel="noopener" href="<?=e($pre.$s[$k])?>"><?=ucfirst($k)?></a><?php endif?><?php if(plat('signup','open')!=='closed'):?><a class="btn2" href="<?=$B?>daftar.php">Buat portfolio</a><?php endif?></div></div></div>
