<?php
$title='Война';
require_once('system/up.php');
_Reg();
switch($_GET['case']){
default:
if($set['id_vrag']==0){
$_SESSION['err'] = 'Не выбран противник';
header('Location: voina.php?case=vrag');
exit();
}
$vrag_set=_FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='".$set['id_vrag']."' LIMIT 1");
$logi=_FetchAssoc("SELECT * FROM `user_voina` WHERE `id_user`='".$user_id."' AND `id_vrag`='".$vrag_set['id']."'  ORDER BY `id` DESC LIMIT 1");
?><div class="main"><div class="block_zero center"><?=$user['login']?> <img src="images/flags/<?=$set['side']?>.png" alt="Флаг"/> <img src="images/icons/vs.png" alt="vs"/> <img src="images/flags/<?=$vrag['side']?>.png" alt="Флаг"/> <?=$vrag_set['login']?></div><div class="dot-line"></div><div class="block_zero center"><img src="hpvrag.php" alt="Индикатор" /></div><div class="mini-line"></div><?
if(empty($logi['rezult'])){
header("Location: voina.php?case=vrag");
exit();
}
if($logi['rezult']=='nikto'){
if ($set['logo']=='on'){
?><img src="images/logotips/nikto.jpg" width="100%" alt="Ничья"/><div class="mini-line"></div><?
}
?><div class="block_zero center"><h3 class="Admin" style="font-weight:bold;">Ничья !!!</h3></div><div class="dot-line"></div><?
}
if($logi['rezult']=='win'){
if ($set['logo']=='on'){
?><img src="images/logotips/win.jpg" width="100%" alt="Победа"/><div class="mini-line"></div><?
}
?><div class="block_zero center"><h3 class="dgreen" style="font-weight:bold;">Победа !!!</h3></div><div class="dot-line"></div><?
}
if($logi['rezult']=='lose'){
if ($set['logo']=='on'){
?><img src="images/logotips/lose.png" width="100%" alt="Поражение"/><div class="mini-line"></div><?
}
?><div class="block_zero center"><h3 class="dred" style="font-weight:bold;">Поражение !!!</h3></div><div class="dot-line"></div><?
}
if($logi['rezult']=='razbt'){
if ($set['logo']=='on'){
?><img src="images/logotips/razbit.jpg" width="100%" alt="Поражение"/><div class="mini-line"></div><?
}
?><div class="block_zero center"><h3 class="dgreen" style="font-weight:bold;">Вы разбили армию противника !!!</h3></div><div class="dot-line"></div><?
}
if($logi['rezult']=='razb'){
if ($set['logo']=='on'){
?><img src="images/logotips/razbit.jpg" width="100%" alt="Поражение"/><div class="mini-line"></div><?
}
?><div class="block_zero center"><h3 class="dred" style="font-weight:bold;">Армия противника уже разбита !!!</h3></div><div class="dot-line"></div><?
}
if($logi['rezult']=='ubit'){
if ($set['logo']=='on'){
?><img src="images/logotips/fataliti.jpg" width="100%" alt="Поражение"/><div class="mini-line"></div><?
}
?><div class="block_zero center"><h3 class="dred" style="font-weight:bold;">Фаталити !!!</h3></div><div class="dot-line"></div><?
}
if($logi['rezult']=='pomil'){
if ($set['logo']=='on'){
?><img src="images/logotips/pomiloval.jpg" width="100%" alt="Поражение"/><div class="mini-line"></div><?
}
?><div class="block_zero center"><h3 class="dgreen" style="font-weight:bold;">Помилование !!!</h3></div><div class="dot-line"></div><?
}
if($logi['rezult']=='dubl'){
if ($set['logo']=='on'){
?><img src="images/logotips/fataliti.jpg" width="100%" alt="Фаталити"/><div class="mini-line"></div><?
}
}
if($logi['rezult']=='uho'){
if ($set['logo']=='on'){
?><img src="images/logotips/uho.jpg" width="100%" alt="Ухо"/><div class="mini-line"></div><?
}
}
if($logi['rezult']=='uho1' OR $logi['rezult']=='uho2' OR $logi['rezult']=='uhi'){
if ($set['logo']=='on'){
?><img src="images/logotips/fataliti.jpg" width="100%" alt="Нельзя"/><div class="mini-line"></div><?
}
}
if($logi['rezult']=='lovk'){
if ($set['logo']=='on'){
?><img src="images/logotips/lovk.jpg" width="100%" alt="Ловкость"/><div class="mini-line"></div><?
}
}
if($logi['rezult']=='ubit'){
?><div class="block_zero center"><a class="btn" href="voina.php?case=fataliti"><span class="end"><span class="label"><span class="dred">Отрезать ухо</span></span></span></a> <a class="btn" href="voina.php?case=pomiloval"><span class="end"><span class="label"><span class="dgreen">Помиловать</span></span></span></a></span><?
}elseif($logi['rezult']=='uho'){
?><div class="block_zero center"><span style="color: #9c9;">Вы убили <a href="view.php?smotr=<?=$set['id_vrag']?>"><?=$vrag_set['login']?></a> и отрезали <?=$vrag_kto?> ухо.</span></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label"><span class="grey">На войну</span></span></span></a><?
mysql_query("UPDATE `user_set` SET `id_vrag`='0' WHERE `id`='".$user_id."' LIMIT 1");
}elseif($logi['rezult']=='uho1'){
?><div class="block_zero center"><span style="color: #c66;">Отрезать ухо одному и тому же<br />противнику можно 1 раз в час!</span><br/><?
$time = $fataliti_vrag['fataliti1'] - time();
?><span style="color: #999;">Осталось: <?=_Time($time)?></span></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label"><span class="grey">На войну</span></span></span></a> <a class="btn" href="voina.php?case=pomiloval"><span class="end"><span class="label"><span class="dgreen">Помиловать</span></span></span></a></span><?
}elseif($logi['rezult']=='uho2'){
?><div class="block_zero center"><span style="color: #c66;">Отрезать ухо одному и тому же<br />противнику можно 1 раз в час!</span><br/><?
$time = $fataliti_vrag['fataliti2'] - time();
?><span style="color: #999;">Осталось: <?=_Time($time)?></span></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label"><span class="grey">На войну</span></span></span></a> <a class="btn" href="voina.php?case=pomiloval"><span class="end"><span class="label"><span class="dgreen">Помиловать</span></span></span></a></span><?
}elseif($logi['rezult']=='uhi'){
?><div class="block_zero center"><span style="color: #c66;">У бедняги <a href="view.php?smotr=<?=$set['id_vrag']?>"><?=$vrag_set['login']?></a> и так нет обеих ушей!</span></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label"><span class="grey">На войну</span></span></span></a><?
}elseif($logi['rezult']=='lovk'){
?><div class="block_zero center"><span style="color: #c66;">Ловкость <a href="view.php?smotr=<?=$set['id_vrag']?>"><?=$vrag_set['login']?></a> позволила <?=$vrag_kto?></br>сбежать с поля боя!</span></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label"><span class="grey">На войну</span></span></span></a><?
mysql_query("UPDATE `user_set` SET `id_vrag`='0' WHERE `id`='".$user_id."' LIMIT 1");
}elseif($logi['rezult']=='pomil'){
$bonus = $set['lvl']*100;
?><div class="block_zero center"><span style="color: #9c9;">Вы помиловали <a href="view.php?smotr=<?=$set['id_vrag']?>"><?=$vrag_set['login']?></a>, сохранив <?=$vrag_kto?> жизнь,<br />но забрали <?=$vrag_kto1?> жетон!<br />Награда от Красного Креста</span> <img src="images/icons/baks.png" alt="vs"/><?=$bonus?> <span style="color: #999;">.</span></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label"><span class="grey">На войну</span></span></span></a><?
mysql_query("UPDATE `user_set` SET `id_vrag`='0' WHERE `id`='".$user_id."' LIMIT 1");
}elseif($logi['rezult']=='dubl' AND $set['pomiloval']!=0){
$time = $set['pomiloval']-time();
?><div class="block_zero center"><span style="color: #c66;">Нельзя так часто миловать противников!</span><br /><span style="color: #999;">Осталось <?=_Time($time)?></span></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label"><span class="grey">На войну</span></span></span></a> <a class="btn" href="voina.php?case=fataliti"><span class="end"><span class="label"><span class="dred">Отрезать ухо</span></span></span></a><?
}elseif($logi['rezult']=='dubl' AND $set['pomiloval']==0){
header("Location: voina.php?case=vrag");
exit();
}else{
?><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label"><span class="grey">На войну</span></span></span></a> <a class="btn" href="voina.php?case=ataka&vrag=<?=$vrag['id']?>"><span class="end"><span class="label"><span class="dred">Атаковать</span></span></span></a></span><?
}

if($logi['nanes']==0){$uvorot_vrag='<span style="color: #3c3;">Уворот!</span>';}else{$uvorot_vrag=FALSE;}
if($logi['poluchil']==0){$uvorot_user='<span style="color: #3c3;">Уворот!</span>';}else{$uvorot_user=FALSE;}
if($logi['poluchil']==20){$krit_vrag='<span style="color: #ff3434;">Крит!</span>';}else{$krit_vrag=FALSE;}
if($logi['nanes']==20){$krit_user='<span style="color: #ff3434;">Крит!</span>';}else{$krit_user=FALSE;}

if($logi['rezult']!='pomil' AND $logi['rezult']!='dubl' AND $logi['rezult']!='razb' AND $logi['rezult']!='uho' AND $logi['rezult']!='uho1' AND $logi['rezult']!='uho2' AND $logi['rezult']!='uhi' AND $logi['rezult']!='lovk'){
?></div><div class="dot-line"></div><div class="block_zero"><img src="images/icons/ataka.png" alt="ataka"/> Нанесено: <span style="color: #9c9;"><?=$logi['nanes']?></span> урона <?=$krit_user?><?=$uvorot_vrag?><br/><img src="images/icons/ataka.png" alt="ataka"/> Получено: <span style="color: #c66;"><?=$logi['poluchil']?></span> урона <?=$krit_vrag?><?=$uvorot_user?><br/><img src="images/icons/baks.png" alt="baks"/> Награблено: <span style="color: #9c9;"><?=$logi['baks']?></span> баксов<br/><img src="images/icons/lvl.png" alt="lvl"/> Заработано: <span style="color: #9c9;"><?=$logi['exp']?></span> опыта<?
}

?></div><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Использовано:</span></div><?

$vuk=_NumFilter(($user_alliance+1)*5);

$data=mysql_query("SELECT * FROM `voina_unit` WHERE `id_user` = '" . $user_id . "' ORDER BY `ataka` DESC LIMIT $vuk");

?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_unit=mysql_fetch_assoc($data)){
$kol=_NumRows("SELECT * FROM `voina_unit` WHERE `id_unit` = '" . $my_unit['id'] . "'");
$data_unit=_FetchAssoc("SELECT * FROM `user_unit` WHERE `id_user` = '" . $user_id . "' AND `id_user` = '" . $my_unit['id'] . "' LIMIT 1");
if($data_unit['kol']>0){
++$cols;
?><td><img src="images/units/<?= $my_unit['id_unit'] ?>.png" width="65px" height="45px" style="border:1px solid #999;" alt="Техника"><br/><center><small><?= $kol ?></small></center></td><?
}
if ($cols == $maxcols) {
?></tr><tr><?
$cols = 0;
}
} 
?></table><?

