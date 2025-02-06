<?php
require_once('./system/up.php');
_NoReg();


echo '<center><div class="hello"><span style="color:#739871;">Вход в игру</center></div>';




echo'<div class="cont">';


if (isset($_POST['send'])) {
    $name = _TextFilter($_POST['login']);
    $pass = md5(_TextFilter($_POST['pass']));
    $sql  = _NumRows("SELECT `login`, `pass` FROM `user_reg` WHERE `login`='" . $name . "' AND `pass`='" . $pass . "' LIMIT 1");
    if (empty($name)) {
        $_SESSION['err'] = 'Введите логин';
        header('Location: login.php');
        exit();
    } elseif (empty($pass)) {
        $_SESSION['err'] = 'Введите пароль';
        header('Location: login.php');
        exit();
    } elseif ($sql == 0) {
        $_SESSION['err'] = 'Введите пароль';
        
        header('Location: login.php');
        exit();
    } else {
        setcookie('login', $name, time() + 86400 * 365, '/');
        setcookie('pass', $pass, time() + 86400 * 365, '/');
        header('Location: bonus.php');
        exit();
    }
}
?>










<form action="login.php" method="post"> <placeholder="Логин"  <br /> 

<input class="text" name="login" placeholder="Логин"/>
<input class="text" name="pass" placeholder="Пароль"/>
<br/>

<button class="form_btn" type="submit" name="send" /> Войти  <span class="form_btn_text"> </span></button></span></span></form><br/>




<center><a href="rec.php"><font color=#BCBCBC>Забыли пароль?</span></a>
<?

if (isset($_SESSION['rec'])) {
    echo '' . $_SESSION['rec'] . '';
    $_SESSION['rec'] = NULL;
}
?>
</div>






<br><br><div class="small grey center"><a href="rules.php"><font color=#AAA>Правила игры </a>  | © 2645.ru Rusalc 2015.</div></div></div>	
<?
require_once('system/down.php');
?>
