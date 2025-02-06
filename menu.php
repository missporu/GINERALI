<?php
///$title='Главная';
require_once ('system/up.php');
_Reg();

if($set['sex']=='m'){
$sex_pers='m';
}else{
$sex_pers='w';
}
?>
<div class="cont">
<table width="100%">
<tr><td width="50%">

<div class="block_dashed"><a href="voina.php?case=vrag"><img src='/img/voina.png' width='100%' alt=''/></b></div></a></div>
</td><td>

<div class="block_dashed"><a href="build.php"><img src='/img/Legion.png' width='100%' alt=''/></b></div></a></div>
</td></tr>
<tr><td width="50%">

<div class="block_dashed"><a href="mission.php"><img src='/img/miss.png' width='100%' alt=''/></b></div></a></div>
</td><td>

<div class="block_dashed"><a href="production.php"><img src='/img/proizvodstvo.png' width='100%' alt=''/></b></div></a></div>
</td></tr>
<tr><td width="50%">

<div class="block_dashed"><a href="unit.php"><img src='/img/tehnika.png' width='100%' alt=''/></b></div></a></div>
</td><td>

<div class="block_dashed"><a href="build.php"><img src='/img/postroiki.png' width='100%' alt=''/></b></div></a></div>
</td></tr>
<tr><td width="50%">

<div class="block_dashed"><a href="blackmarket.php"><img src='/img/rinok.png' width='100%' alt=''/></b></div></a></div>
</td><td>

<div class="block_dashed"><a href="ofclub.php"><img src='/img/klub.png' width='100%' alt=''/></b></div></a></div>
</td></tr></table>
</div>

<div class="mini-line"></div>
<div class="cont">

<table width="100%">
<tr><td width="50%">

<div class="block_dashed"><a href="pers.php"><div class="heading"><img src="images/icons/arrow.png" alt="*"/><b><span style="color: #4f4f4f;"> Профиль</span></b></div></a></div>
</td><td>
<div class="block_dashed"><a href="raiting.php"><div class="heading"><img src="images/icons/arrow.png" alt="*"/><b><span style="color: #4f4f4f;"> Зал славы</span></b></div></a></div>
</td></tr>
<tr><td width="50%">
<div class="block_dashed"><a href="alliance.php"><div class="heading"><img src="images/icons/arrow.png" alt="*"/><b><?=$plus_priglas?> Альянс - <?=number_format($user_alliance+1)?></span></b></div></a></div>
</td><td>
<div class="block_dashed"><a href="rooms.php?case=room"><div class="heading"><img src="images/icons/arrow.png" alt="*"/><b><span style="color: #4f4f4f;"> Общение</span></b></div></a></div>
</td></tr>
<tr><td width="50%">
<div class="block_dashed"><a href="bank.php"><div class="heading"><img src="images/icons/arrow.png" alt="*"/><b><span style="color: #4f4f4f;"> Банк</span></b></div></a></div>
</td><td>
<div class="block_dashed"><a href="mail.php"><div class="heading"><img src="images/icons/arrow.png" alt="*"/><b><?=$plus_mail?> Почта</span></b></div></a></div>
</td></tr>
<tr><td width="50%">
<div class="block_dashed"><a href="hosp.php"><div class="heading"><img src="images/icons/arrow.png" alt="*"/><b><span style="color: #4f4f4f;"> Госпиталь</span></b></div></a></div>
</td><td>
<div class="block_dashed"><a href="news.php"><div class="heading"><img src="images/icons/arrow.png" alt="*"/><b><?=$plus_news?> Новости</span></b></div></a></div>
</td></tr></table></div><?
if($set['prava']==5){
?><div class="mini-line"></div><div class="block_zero center"><a href="admin.php"><div class="head"><span style="color: #9bc;">Админ - панель</span></div></a></div><?
}
require_once ('system/down.php');

?>