?></div></div><?
break;

case 'vrag':

if(isset($_GET['vrag'])){
$vrag=_NumFilter($_GET['vrag']);
mysql_query("UPDATE `user_set` SET `id_vrag`='0' WHERE `id`='".$user_id."' LIMIT 1");
if($set['udar']==0){
$_SESSION['err']='Закончились бои';
header("Location: voina.php?case=vrag");
exit();
}
if($set['hp']<25){
$_SESSION['err']='Закончилось здоровье,<br/>отдохните или сходите в <a href="hosp.php">Госпиталь</a>';
header("Location: voina.php?case=vrag");
exit();
}
header("Location: voina.php?case=ataka&vrag=".$vrag."");
exit();
}

mysql_query("UPDATE `user_set` SET `id_vrag`='0' WHERE `id`='".$user_id."' LIMIT 1");
?><div class="main"><?
if ($set['logo']=='on'){
?><img src="images/logotips/voina.jpg" width="100%" alt="Война"/><div class="mini-line"></div><?
}
?><div class="menuList"><li><a href="sanction.php?case=vrag"><img src="images/icons/arrow.png" alt="Санкции"/>Санкции</a></li></div><div class="mini-line"></div><?
$data_voina = mysql_query("SELECT * FROM `user_set` WHERE `id`!='".$user_id."' AND `lvl`>='".($set['lvl']-3)."' AND `lvl`<='".($set['lvl']+3)."' ORDER BY RAND() LIMIT 5");
while($user_voina=mysql_fetch_assoc($data_voina)){
$vrag_set=_FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='".$user_voina['id']."' LIMIT 1");
$vrag_alliance=_NumRows("SELECT * FROM `alliance_user` WHERE `kto`='".$vrag_set['id']."' OR `s_kem`='".$vrag_set['id']."'");// Колличество альянса
?><div class="block_zero"><img src="images/flags/<?=$user_voina['side']?>.png" alt="Флаг"/><a href="view.php?smotr=<?=$vrag_set['id']?>"> <?=$vrag_set['login']?></a><span style="float: right;"><a class="btn" href="voina.php?case=vrag&vrag=<?=$vrag_set['id']?>"><span class="end"><span class="label"><span class="dred">Атаковать</span></span></span></a></span><br /><small><span style="color: #fffabd;">Ур.</span> <?=$user_voina['lvl']?>, <span style="color: #fffabd;">Ал.</span> <?=($vrag_alliance+1)?>, <span style="color: #fffabd;">Рейтинг</span> <?=$user_voina['raiting']?></small></div><div class="dot-line"></div><?
}
?><div class="block_zero center"><a class="btn" href="voina.php?case=vrag"><span class="end"><span class="label">Другие противники</span></span></a></div><div class="mini-line"></div><?
//НАВИГАЦИЯ
if(empty($_GET['page'])||$_GET['page']==0||$_GET['page']<0){
$_GET[ 'page' ] = 0;
}
$next=_NumFilter($_GET['page']+1);
$back=$_GET['page']-1;
$num=$_GET['page']*5;
if($_GET['page']==0){
$i = 1;
}else{
$i=($_GET['page']*5)+1;
}
$viso=_NumRows( "SELECT `id` FROM `user_voina` WHERE `id_vrag`='".$user_id."'");
$puslap=floor($viso/5);
//НАВИГАЦИЯ

