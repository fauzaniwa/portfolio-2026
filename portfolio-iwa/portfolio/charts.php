<?php
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

