<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
?>
<div class="pgTitle">Логи сайта</div>
<?php
echo alertsShow ();
?>
<div class="pgBody">
    <nav class="pgMenu">
        
    </nav>
</div>