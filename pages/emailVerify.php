<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (autorized () || !isset ($_GET['s']) || empty ($_GET['s'])) {
    header ('Location: /');
    die ();
}
if ($_GET['s'] == 'false') {
    echo '<div class="alert alertSuccess"><b>Вы успешно зарегистрированы!</b> Осталось только активировать Ваш E-mail. Для этого перейдите по ссылке в письме, которое мы отправили на ваш адрес электорнной почты.</div>';
} else {
    if (!isset ($_GET['u']) || empty ($_GET['u'])) {
        $alerts[] = 'Username empty!';
    } else {
        $username = $db->real_escape_string ($_GET['u']);
        $q = $db->query ("SELECT * FROM `users` WHERE `username` = '$username'");
        if ($q) {
            if ($q->num_rows) {
                $q = $db->query ("SELECT * FROM `users` WHERE `username` = '$username' AND `emailCheck` = 0");
                if ($q) {
                    if ($q->num_rows) {
                        if (!isset ($_GET['h']) || empty ($_GET['h'])) {
                            $alerts[] = 'Hash empty!';
                        } else {
                            $res = $q->fetch_assoc ();
                            $h = $_GET['h'];
                            $hash = md5 ($res['passHash']);
                            if ($hash == $h) {
                                $q = $db->query ("UPDATE `users` SET `emailCheck` = 1 WHERE `username` = '$username'");
                                if ($q) {
                                    echo '<div class="alert alertSuccess"><b>Поздравляем!</b> Регистрация завершена, и теперь Вы полноценный участник нашего сообщества.</div>';
                                    $_SESSION['id'] = $res['id'];
                                } else {
                                    $alerts[] = 'DB error!';
                                }
                            } else {
                                $alerts[] = '<div class="alert alertDanger">Hash is not avaliable!<br>'.$res['passHash'].'</div>';
                            }
                        }
                    } else {
                        $alerts[] = '<div class="alert alertDanger">Email is confirmed before!</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger">DB error!</div>';
                }
            } else {
                $alerts[] = '<div class="alert alertDanger">User not finded!</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger">DB error!</div>';
        }
    }
}
echo alertsShow ();