$logi=mysql_query("SELECT * FROM `user_voina` WHERE `id_vrag`='".$user_id."'  ORDER BY `id` DESC LIMIT $num, 5");
while($logi_vrag=mysql_fetch_assoc($logi)){
$logi_voina=_FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='".$logi_vrag['id_user']."'  LIMIT 1");

if($logi_vrag['rezult']=='nikto'){$rezult='<span style="color: #9cc;">Ничья!</span>';}
elseif($logi_vrag['rezult']=='win'){$rezult='<span style="color: #ff3434;">Поражение!</span>';}
elseif($logi_vrag['rezult']=='lose'){$rezult='<span style="color: #3c3;">Победа!</span>';}
elseif($logi_vrag['rezult']=='razb'){$rezult='<span style="color: #ff3434;">Ваша армия уже разбита!</span>';}
elseif($logi_vrag['rezult']=='razbt'){$rezult='<span style="color: #ff3434;">Вашу армию разбили!</span>';}
elseif($logi_vrag['rezult']=='dubl'){$rezult='<span style="color: #ff3434;">Пытался совершить фаталити!</span>';}
elseif($logi_vrag['rezult']=='ubit'){$rezult='<span style="color: #ff3434;">Пытался совершить фаталити!</span>';}
elseif($logi_vrag['rezult']=='pomil'){$rezult='<span style="color: #3c3;">Вас помиловали!</span>';}
elseif($logi_vrag['rezult']=='uho'){$rezult='<span style="color: #ff3434;">Вам отрезали ухо!</span>';}
elseif($logi_vrag['rezult']=='lovk'){$rezult='<span style="color: #3c3;">Вы сбежали с поля боя!</span>';}
elseif($logi_vrag['rezult']=='uho1' OR $logi_vrag['rezult']=='uho2' OR $logi_vrag['rezult']=='uhi'){$rezult='<span style="color: #ff3434;">Пытался совершить фаталити!</span>';}
elseif($logi_vrag['rezult']=='spopa'){$rezult='<span style="color: #ff3434;">Добавил Вас в санкции!</span>';}
elseif($logi_vrag['rezult']=='swin'){$rezult='<span style="color: #ff3434;">Поражение в санкциях!</span>';}
elseif($logi_vrag['rezult']=='snikt'){$rezult='<span style="color: #9cc;">Ничья в санкциях!</span>';}
elseif($logi_vrag['rezult']=='slose'){$rezult='<span style="color: #3c3;">Победа в санкциях!</span>';}
elseif($logi_vrag['rezult']=='srazb'){$rezult='<span style="color: #ff3434;">Вас убили в санкциях!</span>';}
elseif($logi_vrag['rezult']=='suzhe'){$rezult='<span style="color: #ff3434;">атака в санкциях!</span>';}
else{
$rezult=FALSE;
}

echo'<div class="block_zero small"><b><span style="color: #9c9;">'.$logi_vrag['data'].' в '.$logi_vrag['time'].'</span></b><br/>Вас атаковал <a href="view.php?smotr='.$logi_voina['id'].'">'.$logi_voina['login'].'</a> - '.$rezult.'<br/><span style="color: #3c3;">Нанесено: '.$logi_vrag['poluchil'].' урона</span><br/><span style="color: #ff3434;">Получено: '.$logi_vrag['nanes'].' урона</span><br/><span style="color: #ff3434;">Потеряно: '.$logi_vrag['baks'].' баксов</span></div><div class="dot-line"></div>';
$i++;
}
//НАВИГАЦИЯ
echo'<div class="block_zero center">';
if($_GET['page']>0){
echo '<small><b><a href="voina.php?case=vrag&page='.$back.'"><< Вперёд </a></small></b>';
}
if(empty($_GET['page'])||$_GET['page']==0||$_GET['page']<$puslap){
echo '<small><b><a href="voina.php?case=vrag&page='.$next.'"> Назад >></a></small></b>';
}
echo'</div></div>';
//НАВИГАЦИЯ
break;

