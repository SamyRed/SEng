<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (autorized ()) {
?>

<?php
} else {
    $alerts[] ='<div class="alert alertDanger"><b>Ошибка!</b> К этой странице есть доступ только у авторизованых пользователей.</div>';
}
echo alertsShow ();
?>