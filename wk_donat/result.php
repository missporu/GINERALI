<?php
$title = 'Покупка золота';
require_once('config.php');
require_once('../system/sys.php');
if (isset($_POST['id_shop']) && is_numeric($_POST['id_shop']) && isset($_POST['id_bill']) && is_numeric($_POST['id_bill']) && isset($_POST['summa']) && is_numeric($_POST['summa']) && isset($_POST['hash']))	{
$sql=_NumRows("SELECT * FROM `worldkassa` WHERE `id_bill` = '".mysql_real_escape_string($_POST['id_bill'])."' LIMIT 1");
if ($sql>0)		{
$data=_FetchAssoc("SELECT * FROM `worldkassa` WHERE `id_bill` = '".mysql_real_escape_string($_POST['id_bill'])."' LIMIT 1");
if ($_POST['summa']<$data['summa'])			{
//Можно поставить уведомление на подмену суммы пополнения
}
elseif($_POST['hash']!=md5($hash.$id_shop.$_POST['id_bill'].$_POST['summa']))			{
//Можно поставить уведомление, что не совпал хеш
}else	{
foreach($cena_gold as $gold=>$summa)			{
if ($summa==$data['summa']){
$set=_FetchAssoc("SELECT `donat_bonus` FROM `user_set` WHERE `id`='".$data['id_user']."' LIMIT 1");

if($set['donat_bonus']<5000){
$bonus=$gold/100*5;
}elseif($set['donat_bonus']<15000){
$bonus=$gold/100*10;
}elseif($set['donat_bonus']<35000){
$bonus=$gold/100*20;
}else{
$bonus=$gold/100*30;
}// бонусная карта

if($set['donat_bonus']==0){
$donat=($gold+$bonus)*5;//первая покупка
}else{
$donat=$gold+$bonus;//обычная покупка с бонусом
}

$ref=_FetchAssoc("SELECT `refer` FROM `user_reg` WHERE `id`='".$data['id_user']."' LIMIT 1");
if($ref){
mysql_query("UPDATE `user_set` SET `gold`=`gold`+'".round($gold/2)."' WHERE `id`='".$ref['refer']."'");
}

mysql_query("UPDATE `user_set` SET `gold`=`gold`+'".$donat."', `donat_bonus`=`donat_bonus`+'".$donat."', `refer_gold`=`refer_gold`+'".round($gold/2)."' WHERE `id`='".$data['id_user']."'");
}
}
mysql_query("UPDATE `worldkassa` SET `time_oplata`='".time()."' WHERE `id`='".$data['id']."'");
			}
						}
					}
		?>
