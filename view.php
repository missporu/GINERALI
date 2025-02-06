<?php
$title='Просмотр';
require_once('system/up.php');
_Reg();
$smotr=isset($_GET['smotr'])?_NumFilter($_GET['smotr']):NULL;
$smotr=_FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='".$smotr."' LIMIT 1");
$smotr_set=_FetchAssoc("SELECT * FROM `user_set` WHERE `id`='".$smotr['id']."' LIMIT 1");
$fataliti_vrag=_FetchAssoc("SELECT * FROM `user_fataliti` WHERE `id_user`='".$smotr['id']."' LIMIT 1");
$priglas=_NumRows("SELECT * FROM `alliance_priglas` WHERE `id_user`='".$user_id."' AND `kogo`='".$smotr['id']."'");
$vstupil=_NumRows("SELECT * FROM `alliance_user` WHERE `kto`='".$user_id."' AND `s_kem`='".$smotr['id']."'");
$vstupil2=_NumRows("SELECT * FROM `alliance_user` WHERE `kto`='".$smotr['id']."' AND `s_kem`='".$user_id."'");
$smotr_alliance=_NumRows("SELECT * FROM `alliance_user` WHERE `kto`='".$smotr['id']."' OR `s_kem`='".$smotr['id']."'");

if($smotr_set['sex']=='w'){$pol='Девушка';}elseif($smotr_set['sex']=='m'){$pol='Парень';} else {$pol='Не известно';}

if($smotr_set['side'] == 'r') {$strana_smotr='Россия';} elseif ($smotr_set['side'] == 'g') {$strana_smotr='Германия';} elseif ($smotr_set['side'] == 'a') {$strana_smotr='США';} elseif ($smotr_set['side'] == 'u') {$strana_smotr='Украина';} elseif ($smotr_set['side'] == 'b') {$strana_smotr='Белоруссия';} elseif ($smotr_set['side'] == 'c') {$strana_smotr='Китай';} elseif ($smotr_set['side'] == 'k') {$strana_smotr='Казахстан';} else {$strana_smotr='Не известно';}

$left=_FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='".$fataliti_vrag['uho1_kto']."' LIMIT 1");
$right=_FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='".$fataliti_vrag['uho2_kto']."' LIMIT 1");

if($fataliti_vrag['uho1_kto'] != 0 AND $fataliti_vrag['uho2_kto'] != 0) {
$uho='_2';
} elseif($fataliti_vrag['uho1_kto'] != 0) {
$uho='_1';
} elseif($fataliti_vrag['uho2_kto'] != 0) {
$uho='_1';
} else {
$uho=FALSE;
}

$smotr_naem=_FetchAssoc('SELECT * FROM `user_naemniki` WHERE `id_user`="'.$smotr['id'].'" AND `id_naemnik`="4" LIMIT 1');

$user_naem_lara=_FetchAssoc('SELECT * FROM `user_naemniki` WHERE `id_user`="'.$user_id.'" AND `id_naemnik`="5" LIMIT 1');

$smotr_naem_lara=_FetchAssoc('SELECT * FROM `user_naemniki` WHERE `id_user`="'.$smotr['id'].'" AND `id_naemnik`="5" LIMIT 1');

