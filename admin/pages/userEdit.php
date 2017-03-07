<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="./styles.css">';
    echo "<div id='alert alertDanger'><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>";
    die ();
}
if (isset ($_GET['id'])) {
    if ($idGet = intval ($_GET['id'])) {
        if (users ('id', $idGet) === false) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Пользователь с таким идентификатором не найден!</div>';
        } else {
            $id = $idGet;
            if (isset ($_POST['submit'])) {
                if (!empty ($_POST['username'])) {
                    $username = $db->real_escape_string ($_POST['username']);
                    $q = $db->query ("SELECT * FROM `users` WHERE `username` = '$username' AND `id` <> '$id'");
                    if ($q) {
                        if ($q->num_rows) {
                            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Имя пользователя "<b>'.$username.'</b>" занято.</div>';
                        }
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели логин.</div>';
                }
                if (!empty ($_POST['email'])) {
                    $email = $db->real_escape_string ($_POST['email']);
                    $q = $db->query ("SELECT * FROM `users` WHERE `email` = '$email' AND `id` <> '$id'");
                    if ($q) {
                        if ($q->num_rows) {
                            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> E-mail "<b>'.$username.'</b>" занят.</div>';
                        }
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели E-mail.</div>';
                }
                if (!empty ($_POST['money'])) {
                    $money = $_POST['money'];
                    if (is_numeric ($money)) {
                        $q = $db->query ("UPDATE `money` SET `balance` = '$money' WHERE `id` = $id");
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Количество денег должно быть числом.</div>';
                    }
                }
                if (!empty ($_POST['coins'])) {
                    $coins = $_POST['coins'];
                    if (is_numeric ($coins)) {
                        $q = $db->query ("UPDATE `coins` SET `balance` = '$coins' WHERE `id` = $id");
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Количество монет должно быть числом.</div>';
                    }
                }
                if (!empty ($_POST['coinsSrvs'])) {
                    foreach ($_POST['coinsSrvs'] as $key => $item) {
                        if (!empty ($item)) {
                            if (is_numeric ($item)) {
                                $q = $db->query ("UPDATE `".$key."coins` SET `balance` = '$item' WHERE `id` = $id");
                            } else {
                                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Количество монет для сервера с id = "<b>'.$key.'</b>" должно быть числом.</div>';
                            }
                        }
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Отсутствует переменная coinsSrvs.</div>';
                }
                if (!empty ($_POST['pass'])) {
                    if (!empty ($_POST['repass'])) {
                        $pass = $db->real_escape_string ($_POST['pass']);
                        $repass = $db->real_escape_string ($_POST['repass']);
                        if (strlen ($pass) < 6) {
                            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Пароль не может быть короче 6-ти символов.</div>';
                        }
                        if (strlen ($pass) > 50) {
                            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Пароль не может быть длиннее 50-ти символов.</div>';
                        }
                        if (!preg_match_all ('~^[a-z0-9-_ \.]+$~i', $pass)) {
                            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы ввели недопустимый пароль. Можно использовать только буквы латинского алфавита, цифры, а так же тире и символ нижнего подчёркивания, пробел и точку.</div>';
                        }
                        if ($pass != $repass) {
                            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Пароли не совпадают.</div>';
                        }
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели повтор пароля.</div>';
                    }
                }
                if (alertsShow() === false) {
                    if (isset ($pass)) {
                        $passHash = password_hash ($pass, PASSWORD_DEFAULT);
                        $q = $db->query ("UPDATE `users` SET `username` = '$username', `email` = '$email', `passHash` = '$passHash'");
                        
                    } else {
                        $q = $db->query ("UPDATE `users` SET `username` = '$username', `email` = '$email'");
                    }
                    $_SESSION['alerts'][] = '<div class="alert alertSuccess"><b>Выполнено!</b> Информаия изменена.</div>';
                    header ('Location: '.$_SERVER['HTTP_REFERER']);
                    die ();
                }
            }
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор пользователя должен быть числом.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор пользователя не найден.</div>';
}
?>
<div class="pgTitle">Редактирование информации</div>
<?php
echo alertsShow ();
if (isset ($id) && !empty ($id)) {
?>
<div class="pgBody" id="userEdit">
    <form action="" method="post">
        <table width="100%">
            <tr>
                <td colspan="2">
                    <hr>
                    <h3>Основная информация:</h3>
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">Идентификатор пользователя:</td>
                <td class="tableRight">
                    <?=$id?>
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">UUID (Уникальное имя):</td>
                <td class="tableRight">
                    <?=uuid (users ('id', $id)['username']);?>
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">Группа на сайте:</td>
                <td class="tableRight">
                    <?=groups ('id', users ('id', $id)['groupID'])['tBefore'].groups ('id', users ('id', $id)['groupID'])['title'].groups ('id', users ('id', $id)['groupID'])['tAfter']?>
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">Регистрационный IP:</td>
                <td class="tableRight">
                    <?=users ('id', $id)['regIP']?>
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">Последний используемый IP:</td>
                <td class="tableRight">
                    <?=users ('id', $id)['lastIP']?>
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">Дата регистрации:</td>
                <td class="tableRight">
                    <?=seDateFull (users ('id', $id)['regDate'])?>
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">Дата последней активности:</td>
                <td class="tableRight">
                    <?=seDateFull (users ('id', $id)['lastDate'])?>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Никнейм:</td>
                <td class="tableRight">
                    <input type="text" value="<?=users ('id', $id)['username']?>" name="username" class="inputText">
                </td>
            </tr>
            <tr>
                <td class="tableLeft">E-mail:</td>
                <td class="tableRight">
                    <input type="text" value="<?=users ('id', $id)['email']?>" name="email" class="inputText">
<?php
                if (users ('id', $id)['emailCheck'] == 1) {
                    echo '<span style="color: green;">Подтверждён</span>';
                } else {
                    echo '<span style="color: red;">Не подтверждён</span>';
                }
?>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Изменить пароль:</td>
                <td class="tableRight">
                    <label><input type="password" name="pass" class="inputPassword inline"> - пароль</label>
                    <br>
                    <label><input type="password" name="repass" class="inputPassword inline"> - ещё раз</label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                    <h3>Баланс:</h3>
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">Сайт:</td>
                <td class="tableRight">
                    <input type="text" value="<?=money ('id', $id)['balance']?>" name="money" class="inputText inline"> р.
                    <br>
                    <input type="text" value="<?=coins ('username', users ('id', $id)['username'])['balance']?>" name="coins" class="inputText inline"> м.
                </td>
            </tr>
            <tr>
                <td width="65%" class="tableLeft">Сервера:</td>
                <td class="tableRight">
<?php
            $servers = servers ();
            while ($item = $servers->fetch_assoc ()) {
?>
                    <?=$item['title']?>:
                    <br>
                    <input type="text" value="<?=coins ('username', users ('id', $id)['username'], $item['id'])['balance']?>" name="coinsSrvs[<?=$item['id']?>]" class="inputText inline"> м.
                    <br>
<?php
            }
?>
                </td>
            </tr>
        </table>
        <hr><br>
        <input type="submit" name="submit" class="button">
        <br>
    </form>
</div>
<?php
}
?>