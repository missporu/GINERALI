<?php
$_GET['case']=isset($_GET['case'])?htmlspecialchars($_GET['case']):NULL;
if($_GET['case']==''){
$titles=' | Контрабанда';
}elseif($_GET['case']==2){
$titles=' | Настройки';
}elseif($_GET['case']==3){
$titles=' | Ресурсы';
}elseif($_GET['case']==4){
$titles=' | Имущество';
}elseif($_GET['case']==5){
$titles=' | Банк';
}else{
$titles=FALSE;
}
$title='Военторг'.$titles.'';
require_once('system/up.php');
_Reg();
?><div class="main"><div class="menuList"><li><a href="blackmarket.php"><img src="images/icons/arrow.png" alt="*"/>Чёрный рынок</a></li><?
if($_GET['case'] != ''){
?><li><a href="voentorg.php"><img src="images/icons/arrow.png" alt="*"/>Контрабанда</a></li><?
}
if($_GET['case'] != 'podarok'){
?><li><a href="voentorg.php?case=podarok"><img src="images/icons/arrow.png" alt="*"/>Подарки (в разработке)</a></li><?
}
?></div></div><?
switch ($_GET['case']) {
default:
if(isset($_GET['log'])){
$tip=_NumFilter($_GET['log']);
if($tip < 1 OR $tip > 4){
$_SESSION['err'] = 'Нет контрабанды такого типа';
header('Location: voentorg.php');
exit();
}
$data_sail=_FetchAssoc("SELECT * FROM `kontrabanda` WHERE `id`='".$tip."' LIMIT 1");
if($data_sail['cena']>$set['gold']){
$_SESSION['err'] = 'Недостаточно золота!';
header('Location: voentorg.php');
exit();
}
mysql_query("UPDATE `user_set` SET `gold`=`gold`-'" . $data_sail['cena'] . "' WHERE `id`='" . $user_id . "' LIMIT 1");
$random_shans=mt_rand(1,100);
if($random_shans<=$data_sail['shans']){
$random_superunit=mt_rand(1,9);
if($data_sail['id']==4 AND $random_shans<=$data_sail['shans2']){
$random2_superunit=mt_rand(1,9);
mysql_query("UPDATE `user_superunit` SET `kol`=`kol`+'1' WHERE `id_unit`='" . $random2_superunit . "' AND `id_user`='" . $user_id . "' LIMIT 1");
$data2_sail_unit=_FetchAssoc("SELECT * FROM `user_superunit` WHERE `id_unit`='".$random2_superunit."' AND `id_user`='" . $user_id . "' LIMIT 1");
$random2_superunit_screen='<img src="images/superunits/'.$random2_superunit.'.png" style="margin-left:5px;margin-right:0px;border:1px solid #999;" alt="Разработка">';
}else{
$random2_superunit_screen=FALSE;
}
mysql_query("UPDATE `user_superunit` SET `kol`=`kol`+'1' WHERE `id_unit`='" . $random_superunit . "' AND `id_user`='" . $user_id . "' LIMIT 1");
$data_sail_unit=_FetchAssoc("SELECT * FROM `user_superunit` WHERE `id_unit`='".$random_superunit."' AND `id_user`='" . $user_id . "' LIMIT 1");
$random_superunit_screen='<img src="images/superunits/'.$random_superunit.'.png" style="margin-left:5px;margin-right:0px;border:1px solid #999;" alt="Разработка">';
if($data_sail['id']==4 AND $random_shans<=$data_sail['shans2']){
$_SESSION['ok'] = 'Получены 2 секретные разработки!<br/>'.$random_superunit_screen.''.$random2_superunit_screen.'<br/>';
}else{
$_SESSION['ok'] = 'Получена секретная разработка!<br/>'.$random_superunit_screen.'<br/>';
}
header('Location: voentorg.php');
exit();
} else {
$_SESSION['err'] = 'Секретная разработка не получена!';
header('Location: voentorg.php');
exit();
}
}
echo'<div class="main"><div class="mini-line"></div><div class="block_zero center"><h1 class="yellow">Контрабанда</h1></div><div class="mini-line"></div><div class="block_zero"><span style="color: #c66;">Любой каприз за Ваши деньги. Торги проходят по системе "кот в мешке".</span></div>';
$data_kontrabanda = mysql_query("SELECT * FROM `kontrabanda` ORDER BY `id` ASC LIMIT 4");
        while ($user_kontrabanda = mysql_fetch_assoc($data_kontrabanda)) {
        ?><div class="mini-line"></div><table width="100%"><tr><td width="40%"><img class="float-left" src="images/kontrabands/<?= $user_kontrabanda['id'] ?>.png" style="margin-left:5px;margin-right:0px;border:1px solid #999;" alt="Контрабанда"></td><td><b><span style="color: #9c9;"><?= $user_kontrabanda['name'] ?></span></b><br/><small><?= $user_kontrabanda['opisanie'] ?></small></td></tr></table><div class="block_zero center"><span style="color: #f96;"><small><?= $user_kontrabanda['result'] ?><br/></span></small><a class="btn" href="voentorg.php?log=<?= $user_kontrabanda['id'] ?>"><span class="end"><span class="label">Купить за <img src="images/icons/gold.png" alt="*" /> <?= $user_kontrabanda['cena'] ?></span></span></a></center></div><?
        }
        echo'<div class="mini-line"></div><ul class="hint"><li>Размер вознаграждения сильно зависит от размера контрабанды.</li></ul>';
echo'</div></div>';
break;

case 'podarok':

break;

}
require_once('system/down.php');
?>