switch($_GET['case']){
default:

echo'<div class="main">';

if($user_id==1 AND $set['prava']==5 AND $user['login']=='ckpunmuk'){
echo'<div class="menuList"><li><a href="admin.php?case=1_1&id='.$smotr['id'].'"><img src="images/icons/arrow.png" alt="*"/>Редактировать</a></li><li><a href="admin.php?case=3_1&id='.$smotr['id'].'"><img src="images/icons/arrow.png" alt="*"/>Проверить на мультоводство</a></li><li><a href="mail.php?case=post&log='.$smotr['id'].'"><img src="images/icons/arrow.png" alt="*"/>Забанить</a></li></div><div class="mini-line"></div>';
}

echo'<div class="block_zero">';

echo'<img src="images/flags/'.$smotr_set['side'].'.png"  alt="Флаг"/> '.$smotr['login'].'<br /><small><span style="color: #fffabd;">Ур.</span> '.$smotr_set['lvl'].', <span style="color: #fffabd;"></span><span style="color: #fffabd;">Ал.</span> '.($smotr_alliance+1).', <span style="color: #fffabd;"> Рейтинг</span> '.$smotr_set['raiting'].'</small>';

echo'</div><div class="mini-line"></div>';

if($smotr_naem['status']!=1 OR ($smotr_naem['status']==1 AND $user_naem_lara['status']==1 AND $smotr_naem_lara['status']!=1)){

echo'<table width="100%"><tr><td width="25%">';

echo'<img class="float-left" src="images/avatars/'.$smotr_set['avatar'].''.$uho.'.jpg" style="margin-left:10px;margin-right:15px;border:2px solid grey;" alt="Аватар">';

echo'</td><td>';

echo'Страна:<span style="float: right;">'.$strana_smotr.'</span><br />';

echo'Звание:<span style="float: right;">'.$smotr_set['zvanie'].'</span><br />';

echo'Пол:<span style="float: right;">'.$pol.'</span><br />';

echo'Побед:<span style="float: right;">'.$smotr_set['wins'].'</span><br />';

echo'Поражений:<span style="float: right;">'.$smotr_set['loses'].'</span><br />';

echo'Убийств:<span style="float: right;">'.$smotr_set['kills'].'</span><br />';

echo'Смертей:<span style="float: right;">'.$smotr_set['dies'].'</span><br />';

echo'Ушей:<span style="float: right;">'.$smotr_set['uho'].'</span><br />';

echo'Жетонов:<span style="float: right;">'.$smotr_set['zheton'].'</span><br />';

echo'</td></tr></table>';

echo'<div class="mini-line"></div>';

if($fataliti_vrag['uho1_kto'] != 0) {
echo'<div class="block_zero">Левое ухо у: '.$left['login'].'</div><div class="dot-line"></div>';
}

if($fataliti_vrag['uho2_kto'] != 0) {
echo'<div class="block_zero">Правое ухо у: '.$right['login'].'</div><div class="mini-line"></div>';
}

}else{

?><table width="100%"><tr><td width="40%"><img src="images/naemniki/4.jpg" style="border:1px solid #999;" alt="Наёмник"></td><td valign="top"><small>Личность непонятной наружности преграждает Вам путь. Здравствуйте! А Вам кого? Хм, нет здесь такого. Какие танки? Не было их. Оружие?! Отродясь не видел. А Вы, извините, зачем интересуетесь? А Вы, кстати, кто?!!</small></td></tr></table><div class="mini-line"></div><?

}//наёмники

echo'<div class="block_zero center">';

if($smotr['id']==$user_id){
header("Location: pers.php");
exit();
}elseif($vstupil == 1 OR $vstupil2 == 1) {
echo'<a class="btn" href=""><span class="end"><span class="label"><span class="dgreen">Отправить подкрепление</span></span></span></a></div><div class="menuList">';
} elseif($priglas == 0) {
if($smotr_set['lvl']<($set['lvl']+11) AND $smotr_set['lvl']>($set['lvl']-11)){
echo'<a class="btn" href="voina.php?case=ataka&vrag='.$smotr['id'].'"><span class="end"><span class="label"><span class="dred">Атаковать</span></span></span></a>';
}
echo' <a class="btn" href="view.php?case=priglas&smotr='.$smotr['id'].'"><span class="end"><span class="label"><span class="dgreen">Пригласить в альянс</span></span></span></a>';
if($smotr_set['lvl']<($set['lvl']+11) AND $smotr_set['lvl']>($set['lvl']-11)){
echo'</div><div class="mini-line"></div><div class="menuList"><li><a href="view.php?case=sanction&log='.$smotr['id'].'"><img src="images/icons/ataka.png" alt="*"/>Добавить в санкции</a></li></div>';
}
} else {
echo'<small><span class="green">Отправлено приглашение в альянс</span></small></div><div class="menuList">';
}
if($smotr_set['lvl']<($set['lvl']+11) AND $smotr_set['lvl']>($set['lvl']-11)){
echo'<div class="mini-line"></div><div class="menuList"><li><a href="mail.php?case=post&log='.$smotr['id'].'"><img src="images/icons/mail.png" alt="*"/>Написать</a></li>';
}else{
echo'</div><div class="mini-line"></div><div class="menuList"><li><a href="mail.php?case=post&log='.$smotr['id'].'"><img src="images/icons/mail.png" alt="*"/>Написать</a></li>';
}

echo'</div>';

if($smotr_naem['status']!=1 OR ($smotr_naem['status']==1 AND $user_naem_lara['status']==1 AND $smotr_naem_lara['status']!=1)){

?><div class="mini-line"></div><div class="block_zero"><span style="color: #9c9;">Наземная техника</span></div><?

$data=mysql_query("SELECT * FROM `user_unit` WHERE `tip` IN('1','4') AND `id_user`='".$smotr['id']."' ORDER BY `id` ASC");
?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_unit=mysql_fetch_assoc($data)){
if($my_unit['kol']>0){
if($my_unit['tip']==4){
$unit_color='f96';
}else{
$unit_color='999';
}
++$cols;
?><td><img src="images/units/<?= $my_unit['id_unit'] ?>.png" width="65px" height="45px" style="border:1px solid #<?=$unit_color?>;" alt="Техника"><br/><center><small><?= $my_unit['kol'] ?></small></center></td><?
}
if ($cols == $maxcols) {
?></tr><tr><?
$cols = 0;
}
} 
?></table><?

?><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Морская техника</span></div><?

$data=mysql_query("SELECT * FROM `user_unit` WHERE `tip` IN('2','5') AND `id_user`='".$smotr['id']."' ORDER BY `id` ASC");
?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_unit=mysql_fetch_assoc($data)){
if($my_unit['tip']==5){
$unit_color='f96';
}else{
$unit_color='999';
}
if($my_unit['kol']>0){
++$cols;
?><td><img src="images/units/<?= $my_unit['id_unit'] ?>.png" width="65px" height="45px" style="border:1px solid #<?=$unit_color?>;" alt="Техника"><br/><center><small><?= $my_unit['kol'] ?></small></center></td><?
}
if ($cols == $maxcols) {
?></tr><tr><?
$cols = 0;
}
} 
?></table><?

