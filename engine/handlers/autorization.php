<?php
define ('seng', true);

require_once '../configDB.php';
require_once '../config.php';
require_once '../functions.php';

if (isset ($_POST['authSubm'])) {
    if (!empty ($_POST['authLogin'])) {
        $login = $db->real_escape_string ($_POST['authLogin']);
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка авторизации!</b> Вы не ввели имя пользователя.</div>';
    }
    if (!empty ($_POST['authPass'])) {
        $pass = $db->real_escape_string ($_POST['authPass']);
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка авторизации!</b> Вы не ввели пароль.</div>';
    }
    if (alertsShow () === false) {
        $q = $db->query ("SELECT * FROM `users` WHERE `username` = '$login'");
        if ($q) {
            if ($q->num_rows) {
                $user = $q->fetch_assoc ();
                if ($user['emailCheck'] == 1) {
                    if (password_needs_rehash($user['passHash'], PASSWORD_DEFAULT)) {
                        $passHash = password_hash ($pass, PASSWORD_DEFAULT);
                        $q = $db->query ("UPDATE `users` SET `passHash` = '$passHash' WHERE `id` = '".$user['id']."'");
                    }
                    if (password_verify($pass, $user['passHash'])) {
                        $_SESSION['id'] = $user['id'];
                        header ('Location: '.$_SERVER['HTTP_REFERER']);
                        die ();
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка авторизации!</b> Вы ввели неправильный пароль.</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка авторизации!</b> Сначала подтвердите адрес электронной почты.</div>';
                }
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка авторизации!</b> Такое имя пользователя не найдено в системе.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка авторизации!</b> Не удалось выполнить запрос к базе данных. Обратитесь к администратору.</div>';
        }
    }
    if (alertsShow () !== false) {
        $_SESSION['alerts'] = $alerts;
        header ('location: '.$_SERVER['HTTP_REFERER']);
        die ();
    }
}
?>