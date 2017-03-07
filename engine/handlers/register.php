<?php
define ('seng', true);

require_once '../configDB.php';
require_once '../config.php';
require_once '../functions.php';

if (isset ($_POST['regSubmit'])) {
    if (empty ($_POST['regUname'])) {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Вы не ввели имя пользователя.</div>';
    } else {
        $username = $db->real_escape_string ($_POST['regUname']);
        if (strlen ($username) < 3) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> имя пользователя не должно быть короче 3-х символов.</div>';
        }
        if (strlen ($username) > 30) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Имя пользователя не может быть длиннее 30-ти символов</div>';
        }
        if (!preg_match_all ('~^[a-zA-Z0-9-_]+$~i', $username)) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Вы ввели недопустимое имя пользователя. Можно использовать только буквы латинского алфавита, цифры, а так же тире и символ нижнего подчёркивания.</div>';
        }
        $q = $db->query ("SELECT * FROM `users` WHERE `username` = '$username'");
        if ($q) {
            if ($q->num_rows) {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Это имя уже занято. Придумайте другое.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Невозможно сделать выборку логина из базы данных. Обратитесь к администратору.</div>';
        }
    }
    if (empty ($_POST['regEmail'])) {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Вы не ввели E-mail.</div>';
    } else {
        $email = $db->real_escape_string ($_POST['regEmail']);
        if (!filter_var ($email, FILTER_VALIDATE_EMAIL)) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Вы ввели некорректный E-mail. Убедитесь в правильности ввода.</div>';
        }
        $q = $db->query ("SELECT * FROM `users` WHERE `email` = '$email'");
        if ($q) {
            if ($q->num_rows) {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Этот E-mail уже занят.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Невозможно сделать выборку E-mail из базы данных. Обратитесь к администратору.</div>';
        }
    }
    if (empty ($_POST['regPass'])) {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Вы не ввели пароль.</div>';
    } else {
        $pass = $db->real_escape_string ($_POST['regPass']);
        if (strlen ($pass) < 6) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Пароль не может быть короче 6-ти символов.</div>';
        }
        if (strlen ($pass) > 50) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Пароль не может быть длиннее 50-ти символов.</div>';
        }
        if (!preg_match_all ('~^[a-z0-9-_ \.]+$~i', $pass)) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Вы ввели недопустимый пароль. Можно использовать только буквы латинского алфавита, цифры, а так же тире и символ нижнего подчёркивания, пробел и точку.</div>';
        }
    }
    if (empty ($_POST['regRepass'])) {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Вы не ввели повтор пароля.</div>';
    } else {
        $repass = $db->real_escape_string ($_POST['regRepass']);
        if ($pass != $repass) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Пароли не совпадают.</div>';
        }
    }
    if (!isset ($_POST['regCheckbox']) || $_POST['regCheckbox'] != 'on') {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка регистрации!</b> Подтвердите, пожалуйста, ваше согласие с правилами проекта.</div>';
    }
    if (!empty ($alerts)) {
        $_SESSION['alerts'] = $alerts;
        header ('Location: /?page=register');
        die ();
    } else {
        $passHash = password_hash($pass, PASSWORD_DEFAULT);
        $q = $db->query ("INSERT INTO `users` VALUES (NULL, '$username', '$passHash', '$email', 0, ".time ().", '".$_SERVER['REMOTE_ADDR']."', ".time ().", '".$_SERVER['REMOTE_ADDR']."', '5')") or die ($db->error);
        $userId = $db->insert_id;
        $q = $db->query ("INSERT INTO `money` VALUES (NULL, '$username', '0')") or die ($db->error);
        $q = $db->query ("INSERT INTO `coins` VALUES (NULL, '$username', '0')") or die ($db->error);
        $servers = servers ();
        while ($item = $servers->fetch_assoc ()) {
            $q = $db->query ("INSERT INTO `coins".$item['id']."` VALUES (NULL, '$username', '0', '0')") or die ($db->error);
            $q = $db->query ("INSERT INTO `permissions".$item['id']."` (`name`, `type`, `permission`, `world`, `value`) VALUES ('".uuid ('username', $username)."', '1', 'name', '', '$username')") or die ($db->error);
        }
        $q = $db->query ("SELECT `passHash` FROM `users` WHERE `id` = '$userId'");
        $user = $q->fetch_assoc ();
$message = "
Здравствуйте, $username!

Если Вам пришло это письмо - значит вы зарегистрировались на сайте ".SITETITLE.". Если Вы этого не делали - проигнорируйте, пожалуйста это письмо.

Для завершения регистрации перейдите по ссылке: ".SITEURL."?page=emailVerify&s=true&u=".$username."&h=".md5 ($user['passHash'])."

Благодарим за регистрацию! 

Администрания сайта ".SITETITLE."
";
        mail ($email, 'support@seng.kl.com.ua', $message);
        header ('Location: /?page=emailVerify&s=false');
        die ();
    }
} else {
    die ('Request is empty!');
}
?>