case 'ataka':
$vrag=isset($_GET['vrag'])?_NumFilter($_GET['vrag']):NULL;
if($set['id_vrag']==0){
mysql_query("UPDATE `user_set` SET `id_vrag`='".$vrag."' WHERE `id`='".$user_id."' LIMIT 1");
header('Location: voina.php?case=ataka');
exit();
}
$vrag_set=_FetchAssoc("SELECT * FROM `user_set` WHERE `id`='".$set['id_vrag']."' LIMIT 1");

if($set['lvl']>($vrag_set['lvl']+10)){
$_SESSION['err']='Уровень противника меньше, чем на 10!';
header("Location: voina.php?case=vrag");
exit();
}

if($user_id==$vrag_set['id']){
$_SESSION['err']='Нельзя атаковать самого себя!';
header("Location: voina.php?case=vrag");
exit();
}

if($set['lvl']<($vrag_set['lvl']-10)){
$_SESSION['err']='Уровень противника больше, чем на 10!';
header("Location: voina.php?case=vrag");
exit();
}

if($set['udar']==0){
$_SESSION['err']='Закончились бои';
header("Location: voina.php");
exit();
}

if($set['hp']<25){
$_SESSION['err']='Закончилось здоровье,<br/>отдохните или сходите в <a href="hosp.php">Госпиталь</a>';
header("Location: voina.php");
exit();
}



