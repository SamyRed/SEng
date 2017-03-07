<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
echo alertsShow ();
if (autorized ()) {
?>
<div class="pgTitle">Мой склад</div>
<div class="pgBlockBody">
    <center>
        <select id="bsStorageServer">
            <option value="0">Выберите сервер</option>
            <option value="lowtech">Low-Tech</option>
            <option value="hardtech">Hard-Tech</option>
            <option value="magictech">Magical-Tech</option>
            <option value="skytech">Sky-Tech</option>
        </select>
    </center>
</div>
<?php
}
?>