?><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Воздушная техника</span></div><?

$data=mysql_query("SELECT * FROM `user_unit` WHERE `tip` IN('3','6') AND `id_user`='".$smotr['id']."' ORDER BY `id` ASC");
?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_unit=mysql_fetch_assoc($data)){
if($my_unit['tip']==6){
$unit_color='f96';
}else{
$unit_color='999';
}
if($my_unit['kol']>0){
++$cols;
?><td><img src="images/units/<?= $my_unit['id_unit'] ?>.png" width="65px" height="45px" style="border:1px solid #<?=$unit_color?>;" alt="Техника"><br/><center><small><?= $my_unit['kol'] ?></small></center></td><?
}
if ($cols == $maxcols) {
?></tr><tr><?
$cols = 0;
}
} 
?></table><?

?><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Секретные разработки</span></div><?

$data=mysql_query("SELECT * FROM `user_superunit` WHERE `id_user`='".$smotr['id']."' ORDER BY `id` ASC");
?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_unit=mysql_fetch_assoc($data)){
if($my_unit['kol']>0){
++$cols;
?><td><img src="images/superunits/<?= $my_unit['id_unit'] ?>.png" width="65px" height="45px" style="border:1px solid #999;" alt="Техника"><br/><center><small><?= $my_unit['kol'] ?></small></center></td><?
}
if ($cols == $maxcols) {
?></tr><tr><?
$cols = 0;
}
} 
?></table><?

?><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Доходные постройки</span></div><?

$data=mysql_query("SELECT * FROM `user_build` WHERE`tip`='1' AND `id_user`='".$smotr['id']."' ORDER BY `id` ASC");
?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_build=mysql_fetch_assoc($data)){
if($my_build['kol']>0){
++$cols;
?><td><img src="images/buildings/<?= $my_build['id_build'] ?>.png" width="65px" height="45px" style="border:1px solid #999;" alt="Техника"><br/><center><small><?= $my_build['kol'] ?></small></center></td><?
}
if ($cols == $maxcols) {
?></tr><tr><?
$cols = 0;
}
} 
?></table><?

