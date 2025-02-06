<?php
$_GET['case']=isset($_GET['case'])?htmlspecialchars($_GET['case']):NULL;
if($_GET['case']=='naemniki'){
$titles=' | Наёмники';
}elseif($_GET['case']=='laboratory'){
$titles=' | Лаборатория';
}elseif($_GET['case']==3){
$titles=' | Ресурсы';
}elseif($_GET['case']==4){
$titles=' | Имущество';
}elseif($_GET['case']==5){
$titles=' | Банк';
}else{
$titles=FALSE;
}
$title='Чёрный рынок'.$titles.'';
require_once('system/up.php');
_Reg();
?><div class="main"><?
switch($_GET['case']){
default:
        if ($set['logo'] == 'on') {
?><img src="images/logotips/blackmarket.jpg" width="100%" alt="Чёрный рынок"/><div class="mini-line"></div><?
        }
        ?><div class="menuList">
<li><a href="voentorg.php"><img src="images/icons/arrow.png" alt="*"/>Военторг</a></li>
<li><a href="blackmarket.php?case=naemniki"><img src="images/icons/arrow.png" alt="*"/>Наемники</a></li>
<li><a href="blackmarket.php?case=laboratory"><img src="images/icons/arrow.png" alt="*"/>Лаборатория</a></li>
<?
break;

case 'naemniki':
?><div class="menuList"><li><a href="blackmarket.php"><img src="images/icons/arrow.png" alt="*"/>Чёрный рынок</a></li></div><div class="mini-line"></div><?
if(isset($_GET['log'])){
$sum=_NumFilter($_GET['log']);
$naem=_NumFilter($_GET['naem']);
if($sum>$set['gold']){
$_SESSION['err'] = 'Недостаточно золота!';
header('Location: blackmarket.php?case=naemniki');
exit();
}
if($_GET['log']==10){
mysql_query("UPDATE `user_set` SET `gold`=`gold`-'" . $sum . "' WHERE `id`='" . $user_id . "' LIMIT 1");
mysql_query("UPDATE `user_naemniki` SET `time_up`='" . (time()+86400) . "', `status`='1' WHERE `id_user`='" . $user_id . "' AND `id_naemnik`='" . $naem . "' LIMIT 1");
$_SESSION['ok'] = 'Вы наняли наёмника на 1 день!';
header('Location: blackmarket.php?case=naemniki');
exit();
}elseif($_GET['log']==20){
mysql_query("UPDATE `user_set` SET `gold`=`gold`-'" . $sum . "' WHERE `id`='" . $user_id . "' LIMIT 1");
mysql_query("UPDATE `user_naemniki` SET `time_up`='" . (time()+259200) . "', `status`='1' WHERE `id_user`='" . $user_id . "' AND `id_naemnik`='" . $naem . "' IMIT 1");
$_SESSION['ok'] = 'Вы наняли наёмника на 3 дня!';
header('Location: blackmarket.php?case=naemniki');
exit();
}elseif($_GET['log']==50){
mysql_query("UPDATE `user_set` SET `gold`=`gold`-'" . $sum . "' WHERE `id`='" . $user_id . "' LIMIT 1");
mysql_query("UPDATE `user_naemniki` SET `time_up`='" . (time()+604800) . "', `status`='1' WHERE `id_user`='" . $user_id . "' AND `id_naemnik`='" . $naem . "' LIMIT 1");
$_SESSION['ok'] = 'Вы наняли наёмника на неделю!';
header('Location: blackmarket.php?case=naemniki');
exit();
}else{
$_SESSION['err'] = 'Ошибка найма наёмника!';
header('Location: blackmarket.php?case=naemniki');
exit();
}
}
?><div class="block_zero center"><span style="color: #9cc;">Хватит нанимать по объявлениям в газете.<br/>Найми профессионалов.</span></div><?
$data=mysql_query("SELECT * FROM `naemniki` ORDER BY `id` ASC LIMIT 5");
while($naemniki=mysql_fetch_assoc($data)){
$user_naemniki=_FetchAssoc('SELECT * FROM `user_naemniki` WHERE `id_user`="'.$user_id.'" AND `id_naemnik`="'.$naemniki['id'].'" LIMIT 1');
?><div class="mini-line"></div><table width="100%"><tr><td width="40%"><img src="images/naemniki/<?= $naemniki['id'] ?>.jpg" style="border:1px solid #999;" alt="Наёмник"></td><td valign="top"><b><span style="color: #9c9;"><?= $naemniki['name'] ?></span></b><br/><small><?= $naemniki['opisanie'] ?></small></td></tr></table><div class="block_zero center"><span style="color: #f96;"><small><?= $naemniki['chto_daet'] ?><br/></span></small><?
$time =$user_naemniki['time_up'] - time();
if($user_naemniki['status']==1){
?></div><div class="dot-line"></div><div class="block_zero">Будет действовать:<span style="float: right;"><?= _DayTime($time) ?></span></div><?
}else{
?></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="blackmarket.php?case=naemniki&log=10&naem=<?= $naemniki['id'] ?>"><span class="end"><span class="label">Нанять на день за <img src="images/icons/gold.png" alt="*" />10</span></span></a></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="blackmarket.php?case=naemniki&log=20&naem=<?= $naemniki['id'] ?>"><span class="end"><span class="label">Нанять на 3 дня за <img src="images/icons/gold.png" alt="*" />20</span></span></a></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="blackmarket.php?case=naemniki&log=50&naem=<?= $naemniki['id'] ?>"><span class="end"><span class="label">Нанять на неделю за <img src="images/icons/gold.png" alt="*" />50</span></span></a></center></div><?
}
}
break;

case 'laboratory':
        if ($set['logo'] == 'on') {
?><img src="images/logotips/laboratory.jpg" width="100%" alt="Лаборатория"/><div class="mini-line"></div><?
        }
?><div class="menuList"><li><a href="blackmarket.php"><img src="images/icons/arrow.png" alt="*"/>Чёрный рынок</a></li></div><div class="mini-line"></div><?
if(isset($_GET['log'])){
$sum=_NumFilter($_GET['log']);
$lab=_NumFilter($_GET['lab']);
if($sum>$set['gold']){
$_SESSION['err'] = 'Недостаточно золота!';
header('Location: blackmarket.php?case=laboratory');
exit();
}
if($_GET['log']==20){
mysql_query("UPDATE `user_set` SET `gold`=`gold`-'" . $sum . "' WHERE `id`='" . $user_id . "' LIMIT 1");
mysql_query("UPDATE `user_laboratory` SET `time_up`='" . (time()+86400) . "', `status`='1' WHERE `id_user`='" . $user_id . "' AND `id_lab`='" . $lab . "' LIMIT 1");
$_SESSION['ok'] = 'Вы купили препарат на 1 день!';
header('Location: blackmarket.php?case=laboratory');
exit();
}elseif($_GET['log']==40){
mysql_query("UPDATE `user_set` SET `gold`=`gold`-'" . $sum . "' WHERE `id`='" . $user_id . "' LIMIT 1");
mysql_query("UPDATE `user_laboratory` SET `time_up`='" . (time()+259200) . "', `status`='1' WHERE `id_user`='" . $user_id . "' AND `id_lab`='" . $lab . "' IMIT 1");
$_SESSION['ok'] = 'Вы купили препарат на 3 дня!';
header('Location: blackmarket.php?case=laboratory');
exit();
}elseif($_GET['log']==100){
mysql_query("UPDATE `user_set` SET `gold`=`gold`-'" . $sum . "' WHERE `id`='" . $user_id . "' LIMIT 1");
mysql_query("UPDATE `user_laboratory` SET `time_up`='" . (time()+604800) . "', `status`='1' WHERE `id_user`='" . $user_id . "' AND `id_lab`='" . $lab . "' LIMIT 1");
$_SESSION['ok'] = 'Вы купили препарат на неделю!';
header('Location: blackmarket.php?case=laboratory');
exit();
}else{
$_SESSION['err'] = 'Ошибка покупки препарата!';
header('Location: blackmarket.php?case=laboratory');
exit();
}
}
?><div class="block_zero center"><span style="color: #9cc;">"Три недели в пути" – это не миф,<br/>это действие допинга.</span></div><?
$data=mysql_query("SELECT * FROM `laboratory` ORDER BY `id` ASC LIMIT 6");
while($laboratory=mysql_fetch_assoc($data)){
$user_laboratory=_FetchAssoc('SELECT * FROM `user_laboratory` WHERE `id_user`="'.$user_id.'" AND `id_lab`="'.$laboratory['id'].'" LIMIT 1');
?><div class="mini-line"></div><table width="100%"><tr><td width="40%"><img src="images/laboratory/<?= $laboratory['id'] ?>.png" style="border:1px solid #999;" alt="Препарат"></td><td valign="top"><b><span style="color: #9c9;"><?= $laboratory['name'] ?></span></b><br/><small><?= $laboratory['opisanie'] ?></small></td></tr></table><?
$time =$user_laboratory['time_up'] - time();
if($user_laboratory['status']==1){
?><div class="dot-line"></div><div class="block_zero">Будет действовать:<span style="float: right;"><?= _DayTime($time) ?></span></div><?
}else{
?><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="blackmarket.php?case=laboratory&log=20&lab=<?= $laboratory['id'] ?>"><span class="end"><span class="label">Купить на день за <img src="images/icons/gold.png" alt="*" />20</span></span></a></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="blackmarket.php?case=laboratory&log=40&lab=<?= $laboratory['id'] ?>"><span class="end"><span class="label">Купить на 3 дня за <img src="images/icons/gold.png" alt="*" />40</span></span></a></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="blackmarket.php?case=laboratory&log=100&lab=<?= $laboratory['id'] ?>"><span class="end"><span class="label">Купить на неделю за <img src="images/icons/gold.png" alt="*" />100</span></span></a></center></div><?
}
}
break;





}
echo'</div></div>';
require_once('system/down.php');
?>
