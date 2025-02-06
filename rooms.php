<?php
$title = 'Общение';
require_once('system/up.php');
_Reg();
?><div class="main"><?
if ($_GET['case'] != 'room') {
?><div class="menuList"><li><a href="rooms.php?case=room"><img src="images/icons/arrow.png" alt="*" />Выбор чата</a></li></div><div class="mini-line"></div><?
}
switch ($_GET['case']) {
    default:
        if (isset($_GET['tip'])) {
        if($_GET['tip']<1 OR $_GET['tip']>4){
        $_SESSION['err'] = 'Нет такого чата!';
            header('Location: menu.php');
            exit();
        }
            $tip = _NumFilter($_GET['tip']);
        }
        ?><div class="block_zero center"><?
        if (isset($_GET['komu'])) {
            $id_komu = _NumFilter($_GET['komu']);
            $komu    = _FetchAssoc("SELECT * FROM `user_reg` WHERE `id` = '" . $id_komu . "' LIMIT 1");
            ?><form action="rooms.php?case=post&tip=<?= $tip ?>" method="POST"><input class="text large" value="<?= $komu['login'] ?>, " type="text" name="text"/><br/> <span class="btn"><span class="end"><input class="label" type="submit" value="Отправить"></span></span></form><?
        } else {
            ?><form action="rooms.php?case=post&tip=<?= $tip ?>" method="POST""><input class="text large" type="text" name="text"/><br/><span class="btn"><span class="end"><input class="label" type="submit" value="Отправить"></span></span> </a></form><?
        }
        ?></div><div class="mini-line"></div><?
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
        $viso   = _NumRows("SELECT `id` FROM `chat` WHERE `tip`='" . $tip . "'");
        $puslap = floor($viso / 10);
        $data   = mysql_query("SELECT * FROM `chat` WHERE `tip`='" . $tip . "' ORDER BY `id` DESC LIMIT $num, 10");
        while ($rooms = mysql_fetch_assoc($data)) {
            $rooms_user     = _FetchAssoc("SELECT * FROM `user_reg` WHERE `id`='" . $rooms['id_user'] . "' LIMIT 1");
            $set_rooms_user = _FetchAssoc("SELECT * FROM `user_set` WHERE `id`='" . $rooms_user['id'] . "' LIMIT 1");
            echo '<div class="block_zero">';
            if ($set_rooms_user['prava'] == 5) {
                $color = '9bc';
            } elseif ($set_rooms_user['prava'] == 2) {
                $color = 'f96';
            } elseif ($set_rooms_user['prava'] == 0) {
                $color = 'fff';
            }
            ?><img src="images/sex/<?= $set_rooms_user['sex'] ?>.png" alt="Пол"> <img src="images/flags/<?= $set_rooms_user['side'] ?>.png" alt="Флаг"/> <a href="view.php?smotr=<?= $rooms_user['id'] ?>"><span style="color: #<?= $color ?>;"><?= $rooms_user['login'] ?></span><a href="rooms.php?tip=<?= $tip ?>&komu=<?= $rooms_user['id'] ?>"> (»)</a><span style="float: right;"><small><span style="color: #9c9;"><?= $rooms['date'] ?> в <?= $rooms['time'] ?></span></small></span><?
            if(isset($_GET['del']) AND $_GET['del']=='text'){
           $id_text=_NumFilter($_GET['id_text']);
           mysql_query("DELETE FROM `chat` WHERE `id` = '".$id_text."'");
           $_SESSION['ok'] = 'Сообщение удалено!';
           header("Location: rooms.php?tip=".$tip."");
           exit();
           }
            if ($rooms_user['login'] = $user['login']) {
                $nick = '<span style="color: #9c9;">' . $rooms_user['login'] . '</span>';
            }
            $rooms['text'] = str_replace($rooms_user['login'], $nick, $rooms['text']);
            echo '<br/><span style="color: #' . $color . ';">' . _Smile($rooms['text']) . '</span>';
            if ($set['prava'] == 5 OR $set['prava'] == 2){
           echo "<a href='rooms.php?tip=".$tip."&del=text&id_text=".$rooms['id']."'> (х)</a>";
}
           echo'</div><div class="mini-line"></div>';
            $i++;
        }
           echo '<div class="block_zero center">';
        if ($_GET['page'] > 0) {
            echo '<small><b><a href="rooms.php?tip=' . $tip . '&page=' . $back . '"><< Вперёд </a></small></b>';
        }
        if (empty($_GET['page']) || $_GET['page'] == 0 || $_GET['page'] < $puslap) {
            echo '<small><b><a href="rooms.php?tip=' . $tip . '&page=' . $next . '"> Назад >></a></small></b>';
        }
        echo '</div></div></div>';
        break;
            case 'post':
        if (isset($_GET['tip'])) {
            $tip = _NumFilter($_GET['tip']);
        }
        if (isset($_POST['text'])) {
            $text = _TextFilter($_POST['text']);
            if (strlen($text) < 2 OR strlen($text) > 500) {
                $_SESSION['err'] = 'Длина сообщения 2-500 символов.';
                header('Location: rooms.php?tip=' . $tip . '');
                exit();
            }
            mysql_query("INSERT INTO `chat` SET `id_user` = '" . $user_id . "', `text` = '" . $text . "', `time` = '" . $timer . "', `date` = '" . $dater . "', `tip` = '" . $tip . "'");
            $_SESSION['ok'] = 'Сообщение успешно добавлено!';
            header('Location: rooms.php?tip=' . $tip . '');
            exit();
        } else {
            $_SESSION['err'] = 'Введите текст сообщения';
            header('Location: rooms.php?tip=' . $tip . '');
            exit();
        }
        break;
    case 'room':
?><div class="block_zero center"><a class="btn" href="rooms.php?tip=1"><span class="end"><span class="label">Общая</span></span></span></a></div><div class="block_zero"><small><span style="color: #c66;">Запрещено: сообщения о наборе в альянс, для этого есть комната "Альянсы".</span><br/><span style="color: #9c9;">Разрешено: непринужденно общаться о том, о сём.</span></small></div><div class="mini-line"></div> <div class="block_zero center"><a class="btn" href="rooms.php?tip=2"><span class="end"><span class="label">Альянсы</span></span></span></a></div><div class="block_zero"><small><span style="color: #c66;">Запрещено: сообщения не касающиеся заявки/приема в альянс.</span><br/><span style="color: #9c9;">Разрешено: любые сообщения касающиеся заявки/приема в альянс.</span></small></div><div class="mini-line"></div> <div class="block_zero center"><a class="btn" href="rooms.php?tip=3"><span class="end"><span class="label">Легионы</span></span></span></a></div><div class="block_zero"><small><span style="color: #c66;">Запрещено: сообщения о наборе в альянс.</span><br/><span style="color: #9c9;">Разрешено: общение, реклама и заявки касающиеся легионов.</span></small></div><div class="mini-line"></div> <div class="block_zero center"><a class="btn" href="rooms.php?tip=4"><span class="end"><span class="label">Учебка</span></span></span></a></div><div class="block_zero"><small><span style="color: #c66;">Запрещено: сообщения про заявки/прием в альянс.</span><br/><span style="color: #9c9;">Разрешено: делиться опытом, с уважением относиться к старшим, с пониманием относиться к младшим.</span></small></div></div><?
        break;
}
require_once('system/down.php');
?> 
