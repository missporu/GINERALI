<?php
$title = 'Регистрация';
require_once('system/up.php');
_NoReg();



if (isset($_GET['ref'])) {
    $id_refer = _NumFilter($_GET['ref']);
    $data_refer    = _FetchAssoc("SELECT `ip`, `id` FROM `user_reg` WHERE `id`='" . $id_refer . "'");
    $refer = _NumFilter($data_refer['id']);
        if ($data_refer) {
        if ($ip == $data_refer['ip']) {
            $_SESSION['err'] = 'Вы уже производили регистрацию!';
            header('Location: index.php');
            exit();
        }
    }
} else {
    $refer = FALSE;
}




if (isset($_POST['send'])) {
    $name         = _TextFilter($_POST['login']);
    $pass         = _TextFilter($_POST['pass']);
    $repass       = _TextFilter($_POST['repass']);
    $sex          = _TextFilter($_POST['sex']);
    $email        = _TextFilter($_POST['email']);
    $verify_login = mysql_query("SELECT COUNT(`id`) FROM `user_reg` WHERE `login`='" . $name . "'");
    $verify_email = mysql_query("SELECT COUNT(`id`) FROM `user_reg` WHERE `email`='" . $email . "'");
    if (empty($name)) {
        $_SESSION['err'] = 'Введите логин';
        header('Location: reg.php');
        exit();
    } elseif (!preg_match('|^[a-z0-9\-]+$|i', $name)) {
        $_SESSION['err'] = 'Кириллица и символы в логине запрещены';
        header('Location: reg.php');
        exit();
    } elseif (mb_strlen($name) < 3) {
        $_SESSION['err'] = 'Логин короче 3 символов';
        header('Location: reg.php');
        exit();
    } elseif (mb_strlen($name) > 15) {
        $_SESSION['err'] = 'Логин длинее 15 символов';
        header('Location: reg.php');
        exit();
    } elseif (mysql_result($verify_login, 0) > 0) {
        $_SESSION['err'] = 'Такой логин уже занят';
        header('Location: reg.php');
        exit();
    } elseif (empty($pass)) {
        $_SESSION['err'] = 'Введите пароль';
        header('Location: reg.php');
        exit();
    } elseif (mb_strlen($pass) < 3) {
        $_SESSION['err'] = 'Пароль короче 3 символов';
        header('Location: reg.php');
        exit();
    } elseif (mb_strlen($pass) > 20) {
        $_SESSION['err'] = 'Пароль длинее 20 символов';
        header('Location: reg.php');
        exit();
    } elseif (!preg_match('|^[a-z0-9\-]+$|i', $pass)) {
        $_SESSION['err'] = 'Кириллица и символы в пароле запрещены';
        header('Location: reg.php');
        exit();
    } elseif ($name == $pass) {
        $_SESSION['err'] = 'Логин и пароль не должны совпадать';
        header('Location: reg.php');
        exit();
    } elseif (empty($repass)) {
        $_SESSION['err'] = 'Введите пароль ещё раз';
        header('Location: reg.php');
        exit();
    } elseif ($pass != $repass) {
        $_SESSION['err'] = 'Пароли не совпадают';
        header('Location: reg.php');
        exit();
    } elseif (empty($email)) {
        $_SESSION['err'] = 'Введите почтовый ящик';
        header('Location: reg.php');
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['err'] = 'Не правильно введён почтовый ящик';
        header('Location: reg.php');
        exit();
    } elseif (mysql_result($verify_email, 0) > 0) {
        $_SESSION['err'] = 'Такой почтовый ящик уже исспользуется';
        header('Location: reg.php');
        exit();
    } else {
        mysql_query("INSERT INTO `user_reg` SET `login`='" . $name . "', `pass`='" . md5($pass) . "', `email`='" . $email . "', `ip`='" . $ip . "', `browser`='" . $browser . "', `referer`='" . $referer . "', `refer`='" . $refer . "', `data_reg`='" . $dater . "', `time_reg`='" . $timer . "', `site`='" . $site . "'");
        if($refer>0){
        mysql_query("INSERT INTO `user_set` SET `sex`='" . $sex . "', `gold`='20'");
        }else{
        mysql_query("INSERT INTO `user_set` SET `sex`='" . $sex . "'");
        }
        mysql_query("UPDATE `user_set` SET `prava`='5' WHERE `id`='1' AND `prava`!='5'");
        setcookie('login', $name, time() + 86400 * 365, '/');
        setcookie('pass', md5($pass), time() + 86400 * 365, '/');
        header('Location: start.php');
        exit();
    }
}
?>

<div class="hello center"><h1 class="yellow"><?=$title?>

</h1></div>

<div class="cont center"><form action="" method="post">Логин:<br/>


<input class="text" type="text" name="login" value=""/>

<br/>Пароль:<br/><input class="text" type="password" name="pass"/>

<br/>Повторите пароль:<br/><input class="text" type="password" name="repass"/>

<br/>Почта:<br/><input class="text" type="text" name="email"/>

<br/>Пол:<br><select name="sex"><option value="m">Парень</option><option value="w">Девушка</option></select><br/>

<br/>

<button class="form_btn" type="submit" name="send" /> Зарегистрировать<span class="form_btn_text"> </span></button></span></span></form><br/></div>

<br><br><div class="small grey center"><a href="rules.php"><font color=#AAA>Правила игры </a>  | © 2645.ru Rusalc 2015.</div></div></div>	
</span></span></a></div></div></div><?
require_once('system/down.php');
?>