?><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Защитные постройки</span></div><?

$data=mysql_query("SELECT * FROM `user_build` WHERE `tip`='2' AND `id_user`='".$smotr['id']."' ORDER BY `id` ASC");
?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_build=mysql_fetch_assoc($data)){
if($my_build['kol']>0){
++$cols;
?><td><img src="images/buildings/<?= $my_build['id_build'] ?>.png" width="65px" height="45px" style="border:1px solid #999;" alt="Техника"><br/><center><small><?= $my_build['kol'] ?></small></center></td><?
}
if ($cols == $maxcols) {
?></tr><tr><?
$cols = 0;
}
} 
?></table><?

?><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Энергетические постройки</span></div><?

$data=mysql_query("SELECT * FROM `user_build` WHERE `tip`='3' AND `id_user`='".$smotr['id']."' ORDER BY `id` ASC");
?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_build=mysql_fetch_assoc($data)){
if($my_build['kol']>0){
++$cols;
?><td><img src="images/buildings/<?= $my_build['id_build'] ?>.png" width="65px" height="45px" style="border:1px solid #999;" alt="Техника"><br/><center><small><?= $my_build['kol'] ?></small></center></td><?
}
if ($cols == $maxcols) {
?></tr><tr><?
$cols = 0;
}
} 
?></table><?

}

?></div></div><?


break;
case 'priglas':
$data=_FetchAssoc("SELECT * FROM `alliance_priglas` WHERE `id_user`='".$user_id."' AND `kogo`='".$smotr['id']."'");
if($data){
$_SESSION['err']='Уже было отправлено приглашение';
header('Location: view.php?smotr='.$smotr['id'].'');
exit();
}
if($set['diplomat'] == 0) {
$_SESSION['err']='Нет свободных дипломатов';
header('Location: view.php?smotr='.$smotr['id'].'');
exit();
}
mysql_query("INSERT INTO `alliance_priglas` (id_user,kogo,priglas_up) VALUES ('".$user_id."','".$smotr['id']."','"._NumFilter(time()+10800)."')");
mysql_query("INSERT INTO `alliance_diplom` (id_user,diplom_up) VALUES ('".$user_id."','"._NumFilter(time()+3600)."')");
mysql_query('UPDATE `user_set` SET `diplomat`=`diplomat` - "1" WHERE `id`="'.$user_id.'"');
$_SESSION['ok']='Отправлено приглашение в альянс';
header('Location: view.php?smotr='.$smotr['id'].'');
exit();
break;

case 'sanction':
$sanction_id=isset($_GET['log'])?_NumFilter($_GET['log']):NULL;
if(!$sanction_id){
$_SESSION['err']='Не выбран игрок для добавления в санкции!';
header('Location: menu.php');
exit();
}
$set_sanction=_FetchAssoc("SELECT `lvl` FROM `user_set` WHERE `id`='".$sanction_id."' LIMIT 1");
if($set['lvl']<($set_sanction['lvl']-10)){
$_SESSION['err']='Уровень противника больше, чем на 10!';
header('Location: menu.php');
exit();
}
if($set['lvl']>($set_sanction['lvl']+10)){
$_SESSION['err']='Уровень противника меньше, чем на 10!';
header('Location: menu.php');
exit();
}
if($sanction_id==$user_id){
$_SESSION['err']='Вы пытаетесь добавить в санкции<br/>самого себя!';
header('Location: menu.php');
exit();
}
$login_sanction=_FetchAssoc("SELECT `login` FROM `user_reg` WHERE `id`='".$sanction_id."' LIMIT 1");

$data_sanction=_FetchAssoc("SELECT * FROM `sanction` WHERE `kto`='".$user_id."' AND `kogo`='".$sanction_id."' LIMIT 1");
if(!$data_sanction){
mysql_query("INSERT INTO `sanction` (kto,kogo,data) VALUES ('".$user_id."','".$sanction_id."','".$dater."')");
header('Location: view.php?case=sanction&log='.$sanction_id.'');
}