// Победа
if($ITOG_A>$VRAG_ITOG_Z AND $vrag_set['hp']>=25){

$hp_user=mt_rand(0,9);
$hp_vrag=mt_rand(11,20);

$trof_exp=_FetchAssoc("SELECT * FROM `user_trofei` WHERE `id_user`='".$user_id."' AND `id_trof`='5' LIMIT 1");
if($trof_exp['status']==1 AND $trof_exp['time_up']==0){
if ($set['side']=='u'){
$exp_trof=round(5*1.2)/100*$trof_exp['bonus_1'];
$expa=round(5*1.2)+$exp_trof;
}else{
$exp_trof=5/100*$trof_exp['bonus_1'];
$expa=5+$exp_trof;
}
}else{
if ($set['side']=='u'){
$expa=round(5*1.2);
}else{
$expa=5;
}
}

$user_lab_exp=_FetchAssoc('SELECT * FROM `user_laboratory` WHERE `id_user`="'.$user_id.'" AND `id_lab`="4" LIMIT 1');

if($user_lab_exp['status']==1){
$expa=$expa*1.3;
}

$user_lab_expno=_FetchAssoc('SELECT * FROM `user_laboratory` WHERE `id_user`="'.$user_id.'" AND `id_lab`="3" LIMIT 1');

if($user_lab_expno['status']==1){
$expa=0;
}

if($vrag_set['baks']>=(($set['lvl']+1)*5)){
$baks=($set['lvl']+1)*5;
}else{
$baks=0;
}

if($user['refer']>0){
mysql_query("UPDATE `user_set` SET `baks`=`baks`+'".round($baks/10)."' WHERE `id`='".$user['refer']."'");
mysql_query("UPDATE `user_set` SET `hp`=`hp`-'".$hp_user."', `hp_up`='".time()."', `baks`=`baks`+'".$baks."', `exp`=`exp`+'".$expa."', `udar`=`udar`-'1', `udar_up`='".time()."', `unit_hp`=`unit_hp`+'".$hp_user."', `refer_baks`=`refer_baks`+'".round($baks/10)."' WHERE `id`='".$user_id."' LIMIT 1");
}else{
mysql_query("UPDATE `user_set` SET `hp`=`hp`-'".$hp_user."', `hp_up`='".time()."', `baks`=`baks`+'".$baks."', `exp`=`exp`+'".$expa."', `udar`=`udar`-'1', `udar_up`='".time()."', `unit_hp`=`unit_hp`+'".$hp_user."' WHERE `id`='".$user_id."' LIMIT 1");
}
mysql_query("UPDATE `user_set` SET `wins`=`wins`+'1', `raiting_wins`=`raiting_wins`+'1' WHERE `id`='".$user_id."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `hp`=`hp`-'".$hp_vrag."', `hp_up`='".time()."', `baks`=`baks`-'".$baks."' WHERE `id`='".$vrag_set['id']."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `loses`=`loses`+'1', `raiting_loses`=`raiting_loses`+'1' WHERE `id`='".$vrag_set['id']."' LIMIT 1");
$vrag_set=_FetchAssoc("SELECT * FROM `user_set` WHERE `id`='".$vrag_set['id']."' LIMIT 1");

if($vrag_set['hp']>=25){// Победа
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','".$hp_vrag."','".$hp_user."','".$baks."','".$expa."','win')");
header("Location: voina.php");
exit();
}elseif($vrag_set['hp']<=10){// Фаталити
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','".$hp_vrag."','".$hp_user."','".$baks."','".$expa."','ubit')");
header("Location: voina.php");
exit();
}else{// Разбил армию
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','".$hp_vrag."','".$hp_user."','".$baks."','".$expa."','razbt')");
header("Location: voina.php");
exit();
}
}
// Ничья
elseif($ITOG_A==$VRAG_ITOG_Z  AND $vrag_set['hp']>=25){
$hp_user=0;
$hp_vrag=0;
$expa=0;
$baks=0;
mysql_query("UPDATE `user_set` SET `udar`=`udar`-'1', `udar_up`='".time()."' WHERE `id`='".$user_id."' LIMIT 1");
$vrag_set=_FetchAssoc("SELECT * FROM `user_set` WHERE `id`='".$vrag_set['id']."' LIMIT 1");
if($vrag_set['hp']>=25){// Ничья
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','".$hp_vrag."','".$hp_user."','".$baks."','".$expa."','nikto')");
header("Location: voina.php");
exit();
}
}

