<?php
$_GET['case']=isset($_GET['case'])?htmlspecialchars($_GET['case']):NULL;
if($_GET['case']=='raketa'){
$titles=' | Ракеты';
}else{
$titles=' | Шахта';
}
$title='Производство'.$titles.'';
require_once('system/up.php');
_Reg();
?><div class="main"><?
if($set['logo'] == 'on'){
?><img src="images/logotips/production.jpg" width="100%" alt="Банк"/><div class="mini-line"></div><?
}
?><div class="menuList"><?
if($_GET['case'] != ''){
?><li><a href="production.php"><img src="images/icons/arrow.png" alt="*" />Шахта</a></li><?
}
if($_GET['case'] != 'raketa'){
?><li><a href="production.php?case=raketa"><img src="images/icons/arrow.png" alt="*" />Ракеты</a></li><?
}
switch($_GET['case']){
default:

break;


case 'raketa':

break;





}
?></div></div><?
require_once('system/down.php');
?>
