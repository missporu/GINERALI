<?php
if ($user){
echo '<small><center><a href="rules.php"><span style="color: #999;">Правила игры</a> | <a href="faq.php"><span style="color: #999;">Помощь</a> | <a href="http://m.vk.com/voynushka.mobi"><span style="color: #999;">ВКонтакте</a></small><div class="separ"></div><span class="small grey">' . round(microtime(1) - $timeregen, 4) . ' сек., ' . date("H:i:s") . ' | <a href="online.php"><span style="color: #999;">Онлайн: ' . mysql_result(mysql_query("SELECT COUNT(*) FROM `user_set` WHERE `online`> '" . (time() - 600) . "'"), 0) . '</a></div>';

?>

<center>

    <script type="text/javascript" src="http://mobtop.ru/c/107254.js"></script>
    <noscript><a href="http://mobtop.ru/in/107254"><img src="http://mobtop.ru/107254.gif"
                                                        alt="MobTop.Ru - рейтинг мобильных сайтов"/></a></noscript>
    </a>

    <br/>

    <?

    }else{
    echo '<center><div class="separ"></div><span class="small grey">' . round(microtime(1) - $timeregen, 4) . ' сек., ' . date("H:i:s") . ' | Онлайн: ' . mysql_result(mysql_query("SELECT COUNT(*) FROM `user_set` WHERE `online`> '" . (time() - 600) . "'"), 0) . '</span></div><br>';

    ?>

    <center>

        <script type="text/javascript" src="http://mobtop.ru/c/107254.js"></script>
        <noscript><a href="http://mobtop.ru/in/107254"><img src="http://mobtop.ru/107254.gif"
                                                            alt="MobTop.Ru - рейтинг мобильных сайтов"/></a></noscript>
        </a>

        <br/><?php

        }

?>


