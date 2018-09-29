<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo "<div id='alert alertDanger'><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>";
    die ();
}
 
try {
     $db = new mysqli('localhost', 'root', '', 'seng');
} catch (Exception $e ) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    die ("<div id='alert alertDanger'><b>Ошибка!</b> ".$e->getMessage ()."!</div>");
}
