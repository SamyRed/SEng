<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
echo alertsShow ();
if (autorized ()) {
?>
<div class="pgTitle">Префикс</div>
<div class="pgBlockBody">
    <center>
        <select id="pc-prefix-srv">
            <option value="0">Выберите сервер</option>
            <option value="lowtech">Low-Tech</option>
            <option value="hardtech">Hard-Tech</option>
            <option value="magictech">Magical-Tech</option>
            <option value="skytech">Sky-Tech</option>
        </select>
        <input type="text" id="pc-prefix-txt">
        <button type="button" id="pc-prefix-sbm">Обменять</button>
    </center>
</div>
<div class="pgTitle">Сундуки</div>
<div class="pgBlockBody">
    <i>За голосование в топах Вы можете получить сундучки. Из каждого сундучка может выпасть что-то ценное, или не очень.</i>
    <div class="row pc-chests">
        <div class="pc-chests-item col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="item"></div>
        </div>
        <div class="pc-chests-item col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="item"></div>
        </div>
        <div class="pc-chests-item col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="item"></div>
        </div>
        <div class="pc-chests-item col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="item"></div>
        </div>
        <div class="pc-chests-item col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="item"></div>
        </div>
        <div class="pc-chests-item col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="item"></div>
        </div>
    </div>
</div>
<?php
}
?>