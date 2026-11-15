<?php require 'config.php';session_start();header('Content-Type: application/json');
if(!empty($_SESSION['adm'])){echo '{"skip":1}';exit;}
$a=$_GET['a']??'';$pid=(int)($_POST['pid']??0)?:null;
if($a=='like'&&$pid){$d=db();$v=vid();$q=$d->prepare("DELETE FROM likes WHERE pid=? AND vid=?");$q->execute([$pid,$v]);
 if($q->rowCount())$l=0;else{$d->prepare("INSERT INTO likes(pid,vid) VALUES(?,?)")->execute([$pid,$v]);$l=1;}
 $d->prepare("UPDATE projects SET likes=(SELECT COUNT(*) FROM likes WHERE pid=?) WHERE id=?")->execute([$pid,$pid]);
 track($l?'like':'unlike','',$pid);
 $n=$d->prepare("SELECT likes FROM projects WHERE id=?");$n->execute([$pid]);
 echo json_encode(['liked'=>$l,'likes'=>(int)$n->fetchColumn()]);exit;}
$t=$_POST['type']??'';
if($a=='track'&&in_array($t,['section','project_view','filter','role','contact','share','gallery'])){
 if($t=='project_view'&&$pid){$q=db()->prepare("SELECT COUNT(*) FROM events WHERE type='project_view' AND pid=? AND vid=? AND created>NOW()-INTERVAL 30 MINUTE");$q->execute([$pid,vid()]);
  if($q->fetchColumn()){echo '{}';exit;}db()->prepare("UPDATE projects SET views=views+1 WHERE id=?")->execute([$pid]);}
 track($t,$_POST['ref']??'',$pid);}
echo '{}';
