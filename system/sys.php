<?php
require_once('head.php');

date_default_timezone_set('Europe/Minsk');

$db_host = 'localhost';
$db_user = 'sql_gens_belgame';
$db_pass = 'c9ed552a64438';
$db_name = 'sql_gens_belgame';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES `utf8`');
} catch (PDOException $e) {
    die('<center><br/>Ошибка:<br/>Не найдена база MySQL</center>');
}


// Фильтруем текст
function _TextFilter($text)
{
    $text = trim(strip_tags(htmlspecialchars($text, ENT_QUOTES, 'UTF-8')));
    return $text;
}

// Фильтруем числа
function _NumFilter($num)
{
    $num = abs(intval($num));
    return $num;
}


// Получаем текущую дату и время
$date = new DateTime();
$dater = $date->format('d F');
$timer = $date->format('H:i:s');

// Массив для замены месяцев на русские названия
$months = [
    'January' => 'января',
    'February' => 'февраля',
    'March' => 'марта',
    'April' => 'апреля',
    'May' => 'мая',
    'June' => 'июня',
    'July' => 'июля',
    'August' => 'августа',
    'September' => 'сентября',
    'October' => 'октября',
    'November' => 'ноября',
    'December' => 'декабря',
];

// Заменяем английские названия месяцев на русские
$dater = strtr($dater, $months);

// Получаем IP-адрес пользователя
$ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

// Фильтруем данные
$dater = filter_var($dater, FILTER_SANITIZE_STRING);
$timer = filter_var($timer, FILTER_SANITIZE_STRING);
$ip = filter_var($ip, FILTER_SANITIZE_STRING);


// Используем filter_var вместо filter_input для фильтрации входных данных
$browser = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_STRING);

// Проверка наличия HTTP_REFERER
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL);
} else {
    $referer = 'http://' . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING);
}


// Проверяем, установлены ли куки 'login' и 'pass'
if (isset($_COOKIE['login']) && isset($_COOKIE['pass'])) {
    // Получаем значения куков
    $login = $_COOKIE['login'];
    $pass = $_COOKIE['pass'];

    // Подготавливаем запрос к базе данных с использованием PDO
    $stmt = $pdo->prepare('SELECT * FROM `user_reg` WHERE `login` = :login AND `pass` = :pass LIMIT 1');
    $stmt->execute(['login' => $login, 'pass' => $pass]);

    // Получаем результат запроса
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Если куки не установлены, устанавливаем $user в FALSE
    $user = FALSE;
}


// Функция авторизованного пользователя
function _NoReg()
{
    global $user;
    if ($user) {
        $_SESSION['ok'] = 'Добро пожаловать в игру!';
        header('Location: menu.php');
        exit();
    }
}

// Функция для не авторизованного пользователя
function _Reg()
{
    global $user;
    if (!$user) {
        $_SESSION['err'] = 'Вы не авторизованы!';
        header('Location: index.php');
        exit();
    }
}


function _ExitReg()
{
    // Удаляем куки
    setcookie("login", '', time() - 3600);
    setcookie("pass", '', time() - 3600);

    // Уничтожаем сессию
    session_destroy();

    // Перенаправляем на главную страницу
    header("Location: index.php");
    exit();
}


// Фильтруем домен с помощью стандартной функции PHP
$site = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING);


// Установка почты поддержки
$support = 'support@' . $site;


function _GenCode($length)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[random_int(0, $clen)]; // Используем random_int вместо mt_rand для лучшей безопасности
    }
    return $code;
}


// Подготовка запроса
$stmt = $pdo->prepare('SELECT COUNT(*) FROM `user_reg`');

// Выполнение запроса
$stmt->execute();

// Получение количества пользователей
$reg = $stmt->fetchColumn();


function _Users($reg, $form1, $form2, $form3)
{
    $reg = filter_var($reg, FILTER_VALIDATE_INT) % 100; // Используем стандартную функцию PHP для фильтрации чисел
    $all1 = $reg % 10;
    if ($reg > 10 && $reg < 20) return $form3;
    if ($all1 > 1 && $all1 < 5) return $form2;
    if ($all1 == 1) return $form1;
    return $form3;
}


function _Time($time = 0)
{
    $h = str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT);
    $i = str_pad(floor(($time % 3600) / 60), 2, '0', STR_PAD_LEFT);
    $s = str_pad($time % 60, 2, '0', STR_PAD_LEFT);
    return $h . ':' . $i . ':' . $s;
}


