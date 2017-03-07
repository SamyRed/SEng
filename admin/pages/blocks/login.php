<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['authSubm'])) {
    if (!empty ($_POST['authLogin']) && !empty ($_POST['authPass'])) {
        $login = $db->real_escape_string ($_POST['authLogin']);
        $pass = $db->real_escape_string ($_POST['authPass']);
        $q = $db->query ("SELECT * FROM `users` WHERE `username` = '$login' LIMIT 1");
        if ($q) {
            if ($q->num_rows) {
                $res = $q->fetch_assoc ();
                if (password_verify($pass, $res['passHash'])) {
                    if (adminAccess ('username', $res['username']) === true) {
                        $_SESSION['id'] = $res['id'];
                        header ('Location: '.$_SERVER['HTTP_REFERER']);
                        die ();
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к панели администратора.</div>';
                        var_dump (adminAccess ('username', $res['username']));
                        die ();
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы ввели неправильный пароль.</div>';
                }
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Пользователь с таким именем не найден</div>';
            }
        } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Невозможно сделать выборку из БД. Обратитесь к администратору.</div>';
            }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели логин, или пароль.</div>';
    }
}
if (!empty ($alerts)) {
    foreach ($alerts as $item) {
        echo $item;
    }
}
?>
<!DOCKTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../styles/main.css">
    </head>
    <body>
        <div id="authContainer">
            <div id="authBody">
                <h3>Необходима авторизация!</h3>
                <form action="" method="post">
                    <label>Логин:</label>
                    <input type="text" name="authLogin" class="inputText">
                    <label>Пароль:</label>
                    <input type="password" name="authPass" class="inputPassword">
                    <br>
                    <input type="submit" name="authSubm" class="button">
                </form>
            </div>
        </div>
    </body>
</html>