const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)],RM=matchMedia('(prefers-reduced-motion:reduce)').matches,FP=matchMedia('(hover:hover) and (pointer:fine)').matches,B=document.documentElement.dataset.b||'';
const tr=(t,r='',p='')=>{const f=new FormData();f.append('type',t);f.append('ref',r);if(p)f.append('pid',p);navigator.sendBeacon?navigator.sendBeacon(B+'api.php?a=track',f):fetch(B+'api.php?a=track',{method:'POST',body:f})};
$('#th').onclick=()=>{const d=document.documentElement.classList.toggle('dark');localStorage.t=d?'d':'l'};
const ov=$('#ov'),mo=o=>{ov.classList.toggle('open',o);ov.setAttribute('aria-hidden',!o);document.body.style.overflow=o?'hidden':''};
$('#mb').onclick=()=>mo(true);$('#mx').onclick=()=>mo(false);$$('#ov a').forEach(a=>a.addEventListener('click',()=>mo(false)));addEventListener('keydown',e=>{if(e.key=='Escape')mo(false)});
const hd=$('#hd'),pg=$('#pg'),hs=$('#hs'),hp=$('#hp'),N={home:'Beranda',klien:'Klien',layanan:'Layanan',karya:'Karya',tentang:'Tentang',prestasi:'Prestasi',kontak:'Kontak'};let ly=0;
addEventListener('scroll',()=>{const y=scrollY,h=document.documentElement.scrollHeight-innerHeight,p=h>0?Math.min(y/h,1):0;pg.style.transform=`scaleX(${p})`;hp.textContent=String(Math.round(p*100)).padStart(2,'0')+'%';hd.classList.toggle('up',y>120&&y>ly);ly=y;
const c=$$('[data-sec]').filter(s=>s.getBoundingClientRect().top<innerHeight*.4).pop();if(c&&N[c.id])hs.textContent=N[c.id]},{passive:true});
$$('[data-ct]').forEach(a=>a.addEventListener('click',()=>tr('contact',a.dataset.ct)));
function cnt(x){const n=+x.dataset.n;if(RM||n<2)return;let s=null;const f=t=>{s??=t;const k=Math.min((t-s)/1300,1);x.textContent=Math.round(n*(1-Math.pow(1-k,3))).toLocaleString('id-ID');if(k<1)requestAnimationFrame(f)};requestAnimationFrame(f)}
const io=new IntersectionObserver(es=>es.forEach(e=>{if(!e.isIntersecting)return;const x=e.target;io.unobserve(x);x.classList.add('in');if(x.dataset.sec!==undefined)tr('section',x.id);if(x.dataset.n)cnt(x)}),{threshold:.1});
$$('.rv,[data-sec],[data-n]').forEach(x=>io.observe(x));
async function like(id){try{const f=new FormData();f.append('pid',id);const r=await(await fetch(B+'api.php?a=like',{method:'POST',body:f})).json();if(r.likes===undefined)return;$$(`.lk[data-p="${id}"]`).forEach(b=>{b.classList.toggle('on',!!r.liked);b.querySelector('b').textContent=r.likes})}catch(e){}}
if(FP&&!RM){const cu=$('#cu');
$$('.tilt').forEach(c=>{c.onmousemove=e=>{const r=c.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;c.style.transform=`perspective(800px) rotateY(${x*10}deg) rotateX(${-y*10}deg) translateY(-4px)`};c.onmouseleave=()=>c.style.transform=''});
$$('.mag').forEach(b=>{b.onmousemove=e=>{const r=b.getBoundingClientRect();b.style.transform=`translate(${(e.clientX-r.left-r.width/2)*.25}px,${(e.clientY-r.top-r.height/2)*.35}px)`};b.onmouseleave=()=>b.style.transform=''});
onmousemove=e=>{cu.style.opacity=1;cu.style.transform=`translate(${e.clientX}px,${e.clientY}px)`;$$('[data-px]').forEach(x=>x.style.translate=`${(e.clientX/innerWidth-.5)*x.dataset.px}px ${(e.clientY/innerHeight-.5)*x.dataset.px}px`)};
document.addEventListener('mouseover',e=>cu.classList.toggle('big',!!e.target.closest('a,button,.tilt')))}

const vio=new IntersectionObserver(es=>es.forEach(e=>{const v=e.target;if(e.isIntersecting)v.play().catch(()=>{});else v.pause()}),{threshold:.35});
$$('video[data-auto]').forEach(v=>{if(RM)v.controls=true;else vio.observe(v)});
