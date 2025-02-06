<?php
$title = 'Зал славы';
require_once('system/up.php');
_Reg();
?><div class="main"><?
if($set['logo'] == 'on'){
?><img src="images/logotips/raiting.jpg" width="100%" alt="Рейтинг"/><div class="mini-line"></div><?
}
?><div class="menuList"><?
if($_GET['case'] != ''){
?><li><a href="raiting.php"><img src="images/icons/arrow.png" alt="*" />Рейтинг</a></li><?
}
if($_GET['case'] != 'heroes'){
?><li><a href="raiting.php?case=heroes"><img src="images/icons/arrow.png" alt="*" />Герои</a></li><?
}
if($_GET['case'] != 'legions'){
?><li><a href="raiting.php?case=legions"><img src="images/icons/arrow.png" alt="*" />Легионы</a></li><?
}
if($_GET['case'] != 'statistika'){
?><li><a href="raiting.php?case=statistika"><img src="images/icons/arrow.png" alt="*" />Статистика</a></li><?
}
?></div><div class="mini-line"></div><?
switch($_GET['case']){
default:
$user_rating=_NumRows("SELECT * FROM `user_set` WHERE `raiting`>='".$set['raiting']."' ORDER BY `raiting` DESC");
?><div class="block_zero">Вы на <?=$user_rating?> месте<br/>Ваш рейтинг: <?=$set['raiting']?></div><div class="mini-line"></div><?
$data_rate=mysql_query("SELECT * FROM `user_set` ORDER BY `raiting` DESC LIMIT 10");
$i=1;
while($rate=mysql_fetch_assoc($data_rate)){
$rate_login=_FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='".$rate['id']."' LIMIT 1");
$rate_alliance=_NumRows("SELECT * FROM `alliance_user` WHERE `kto`='".$rate['id']."' OR `s_kem`='".$rate['id']."'");
?><div class="block_zero"><?=$i?>. <img src="images/flags/<?= $rate['side'] ?>.png" alt="*"/><a href="view.php?smotr=<?= $rate['id'] ?>"> <?= $rate_login['login'] ?></a><br/><small><span style="color: #fffabd;">Ур: <?= $rate['lvl'] ?>, Ал: <?= ($rate_alliance + 1) ?>, Рейтинг: <?= $rate['raiting'] ?></span></small></div><div class="dot-line"></div><?
$i++;
}
?><div class="mini-line"></div><ul class="hint"><li>Здесь отображается общеигровой рейтинг.</li><li>При расчете рейтинга принимаются во внимание победы и поражения в боях.</li><ul><?
break;

case 'heroes':
break;

case 'legions':
break;

case 'statistika':
break;

}
?></div></div><?
require_once('system/down.php');
?>
