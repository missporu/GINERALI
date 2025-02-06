<?php
$_GET['case']=isset($_GET['case'])?htmlspecialchars($_GET['case']):NULL;
if($_GET['case']=='donat'){
$titles=' | Покупка золота';
}elseif($_GET['case']=='obmen'){
$titles=' | Обменник';
}elseif($_GET['case']=='kredit'){
$titles=' | Кредит';
}else{
$titles=' | Хранилище';
}
$title='Банк'.$titles.'';
require_once('system/up.php');
_Reg();
?><div class="main"><?
if($set['logo'] == 'on' AND $_GET['case'] != 'worldkassa'){
?><img src="images/logotips/bank.jpg" width="100%" alt="Банк"/><div class="mini-line"></div><?
}
?><div class="menuList"><?
if($_GET['case'] != '' AND $_GET['case'] != 'worldkassa'){
?><li><a href="bank.php"><img src="images/icons/arrow.png" alt="*" />Хранилище</a></li><?
}
if($_GET['case'] != 'donat'){
?><li><a href="bank.php?case=donat"><img src="images/icons/arrow.png" alt="*" />Покупка золота</a></li><?
}
if($_GET['case'] != 'obmen' AND $_GET['case'] != 'worldkassa'){
?><li><a href="bank.php?case=obmen"><img src="images/icons/arrow.png" alt="*" />Обменник</a></li><?
}
if($_GET['case'] != 'kredit' AND $_GET['case'] != 'worldkassa'){
?><li><a href="bank.php?case=kredit"><img src="images/icons/arrow.png" alt="*" />Кредит</a></li><?
}
if($_GET['case'] != 'worldkassa'){
?></div><div class="mini-line"></div><div class="block_zero center"><?
}
$trof_bank=_FetchAssoc("SELECT * FROM `user_trofei` WHERE `id_user` = '".$user_id."' AND `id_trof` = '1'");
if($trof_bank['status']==1 AND $trof_bank['time_up']==0){
$bonus_bank=10-$trof_bank['bonus_1'];
}else{
$bonus_bank=10;
}

