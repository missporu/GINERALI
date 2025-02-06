<?php
$title = 'Админ-панель';
require_once('system/up.php');
_Reg();

// Проверка прав доступа
if ($user_id != 1 and $set['prava'] != 5) {
    $_SESSION['err'] = 'Нет доступа';
    header('Location: menu.php');
    exit();
} ?>
    <div class="main"><?php
switch ($_GET['case']) {

default: ?>
    <div class="menuList">
    <li><a href="admin.php?case=1"><img src="images/icons/arrow.png" alt="*"/>Редактирование игрока</a></li>
    <li><a href="admin.php?case=2"><img src="images/icons/arrow.png" alt="*"/>Добавить новость</a></li><?
    break;

    case '1':
    if (isset($_POST['login'])){
        $name = _TextFilter($_POST['login']);
        $stmt = $pdo->prepare("SELECT `id` FROM `user_reg` WHERE `login`=:login LIMIT 1");
        $stmt->execute(['login' => $name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            $_SESSION['err'] = 'Игрок не найден';
            header('Location: admin.php?case=1');
            exit();
        } else {
            $_SESSION['ok'] = 'Игрок найден';
            header('Location: admin.php?case=1_1&id=' . $data['id'] . '');
            exit();
        }
    } else { ?>
        <div class="block_zero center">
            <form action="admin.php?case=1" method="post">Введите никнейм:<br/>
                <input class="text" type="text" name="login"/><br/><br/>
                <span class="btn">
                    <span class="end">
                        <input class="label" type="submit" value="Найти">
                    </span>
                </span>
            </form><?php
    }
    break;

    case '1_1':
    $id = isset($_GET['id']) ? _NumFilter($_GET['id']) : NULL;
    $stmt = $pdo->prepare("SELECT * FROM `user_reg` WHERE `id`=:id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT * FROM `user_set` WHERE `id`=:id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $data_set = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="block_zero center">Игрок: <a href="view.php?smotr=<?= $data['id'] ?>"><?= $data['login'] ?></a></div>
    <div class="dot-line"></div>
    <div class="block_zero"><?= $data_set['gold'] ?>

    <?
    break;

    case '2':
    if (isset($_POST['news'])){
        $tema = _TextFilter($_POST['tema']);
        $text = _TextFilter($_POST['text']);
        if (!$tema) {
            $_SESSION['err'] = 'Введите тему новости';
            header('Location: admin.php?case=2');
            exit();
        } elseif (!$text) {
            $_SESSION['err'] = 'Введите текст новости';
            header('Location: admin.php?case=2');
            exit();
        } else {
            $d = date("d F Y");
            $d = str_replace("January", "января", $d);
            $d = str_replace("February", "февраля", $d);
            $d = str_replace("March", "марта", $d);
            $d = str_replace("April", "апреля", $d);
            $d = str_replace("May", "мая", $d);
            $d = str_replace("June", "июня", $d);
            $d = str_replace("July", "июля", $d);
            $d = str_replace("August", "августа", $d);
            $d = str_replace("September", "сентября", $d);
            $d = str_replace("October", "октября", $d);
            $d = str_replace("November", "ноября", $d);
            $d = str_replace("December", "декабря", $d);
            $date_news = _TextFilter($d);
            $time_news = _TextFilter(date("H:i:s"));
            $stmt = $pdo->prepare("INSERT INTO `news` SET `data`=:data, `time`=:time, `avtor`=:avtor, `tema`=:tema, `text`=:text, `status`='1'");
            $stmt->execute(['data' => $date_news, 'time' => $time_news, 'avtor' => $user_id, 'tema' => $tema, 'text' => $text]);
            $stmt = $pdo->prepare("UPDATE `user_set` SET `news`='1'");
            $stmt->execute();
            $_SESSION['ok'] = 'Новость добавлена';
            header('Location: admin.php');
            exit();
        }
    }else{
    ?>
    <div class="block_zero center">
    <form action="admin.php?case=2" method="post">Тема новости:<br/><input class="text large" type="text"
                                                                           name="tema"/><br/>Текст
        новости:<br/><textarea class="text large" type="text" name="text" rows="10" cols="50"
                               placeholder="Введите текст новости"/></textarea><br/><span class="btn"><span class="end"><input
                        class="label" type="submit" name="news" value="Опубликовать"></span></span> </a></form><?php
}
    break;

    case '3_1':
        $id = isset($_GET['id']) ? _NumFilter($_GET['id']) : NULL;
        $stmt = $pdo->prepare("SELECT * FROM `user_reg` WHERE `id`=:id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("SELECT * FROM `user_set` WHERE `id`=:id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data_set = $stmt->fetch(PDO::FETCH_ASSOC);

        ?>
        <div class="block_zero">Возможные мульты игрока: <a
                    href="view.php?smotr=<?= $data['id'] ?>"><?= $data['login'] ?></a></div>
        <div class="mini-line"></div><?

        $stmt = $pdo->prepare("SELECT * FROM `user_reg` WHERE `browser`=:browser AND `id`!=:id ORDER BY `id` ASC");
        $stmt->execute(['browser' => $data['browser'], 'id' => $id]);

        while ($mult = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stmt = $pdo->prepare("SELECT * FROM `user_set` WHERE `id`=:id LIMIT 1");
            $stmt->execute(['id' => $mult['id']]);
            $mult_set = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="block_zero">Игрок: <a href="view.php?smotr=<?= $mult['id'] ?>"><?= $mult['login'] ?></a><span
                        style="float: right;"><small><?= $mult['ip'] ?> / <?= $mult_set['ip_new'] ?></small></span>
            </div>
            <div class="dot-line"></div><?
        }
        break;

}
?></div></div><?
require_once('system/down.php');