// Поражение
elseif($ITOG_A<$VRAG_ITOG_Z  AND $vrag_set['hp']>=25){
$hp_user=mt_rand(11,20);
$hp_vrag=mt_rand(0,9);
$expa=0;
$baks=0;
mysql_query("UPDATE `user_set` SET `hp`=`hp`-'".$hp_user."', `hp_up`='".time()."', `udar`=`udar`-'1', `udar_up`='".time()."', `unit_hp`=`unit_hp`+'".$hp_user."' WHERE `id`='".$user_id."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `loses`=`loses`+'1', `raiting_loses`=`raiting_loses`+'1' WHERE `id`='".$user_id."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `hp`=`hp`-'".$hp_vrag."', `hp_up`='".time()."' WHERE `id`='".$vrag_set['id']."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `wins`=`wins`+'1', `raiting_wins`=`raiting_wins`+'1' WHERE `id`='".$vrag_set['id']."' LIMIT 1");
$vrag_set=_FetchAssoc("SELECT * FROM `user_set` WHERE `id`='".$vrag_set['id']."' LIMIT 1");
if($vrag_set['hp']>=25){// Поражение
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','".$hp_vrag."','".$hp_user."','".$baks."','".$expa."','lose')");
header("Location: voina.php");
exit();
}else{// Разбил армию
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','".$hp_vrag."','".$hp_user."','".$baks."','".$expa."','razbt')");
header("Location: voina.php");
exit();
}


}else{// Армия уже разбита
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','','','','','razb')");
header("Location: voina.php");
exit();
}
break;

