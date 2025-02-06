<?php
$title = 'Профиль';
require_once('system/up.php');
_Reg();
?><div class="main"><div class="menuList"><?

if($_GET['case'] != ''){
?><li><a href="pers.php"><img src="images/icons/arrow.png" alt="*" />Персонаж</a></li><?
}
if($_GET['case'] != 'raspred'){
?><li><a href="pers.php?case=raspred"><img src="images/icons/arrow.png" alt="*" />Навыки</a></li><?
}
if($_GET['case'] != 'unitbuild'){
?><li><a href="pers.php?case=unitbuild"><img src="images/icons/arrow.png" alt="*" />Имущество</a></li><?
}
if($_GET['case'] != 'trof'){
?><li><a href="pers.php?case=trof"><img src="images/icons/arrow.png" alt="*" />Трофеи</a></li><?
}

?></div><?

switch ($_GET['case']) {
default:

?><div class="mini-line"></div><?

?><div class="block_zero"><img src="images/flags/<?=$set['side']?>.png"  alt="Флаг"/> <?=$user['login']?><br /><small><span style="color: #fffabd;">Ур.</span> <?=$set['lvl']?>, <span style="color: #fffabd;">Ал.</span> <?=number_format($user_alliance+1)?>, <span style="color: #fffabd;"> Рейтинг</span> <?=$set['raiting']?></small></div><div class="mini-line"></div><?

$left = _FetchAssoc("SELECT * FROM `user_reg` WHERE `id` = '".$fataliti_user['uho1_kto']."' LIMIT 1");
$right = _FetchAssoc("SELECT * FROM `user_reg` WHERE `id` = '".$fataliti_user['uho2_kto']."' LIMIT 1");
if($fataliti_user['uho1_kto'] != 0 AND $fataliti_user['uho2_kto'] != 0) {
$uho = '_2';
} elseif($fataliti_user['uho1_kto'] != 0) {
$uho = '_1';
} elseif($fataliti_user['uho2_kto'] != 0) {
$uho = '_1';
} else {
$uho = FALSE;
}

echo'<table width="100%"><tr><td width="25%">';

echo'<img class="float-left" src="images/avatars/'.$set['avatar'].''.$uho.'.jpg" style="margin-left:10px;margin-right:15px;border:2px solid grey;" alt="Аватар">';

echo'</td><td>';

echo'Страна:<span style="float: right;">'.$strana.'</span><br />';

echo'Звание:<span style="float: right;">'.$set['zvanie'].'</span><br />';

echo'Пол:<span style="float: right;">'.$pol.'</span><br />';

echo'Побед:<span style="float: right;">'.$set['wins'].'</span><br />';

echo'Поражений:<span style="float: right;">'.$set['loses'].'</span><br />';

echo'Убийств:<span style="float: right;">'.$set['kills'].'</span><br />';

echo'Смертей:<span style="float: right;">'.$set['dies'].'</span><br />';

echo'Ушей:<span style="float: right;">'.$set['uho'].'</span><br />';

echo'Жетонов:<span style="float: right;">'.$set['zheton'].'</span><br />';

echo'</td></tr></table>';

echo'<div class="mini-line"></div>';

echo'<small>';

if($fataliti_user['uho1_kto'] != 0) {
echo'<div class="block_zero">Левое ухо у: '.$left['login'].'';
$time = $fataliti_user['uho1_up'] - time();
echo'<br />Отрастёт через: '._Time($time).' <a href="hosp.php">пришить</a>';
}

if($fataliti_user['uho2_kto'] != 0) {
echo'</div><div class="dot-line"></div><div class="block_zero">Правое ухо у: '.$right['login'].'';
$time = $fataliti_user['uho2_up'] - time();
echo'<br />Отрастёт через: '._Time($time).' <a href="hosp.php">пришить</a>';
}

echo'</small>';

if($fataliti_user['uho1_kto'] != 0 OR $fataliti_user['uho2_kto'] != 0) {
echo'</div><div class="mini-line"></div>';
}

echo'<div class="block_zero">Боевая эффективность:<br />';

echo'Атака: '.round($ITOG_A,0).'<span style="float: right;">Крит: '.$KRIT.'</span><br />';

echo'Защита: '.round($ITOG_Z,0).'<span style="float: right;">Уворот: '.$UVOROT.'</span><br />';

echo'<a href="pers.php?case=info">Подробно</a>';

echo'</div><div class="mini-line"></div><div class="block_zero">';

echo'Денежный поток (в час):<br />';

echo'Доход<span style="float: right;">'.$set['dohod'].'</span><br />';

echo'Содержание<span style="float: right;">'.$set['soderzhanie'].'</span><br />';

echo'Чистая прибыль<span style="float: right;">'.$set['chistaya'].'</span><br />';
/*
echo'</div><div class="mini-line"></div><div class="block_zero">';

echo'Фаталити:<br />';

echo'Жетоны<span style="float: right;">'.$user['zheton'].'</span><br />';

echo'Отрезанные уши<span style="float: right;">'.$user['uho'].'</span><br />';

echo'Фаталити доступно<span style="float: right;">'.$user['fataliti_dost'].'/5</span><br />';
*/


echo'</div></div>';

break;

case 'unitbuild':

?><div class="mini-line"></div><div class="block_zero"><img src="images/flags/<?=$set['side']?>.png"  alt="Флаг"/> <?=$user['login']?><br /><small><span style="color: #fffabd;">Ур.</span> <?=$set['lvl']?>, <span style="color: #fffabd;">Ал.</span> <?=number_format($user_alliance+1)?>, <span style="color: #fffabd;"> Рейтинг</span> <?=$set['raiting']?></small></div>

<div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Наземная техника</span></div><?

$data=mysql_query("SELECT * FROM `user_unit` WHERE `tip` IN('1','4') AND `id_user`='".$user_id."' ORDER BY `id` ASC");
?><table><tr><?
$cols = 0;
$maxcols = 4;
while($my_unit=mysql_fetch_assoc($data)){
if($my_unit['tip']==4){
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

?><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">Морская техника</span></div><?

$data=mysql_query("SELECT * FROM `user_unit` WHERE `tip` IN('2','5') AND `id_user`='".$user_id."' ORDER BY `id` ASC");
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

$data=mysql_query("SELECT * FROM `user_unit` WHERE `tip` IN('3','6') AND `id_user`='".$user_id."' ORDER BY `id` ASC");
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

$data=mysql_query("SELECT * FROM `user_superunit` WHERE `id_user`='".$user_id."' ORDER BY `id` ASC");
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

$data=mysql_query("SELECT * FROM `user_build` WHERE`tip`='1' AND `id_user`='".$user_id."' ORDER BY `id` ASC");
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

$data=mysql_query("SELECT * FROM `user_build` WHERE `tip`='2' AND `id_user`='".$user_id."' ORDER BY `id` ASC");
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

$data=mysql_query("SELECT * FROM `user_build` WHERE `tip`='3' AND `id_user`='".$user_id."' ORDER BY `id` ASC");
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

?></div></div><?

break;

case 'raspred':

?><div class="mini-line"></div><div class="main"><div class="block_zero center">Не использовано <?= $set['skill'] ?> очков навыков</div><div class="dot-line"></div><div class="block_zero"><img src="images/icons/mp.png" alt="*"> <b>Энергия:</b><span style="float: right;"><?= $set['max_mp'] ?></span><br/><span style="color: #9c9;"><small>Расходуется при<span style="float: right;"><a class="btn" href="pers.php?case=enka"><span class="end"><span class="label">1 очко +10</span></span></span></a><br/>выполнении спецопераций</small></span></span><br/></div><div class="dot-line"></div><div class="block_zero"><img src="images/icons/hp.png" alt="*"> <b>Здоровье:</b><span style="float: right;"><?= $set['max_hp'] ?></span><br/><span style="color: #9c9;"><small>Для участия в боях необходимо<span style="float: right;"><a class="btn" href="pers.php?case=zdor"><span class="end"><span class="label">1 очко +10</span></span></span></a><br/>минимум 25 здоровья</small></span></span><br/></div><div class="dot-line"></div><div class="block_zero"><img src="images/icons/ataka.png" alt="*"> <b>Боеприпасы:</b><span style="float: right;"><?= $set['max_udar'] ?></span><br/><span style="color: #9c9;"><small>Количество нападений, которые<span style="float: right;"><a class="btn" href="pers.php?case=udar"><span class="end"><span class="label">2 очка +1</span></span></span></a><br/>совершаются на противников</small></span></span><br/></div><div class="dot-line"></div><div class="block_zero"><img src="images/icons/krit.png" alt="*"> <b>Жестокость:</b><span style="float: right;"><?= $set['krit'] ?></span><br/><span style="color: #9c9;"><small>Чем выше, тем больше<span style="float: right;"><a class="btn" href="pers.php?case=krit"><span class="end"><span class="label">2 очка +1</span></span></span></a><br/>шанс совершить фаталити</small></span></span><br/></div><div class="dot-line"></div><div class="block_zero"><img src="images/icons/uvorot.png" alt="*"> <b>Ловкость:</b><span style="float: right;"><?= $set['uvorot'] ?></span><br/><span style="color: #9c9;"><small>Чем выше, тем больше<span style="float: right;"><a class="btn" href="pers.php?case=uvorot"><span class="end"><span class="label">2 очка +1</span></span></span></a><br/>шанс защититься от фаталити</small></span></span><br/></div></div></div><?
        break;        
    case 'enka':
        if ($set['skill'] < 1) {
            $_SESSION['err'] = 'Недостаточно очков навыков';
            header('Location: pers.php?case=raspred');
            exit();
        }
        mysql_query('UPDATE `user_set` SET `mp` = `mp` + "10", `max_mp` = `max_mp` + "10", `skill` = `skill` - "1" WHERE `id` = "' . $user_id . '"');
        $_SESSION['ok'] = 'Навык успешно повышен';
        header('Location: pers.php?case=raspred');
        exit();
        break;        
    case 'zdor':
        if ($set['skill'] < 1) {
            $_SESSION['err'] = 'Недостаточно очков навыков';
            header('Location: pers.php?case=raspred');
            exit();
        }        
        if($set['side']=='b'){
        $hp_side=12;
        }else{
        $hp_side=10;
        }        
        $trof_hp=_FetchAssoc("SELECT * FROM `user_trofei` WHERE `id_user` = '".$user_id."' AND `id_trof` = '6'");
        
        if($trof_hp['status']==1 AND $trof_hp['time_up']==0){
        $h_p=$hp_side+(($set['hp']*100)/(100+$trof_hp['bonus_1']));
    $hp=$h_p+$h_p/100*($trof_hp['bonus_1']);
        $max_h_p=$hp_side+(($set['max_hp']*100)/(100+$trof_hp['bonus_1']));
      $max_hp=$max_h_p+$max_h_p/100*($trof_hp['bonus_1']);
}else{
$hp=$set['hp']+$hp_side;
$max_hp=$set['max_hp']+$hp_side;
}        
        mysql_query("UPDATE `user_set` SET `hp`='".$hp."', `max_hp`='".$max_hp."', `skill` = `skill` - '1' WHERE `id`='".$user_id."' LIMIT 1");
        $_SESSION['ok'] = 'Навык успешно повышен';
        header('Location: pers.php?case=raspred');
        exit();
        break;        
    case 'udar':
        if ($set['skill'] < 2) {
            $_SESSION['err'] = 'Недостаточно очков навыков';
            header('Location: pers.php?case=raspred');
            exit();
        }
        mysql_query('UPDATE `user_set` SET `udar` = `udar` + "1", `max_udar` = `max_udar` + "1", `skill` = `skill` - "2" WHERE `id` = "' . $user_id . '"');
        $_SESSION['ok'] = 'Навык успешно повышен';
        header('Location: pers.php?case=raspred');
        exit();
        break;        
    case 'krit':
        if ($set['skill'] < 2) {
            $_SESSION['err'] = 'Недостаточно очков навыков';
            header('Location: pers.php?case=raspred');
            exit();
        }
        mysql_query('UPDATE `user_set` SET `skill` = `skill` - "2", `krit` = `krit` + "1" WHERE `id` = "' . $user_id . '"');
        $_SESSION['ok'] = 'Навык успешно повышен';
        header('Location: pers.php?case=raspred');
        exit();
        break;        
    case 'uvorot':
        if ($set['skill'] < 2) {
            $_SESSION['err'] = 'Недостаточно очков навыков';
            header('Location: pers.php?case=raspred');
            exit();
        }
        mysql_query('UPDATE `user_set` SET `skill` = `skill` - "2", `uvorot` = `uvorot` + "1" WHERE `id` = "' . $user_id . '"');
        $_SESSION['ok'] = 'Навык успешно повышен';
        header('Location: pers.php?case=raspred');
        exit();
        break;

case 'info':

?><div class="mini-line"></div><?
echo'<div class="block_zero center"><h1 class="yellow">Боевая эффективность</h1></div><div class="mini-line"></div><div class="block_zero center">Атака:</div><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">


Атака техники<span style="float: right;">'.($A1+$A2).'</span><br />
Атака разработок<span style="float: right;">'.$SA.'</span><br />
Бонус от наемников<span style="float: right;">'.round(($A4+$A5+$A6),0).'</span><br />
Бонус от трофеев<span style="float: right;">'.$a_trof_bonus.'</span><br />
Бонус от страны<span style="float: right;">'.round($A3,0).'</span><br />
Подкрепления<span style="float: right;"></span><br />
Бонус от флага<span style="float: right;"></span><br />
Достижения<span style="float: right;"></span><br />
Праздничный бонус<span style="float: right;"></span><br />
Поддержки, падлянки<span style="float: right;"></span><br />
Жестокость<span style="float: right;">'.$KRIT.'</span><br />
</span>
<b>Суммарная атака<span style="float: right;">'.round($ITOG_A,0).'</b></span><br />

</div><div class="mini-line"></div><div class="block_zero center">Защита:</div><div class="dot-line"></div><div class="block_zero"><span style="color: #9c9;">

Защита техники<span style="float: right;">'.($Z1+$Z2).'</span><br />
Защита разработок<span style="float: right;">'.$SZ.'</span><br />
Защита построек<span style="float: right;">'.$PZ1.'</span><br />
Бонус от наемников<span style="float: right;">'.round(($Z4+$Z5+$Z6),0).'</span><br />
Бонус от трофеев<span style="float: right;">'.$z_trof_bonus.'</span><br />
Бонус от страны<span style="float: right;">'.round(($Z3+$PZ2),0).'</span><br />
Подкрепления<span style="float: right;"></span><br />
Бонус от флага<span style="float: right;"></span><br />
Достижения<span style="float: right;"></span><br />
Праздничный бонус<span style="float: right;"></span><br />
Поддержки, падлянки<span style="float: right;"></span><br />
Ловкость<span style="float: right;">'.$UVOROT.'</span><br />
</span>
<b>Суммарная защита<span style="float: right;">'.round($ITOG_Z,0).'</b></span><br />

</div></div>';
break;

case 'trof':
if(isset($_GET['log'])){
$id_trof=isset($_GET['id_trof'])?_NumFilter($_GET['id_trof']):NULL;
$trof_set=_FetchAssoc("SELECT * FROM `user_trofei` WHERE `id_trof`='".$id_trof."' AND `id_user`='".$user_id."' LIMIT 1");
$shag_set=_FetchAssoc("SELECT `name`, `shag_1`, `shag_2` FROM `trofei` WHERE `id`='".$trof_set['id_trof']."' LIMIT 1");
if($trof_set['lvl']>9){
$_SESSION['err'] = 'Трофей "'.$shag_set['name'].'" уже прокачан до максимального уровня!';
header('Location: pers.php?case=trof');
exit();
}
if($trof_set['id_trof']<1 OR $trof_set['id_trof']>18){
$_SESSION['err'] = 'Нет такого трофея!';
header('Location: pers.php?case=trof');
exit();
}
if($_GET['log']=='gold'){
if($trof_set['cena_gold']>$set['gold']){
$_SESSION['err'] = 'Недостаточно золота!';
header('Location: pers.php?case=trof');
exit();
}
mysql_query("UPDATE `user_trofei` SET `lvl`='".($trof_set['lvl']+1)."', `cena_baks`='".($trof_set['cena_baks']*2)."', `cena_gold`='".($trof_set['cena_gold']*2)."', `time_up`='0', `day`='".($trof_set['day']+2)."', `bonus_1`='".($trof_set['bonus_1']+$shag_set['shag_1'])."', `bonus_2`='".($trof_set['bonus_2']+$shag_set['shag_2'])."', `next_1`='".($trof_set['next_1']+$shag_set['shag_1'])."', `next_2`='".($trof_set['next_2']+$shag_set['shag_2'])."' WHERE `id_trof`='".$id_trof."' AND `id_user`='".$user_id."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `gold`=`gold`-'".$trof_set['cena_gold']."' WHERE `id`='".$user_id."' LIMIT 1");

if($trof_set['id_trof']==6){
if($trof_set['time_up']==0){
$h_p=($set['hp']*100)/(100+$trof_set['bonus_1']);
$hp=$h_p+$h_p/100*($trof_set['bonus_1']+$shag_set['shag_1']);
$max_h_p=($set['max_hp']*100)/(100+$trof_set['bonus_1']);
$max_hp=$max_h_p+$max_h_p/100*($trof_set['bonus_1']+$shag_set['shag_1']);
mysql_query("UPDATE `user_set` SET `hp`='".$hp."', `max_hp`='".$max_hp."' WHERE `id`='".$user_id."' LIMIT 1");
}else{
$hp=$set['hp']/100*($trof_set['bonus_1']+$shag_set['shag_1']);
$max_hp=$set['max_hp']/100*($trof_set['bonus_1']+$shag_set['shag_1']);
mysql_query("UPDATE `user_set` SET `hp`=`hp`+'".$hp."', `max_hp`=`max_hp`+'".$max_hp."' WHERE `id`='".$user_id."' LIMIT 1");
}
}

if($trof_set['id_trof']==4){
if($trof_set['time_up']==0){
$soderzhan=($set['soderzhanie']*100)/(100-$trof_set['bonus_1']);
$soderzhanie=$soderzhan-$soderzhan/100*($trof_set['bonus_1']+$shag_set['shag_1']);
$chistaya=$set['dohod']-$soderzhanie;
mysql_query("UPDATE `user_set` SET `soderzhanie`='".$soderzhanie."', `chistaya`='".$chistaya."' WHERE `id`='".$user_id."' LIMIT 1");
}else{
$soderzhanie=$set['soderzhanie']/100*($trof_set['bonus_1']+$shag_set['shag_1']);
$chistaya=$set['chistaya']+$soderzhanie;
mysql_query("UPDATE `user_set` SET `soderzhanie`=`soderzhanie`-'".$soderzhanie."', `chistaya`='".$chistaya."' WHERE `id`='".$user_id."' LIMIT 1");
}
}

$_SESSION['ok'] = 'Трофей "'.$shag_set['name'].'" прокачан до '.($trof_set['lvl']+1).' уровня!';
header('Location: pers.php?case=trof');
exit();
}
if($_GET['log']=='baks'){
$time_stop=_FetchAssoc("SELECT * FROM `user_trofei` WHERE `id_user` = '".$user_id."' AND `time_up`!='0' LIMIT 1");
if($time_stop){
$_SESSION['err'] = 'За баксы можно прокачивать только один трофей!';
header('Location: pers.php?case=trof');
exit();
}
if($trof_set['cena_baks']>$set['baks']){
$_SESSION['err'] = 'Недостаточно баксов!';
header('Location: pers.php?case=trof');
exit();
}
mysql_query("UPDATE `user_trofei` SET `time_up`='".(time()+($trof_set['day']*86400))."' WHERE `id_trof`='".$id_trof."' AND `id_user`='".$user_id."' LIMIT 1");
mysql_query("UPDATE `user_set` SET `baks`=`baks`-'".$trof_set['cena_baks']."' WHERE `id`='".$user_id."' LIMIT 1");

if($trof_set['id_trof']==6){
$trof_hp=($set['hp']*100)/(100+$trof_set['bonus_1']);
$trof_max_hp=($set['max_hp']*100)/(100+$trof_set['bonus_1']);
mysql_query("UPDATE `user_set` SET `hp`='".$trof_hp."', `max_hp`='".$trof_max_hp."' WHERE `id`='".$user_id."' LIMIT 1");
}

if($trof_set['id_trof']==4){
$trof_soderzhanie=($set['soderzhanie']*100)/(100-$trof_set['bonus_1']);
$trof_chistaya=$set['dohod']-$trof_soderzhanie;
mysql_query("UPDATE `user_set` SET `soderzhanie`='".$trof_soderzhanie."', `chistaya`='".$trof_chistaya."' WHERE `id`='".$user_id."' LIMIT 1");
}

$_SESSION['ok'] = 'Вы начали прокачку трофея "'.$shag_set['name'].'" до '.($trof_set['lvl']+1).' уровня!';
header('Location: pers.php?case=trof');
exit();
}
}
$data_trofei=mysql_query("SELECT * FROM `user_trofei` WHERE `id_user`='".$user_id."' ORDER BY `id_trof` ASC LIMIT 8");
while($data_trof=mysql_fetch_assoc($data_trofei)){
$user_trof=_FetchAssoc("SELECT * FROM `trofei` WHERE `id`='".$data_trof['id_trof']."' LIMIT 1");
if($data_trof['status']==0){
        $lock='_locked';
        $ur=FALSE;
        $color='#999';
        $colo='#ff3434';
        $col='#999';        
        $opisanie=$user_trof['opisanie_1'];
        $opisanie2=FALSE;
        $uslovie=$user_trof['uslovie_1'];
        $uslovie2=FALSE;
        }else{
        $lock=FALSE;
        $ur='('.$data_trof['lvl'].' ур.)';
        $color='#fffabd';
        $colo='#f96';
        $col='#9c9';        
        $opisanie=''.$user_trof['opisanie_2'].' '.$data_trof['bonus_1'].'%';
        $uslovie=''.$user_trof['uslovie_2'].' '.$data_trof['next_1'].'%';        
        if($user_trof['opisanie_3']){
        $opisanie2=' '.$user_trof['opisanie_3'].' '.$data_trof['bonus_2'].'%';
        }else{
        $opisanie2=FALSE;
        }        
        if($user_trof['uslovie_3']){
        $uslovie2=' '.$user_trof['uslovie_3'].' '.$data_trof['next_2'].'% '.$user_trof['uslovie_4'].'';
        }else{
        $uslovie2=FALSE;
        }        
        }
?><div class="mini-line"></div><table width="100%"><tr><td width="40%"><img class="float-left" src="images/trofei/<?= $data_trof['id_trof'].''.$lock?>.png" style="margin-left:0px;margin-right:0px;border:1px solid #999;" alt="Трофей"></td><td valign="top"><b><span style="color: <?=$col?>;"><small><?=$user_trof['name'].' '.$ur?></span></b><br/><span style="color: <?=$color?>;"><?=$opisanie?><?=$opisanie2?></small></span></td></tr></table><div class="dot-line"></div><div class="block_zero center"><span style="color: <?=$colo?>;"><?

if($data_trof['lvl']<10){

?><small><?=$uslovie?><?=$uslovie2?></small></span><?
if($data_trof['status']!=0){

?></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="pers.php?case=trof&log=gold&id_trof=<?= $user_trof['id'] ?>"><span class="end"><span class="label">Прокачать моментально за <img src="images/icons/gold.png" alt="*" /> <?= number_format($data_trof['cena_gold'])?></span></span></a><?

if($data_trof['time_up']==0){
?></div><div class="dot-line"></div><div class="block_zero center"><a class="btn" href="pers.php?case=trof&log=baks&id_trof=<?= $user_trof['id'] ?>"><span class="end"><span class="label">Прокачивать <?= $data_trof['day'].' ' . _Users($data_trof['day'], 'день', 'дня', 'дней')?> за <img src="images/icons/baks.png" alt="*" /> <?= number_format($data_trof['cena_baks'])?></span></span></a><?
}else{
$time_up=$data_trof['time_up']-time();
?></div><div class="dot-line"></div><div class="block_zero center"><small>Прокачается через: <?=_DayTime($time_up)?></small><?
}
}
}else{
?><b><span style="color: <?=$colo?>;">Трофей максимально прокачан</span></b><?
}
?></div><?
}
?><div class="mini-line"></div><ul class="hint"><li>При прокачке трофея за игровые деньги он не используется все время, нужное для прокачки.</li><li>Можно прокачивать только один трофей одновременно.</li><li>Цена прокачки трофея зависит от его уровня.</li></ul></div></div><?
break;














}
require_once('system/down.php');
?>
