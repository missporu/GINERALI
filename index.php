<?php
require_once('./system/up.php');
_NoReg();
if (isset($_POST['send'])) {
  $name = filter_var($_POST['login'], FILTER_SANITIZE_STRING);
  $pass = password_hash(filter_var($_POST['pass'], FILTER_SANITIZE_STRING), PASSWORD_DEFAULT); // заменяем md5 на password_hash
  // Используем подготовленные выражения для защиты от SQL-инъекций
  $stmt = $pdo->prepare("SELECT `login`, `pass` FROM `user_reg` WHERE `login` = :login AND `pass` = :pass LIMIT 1");
  $stmt->execute(['login' => $name, 'pass' => $pass]);
  $sql = $stmt->rowCount();
  if (empty($name)) {
      $_SESSION['err'] = 'Введите логин';
      header('Location: login.php');
      exit();
  } elseif (empty($pass)) {
      $_SESSION['err'] = 'Введите пароль';
      header('Location: login.php');
      exit();
  } elseif ($sql == 0) {
      $_SESSION['err'] = 'Игрок не найден';
      $_SESSION['rec'] = '<br/><br/><a href="rec.php"><span class="btn"><span class="end"><input class="label" type="submit" value="Забыт пароль"/></span></span></a>';
      header('Location: login.php');
      exit();
  } else {
      setcookie('login', $name, time() + 86400 * 365, '/');
      setcookie('pass', $pass, time() + 86400 * 365, '/');
      header('Location: bonus.php');
      exit();
  }
} ?>



<center>
    <div class="vverx">
        <img src="img/logo.png" width="53%" height="46px" alt="логотип вверху"/>
    <div>
</center>

<img src='/images/logotips/logo.png' width='100%' alt=''/>

<div class="tekst">
    <center>
        <span style="color: #E8DCA7 ;">
            Здравствуй, боец! Если ты здесь впервые, жми "Начать игру"
        </span>
    </center>
</div>

<div class="menudiv">
    <a href="reg.php">
        <img src='/img/vxod.png' width='100%' alt=''/>
    </a>
</div><?php 

if (isset($_SESSION['err'])) {

}


// Проверяем, существует ли переменная сессии 'rec'
if (isset($_SESSION['rec'])) {
   // Выводим значение переменной сессии 'rec'
   echo $_SESSION['rec'];
   // Удаляем значение переменной сессии 'rec'
   unset($_SESSION['rec']);
}
 ?>

<center>
    <a href="login.php">
        <img src='/images/vxod2.png' width='100%' alt=''/>
    </a>
</center></span>






<br><br>
<div class="small grey">
    <center>
        <a href="rules.php">
            <font color=#AAA>Правила игры 
            </a>   © MOPESOFT.RU  2015.
        </a>
    </center>
</div><?php
require_once('system/down.php');