case 'pomiloval':
if($set['id_vrag']==0){
$_SESSION['err'] = 'Не выбран противник';
header('Location: voina.php?case=vrag');
exit();
}
$vrag_set=_FetchAssoc("SELECT * FROM `user_set` WHERE `id` = '".$set['id_vrag']."' LIMIT 1");
if($set['pomiloval']==0){
$bonus=$set['lvl']*100;
if($user['refer']>0){
mysql_query("UPDATE `user_set` SET `baks`=`baks`+'".round($bonus/10)."' WHERE `id`='".$user['refer']."'");
mysql_query('UPDATE `user_set` SET `baks` = `baks` + "'.$bonus.'", `zheton` = `zheton` + "1", `pomiloval` = "'.(time()+3600).'", `refer_baks`=`refer_baks`+"'.round($bonus/10).'" WHERE `id` = "'.$user_id.'"');
}else{
mysql_query('UPDATE `user_set` SET `baks` = `baks` + "'.$bonus.'", `zheton` = `zheton` + "1", `pomiloval` = "'.(time()+3600).'" WHERE `id` = "'.$user_id.'"');
}
$hp_user=0;$hp_vrag=0;$expa=0;$baks=0;
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','".$hp_vrag."','".$hp_user."','".$baks."','".$expa."','pomil')");
header("Location: voina.php");
exit();
}else{
$hp_user=0;$hp_vrag=0;$expa=0;$baks=0;
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$vrag_set['id']."','".$dater."','".$timer."','".$hp_vrag."','".$hp_user."','".$baks."','".$expa."','dubl')");
header('Location: voina.php');
exit();
}
break;

