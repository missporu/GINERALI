<?php
$title='Онлайн';
require_once('system/up.php');
_Reg();
?><div class="main"><div class="block_zero"><?
if ($_GET['case'] == ''){
?>Все игроки | <a href="online.php?case=search">Поиск</a></div><?
}else{
?><a href="online.php">Все игроки</a> | Поиск</div><?
}
switch ($_GET['case']) {
default:
    if (empty($_GET['page']) || $_GET['page'] == 0 || $_GET['page'] < 0) {
            $_GET['page'] = 0;
        }
        $next = _NumFilter($_GET['page'] + 1);
        $back = $_GET['page'] - 1;
        $num  = $_GET['page'] * 10;
        if ($_GET['page'] == 0) {
            $i = 1;
        } else {
            $i = ($_GET['page'] * 10) + 1;
        }
        $viso   = _NumRows("SELECT `id` FROM `user_set` WHERE `online`> '".(time()-600)."'");
        $puslap = floor($viso / 10);
        $data_online=mysql_query("SELECT * FROM `user_set` WHERE `online`> '".(time()-600)."' ORDER BY `id` ASC LIMIT $num, 10");
        $i = 1;
        while ($online=mysql_fetch_assoc($data_online)){
        $login_online   = _FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='".$online['id']."' LIMIT 1");
        if($login_online['id']==1 && $online['prava']==5 && $login_online['login']=='ckpunmuk'){
        $mesto='где-то здесь';
        }elseif($online['mesto']=='Онлайн-игра Войнушка - браузерная ролевая стратегия'){
        $mesto='Главная';
        }else{
        $mesto=$online['mesto'];
        }
        ?><div class="dot-line"></div><div class="block_zero"><?=$i?>. <img src="images/flags/<?= $online['side'] ?>.png" alt="*"/><a href="view.php?smotr=<?= $online['id'] ?>"> <?= $login_online['login'] ?></a><span class="small grey"> ~ <?= $mesto ?></span></div><?
        $i++;
        }
        echo'<div class="mini-line"></div>';
         echo '<div class="block_zero center">';
        if ($_GET['page'] > 0) {
            echo '<small><b><a href="online.php?page=' . $back . '"><< Назад </a></small></b>';
        }
        if (empty($_GET['page']) || $_GET['page'] == 0 || $_GET['page'] < $puslap) {
            echo '<small><b><a href="online.php?page=' . $next . '"> Вперёд >></a></small></b>';
        }
        ?></div></div></div><?
        break;
        case 'search':
        if(isset($_POST['login'])){
        $name = _TextFilter($_POST['login']);
        $sql  = _NumRows("SELECT `login` FROM `user_reg` WHERE `login`='" . $name . "' LIMIT 1");
        $sql2  = _FetchAssoc("SELECT `id` FROM `user_reg` WHERE `login`='" . $name . "' LIMIT 1");
        if (empty($name)) {
        $_SESSION['err'] = 'Вы не ввели никнейм для поиска!';
        header('Location: online.php?case=search');
        exit();
        } elseif ($name == $user['login']) {
        $_SESSION['err'] = 'Вы ввели свой никнейм!';
        header('Location: online.php?case=search');
        exit();
        } elseif ($sql == 0) {
        $_SESSION['err'] = 'Игрок не найден!';
        header('Location: online.php?case=search');
        exit();
        } else {
        $_SESSION['ok'] = 'Игрок '.$name.' найден!';
        header('Location: view.php?smotr='.$sql2['id'].'');
        exit();
        }
}
        ?><div class="mini-line"></div><div class="block_zero">Введите никнейм:<form action="online.php?case=search" method="post"><input class="text" type="text" name="login" size="30"/><br/><span class="btn"><span class="end"><input class="label" type="submit" name="send" value="Найти"></span></span> </a></form></div><div class="mini-line"></div><ul class="hint"><li>Здесь можно найти нужного игрока по его никнейму.</li></div><?        
        break;
        }
require_once('system/down.php');
?>
