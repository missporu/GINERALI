<?php
$title='Настройки';
require_once('system/up.php');
_Reg();
if($set['logo'] == 'on'){
echo'<img src="images/logotips/set.jpg" width="100%" alt="logo"/>';
echo'<div class="mini-line"></div>';
}
switch($_GET['case']){
default:
echo'<div class="main"><div class="menuList">';
echo'<li><a href="set.php?case=logotip"><img src="images/icons/arrow.png" alt="*"/>Большие картинки</a></li>';
echo'<li><a href="set.php?case=fon"><img src="images/icons/fon.png" alt="*"/>Фон игры</a></li>';
echo'</div></div>';
break;
case 'logotip':
if(empty($_POST['logotip'])){
echo "<form action='set.php?case=logotip' method='POST'>";
echo'<div class="main"><div class="block_zero center">Показ больших картинок:';
echo'</div><div class="dot-line"></div><div class="block_zero center">';
if($set['logo'] == 'off'){
echo'<input type="radio" name="logotip" value="on" CHECKED> Включить<br/><br/><span class="btn"><span class="end"><input class="label" type="submit" value="Выполнить"></span></span></form></div></div>';
}else{
echo'<input type="radio" name="logotip" value="off" CHECKED> Отключить<br/><br/><span class="btn"><span class="end"><input class="label" type="submit" value="Выполнить"></span></span></form></div></div>';
}
}else{
$pan = _TextFilter($_POST['logotip']);
mysql_query("UPDATE `user_set` SET
`logo` =  '".$pan."' WHERE `id` = '".$user_id."' LIMIT 1");
$_SESSION['ok']="Настройки успешно изменены!";
header('Location: set.php?case=logotip');
exit();
}
echo'</div><div class="dot-line"></div><div class="main"><div class="block_zero"><a href="set.php"><span style="color: #999;"><< Назад</span></a></div></div>';
break;
case 'fon':
if(empty($_POST['fon'])){
echo "<form action='set.php?case=fon' method='POST'>";
echo'<div class="main"><div class="block_zero center">Фон игры: '.$cvet.'';
echo'</div><div class="dot-line"></div><div class="block_zero">';
echo'<input type="radio" name="fon" value="standart" CHECKED/> Стандартный<br/><br/><input type="radio" name="fon" value="blue"/> Синий<br/><br/><input type="radio" name="fon" value="green"/> Зелёный<br/><br/><input type="radio" name="fon" value="red"/> Красный<br/><br/><center><span class="btn"><span class="end"><input class="label" type="submit" value="Изменить"></span></span></form></div></div></center>';
}else{
$pan = _TextFilter($_POST['fon']);
mysql_query("UPDATE `user_set` SET `fon` =  '".$pan."' WHERE `id` = '".$user_id."' LIMIT 1");
$_SESSION['ok']="Настройки успешно изменены!";
header('Location: set.php?case=fon');
exit();
}
echo'</div><div class="dot-line"></div><div class="main"><div class="block_zero"><a href="set.php"><span style="color: #999;"><< Назад</span></a></div></div>';
break;

}
require_once('system/down.php');
?>