case 'fataliti';
if($set['id_vrag']==0){
$_SESSION['err'] = 'Не выбран противник';
header('Location: voina.php?case=vrag');
exit();
}
if($KRIT > $VRAG_UVOROT) {
if($fataliti_vrag['uho1_kto']!=0 AND $fataliti_vrag['uho2_kto']!=0){
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$set['id_vrag']."','".$dater."','".$timer."','','','','','uhi')");
header('Location: voina.php');
exit();
}
elseif($fataliti_vrag['uho1_kto']==0 AND $fataliti_vrag['fataliti1']==0){
mysql_query('UPDATE `user_fataliti` SET `uho1_kto` = "'.$user_id.'", `uho1_up` = "'.(time()+86400).'", `fataliti1` = "'.(time()+3600).'" WHERE `id_user` = "'.$set['id_vrag'].'"');
mysql_query('UPDATE `user_set` SET `hp` = "0", `hp_up` = "'.time().'" WHERE `id` = "'.$set['id_vrag'].'"');
mysql_query('UPDATE `user_set` SET `dies` = `dies` + "1" WHERE `id` = "'.$set['id_vrag'].'"');
mysql_query('UPDATE `user_set` SET `uho` = `uho` + "1" WHERE `id` = "'.$user_id.'"');
mysql_query('UPDATE `user_set` SET `kills` = `kills` + "1" WHERE `id` = "'.$user_id.'"');
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$set['id_vrag']."','".$dater."','".$timer."','','','','','uho')");
header('Location: voina.php');
exit();
}elseif($fataliti_vrag['uho1_kto'] == $user_id AND $fataliti_vrag['fataliti1'] != 0) {
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$set['id_vrag']."','".$dater."','".$timer."','','','','','uho1')");
header('Location: voina.php');
exit();
}
elseif($fataliti_vrag['uho2_kto'] == '0' AND $fataliti_vrag['fataliti2'] == '0') {
mysql_query('UPDATE `user_fataliti` SET `uho2_kto` = "'.$user_id.'", `uho2_up` = "'.(time()+86400).'", `fataliti2` = "'.(time()+3600).'" WHERE `id_user` = "'.$set['id_vrag'].'"');
mysql_query('UPDATE `user_set` SET `hp` = "0", `hp_up` = "'.time().'" WHERE `id` = "'.$set['id_vrag'].'"');
mysql_query('UPDATE `user_set` SET `dies` = `dies` + "1" WHERE `id` = "'.$set['id_vrag'].'"');
mysql_query('UPDATE `user_set` SET `uho` = `uho` + "1" WHERE `id` = "'.$user_id.'"');
mysql_query('UPDATE `user_set` SET `kills` = `kills` + "1" WHERE `id` = "'.$user_id.'"');
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$set['id_vrag']."','".$dater."','".$timer."','','','','','uho')");
header('Location: voina.php');
exit();
}elseif($fataliti_vrag['uho2_kto'] == $user_id AND $fataliti_vrag['fataliti2'] != '0') {
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$set['id_vrag']."','".$dater."','".$timer."','','','','','uho2')");
header('Location: voina.php');
exit();
}
} else {
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES('".$user_id."','".$set['id_vrag']."','".$dater."','".$timer."','','','','','lovk')");
header('Location: voina.php');
exit();
}
break;


}
require_once('system/down.php');
?>