function _DayTime($time)
{
    if (is_numeric($time)) {
        $value = ['years' => 0, 'days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0];
        $intervals = ['years' => 31536000, 'days' => 86400, 'hours' => 3600, 'minutes' => 60, 'seconds' => 1];
        foreach ($intervals as $unit => $interval) {
            if ($time >= $interval) {
                $value[$unit] = floor($time / $interval);
                $time = ($time % $interval);
            }
        }
        $timeString = '';
        foreach ($value as $unit => $amount) {
            if ($amount > 0) {
                $timeString .= $amount . ' ' . $unit . ' ';
            }
        }
        return trim($timeString); // Убран лишний пробел в конце строки
    } else {
        return false;
    }
}


function _Smile($smile)
{
    // Создаем массив с соответствиями смайлов и их изображений
    $smiles = [
        ':)' => '<img src="images/smiles/1.gif" alt="*"/>',
        ':(' => '<img src="images/smiles/2.gif" alt="*"/>',
        ':p' => '<img src="images/smiles/3.gif" alt="*"/>',
        ':d' => '<img src="images/smiles/4.gif" alt="*"/>',
        ':ban' => '<img src="images/smiles/5.gif" alt="*"/>'
    ];

    // Заменяем все смайлы на соответствующие изображения
    $smile = str_replace(array_keys($smiles), array_values($smiles), $smile);

    return $smile;
}


if ($user) {


// ПЕРСОНАЖ

// Фильтруем ID пользователя
    $user_id = filter_var($user['id'], FILTER_VALIDATE_INT);

// Обновляем местоположение пользователя
    $pdo->prepare("UPDATE `user_set` SET `mesto`=:mesto WHERE `id`=:id LIMIT 1")
        ->execute(['mesto' => filter_var($title, FILTER_SANITIZE_STRING), 'id' => $user_id]);

// Получаем данные пользователя
    $set = $pdo->prepare("SELECT * FROM `user_set` WHERE `id`=:id")
        ->execute(['id' => $user_id])
        ->fetch(PDO::FETCH_ASSOC);


    // Создаем массив с соответствием уровней и званий
    $zvanieMap = [
        10 => 'Ефрейтор',
        20 => 'Мл. сержант',
        30 => 'Сержант',
        40 => 'Ст. сержант',
        50 => 'Старшина',
        60 => 'Прапорщик',
        70 => 'Ст. прапорщик',
        80 => 'Мл. лейтенант',
        90 => 'Лейтенант',
        100 => 'Ст. лейтенант',
        110 => 'Капитан',
        120 => 'Майор',
        130 => 'Подполковник',
        140 => 'Полковник',
        150 => 'Генерал-майор',
        160 => 'Генерал-лейтенант',
        170 => 'Генерал-полковник',
        180 => 'Генерал армии',
        200 => 'Маршал',
    ];

// Используем массив для назначения звания
    $zvan = $zvanieMap[$set['lvl']] ?? $set['zvanie'];

// Обновляем звание в базе данных, если оно изменилось
    if ($set['zvanie'] != $zvan) {
        $stmt = $pdo->prepare("UPDATE `user_set` SET `zvanie`=:zvanie WHERE `id`=:id");
        $stmt->execute(['zvanie' => $zvan, 'id' => $user_id]);
    }


// Проверка на обучение
    if ($set['mesto'] != 'Курс молодого бойца' and $set['start'] < 12) {
        $_SESSION['err'] = 'Вы не закончили обучение!';
        header("Location: start.php?case=" . $set['start']);
        exit();
    }

// Улучшение: Используем массив для сопоставления цветов вместо множественных условий
    $colors = [
        'blue' => 'синий',
        'green' => 'зелёный',
        'red' => 'красный',
    ];
    $cvet = $colors[$set['fon']] ?? 'стандартный';

    echo '<link rel="stylesheet" href="style/' . $set['fon'] . '/style.css" type="text/css" />'; // Фон игры

    // Подготовка запросов
    $stmt_browser = $pdo->prepare("UPDATE `user_set` SET `browser_new`=:browser WHERE `id`=:id");
    $stmt_ip = $pdo->prepare("UPDATE `user_set` SET `ip_new`=:ip WHERE `id`=:id");

// Обновление браузера и IP
    if ($browser != $set['browser_new']) {
        $stmt_browser->execute(['browser' => $browser, 'id' => $user_id]);
    }

    if ($ip != $set['ip_new']) {
        $stmt_ip->execute(['ip' => $ip, 'id' => $user_id]);
    }


// Обновление данных пользователя
    $pdo->prepare("UPDATE `user_set` SET `online`=:online, `last_date_visit`=:last_date_visit, `last_time_visit`=:last_time_visit WHERE `id`=:id")
        ->execute(['online' => time(), 'last_date_visit' => $dater, 'last_time_visit' => $timer, 'id' => $user_id]);

// Получение количества альянсов
    $user_alliance = $pdo->prepare("SELECT COUNT(*) FROM `alliance_user` WHERE `kto`=:user_id OR `s_kem`=:user_id")
        ->execute(['user_id' => $user_id])
        ->fetchColumn();


// Получение количества записей в таблице `sanction` с `time_up` меньше текущего времени
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `sanction` WHERE `time_up` < :time");
    $stmt->execute(['time' => time()]);
    $sanctions = $stmt->fetchColumn();

// Если количество записей больше 0, обновляем `time_up` на 0 для всех записей с `time_up` меньше текущего времени
    if ($sanctions > 0) {
        $stmt = $pdo->prepare("UPDATE `sanction` SET `time_up` = '0' WHERE `time_up` < :time");
        $stmt->execute(['time' => time()]);
    }


// Ежедневный сброс ставки для санкций
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `sanction` WHERE `data` != :dater");
    $stmt->execute([':dater' => $dater]);
    $stavka = $stmt->fetchColumn();

    if ($stavka > 0) {
        $stmt = $pdo->prepare("UPDATE `sanction` SET `stavka` = '100' WHERE `data` != :dater");
        $stmt->execute([':dater' => $dater]);
    }

// Оповещение о приглашении в альянс
    $stmt = $pdo->prepare("SELECT * FROM `alliance_priglas` WHERE `kogo` = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $priglas = $stmt->fetch();

    if ($priglas) {
        $plus_priglas = '<span style="color: #3c3;">';
        $plus_prigl = '<span style="float: right;"><span style="color: #3c3;">+</span></span>';
    } else {
        $plus_priglas = '<span style="color: #4f4f4f;">';
        $plus_prigl = FALSE;
    }


// Регенерация дипломатов 1 час
    $stmt = $pdo->prepare("SELECT * FROM `alliance_diplom` WHERE `id_user` = :user_id ORDER BY `id` ASC LIMIT 1");
    $stmt->execute([':user_id' => $user_id]);
    $pri = $stmt->fetch();

    if ($pri && $pri['diplom_up'] < time()) {
        $stmt = $pdo->prepare('DELETE FROM `alliance_diplom` WHERE `id_user` = :user_id AND `diplom_up` < :time LIMIT 1');
        $stmt->execute([':user_id' => $user_id, ':time' => time()]);

        $stmt = $pdo->prepare('SELECT `diplomat`, `diplomat_max` FROM `user_set` WHERE `id` = :user_id');
        $stmt->execute([':user_id' => $user_id]);
        $set = $stmt->fetch();

        if ($set['diplomat'] < $set['diplomat_max']) {
            $stmt = $pdo->prepare('UPDATE `user_set` SET `diplomat` = `diplomat` + 1 WHERE `id` = :user_id');
            $stmt->execute([':user_id' => $user_id]);
        }
    }


// Выборка приглашений для пользователя
    $stmt = $pdo->prepare('SELECT * FROM `alliance_priglas` WHERE `kogo` = :user_id ORDER BY `id` DESC');
    $stmt->execute(['user_id' => $user_id]);
    $dip = $stmt->fetch();

// Проверка наличия приглашений и их удаление, если они старше 3 часов
    if ($dip && $dip['priglas_up'] < time()) {
        $stmt = $pdo->prepare('DELETE FROM `alliance_priglas` WHERE `kogo` = :user_id AND `priglas_up` < :time');
        $stmt->execute(['user_id' => $user_id, 'time' => time()]);
    }


    // Определяем пол персонажа
    $pol = match ($set['sex']) {
        'w' => 'Девушка',
        'm' => 'Парень',
        default => 'Не известно',
    };


    // Создаем массив с соответствием кодов стран
    $countryCodes = [
        'r' => 'Россия',
        'g' => 'Германия',
        'a' => 'США',
        'u' => 'Украина',
        'b' => 'Белоруссия',
        'c' => 'Китай',
        'k' => 'Казахстан',
    ];

// Используем функцию array_key_exists для проверки наличия кода страны в массиве
    if (array_key_exists($set['side'], $countryCodes)) {
        $strana = $countryCodes[$set['side']];
    } else {
        $strana = 'Не известно';
    }


    // Подготовка запроса для выборки данных из таблицы `build`
    $stmt = $pdo->prepare("SELECT * FROM `build` WHERE `lvl`=:lvl LIMIT 1");
    $stmt->execute(['lvl' => $set['lvl']]);
    $data_build = $stmt->fetch(PDO::FETCH_ASSOC);

// Подготовка запроса для выборки данных из таблицы `user_build`
    $stmt = $pdo->prepare("SELECT * FROM `user_build` WHERE `id_user`=:id_user AND `lvl`=:lvl LIMIT 1");
    $stmt->execute(['id_user' => $user_id, 'lvl' => $data_build['lvl']]);
    $user_build = $stmt->fetch(PDO::FETCH_ASSOC);

// Если записи в таблице `user_build` не найдено, то добавляем новые данные
    if (!$user_build) {
        // Подготовка запроса для вставки данных в таблицу `user_build`
        $stmt = $pdo->prepare("INSERT INTO `user_build` (id_user,id_build,name,tip,lvl,kol,bonus,cena) VALUES (:id_user, :id_build, :name, :tip, :lvl, :kol, :bonus, :cena)");
        $stmt->execute([
            'id_user' => $user_id,
            'id_build' => $data_build['id'],
            'name' => $data_build['name'],
            'tip' => $data_build['tip'],
            'lvl' => $data_build['lvl'],
            'kol' => $data_build['kol'],
            'bonus' => $data_build['bonus'],
            'cena' => $data_build['cena']
        ]);

        // Подготовка запроса для обновления данных в таблице `user_set`
        $stmt = $pdo->prepare('UPDATE `user_set` SET `build_up`=:build_up WHERE `id`=:id');
        $stmt->execute(['build_up' => time(), 'id' => $user_id]);
    }


    // Проверяем, прошло ли время с последнего обновления
    if (time() > $set['build_up'] && $set['build_up'] != 0) {
        // Вычисляем доход
        $dohod_up = intval((time() - $set['build_up']) / 3600);
        $dohod_new = intval($set['chistaya'] * $dohod_up);

        // Если прошло хотя бы один час, обновляем данные в базе
        if ($dohod_up >= 1) {
            // Подготавливаем запросы для обновления данных
            $stmt1 = $pdo->prepare('UPDATE `user_set` SET `baks`=`baks`+:dohod_new, `build_up`=:time, `refer_baks`=`refer_baks`+:refer_baks WHERE `id` = :user_id');
            $stmt2 = $pdo->prepare('UPDATE `user_set` SET `baks`=`baks`+:refer_baks WHERE `id` = :refer_id');

            // Выполняем запросы с параметрами
            $stmt1->execute([
                ':dohod_new' => $dohod_new,
                ':time' => time(),
                ':refer_baks' => round($dohod_new / 10),
                ':user_id' => $user_id
            ]);
            $stmt2->execute([
                ':refer_baks' => round($dohod_new / 10),
                ':refer_id' => $user['refer']
            ]);
        }
    } // Выплата с доходных построек


// Получение данных из таблицы `unit`
    $stmt = $pdo->prepare("SELECT * FROM `unit` WHERE `lvl` = :lvl LIMIT 1");
    $stmt->execute(['lvl' => $set['lvl']]);
    $data_unit = $stmt->fetch(PDO::FETCH_ASSOC);

// Получение данных из таблицы `user_unit`
    $stmt = $pdo->prepare("SELECT * FROM `user_unit` WHERE `id_user` = :id_user AND `lvl` = :lvl LIMIT 1");
    $stmt->execute(['id_user' => $user_id, 'lvl' => $data_unit['lvl']]);
    $user_unit = $stmt->fetch(PDO::FETCH_ASSOC);

// Если пользовательская техника не найдена и данные о технике существуют, добавляем технику в таблицу `user_unit`
    if (!$user_unit && $data_unit) {
        $stmt = $pdo->prepare("INSERT INTO `user_unit` (id_user,id_unit,name,tip,lvl,kol,ataka,zaschita,soderzhanie,cena) VALUES (:id_user, :id_unit, :name, :tip, :lvl, :kol, :ataka, :zaschita, :soderzhanie, :cena)");
        $stmt->execute([
            'id_user' => $user_id,
            'id_unit' => $data_unit['id'],
            'name' => $data_unit['name'],
            'tip' => $data_unit['tip'],
            'lvl' => $data_unit['lvl'],
            'kol' => $data_unit['kol'],
            'ataka' => $data_unit['ataka'],
            'zaschita' => $data_unit['zaschita'],
            'soderzhanie' => $data_unit['soderzhanie'],
            'cena' => $data_unit['cena']
        ]);
    }


// Получение данных о трофеях пользователя
    $stmt = $pdo->prepare("SELECT * FROM `user_trofei` WHERE `id_user` = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user_trof = $stmt->fetch(PDO::FETCH_ASSOC);

// Если трофеи пользователя не найдены, добавляем их
    if (!$user_trof) {
        for ($t = 1; $t <= 18; $t++) {
            // Получение данных о трофеях
            $stmt = $pdo->prepare("SELECT * FROM `trofei` WHERE `id` = :id LIMIT 1");
            $stmt->execute(['id' => $t]);
            $data_trof = $stmt->fetch(PDO::FETCH_ASSOC);

            // Добавление трофеев пользователя
            $stmt = $pdo->prepare("INSERT INTO `user_trofei` (id_user,id_trof,status,lvl,cena_baks,cena_gold,time_up,day,bonus_1,bonus_2,next_1,next_2) VALUES (:user_id, :id_trof, :status, :lvl, :cena_baks, :cena_gold, :time_up, :day, :bonus_1, :bonus_2, :next_1, :next_2)");
            $stmt->execute([
                'user_id' => $user_id,
                'id_trof' => $data_trof['id'],
                'status' => $data_trof['status'],
                'lvl' => $data_trof['lvl'],
                'cena_baks' => $data_trof['cena_baks'],
                'cena_gold' => $data_trof['cena_gold'],
                'time_up' => $data_trof['time_up'],
                'day' => $data_trof['day'],
                'bonus_1' => $data_trof['bonus_1'],
                'bonus_2' => $data_trof['bonus_2'],
                'next_1' => $data_trof['next_1'],
                'next_2' => $data_trof['next_2']
            ]);
        }
    }


    // Подготовка запроса для получения данных из таблицы user_trofei
    $stmt = $pdo->prepare("SELECT * FROM `user_trofei` WHERE `id_user` = :user_id AND `time_up`!='0' LIMIT 1");
    $stmt->execute(['user_id' => $user_id]);
    $time_trof = $stmt->fetch(PDO::FETCH_ASSOC);

// Подготовка запроса для получения данных из таблицы trofei
    $stmt = $pdo->prepare("SELECT `name`, `shag_1`, `shag_2` FROM `trofei` WHERE `id` = :trof_id LIMIT 1");
    $stmt->execute(['trof_id' => $time_trof['id_trof']]);
    $shag_time = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка, что время прокачки трофея истекло
    if ($time_trof['time_up'] <= time()) {
        // Обновление данных в таблице user_trofei
        $stmt = $pdo->prepare("UPDATE `user_trofei` SET `lvl`=:lvl, `time_up`='0', `cena_baks`=:cena_baks, `cena_gold`=:cena_gold, `day`=:day, `bonus_1`=:bonus_1, `bonus_2`=:bonus_2, `next_1`=:next_1, `next_2`=:next_2 WHERE `id_user`=:user_id AND `id_trof`=:trof_id LIMIT 1");
        $stmt->execute([
            'lvl' => $time_trof['lvl'] + 1,
            'cena_baks' => $time_trof['cena_baks'] * 2,
            'cena_gold' => $time_trof['cena_gold'] * 2,
            'day' => $time_trof['day'] + 2,
            'bonus_1' => $time_trof['bonus_1'] + $shag_time['shag_1'],
            'bonus_2' => $time_trof['bonus_2'] + $shag_time['shag_2'],
            'next_1' => $time_trof['next_1'] + $shag_time['shag_1'],
            'next_2' => $time_trof['next_2'] + $shag_time['shag_2'],
            'user_id' => $user_id,
            'trof_id' => $time_trof['id_trof']
        ]);

        // Обновление данных в таблице user_set, если id трофея равен 6
        if ($time_trof['id_trof'] == 6) {
            $trof_hp = $set['hp'] / 100 * ($time_trof['bonus_1'] + $shag_time['shag_1']);
            $trof_max_hp = $set['max_hp'] / 100 * ($time_trof['bonus_1'] + $shag_time['shag_1']);
            $stmt = $pdo->prepare("UPDATE `user_set` SET `hp`=`hp`+:trof_hp, `max_hp`=`max_hp`+:trof_max_hp WHERE `id`=:user_id LIMIT 1");
            $stmt->execute(['trof_hp' => $trof_hp, 'trof_max_hp' => $trof_max_hp, 'user_id' => $user_id]);
        }

        // Обновление данных в таблице user_set, если id трофея равен 4
        if ($time_trof['id_trof'] == 4) {
            $soderzhanie = $set['soderzhanie'] / 100 * ($time_trof['bonus_1'] + $shag_time['shag_1']);
            $chistaya = $set['chistaya'] + $soderzhanie;
            $stmt = $pdo->prepare("UPDATE `user_set` SET `soderzhanie`=`soderzhanie`-:soderzhanie, `chistaya`=:chistaya WHERE `id`=:user_id LIMIT 1");
            $stmt->execute(['soderzhanie' => $soderzhanie, 'chistaya' => $chistaya, 'user_id' => $user_id]);
        }
    }


// Получение данных о пользователе
    $stmt = $pdo->prepare("SELECT * FROM `user_superunit` WHERE `id_user` = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user_superunit = $stmt->fetch(PDO::FETCH_ASSOC);

// Если данные о пользователе не найдены, добавляем супертехнику
    if (!$user_superunit) {
        for ($i = 1; $i <= 10; $i++) {
            // Получение данных о супертехнике
            $stmt = $pdo->prepare("SELECT * FROM `superunit` WHERE `id` = :id LIMIT 1");
            $stmt->execute(['id' => $i]);
            $data_superunit = $stmt->fetch(PDO::FETCH_ASSOC);

            // Добавление супертехники пользователю
            $stmt = $pdo->prepare("INSERT INTO `user_superunit` (id_user,id_unit,name,kol,ataka,zaschita) VALUES (:user_id, :id_unit, :name, :kol, :ataka, :zaschita)");
            $stmt->execute([
                'user_id' => $user_id,
                'id_unit' => $data_superunit['id'],
                'name' => $data_superunit['name'],
                'kol' => $data_superunit['kol'],
                'ataka' => $data_superunit['ataka'],
                'zaschita' => $data_superunit['zaschita']
            ]);
        }
    }


// Получение информации о бонусах пользователя
    $stmt = $pdo->prepare("SELECT * FROM `user_bonus` WHERE `id_user` = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user_bonus = $stmt->fetch(PDO::FETCH_ASSOC);

// Если бонусы не найдены, добавляем их в базу данных
    if (!$user_bonus) {
        $stmt = $pdo->prepare("INSERT INTO `user_bonus` (`id_user`, `day`, `month`, `year`) VALUES (:user_id, :day, :month, :year)");
        $stmt->execute([
            'user_id' => $user_id,
            'day' => date("d"),
            'month' => date("F"),
            'year' => date("Y")
        ]);
    }

// Проверка на старый бонус
    $stmt = $pdo->prepare("SELECT `status_bonus` FROM `user_bonus` WHERE `id_user` = :user_id AND `status_bonus` = '1' AND (`day` != :day OR `month` != :month OR `year` != :year) LIMIT 1");
    $stmt->execute([
        'user_id' => $user_id,
        'day' => date("d"),
        'month' => date("F"),
        'year' => date("Y")
    ]);
    $old_bonus = $stmt->fetch(PDO::FETCH_ASSOC);

// Если старый бонус найден, обновляем статус бонуса и перенаправляем пользователя
    if ($old_bonus) {
        $stmt = $pdo->prepare("UPDATE `user_bonus` SET `status_bonus` = '0' WHERE `id_user` = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        header('Location: bonus.php');
        exit();
    }


// Получение данных о пользователе
    $stmt = $pdo->prepare("SELECT * FROM `user_naemniki` WHERE `id_user` = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user_naem = $stmt->fetch(PDO::FETCH_ASSOC);

// Если пользователь не найден, добавляем его
    if (!$user_naem) {
        for ($i = 1; $i <= 5; $i++) {
            $stmt = $pdo->prepare("INSERT INTO `user_naemniki` SET `id_user` = :user_id, `id_naemnik` = :id_naemnik");
            $stmt->execute(['user_id' => $user_id, 'id_naemnik' => $i]);
        }
    }

// Получение данных о наемниках
    $stmt = $pdo->prepare("SELECT * FROM `user_naemniki` WHERE `id_user` = :user_id AND `time_up` <= :time AND `status` = '1'");
    $stmt->execute(['user_id' => $user_id, 'time' => time()]);
    $user_n = $stmt->fetch(PDO::FETCH_ASSOC);

// Если наемники найдены, обновляем их статус
    if ($user_n) {
        $stmt = $pdo->prepare("UPDATE `user_naemniki` SET `status` = '0' WHERE `id_user` = :user_id AND `time_up` <= :time AND `status` = '1'");
        $stmt->execute(['user_id' => $user_id, 'time' => time()]);
    }


    // Подключение к базе данных через PDO
    $pdo = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

// Получение данных из таблицы user_laboratory
    $stmt = $pdo->prepare("SELECT * FROM `user_laboratory` WHERE `id_user` = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user_lab = $stmt->fetch(PDO::FETCH_ASSOC);

// Если данные не найдены, добавляем записи в таблицу
    if (!$user_lab) {
        for ($i = 1; $i <= 6; $i++) {
            $stmt = $pdo->prepare("INSERT INTO `user_laboratory` (`id_user`, `id_lab`) VALUES (:user_id, :lab_id)");
            $stmt->execute(['user_id' => $user_id, 'lab_id' => $i]);
        }
    }

// Получение данных из таблицы user_laboratory с определенными условиями
    $stmt = $pdo->prepare("SELECT * FROM `user_laboratory` WHERE `id_user` = :user_id AND `time_up` <= :time AND `status` = '1'");
    $stmt->execute(['user_id' => $user_id, 'time' => time()]);
    $user_l = $stmt->fetch(PDO::FETCH_ASSOC);

// Если данные найдены, обновляем статус
    if ($user_l) {
        $stmt = $pdo->prepare("UPDATE `user_laboratory` SET `status` = '0' WHERE `id_user` = :user_id AND `time_up` <= :time AND `status` = '1'");
        $stmt->execute(['user_id' => $user_id, 'time' => time()]);
    }


    // Подключение к базе данных через PDO
    $pdo = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

// Подготовка запроса
    $stmt = $pdo->prepare("SELECT * FROM `user_superunit` WHERE `id_unit` = :id_unit AND `id_user` = :id_user LIMIT 1");

// Выполнение запроса и получение результатов
    $results = [];
    for ($i = 1; $i <= 10; $i++) {
        $stmt->execute(['id_unit' => $i, 'id_user' => $user_id]);
        $results[] = $stmt->fetch(PDO::FETCH_ASSOC);
    }
// $results теперь содержит результаты запросов для id_unit от 1 до 10


// Запрос на выборку данных из таблицы `mail`
    $stmt = $pdo->prepare("SELECT * FROM `mail` WHERE `komu`=:user_id AND `status`='0'");
    $stmt->execute(['user_id' => $user_id]);
    $data_mail_plus = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка наличия данных и установка значений переменных
    if ($data_mail_plus) {
        $plus_mail = '<span style="color: #3c3;">';
        $icon_mail = '<a href="mail.php"><img src="images/icons/mail.png" width="18px" height="15px" alt="Почта"></a>';
    } else {
        $plus_mail = '<span style="color: #4f4f4f;">';
        $icon_mail = FALSE;
    }

// Установка значения переменной $plus_news в зависимости от значения $set['news']
    $plus_news = ($set['news'] == 1) ? '<span style="color: #3c3;">' : '<span style="color: #4f4f4f;">';


    // Подготовка запроса для выборки данных из таблицы `operation`
    $stmt = $pdo->prepare("SELECT * FROM `operation` WHERE `lvl` = :lvl LIMIT 1");
    $stmt->execute(['lvl' => $set['lvl']]);
    $data_operation = $stmt->fetch(PDO::FETCH_ASSOC);

// Подготовка запроса для выборки данных из таблицы `user_operation`
    $stmt = $pdo->prepare("SELECT * FROM `user_operation` WHERE `id_user` = :id_user AND `id_operation` = :id_operation LIMIT 1");
    $stmt->execute(['id_user' => $user_id, 'id_operation' => $data_operation['id']]);
    $user_operation = $stmt->fetch(PDO::FETCH_ASSOC);

// Если пользовательская операция не найдена и операция существует, вставляем новую запись в таблицу `user_operation`
    if (!$user_operation && $data_operation) {
        $stmt = $pdo->prepare("INSERT INTO `user_operation` (id_user, id_operation, name, lvl, max_exp, rang, point) VALUES (:id_user, :id_operation, :name, :lvl, :max_exp, :rang, :point)");
        $stmt->execute([
            'id_user' => $user_id,
            'id_operation' => $data_operation['id'],
            'name' => $data_operation['name'],
            'lvl' => $data_operation['lvl'],
            'max_exp' => $data_operation['exp'],
            'rang' => $data_operation['rang'],
            'point' => $data_operation['point']
        ]);
    }
//Добавление спецопераций по уровням


// Получение данных миссии
    $stmt = $pdo->prepare("SELECT * FROM `mission` WHERE `lvl`=:lvl LIMIT 1");
    $stmt->execute(['lvl' => $set['lvl']]);
    $data_mission = $stmt->fetch(PDO::FETCH_ASSOC);

// Получение данных пользователя
    $stmt = $pdo->prepare("SELECT * FROM `user_mission` WHERE `id_user`=:id_user AND `id_mission`=:id_mission LIMIT 1");
    $stmt->execute(['id_user' => $user_id, 'id_mission' => $data_mission['id']]);
    $user_mission = $stmt->fetch(PDO::FETCH_ASSOC);

// Если пользователь не имеет миссии и миссия существует, добавляем миссию пользователю
    if (!$user_mission && $data_mission) {
        $stmt = $pdo->prepare("INSERT INTO `user_mission` (id_user,id_operation,id_mission,name,exp_mission,max_exp,alliance,id_unit,kol_unit,exp_priz,baks_priz,lvl) VALUES (:id_user, :id_operation, :id_mission, :name, :exp_mission, :max_exp, :alliance, :id_unit, :kol_unit, :exp_priz, :baks_priz, :lvl)");
        $stmt->execute([
            'id_user' => $user_id,
            'id_operation' => $data_mission['id_operation'],
            'id_mission' => $data_mission['id'],
            'name' => $data_mission['name'],
            'exp_mission' => $data_mission['exp_mission'],
            'max_exp' => $data_mission['exp_mission'] * 2,
            'alliance' => $data_mission['alliance'],
            'id_unit' => $data_mission['id_unit'],
            'kol_unit' => $data_mission['kol_unit'],
            'exp_priz' => $data_mission['exp_priz'],
            'baks_priz' => $data_mission['baks_priz'],
            'lvl' => $data_mission['lvl']
        ]);
    }


    // Подготовка запроса для обновления рейтинга
    $stmt = $pdo->prepare("UPDATE `user_set` SET `raiting` = `raiting` + :raiting_change, `raiting_wins` = :raiting_wins, `raiting_loses` = :raiting_loses WHERE `id` = :user_id LIMIT 1");

// Обновление рейтинга в случае победы
    if ($set['raiting_wins'] >= 9) {
        $stmt->execute([
            'raiting_change' => 1,
            'raiting_wins' => 0,
            'raiting_loses' => $set['raiting_loses'],
            'user_id' => $user_id
        ]);
    }

// Обновление рейтинга в случае поражения
    if ($set['raiting_loses'] >= 9) {
        $stmt->execute([
            'raiting_change' => -1,
            'raiting_wins' => $set['raiting_wins'],
            'raiting_loses' => 0,
            'user_id' => $user_id
        ]);
    }


    if ($set['hp'] >= 0) {
        $set['max_hp'] = filter_var($set['max_hp'], FILTER_SANITIZE_NUMBER_INT);
        if ($set['hp'] < $set['max_hp']) {
            $hp_up = (time() - $set['hp_up']) / 18;
            $hp_new = $set['hp'] + $hp_up;
            if ($hp_new < $set['max_hp']) {
                if ($hp_up >= 1) {
                    // Обновление здоровья и времени последнего обновления
                    $stmt = $pdo->prepare('UPDATE `user_set` SET `hp`=:hp, `hp_up`=:hp_up WHERE `id`=:id');
                    $stmt->execute(['hp' => $hp_new, 'hp_up' => time(), 'id' => $user_id]);
                }
            } else {
                // Установка максимального здоровья
                $stmt = $pdo->prepare('UPDATE `user_set` SET `hp`=:max_hp WHERE `id`=:id');
                $stmt->execute(['max_hp' => $set['max_hp'], 'id' => $user_id]);
            }
        } else {
            // Установка здоровья в 0
            $stmt = $pdo->prepare('UPDATE `user_set` SET `hp`="0" WHERE `id`=:id');
            $stmt->execute(['id' => $user_id]);
        }
    }

    if ($set['soderzhanie'] < 0) {
        // Обнуление содержания и установка чистой суммы
        $stmt = $pdo->prepare('UPDATE `user_set` SET `soderzhanie`="0", `chistaya`=:dohod WHERE `id`=:id');
        $stmt->execute(['dohod' => $set['dohod'], 'id' => $user_id]);
    }


// Подготовка запроса
    $stmt = $pdo->prepare("SELECT * FROM `user_trofei` WHERE `id_user` = :user_id AND `id_trof` = '2'");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $trof_udar = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка статуса и времени
    if ($trof_udar['status'] == 1 and $trof_udar['time_up'] == 0) {
        $time_udar = (180 - ($trof_udar['bonus_1'] / 8 * 15));
    } else {
        $time_udar = 180;
    }


// Подготовка запроса
    $stmt = $pdo->prepare('SELECT * FROM `user_laboratory` WHERE `id_user` = :user_id AND `id_lab` = "2" LIMIT 1');
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user_lab_udar = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка статуса и изменение времени
    if ($user_lab_udar['status'] == 1) {
        $time_udar /= 2;
    }


// Проверка значения $set['udar']
    if ($set['udar'] >= 0) {
        // Фильтрация значения $set['max_udar']
        $set['max_udar'] = filter_var($set['max_udar'], FILTER_VALIDATE_INT);

        // Проверка значения $set['udar'] относительно $set['max_udar']
        if ($set['udar'] < $set['max_udar']) {
            // Расчет нового значения $udar_new
            $udar_up = (time() - $set['udar_up']) / $time_udar;
            $udar_new = $set['udar'] + $udar_up;

            // Проверка значения $udar_new относительно $set['max_udar']
            if ($udar_new < $set['max_udar']) {
                // Обновление значения `udar` и `udar_up` в базе данных
                if ($udar_up >= 1) {
                    $stmt = $pdo->prepare('UPDATE `user_set` SET `udar`=:udar, `udar_up`=:udar_up WHERE `id`=:id');
                    $stmt->execute(['udar' => $udar_new, 'udar_up' => time(), 'id' => $user_id]);
                }
            } else {
                // Обновление значения `udar` в базе данных
                $stmt = $pdo->prepare('UPDATE `user_set` SET `udar`=:udar WHERE `id`=:id');
                $stmt->execute(['udar' => $set['max_udar'], 'id' => $user_id]);
            }
        } else {
            // Обновление значения `udar` в базе данных
            $stmt = $pdo->prepare('UPDATE `user_set` SET `udar`=:udar WHERE `id`=:id');
            $stmt->execute(['udar' => $set['max_udar'], 'id' => $user_id]);
        }
    } else {
        // Обновление значения `udar` в базе данных
        $stmt = $pdo->prepare('UPDATE `user_set` SET `udar`=0 WHERE `id`=:id');
        $stmt->execute(['id' => $user_id]);
    }


// Подготовка запроса
    $stmt = $pdo->prepare("SELECT `status`, `time_up`, `bonus_1` FROM `user_trofei` WHERE `id_user` = :user_id AND `id_trof` = '3'");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $trof_enka = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка статуса и времени
    if ($trof_enka['status'] == 1 and $trof_enka['time_up'] == 0) {
        $time_enka = (240 - ($trof_enka['bonus_1'] / 8 * 15));
    } else {
        $time_enka = 240;
    }


// Запрос к базе данных
    $stmt = $pdo->prepare('SELECT * FROM `user_laboratory` WHERE `id_user`=:id_user AND `id_lab`=1 LIMIT 1');
    $stmt->execute(['id_user' => $user_id]);
    $user_lab_mp = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка статуса
    if ($user_lab_mp['status'] == 1) {
        $time_enka = $time_enka / 2;
    }

// Проверка значения mp
    if ($set['mp'] >= 0) {
        $set['max_mp'] = filter_var($set['max_mp'], FILTER_VALIDATE_INT);
        if ($set['mp'] < $set['max_mp']) {
            $mp_up = (time() - $set['mp_up']) / $time_enka;
            $mp_new = $set['mp'] + ($mp_up * $set['build_energy']);
            if ($mp_new < $set['max_mp']) {
                if ($mp_up >= 1) {
                    $stmt = $pdo->prepare('UPDATE `user_set` SET `mp`=:mp, `mp_up`=:mp_up WHERE `id`=:id');
                    $stmt->execute(['mp' => $mp_new, 'mp_up' => time(), 'id' => $user_id]);
                }
            } else {
                $stmt = $pdo->prepare('UPDATE `user_set` SET `mp`=:mp WHERE `id`=:id');
                $stmt->execute(['mp' => $set['max_mp'], 'id' => $user_id]);
            }
        } else {
            $stmt = $pdo->prepare('UPDATE `user_set` SET `mp`=:mp WHERE `id`=:id');
            $stmt->execute(['mp' => $set['max_mp'], 'id' => $user_id]);
        }
    } else {
        $stmt = $pdo->prepare('UPDATE `user_set` SET `mp`=0 WHERE `id`=:id');
        $stmt->execute(['id' => $user_id]);
    }


// Регенерация помилований
// Проверяем, что текущее время больше значения в $set['pomiloval'] и что $set['pomiloval'] не равно 0
    if (time() > $set['pomiloval'] and $set['pomiloval'] != 0) {
        // Подготавливаем SQL-запрос для обновления значения `pomiloval` в таблице `user_set`
        $stmt = $pdo->prepare('UPDATE `user_set` SET `pomiloval` = "0" WHERE `id` = :user_id');
        // Выполняем подготовленный запрос, передавая значение $user_id в качестве параметра
        $stmt->execute(['user_id' => $user_id]);
    }


// Добавление в таблицу фаталити
// Подготовка запроса для выборки данных из таблицы user_fataliti
    $stmt = $pdo->prepare("SELECT * FROM `user_fataliti` WHERE `id_user` = :user_id LIMIT 1");

// Привязка параметра user_id к запросу
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

// Выполнение запроса
    $stmt->execute();

// Получение результата запроса
    $fataliti_user = $stmt->fetch(PDO::FETCH_ASSOC);

// Если запись не найдена, то вставляем новую запись в таблицу user_fataliti
    if (!$fataliti_user) {
        // Подготовка запроса для вставки данных в таблицу user_fataliti
        $stmt = $pdo->prepare("INSERT INTO `user_fataliti` (`id_user`) VALUES (:user_id)");

        // Привязка параметра user_id к запросу
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        // Выполнение запроса
        $stmt->execute();
    }


// Регенерация ушей
// Проверяем, что время больше, чем uho1_up и uho1_up не равно 0
    if (time() > $fataliti_user['uho1_up'] && $fataliti_user['uho1_up'] != 0) {
        // Подготавливаем запрос на обновление данных в таблице user_fataliti
        $stmt = $pdo->prepare('UPDATE `user_fataliti` SET `uho1_kto` = "0", `uho1_up` = "0" WHERE `id_user` = :user_id');
        // Выполняем запрос, передавая user_id в качестве параметра
        $stmt->execute(['user_id' => $user_id]);
    }

// Проверяем, что время больше, чем uho2_up и uho2_up не равно 0
    if (time() > $fataliti_user['uho2_up'] && $fataliti_user['uho2_up'] != 0) {
        // Подготавливаем запрос на обновление данных в таблице user_fataliti
        $stmt = $pdo->prepare('UPDATE `user_fataliti` SET `uho2_kto` = "0", `uho2_up` = "0" WHERE `id_user` = :user_id');
        // Выполняем запрос, передавая user_id в качестве параметра
        $stmt->execute(['user_id' => $user_id]);
    }


// Регенерация фаталити
    if (time() > $fataliti_user['fataliti1'] || $fataliti_user['fataliti1'] != 0) {
        $stmt = $pdo->prepare('UPDATE `user_fataliti` SET `fataliti1` = "0" WHERE `id_user` = :user_id AND `fataliti1` < :time');
        $stmt->execute(['user_id' => $user_id, 'time' => time()]);
    }
    if (time() > $fataliti_user['fataliti2'] || $fataliti_user['fataliti2'] != 0) {
        $stmt = $pdo->prepare('UPDATE `user_fataliti` SET `fataliti2` = "0" WHERE `id_user` = :user_id AND `fataliti2` < :time');
        $stmt->execute(['user_id' => $user_id, 'time' => time()]);
    }


// Очистка всех логов, кроме сегодня
    $old_log = $pdo->query("SELECT `data` FROM `user_voina` WHERE `id_user` = :user_id AND `data` != :dater LIMIT 1", [
        'user_id' => $user_id,
        'dater' => $dater
    ])->fetch(PDO::FETCH_ASSOC);

    if ($old_log) {
        $pdo->query("DELETE FROM `user_voina` WHERE `data` != :dater", [
            'dater' => $dater
        ]);
    }


// Выполнение запроса с использованием подготовленных выражений
    $stmt = $pdo->prepare('SELECT * FROM `ofclub_dopros` WHERE `id_user` = :id_user');
    $stmt->execute(['id_user' => $user_id]);
    $set_ofclub_dopros = $stmt->fetch(PDO::FETCH_ASSOC);

// Если запись не найдена, добавляем новую запись
    if (!$set_ofclub_dopros) {
        $stmt = $pdo->prepare("INSERT INTO `ofclub_dopros` (`id_user`) VALUES (:id_user)");
        $stmt->execute(['id_user' => $user_id]);
    }
//добавление в допрос шпиона


// БОЕВАЯ СИСТЕМА ПЕРСОНАЖ

// Используем подготовленные выражения для защиты от SQL-инъекций
    $stmt = $pdo->prepare('SELECT * FROM `user_naemniki` WHERE `id_user` = :user_id AND `id_naemnik` = :naemnik_id LIMIT 1');

// Запрашиваем данные о наемниках
    $stmt->execute(['user_id' => $user_id, 'naemnik_id' => 1]);
    $user_naem_z = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->execute(['user_id' => $user_id, 'naemnik_id' => 2]);
    $user_naem_m = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->execute(['user_id' => $user_id, 'naemnik_id' => 3]);
    $user_naem_v = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->execute(['user_id' => $user_id, 'naemnik_id' => 5]);
    $user_naem_l = $stmt->fetch(PDO::FETCH_ASSOC);

// Запрашиваем данные о юнитах
    $stmt = $pdo->prepare('SELECT * FROM `voina_unit` WHERE `id_user` = :user_id ORDER BY `ataka` DESC LIMIT :limit');
    $stmt->execute(['user_id' => $user_id, 'limit' => ($user_alliance + 1) * 5]);
    $data_unit_a = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Инициализируем переменные для атаки
    $A1 = $A2 = $A3 = $A4 = $A5 = $A6 = 0;

// Проходим по всем юнитам
    foreach ($data_unit_a as $vua) {
        // Добавляем бонусы наемников
        if ($user_naem_z['status'] == 1 && in_array($vua['tip'], [1, 4])) {
            $A4 += $vua['ataka'] * 0.2;
        }
        if ($user_naem_m['status'] == 1 && in_array($vua['tip'], [2, 5])) {
            $A5 += $vua['ataka'] * 0.2;
        }
        if ($user_naem_v['status'] == 1 && in_array($vua['tip'], [3, 6])) {
            $A6 += $vua['ataka'] * 0.2;
        }

        // Добавляем бонусы в зависимости от стороны
        if ($set['side'] == 'r' && in_array($vua['tip'], [2, 5])) {
            $A1 += $vua['ataka'];
            $A3 += $vua['ataka'] * 0.2;
        } elseif ($set['side'] == 'g' && in_array($vua['tip'], [1, 4])) {
            $A1 += $vua['ataka'];
            $A3 += $vua['ataka'] * 0.2;
        } elseif ($set['side'] == 'a' && in_array($vua['tip'], [3, 6])) {
            $A1 += $vua['ataka'];
            $A3 += $vua['ataka'] * 0.2;
        } else {
            $A2 += $vua['ataka'];
        }
    }

// Суммируем атаку
    $A = $A1 + $A2 + $A3 + $A4 + $A5 + $A6;


    // Подготовка запроса с использованием PDO
    $stmt = $pdo->prepare("SELECT * FROM `voina_unit` WHERE `id_user` = :user_id ORDER BY `zaschita` DESC LIMIT :limit");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $vuk, PDO::PARAM_INT);
    $stmt->execute();

    $Z1 = $Z2 = $Z3 = $Z4 = $Z5 = $Z6 = 0;

// Обработка результатов запроса
    while ($vuz = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Улучшение: объединение условий для наёмников
        if ($user_naem_z['status'] == 1 && in_array($vuz['tip'], [1, 4])) {
            $Z4 += $vuz['zaschita'] * 0.2; // Бонус наёмник
        }
        if ($user_naem_m['status'] == 1 && in_array($vuz['tip'], [2, 5])) {
            $Z5 += $vuz['zaschita'] * 0.2; // Бонус наёмник
        }
        if ($user_naem_v['status'] == 1 && in_array($vuz['tip'], [3, 6])) {
            $Z6 += $vuz['zaschita'] * 0.2; // Бонус наёмник
        }

        // Улучшение: объединение условий для стран
        if (in_array($vuz['tip'], [1, 4]) && $set['side'] == 'g') {
            $Z1 += $vuz['zaschita'];
            $Z3 += $vuz['zaschita'] * 0.2; // Бонус Германия
        } elseif (in_array($vuz['tip'], [2, 5]) && $set['side'] == 'r') {
            $Z1 += $vuz['zaschita'];
            $Z3 += $vuz['zaschita'] * 0.2; // Бонус Россия
        } elseif (in_array($vuz['tip'], [3, 6]) && $set['side'] == 'a') {
            $Z1 += $vuz['zaschita'];
            $Z3 += $vuz['zaschita'] * 0.2; // Бонус США
        } else {
            $Z2 += $vuz['zaschita'];
        }
    }

// Защита техники
    $Z = $Z1 + $Z2 + $Z3 + $Z4 + $Z5 + $Z6;


// Запрос для получения атаки супертехники
    $stmt = $pdo->prepare("SELECT * FROM `user_superunit` WHERE `id_user` = :user_id ORDER BY `ataka`");
    $stmt->execute(['user_id' => $user_id]);
    $SA = 0;
    while ($vsua = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $SA += $vsua['ataka'] * $vsua['kol'];
    }

// Запрос для получения защиты супертехники
    $stmt = $pdo->prepare("SELECT * FROM `user_superunit` WHERE `id_user` = :user_id ORDER BY `zaschita`");
    $stmt->execute(['user_id' => $user_id]);
    $SZ = 0;
    while ($vsuz = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $SZ += $vsuz['zaschita'] * $vsuz['kol'];
    }

// Запрос для получения защиты построек
    $stmt = $pdo->prepare("SELECT * FROM `user_build` WHERE `tip` = '2' AND `id_user` = :user_id ORDER BY `bonus`");
    $stmt->execute(['user_id' => $user_id]);
    $PZ1 = 0;
    $PZ2 = 0;
    while ($vpuz = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($set['side'] == 'k') {
            $PZ1 += $vpuz['bonus'] * $vpuz['kol'];
            $PZ2 += ($vpuz['bonus'] * $vpuz['kol']) * 0.2;
        } else {
            $PZ1 += $vpuz['bonus'] * $vpuz['kol'];
        }
    }
    $PZ = $PZ1 + $PZ2;


    // Подготовка запроса
    $stmt = $pdo->prepare("SELECT * FROM `user_trofei` WHERE `id_user` = :user_id AND `id_trof` = :trof_id LIMIT 1");

// Выполнение запроса для трофея с id 7
    $stmt->execute(['user_id' => $user_id, 'trof_id' => 7]);
    $trof_a = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка статуса и времени трофея
    if ($trof_a['status'] == 1 && $trof_a['time_up'] == 0) {
        $a_trof_bonus = ($A + $SA) / 100 * $trof_a['bonus_1'];
        $ITOG_A = $A + $SA + $a_trof_bonus;
    } else {
        $a_trof_bonus = 0;
        $ITOG_A = $A + $SA;
    }

// Выполнение запроса для трофея с id 8
    $stmt->execute(['user_id' => $user_id, 'trof_id' => 8]);
    $trof_z = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка статуса и времени трофея
    if ($trof_z['status'] == 1 && $trof_z['time_up'] == 0) {
        $z_trof_bonus = ($Z + $SZ + $PZ) / 100 * $trof_z['bonus_1'];
        $ITOG_Z = $Z + $SZ + $PZ + $z_trof_bonus;
    } else {
        $z_trof_bonus = 0;
        $ITOG_Z = $Z + $SZ + $PZ;
    }


// Запрос для получения данных из таблицы user_laboratory
    $stmt = $pdo->prepare('SELECT * FROM `user_laboratory` WHERE `id_user`=:id_user AND `id_lab`=:id_lab LIMIT 1');

// Выполнение запроса для id_lab = 5
    $stmt->execute(['id_user' => $user_id, 'id_lab' => 5]);
    $user_lab_krit = $stmt->fetch(PDO::FETCH_ASSOC);

    $KRIT = $set['krit'];

// Проверка статуса и добавление 50 к значению $KRIT
    if ($user_lab_krit['status'] == 1) {
        $KRIT += 50;
    }

// Выполнение запроса для id_lab = 6
    $stmt->execute(['id_user' => $user_id, 'id_lab' => 6]);
    $user_lab_uvorot = $stmt->fetch(PDO::FETCH_ASSOC);

    $UVOROT = $set['uvorot'];

// Проверка статуса и добавление 50 к значению $UVOROT
    if ($user_lab_uvorot['status'] == 1) {
        $UVOROT += 50;
    }


// ПРОТИВНИК

// Подготовка запроса
    $stmt = $pdo->prepare("SELECT * FROM `user_set` WHERE `id` = :id LIMIT 1");
    $stmt->bindParam(':id', $set['id_vrag']);
    $stmt->execute();
    $vrag = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vrag) {


// Улучшение: Используйте тернарный оператор для более компактного кода
        $vrag_pol = ($vrag['sex'] == 'w') ? 'Девушка' : 'Парень';
        $vrag_kto = ($vrag['sex'] == 'w') ? 'ей' : 'ему';
        $vrag_kto1 = ($vrag['sex'] == 'w') ? 'её' : 'его';


        if ($vrag['hp'] >= 0) {
            $vrag['max_hp'] = filter_var($vrag['max_hp'], FILTER_VALIDATE_INT);
            if ($vrag['hp'] < $vrag['max_hp']) {
                $hp_up = intval((time() - $vrag['hp_up']) / 20);
                $hp_new = $vrag['hp'] + $hp_up;
                if ($hp_new < $vrag['max_hp']) {
                    if ($hp_up >= 1) {
                        $stmt = $pdo->prepare('UPDATE `user_set` SET `hp`=:hp, `hp_up`=:hp_up WHERE `id`=:id');
                        $stmt->execute(['hp' => $hp_new, 'hp_up' => time(), 'id' => $vrag['id']]);
                    }
                } else {
                    $stmt = $pdo->prepare('UPDATE `user_set` SET `hp`=:hp WHERE `id`=:id');
                    $stmt->execute(['hp' => $vrag['max_hp'], 'id' => $vrag['id']]);
                }
            } else {
                $stmt = $pdo->prepare('UPDATE `user_set` SET `hp`=:hp WHERE `id`=:id');
                $stmt->execute(['hp' => $vrag['max_hp'], 'id' => $vrag['id']]);
            }
        } else {
            $stmt = $pdo->prepare('UPDATE `user_set` SET `hp`=0 WHERE `id`=:id');
            $stmt->execute(['id' => $vrag['id']]);
        }
// Регенерация здоровья противника


        // Подготовка запросов
        $stmt1 = $pdo->prepare("UPDATE `user_set` SET `raiting`=`raiting`+1, `raiting_wins`=0 WHERE `id`=:id LIMIT 1");
        $stmt2 = $pdo->prepare("UPDATE `user_set` SET `raiting`=`raiting`-1, `raiting_loses`=0 WHERE `id`=:id LIMIT 1");
        $stmt3 = $pdo->prepare('UPDATE `user_set` SET `baks`=`baks`+:dohod_new, `build_up`=:time WHERE `id` = :id');

// Обновление рейтинга противника
        if ($vrag['raiting_wins'] >= 9) {
            $stmt1->execute(['id' => $vrag['id']]);
        }

        if ($vrag['raiting_loses'] >= 9) {
            $stmt2->execute(['id' => $vrag['id']]);
        }

// Выплата с доходных построек противника
        if (time() > $vrag['build_up'] and $vrag['build_up'] != 0) {
            $dohod_up = intval((time() - $vrag['build_up']) / 3600);
            $dohod_new = intval($vrag['chistaya'] * $dohod_up);
            if ($dohod_up >= 1) {
                $stmt3->execute(['dohod_new' => $dohod_new, 'time' => time(), 'id' => $vrag['id']]);
            }
        }


        // Подготовка запроса для выборки данных из таблицы user_fataliti
        $stmt = $pdo->prepare("SELECT * FROM `user_fataliti` WHERE `id_user` = :id LIMIT 1");
        $stmt->execute(['id' => $vrag['id']]);
        $fataliti_vrag = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка времени и обновление данных в таблице user_fataliti
        if (time() > $fataliti_vrag['uho1_up'] and $fataliti_vrag['uho1_up'] != 0) {
            $stmt = $pdo->prepare('UPDATE `user_fataliti` SET `uho1_kto` = "0", `uho1_up` = "0" WHERE `id_user` = :id');
            $stmt->execute(['id' => $vrag['id']]);
        }
        if (time() > $fataliti_vrag['uho2_up'] and $fataliti_vrag['uho2_up'] != 0) {
            $stmt = $pdo->prepare('UPDATE `user_fataliti` SET `uho2_kto` = "0", `uho2_up` = "0" WHERE `id_user` = :id');
            $stmt->execute(['id' => $vrag['id']]);
        }


// БОЕВАЯ СИСТЕМА ПРОТИВНИК

// Получение количества альянса
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `alliance_user` WHERE `kto` = :id_vrag OR `s_kem` = :id_vrag");
        $stmt->execute(['id_vrag' => $set['id_vrag']]);
        $vrag_alliance = $stmt->fetchColumn();

// Берем по 5 техники на каждого члена альянса
        $vrag_vuk = ($vrag_alliance + 1) * 5;

// Получение информации о наемниках
        $stmt = $pdo->prepare('SELECT * FROM `user_naemniki` WHERE `id_user` = :id_vrag AND `id_naemnik` = :id_naemnik LIMIT 1');
        $stmt->execute(['id_vrag' => $set['id_vrag'], 'id_naemnik' => 1]);
        $vrag_naem_z = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->execute(['id_vrag' => $set['id_vrag'], 'id_naemnik' => 2]);
        $vrag_naem_m = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->execute(['id_vrag' => $set['id_vrag'], 'id_naemnik' => 3]);
        $vrag_naem_v = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->execute(['id_vrag' => $set['id_vrag'], 'id_naemnik' => 5]);
        $vrag_naem_l = $stmt->fetch(PDO::FETCH_ASSOC);

// Получение информации о технике
        $stmt = $pdo->prepare("SELECT * FROM `voina_unit` WHERE `id_user` = :id_vrag ORDER BY `ataka` DESC LIMIT :vrag_vuk");
        $stmt->execute(['id_vrag' => $set['id_vrag'], 'vrag_vuk' => $vrag_vuk]);
        $data_unit_vrag_a = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $VRAG_A1 = 0;
        $VRAG_A2 = 0;
        $VRAG_A3 = 0;

        foreach ($data_unit_vrag_a as $vrag_vua) {
            if ($vrag['side'] == 'r' and ($vrag_vua['tip'] == 2 or $vrag_vua['tip'] == 5)) {
                $VRAG_A1 += $vrag_vua['ataka'];
                $VRAG_A3 += $vrag_vua['ataka'] * 0.2; // Бонус Россия
            } elseif ($vrag['side'] == 'g' and ($vrag_vua['tip'] == 1 or $vrag_vua['tip'] == 4)) {
                $VRAG_A1 += $vrag_vua['ataka'];
                $VRAG_A3 += $vrag_vua['ataka'] * 0.2; // Бонус Германия
            } elseif ($vrag['side'] == 'a' and ($vrag_vua['tip'] == 3 or $vrag_vua['tip'] == 6)) {
                $VRAG_A1 += $vrag_vua['ataka'];
                $VRAG_A3 += $vrag_vua['ataka'] * 0.2; // Бонус США
            } else {
                $VRAG_A2 += $vrag_vua['ataka'];
            }
        }

// Атака техники

// Выполнение запроса с использованием подготовленных выражений
        $stmt = $pdo->prepare("SELECT * FROM `voina_unit` WHERE `id_user` = :id_user ORDER BY `zaschita` DESC LIMIT :limit");
        $stmt->execute(['id_user' => $set['id_vrag'], 'limit' => $vrag_vuk]);

// Инициализация переменных
        $VRAG_Z1 = $VRAG_Z2 = $VRAG_Z3 = $VRAG_Z4 = $VRAG_Z5 = $VRAG_Z6 = 0;

// Обработка результатов запроса
        while ($vrag_vuz = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Обработка бонусов наёмников
            if ($vrag_naem_z['status'] == 1 && in_array($vrag_vuz['tip'], [1, 4])) {
                $VRAG_Z4 += $vrag_vuz['zaschita'] * 0.2;
            }
            if ($vrag_naem_m['status'] == 1 && in_array($vrag_vuz['tip'], [2, 5])) {
                $VRAG_Z5 += $vrag_vuz['zaschita'] * 0.2;
            }
            if ($vrag_naem_v['status'] == 1 && in_array($vrag_vuz['tip'], [3, 6])) {
                $VRAG_Z6 += $vrag_vuz['zaschita'] * 0.2;
            }

            // Обработка бонусов стран
            if ($vrag['side'] == 'r' && in_array($vrag_vuz['tip'], [2, 5])) {
                $VRAG_Z1 += $vrag_vuz['zaschita'];
                $VRAG_Z3 += $vrag_vuz['zaschita'] * 0.2;
            } elseif ($vrag['side'] == 'g' && in_array($vrag_vuz['tip'], [1, 4])) {
                $VRAG_Z1 += $vrag_vuz['zaschita'];
                $VRAG_Z3 += $vrag_vuz['zaschita'] * 0.2;
            } elseif ($vrag['side'] == 'a' && in_array($vrag_vuz['tip'], [3, 6])) {
                $VRAG_Z1 += $vrag_vuz['zaschita'];
                $VRAG_Z3 += $vrag_vuz['zaschita'] * 0.2;
            } else {
                $VRAG_Z2 += $vrag_vuz['zaschita'];
            }
        }

// Расчет общей защиты
        $VRAG_Z = $VRAG_Z1 + $VRAG_Z2 + $VRAG_Z3 + $VRAG_Z4 + $VRAG_Z5 + $VRAG_Z6;


// Запрос для получения атаки супертехники
        $stmt = $pdo->prepare("SELECT * FROM `user_superunit` WHERE `id_user` = :id_user ORDER BY `ataka`");
        $stmt->execute(['id_user' => $set['id_vrag']]);
        $VRAG_SA = 0;
        while ($vrag_vsua = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $VRAG_SA += $vrag_vsua['ataka'] * $vrag_vsua['kol'];
        } // Атака супертехники

// Запрос для получения защиты супертехники
        $stmt = $pdo->prepare("SELECT * FROM `user_superunit` WHERE `id_user` = :id_user ORDER BY `zaschita`");
        $stmt->execute(['id_user' => $set['id_vrag']]);
        $VRAG_SZ = 0;
        while ($vrag_vsuz = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $VRAG_SZ += $vrag_vsuz['zaschita'] * $vrag_vsuz['kol'];
        } // Защита супертехники


        // Подготовка запроса с использованием PDO
        $stmt = $pdo->prepare("SELECT * FROM `user_build` WHERE `tip` = '2' AND `id_user` = :id_user ORDER BY `bonus`");
        $stmt->execute(['id_user' => $set['id_vrag']]);

        $VRAG_PZ1 = 0;
        $VRAG_PZ2 = 0;

// Обработка результатов запроса
        while ($vrag_vpuz = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $VRAG_PZ1 += $vrag_vpuz['bonus'] * $vrag_vpuz['kol'];
            if ($vrag['side'] == 'k') {
                $VRAG_PZ2 += ($vrag_vpuz['bonus'] * $vrag_vpuz['kol']) * 0.2; // Бонус Казахстан
            }
        }

// Защита построек
        $VRAG_PZ = $VRAG_PZ1 + $VRAG_PZ2;

// Итоговые значения
        $VRAG_ITOG_A = $VRAG_A + $VRAG_SA;
        $VRAG_ITOG_Z = $VRAG_Z + $VRAG_SZ + $VRAG_PZ;


        // Подготовка запроса с использованием PDO
        $stmt = $pdo->prepare('SELECT * FROM `user_laboratory` WHERE `id_user`=:id_user AND `id_lab`=:id_lab LIMIT 1');

// Выполнение запроса для id_lab = 5
        $stmt->execute(['id_user' => $set['id_vrag'], 'id_lab' => 5]);
        $vrag_lab_krit = $stmt->fetch(PDO::FETCH_ASSOC);

        $VRAG_KRIT = $vrag['krit'];

        if ($vrag_lab_krit['status'] == 1) {
            $VRAG_KRIT += 50;
        }

// Выполнение запроса для id_lab = 6
        $stmt->execute(['id_user' => $set['id_vrag'], 'id_lab' => 6]);
        $vrag_lab_uvorot = $stmt->fetch(PDO::FETCH_ASSOC);

        $VRAG_UVOROT = $vrag['uvorot'];

        if ($vrag_lab_uvorot['status'] == 1) {
            $VRAG_UVOROT += 50;
        }


    }


// Фильтруем входящие данные
    $_GET['case'] = isset($_GET['case']) ? filter_var($_GET['case'], FILTER_SANITIZE_STRING) : null;

    $i1 = time() - 600;
// Обновляем данные в таблице user_set
    $stmt = $pdo->prepare("UPDATE `user_set` SET `online`='' WHERE `online`<:time");
    $stmt->execute(['time' => $i1]);

}
$stmt = $pdo->prepare("UPDATE `user_set` SET `online`='' WHERE `online`<:time");

$stmt->bindParam(':time', $i1);
$stmt->execute();