switch($_GET['case']){
default:
if(isset($_POST['inhran'])){
	$in = _NumFilter($_POST['in']);
	if(empty($in)){
$_SESSION['err'] = 'Введите сумму';
header('Location: bank.php');
exit();	
}		 elseif($set['baks'] < $in){
mysql_query('UPDATE `user_set` SET `baks` =  `baks` - "'.$set['baks'].'", `baks_hran` = `baks_hran` + "'.($set['baks']-($set['baks']/100*$bonus_bank)).'" WHERE `id` = "'.$user_id.'"');
$_SESSION['err'] = 'Не хватает баксов,<br />помещено в хранилище <img src="images/icons/baks.png" alt="*" /> '.number_format($set['baks']-($set['baks']/100*$bonus_bank)).'';
header('Location: bank.php');
exit();
} else {
mysql_query('UPDATE `user_set` SET `baks` =  `baks` - "'.$in.'", `baks_hran` = `baks_hran` + "'.($in-($in/100*$bonus_bank)).'" WHERE `id` = "'.$user_id.'"');	
$_SESSION['ok'] = 'Помещено в хранилище <img src="images/icons/baks.png" alt="*" /> '.number_format($in-($in/100*$bonus_bank)).'';
header('Location: bank.php');
exit();
}
}

if(isset($_POST['outhran'])){
	$out = _NumFilter($_POST['out']);
	if(empty($out)) {
$_SESSION['err'] = 'Введите сумму';
header('Location: bank.php');
exit();	
}		 elseif($set['baks_hran'] < $out) {
mysql_query('UPDATE `user_set` SET `baks` =  `baks` + "'.$set['baks_hran'].'", `baks_hran` = `baks_hran` - "'.$set['baks_hran'].'" WHERE `id` = "'.$user_id.'"');
$_SESSION['err'] = 'Не хватает баксов,<br />изъято из хранилища <img src="images/icons/baks.png" alt="*" /> '.number_format($set['baks_hran']).'';
header('Location: bank.php');
exit();
} else {
mysql_query('UPDATE `user_set` SET `baks` =  `baks` + "'.$out.'", `baks_hran` = `baks_hran` - "'.$out.'" WHERE `id` = "'.$user_id.'"');	
$_SESSION['ok'] = 'Изъято из хранилища <img src="images/icons/baks.png" alt="*" /> '.number_format($out).'';
header('Location: bank.php');
exit();
}
}
?>Общий баланс: <img src="images/icons/baks.png" alt="*"/> <?=number_format($set['baks_hran'])?></div><div class="dot-line"></div><div class="block_zero center"><form action="bank.php" method="post"><input class="text" type="text" name="in" value="<?=$set['baks']?>" /><br /><span class="btn"><span class="end"><input class="label" name="inhran" type="submit" value="Положить" /></span></span></form></div><div class="dot-line"></div><div class="block_zero center"><form action="?" method="post"><input class="text" type="text" name="out" value="<?=$set['baks_hran']?>" /><br /><span class="btn"><span class="end"><input class="label" name="outhran" type="submit" value="Снять"/></span></span></form></div><div class="mini-line"></div><ul class="hint"><li>Здесь ты можешь хранить честно отобранные у врага деньги без опасения, что их у тебя отберут.</li><li>При вкладе снимается 10% от суммы вклада, уменьшить который можно, прокачивая трофей "Инкассатор".</li></ul></div><?
break;

case 'donat':
?>Ваш бонус:<br/><?
if($set['donat_bonus']<5000){
?><img src="images/donat/5.jpg" alt="Бонус-карта"/><br/>До бонуса 10% осталось купить <img src="images/icons/gold.png" alt="Бонус-карта"/><?=5000-$set['donat_bonus']?><?
}elseif($set['donat_bonus']<15000){
?><img src="images/donat/10.jpg" alt="Бонус-карта"/><br/>До бонуса 20% осталось купить <img src="images/icons/gold.png" alt="Бонус-карта"/><?=15000-$set['donat_bonus']?><?
}elseif($set['donat_bonus']<35000){
?><img src="images/donat/20.jpg" alt="Бонус-карта"/><br/>До бонуса 30% осталось купить <img src="images/icons/gold.png" alt="Бонус-карта"/><?=35000-$set['donat_bonus']?><?
}else{
?><img src="images/donat/30.jpg" alt="Бонус-карта"/><?
}
?></div><div class="mini-line"></div><div class="menuList"><li><a href="bank.php?case=worldkassa"><img src="images/icons/gold.png" alt="*" />Worldkassa</a></li></div><?
?></div></div><?
break;

case 'obmen':
if(isset($_GET['log'])){
$obmen=_NumFilter($_GET['log']);
if($set['gold'] < $obmen) {
$_SESSION['err'] = 'Не хватает золота для обмена';
header('Location: bank.php?case=obmen');
exit();
} else {
$summa = _NumFilter($obmen * (($set['lvl']+1)*500));
mysql_query('UPDATE `user_set` SET `baks` =  `baks` + "'.$summa.'", `gold` = `gold` - "'.$obmen.'" WHERE `id` = "'.$user_id.'"');
$_SESSION['ok'] = 'Обмен <img src="images/icons/gold.png" alt="*" /> '.$obmen.' на <img src="images/icons/baks.png" alt="*" /> '.number_format($summa).' совершён';
header('Location: bank.php?case=obmen');
exit();
}
}
?>Текущий курс: <img src="images/icons/gold.png" alt="*" /> 1 = <img src="images/icons/baks.png" alt="*" /> <?=number_format(($set['lvl']+1)*500)?></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="bank.php?case=obmen&log=5"><span class="end"><span class="label">Обменять <img src="images/icons/gold.png" alt="*" /> 5 на <img src="images/icons/baks.png" alt="*" /> <?=number_format(5 * (($set['lvl']+1)*500))?></span></span></a></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="bank.php?case=obmen&log=50"><span class="end"><span class="label">Обменять <img src="images/icons/gold.png" alt="*" /> 50 на <img src="images/icons/baks.png" alt="*" /> <?=number_format(50 * (($set['lvl']+1)*500))?></span></span></a></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="bank.php?case=obmen&log=100"><span class="end"><span class="label">Обменять <img src="images/icons/gold.png" alt="*" /> 100 на <img src="images/icons/baks.png" alt="*" /> <?=number_format(100 * (($set['lvl']+1) * 500))?></span></span></a></div><div class="mini-line"></div><ul class="hint"><li>Здесь можно обменять золото на деньги. Ворованные телефоны, часы и вражеские снаряды обмену не подлежат.</li></ul></div><?
break;

case 'kredit':

?></div></div><?
break;

case 'worldkassa':
if($set['donat_bonus']<5000){
$bonus=5;
}elseif($set['donat_bonus']<15000){
$bonus=10;
}elseif($set['donat_bonus']<35000){
$bonus=20;
}else{
$bonus=30;
}// бонусная карта
if($set['logo'] == 'on'){
?><div class="block_zero center"><img src="images/donat/wk.jpg" width="150" height="60" alt="Worldkassa"/><?
}
?></div><div class="mini-line"></div><?
$data=mysql_query("SELECT * FROM `worldkassa_summa` ORDER BY `id` ASC LIMIT 5");
while($worldkassa_summa=mysql_fetch_assoc($data)){
?><a href="wk_donat/index.php?gold=<?=$worldkassa_summa['summa']?>"><img class="float-left" src="images/donat/<?=$worldkassa_summa['id']?>.png" width="50" height="50" style="margin-left:1px; margin-right:10px;" alt="Золото"><b><span style="color: #9c9"><?=$worldkassa_summa['opisanie']?></span></b><span style="color: #999"> - <?=$worldkassa_summa['summa']?> рублей</span><br><img src="images/icons/gold.png" alt="*"><?=$worldkassa_summa['summa']?> золота<br><span style="color: #9bc;">+ <?=$bonus?>% по Бонусной Карте</span></a><div class="dot-line"></div><?
}
?></div></div><?
break;
}
require_once('system/down.php');
?>
