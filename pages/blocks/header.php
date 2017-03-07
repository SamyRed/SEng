<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
?>
<div class="background"></div>
<header>
    <h1 id="siteTitle"><?=$config['siteTitle']?></h1>
    <h4 id="siteDesc"><?=$config['siteDesc']?></h4>
</header>