if(isset($_POST['send'])){
$sanction_id=isset($_GET['log'])?_NumFilter($_GET['log']):NULL;
$data_sanction=_FetchAssoc("SELECT * FROM `sanction` WHERE `kto`='".$user_id."' AND `kogo`='".$sanction_id."' LIMIT 1");
if(!$sanction_id){
$_SESSION['err']='Не выбран игрок для добавления в санкции!';
header('Location: menu.php');
exit();
}
if($sanction_id==$user_id){
$_SESSION['err']='Вы пытаетесь добавить в санкции<br/>самого себя!';
header('Location: menu.php');
exit();
}
$sanction_summa=isset($_POST['summa'])?_NumFilter($_POST['summa']):NULL;
if(($data_sanction['stavka']*$set['lvl'])>$sanction_summa){
$_SESSION['err']='Вы ввели ссумму, которая<br/> меньше минимального вознаграждения!';
header('Location: view.php?case=sanction&log='.$sanction_id.'');
exit();
}
if($set['baks']<$sanction_summa){
$_SESSION['err']='Не хватает баксов!';
header('Location: view.php?case=sanction&log='.$sanction_id.'');
exit();
}
$login_sanction=_FetchAssoc("SELECT `login` FROM `user_reg` WHERE `id`='".$sanction_id."' LIMIT 1");
$sex_sanction=_FetchAssoc("SELECT `sex` FROM `user_set` WHERE `id`='".$sanction_id."' LIMIT 1");
mysql_query("UPDATE `sanction` SET `data`='".$dater."', `time_up`='".(time()+3600)."', `nagrada`=`nagrada`+'".$sanction_summa."', `stavka`='".($data_sanction['stavka']*2)."' WHERE `kto`='".$user_id."' AND `kogo`='".$sanction_id."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `sanction_status`='1' WHERE `id`='".$sanction_id."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `baks`=`baks`-'".$sanction_summa."' WHERE `id`='".$user_id."' LIMIT 1");
mysql_query("INSERT INTO `user_voina` (id_user,id_vrag,data,time,nanes,poluchil,baks,exp,rezult) VALUES ('".$user_id."','".$sanction_id."','".$dater."','".$timer."','','','','','spopa')");
if($sex_sanction['sex']=='m'){
$kto='ним';
}else{
$kto='ней';
}
$_SESSION['ok']='Вы добавили '.$login_sanction['login'].' в санкции.<br/>Скоро с '.$kto.' поквитаются!';
header('Location: view.php?smotr='.$sanction_id.'');
}

?><div class="main"><div class="menuList"><li><a href="voina.php?case=vrag"><img src="images/icons/ataka.png" alt="*"/>Война</a></li></div><div class="mini-line"></div><div class="block_zero center"><h1 class="yellow">Добавление в санкции</h1></div><div class="mini-line"></div><div class="block_zero center"><?

if($data_sanction AND $data_sanction['time_up']!=0){
$time = $data_sanction['time_up'] - time();
?>Добавлять одного и того же игрока в санкции можно не чаще, чем раз в час.<br/>Осталось: <?=_Time($time)?></div></div><?
}else{
?>Вы собираетесь добавить <?=$login_sanction['login']?> в санкции. Минимальное вознаграждение <img src="images/icons/baks.png" alt="*"/> <?=number_format($data_sanction['stavka']*$set['lvl'])?></div><div class="dot-line"></div><div class="block_zero center">Введите сумму вознаграждения:<form action="view.php?case=sanction&log=<?=$sanction_id?>" method="post"><input class="text" type="text" name="summa" size="30" value="<?=($data_sanction['stavka']*$set['lvl'])?>"/><br/><span class="btn"><span class="end"><input class="label" type="submit" name="send" value="Огласить санкцию"></span></span> </a></form></div><div class="mini-line"></div><ul class="hint"><li>Здесь можно добавить в санкции игрока, заплатив за его голову вознаграждение.</li></ul></div><?
}
break;
}
require_once('system/down.php');
?>
