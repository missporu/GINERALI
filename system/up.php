<?php
require_once('sys.php');

if ($user) {
    echo '<div class="foot">';
    if ($set['start'] == 12) {
        echo '<div class="vverx">
           <span style="float: left;"><a href="/menu.php"><img src="img/dom.png" width="100%" alt="логотип вверху"/></a></span>
           <span style="float: center;"><img src="img/logo.png" width="53%" alt="логотип вверху"/></span>
           <span style="float: right;"><a href=""><img src="/img/obnovi.png" width="100%" alt="Обновить"></a></span>
       </div>';
    }

    echo '<div class="main">';


// Получаем уровень из базы данных
    $stmt = $pdo->prepare('SELECT lvl FROM table_name WHERE id = :id');
    $stmt->execute(['id' => $set['id']]);
    $set = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверяем уровень и вычисляем опыт
    if ($set['lvl'] < 220) {
        $exp = intval($level[$set['lvl'] + 1]);
    } else {
        $exp = false;
    }


    if ($set['exp'] >= $exp) {
        $exp_lvl = $set['exp'] - $exp;
        $stmt = $pdo->prepare("UPDATE `user_set` SET `lvl`=`lvl`+1, `exp`=:exp_lvl, `hp`=:max_hp, `mp`=:max_mp, `udar`=:max_udar, `skill`=`skill`+5 WHERE `id`=:user_id LIMIT 1");
        $stmt->execute(['exp_lvl' => $exp_lvl, 'max_hp' => $set['max_hp'], 'max_mp' => $set['max_mp'], 'max_udar' => $set['max_udar'], 'user_id' => $user_id]);

        $stmt = $pdo->prepare("UPDATE `user_superunit` SET `ataka`=`ataka`+5, `zaschita`=`zaschita`+5 WHERE `id_unit` IN (1, 2, 3, 6, 7, 8, 9) AND `id_user`=:user_id LIMIT 1");
        $stmt->execute(['user_id' => $user_id]);

        $stmt = $pdo->prepare("UPDATE `user_superunit` SET `ataka`=`ataka`, `zaschita`=`zaschita`+5 WHERE `id_unit` IN (4, 5) AND `id_user`=:user_id LIMIT 1");
        $stmt->execute(['user_id' => $user_id]);

        $stmt = $pdo->prepare("UPDATE `user_superunit` SET `ataka`=`ataka`+50, `zaschita`=`zaschita`+50 WHERE `id_unit`=10 AND `id_user`=:user_id LIMIT 1");
        $stmt->execute(['user_id' => $user_id]);

        $_SESSION['light'] = '<span class="quality-4">Вы получили новый уровень!</span></div><div class="mini-line"></div>';
    }


// Подготовка запроса
    $stmt = $pdo->prepare("SELECT * FROM `user_set` WHERE `id` = :user_id");

// Привязка параметра
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

// Выполнение запроса
    $stmt->execute();

// Получение результата
    $set = $stmt->fetch(PDO::FETCH_ASSOC);

    // Улучшенный код
$progressWidth = round(100 / ($exp / $set['exp']));
echo '<div class="exp_bar"><div class="progress" style="width: ' . $progressWidth . '%"></div></div>';

    $skill_kol = ($set['skill'] > 0) ? '#ff3434' : '#3c3';


    echo '<div class="game_info">
       <table width="100%">
           <tr>
               <td width="33%" align="center"><a href="/bank.php"><div class="head"><img src="/images/icons/baks.png" alt="Бакс"/><b><span style="color: #9c9;"><small>' . number_format($set['baks']) . '</b></span></small></div></a></td>
               <td width="33%" align="center"><a href="/bank.php?case=donat"><div class="head"><img src="/images/icons/gold.png" alt="Золото"/><b><span style="color: #ffd555;"><small>' . number_format($set['gold']) . '</b></span></small></div></a></td>
               <td width="33%" align="center"><a href="/pers.php?case=raspred"><div class="head"><img src="/images/icons/lvl.png" alt="*"/><b><span style="color: ' . $skill_kol . ';"><small>' . $set['lvl'] . '</b></span></small></div></a></td>
           </tr>
           <tr>
               <td width="33%" align="center"><a href="/hosp.php"><div class="head"><img src="/images/icons/hp.png" alt="*"/><b><span style="color: #c66;"><small>' . $set['hp'] . '</b></span></small></div></a></td>
               <td width="33%" align="center"><a href="/mission.php"><div class="head"><img src="/images/icons/mp.png" alt="*"/><b><span style="color: #9cc;"><small>' . $set['mp'] . '</b></span></small></div></a></td>
               <td width="33%" align="center"><a href="/voina.php?case=vrag"><div class="head"><img src="/images/icons/ataka.png" alt="*"/><b><span style="color: #ffffff;"><small>' . $set['udar'] . '</b></span></small></div></a></td>
           </tr>
       </table>
   </div></div>';
}

if (isset($_SESSION['err'])) {
    echo '<div class="error center"><img src="/images/icons/error.png"> ' . $_SESSION['err'] . '</div>';
    $_SESSION['err'] = NULL;
}

if (isset($_SESSION['ok'])) {
    echo '<div class="ok center"><img src="/images/icons/ok.png"> ' . $_SESSION['ok'] . '</div>';
    $_SESSION['ok'] = NULL;
}

if (isset($_SESSION['light'])) {
    echo '<div class="block_light center">' . $_SESSION['light'] . '</div>';
    $_SESSION['light'] = NULL;
}
