<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_GET['u'])) {
    if (users ('username', $_GET['u']) === false) {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Пользователь с таким никнеймом не найден!</div>';
    } else {
        $user = users ('username', $_GET['u']);
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор пользователя не найден.</div>';
}
?>
<?php
echo alertsShow();
if (alertsShow() === false) {
?>
<div class="pgTitle"><?=$user['username']?></div>
<div class="pgBody">
    
</div>
<?php
}
?>