<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
echo alertsShow ();
if (autorized ()) {
?>
<div class="pgTitle">Покупка статуса</div>
<div class="pgBlockBody">
    <center>
        <i>Посмотреть список доступных статусов вы можете на странице "<a href="/?page=donate">Платные услуги</a>"</i>
        <select id="pc-buy-satus-srv">
            <option value="0">Выберите сервер</option>
            <option value="lowtech">Low-Tech</option>
            <option value="hardtech">Hard-Tech</option>
            <option value="magictech">Magical-Tech</option>
            <option value="skytech">Sky-Tech</option>
        </select>
        <select id="pc-buy-satus-stt">
            <option value="0">Выберите статус</option>
            <option value="vip">VIP</option>
            <option value="ultra">Ultra</option>
        </select>
        <br>
        <i id="pc-buy-status-cngd">Не выбрано</i>
        <br>
        <button type="button" id="pc-buy-status-sbm">Купить</button>
    </center>
</div>
<div class="pgTitle">Мои статусы</div>
<div class="pgBlockBody">
    <center>
        <div class="pc-stts-title">Low-Tech</div>
            <div class="pc-stts-stt">Игрок<i>x</i></div>
        <div class="pc-stts-title">Hard-Tech</div>
            <div class="pc-stts-stt">Администратор<i>x</i></div>
        <div class="pc-stts-title">Magical-Tech</div>
            <div class="pc-stts-stt">VIP<i title="Удалить">x</i></div>
        <div class="pc-stts-title">Sky-Tech</div>
            <div class="pc-stts-stt">Ultra<i title="Удалить">x</i></div>
    </center>
</div>
<?php
}
?>