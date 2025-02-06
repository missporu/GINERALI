<?php
session_start();
ob_start();
$timeregen = microtime(TRUE);
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="content-type" content="text/html; charset=utf-8"/><meta name="generator" content="Онлайн-игра Генералы- браузерная ролевая стратегия"/><meta name="description" content="Новая браузерная онлайн-игра, в которую можно играть с компьютера и мобильного телефона!"/><meta name="keywords" content="войнушка, онлайн-игра, игра онлайн, онлайн, игра, компьютера, мобильного, телефона, играть, браузерная, новая, игрок, ролевая, стратегия"/><meta name="revisit" content="1"/><meta name="robots" content="all"/><meta name="viewport" content="width=device-width; minimum-scale=1; maximum-scale=1"/><meta name="yandex-verification" content="58248272434241c9"/><meta name="google-site-verification" content="xwPAHIxzH3qo_6c3l0Xli6RX9QbXlfzC__9WtGWxwZw"/><meta name="wot-verification" content="9f1312711c55567c1d37"/><meta name="openstat-verification" content="43b84be4deab76f89080ea3a9558bdb0a093f83e" /><link href="/style/standart/style.css" rel="stylesheet" type="text/css"/><link rel="icon" href="/war-game.ico" type="image/x-icon"/><link rel="shortcut icon" href="/war-game.ico" type="image/x-icon"/>

<?
 ///echo '<center><div class="vverx"><img src="img/logo.png" width="53%" height="46px" alt="логотип вверху"/></center><div>';

if (empty($title)) {
    $title = 'Генералы';
}
echo '<title>'  . $title . '</title>';
echo '</head><body>';
?>

