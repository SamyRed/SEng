<?php
/*------------Этот файл будет выполняться при любом обращнии к сайту----------*/
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="./styles.css">';
    echo "<div id='alert alertDanger'><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>";
    die ();
}

$nowTime = time ();
$clientIP = $_SERVER['REMOTE_ADDR'];

if (autorized ()) {
    $userID = intval ($_SESSION['id']);
    $q = $db->query ("UPDATE `users` SET `lastDate` = '$nowTime', `lastIP` = '$clientIP' WHERE `id` = '$userID'");
}