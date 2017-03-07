<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_GET['serverID'])) {
    $serverID = $_GET['serverID'];
} else {
    header ('Location: '.$_SESSION['HTTP_REFERER'].'?page=logsServers&serverID=1');
    die ();
}
if (!$servers = servers ()) {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Такого сервера не существует.</div>';
}
if (isset ($_POST['actionSubm'])) {
    $action = $db->real_escape_string ($_POST['actionSelect']);
} else {
    $action = 'co_block';
}
$q = $db->query ("SELECT * FROM `$serverID$action`");
if ($q) {
    if ($q->num_rows) {
        $actions = '';
        while ($item = $q->fetch_assoc ()) {
            if ($action == 'co_block') {
                $actions .= '['.$item['time'].']: '.$item['user'];
            }
            $actions .= '[Time] '.seDateFull ($item['time']).'&#13;&#10;';
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Действия не найдены.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Невозможно выполнить запрос к базе данных.</div>';
}
?>
<div class="pgTitle">Логи серверов</div>
<?php
echo alertsShow ();
?>
<nav class="pgMenu">
<?php
if (alertsShow () === false) {
    while ($item = $servers->fetch_assoc ()) {
?>
    <a href="/admin/?page=logsServers&serverID=////<?=$item['id']?>"><?=$item['title']?></a>
<?php
    }
}
?>
</nav>
<div class="pgBody" id="logsServers">
    <br>
    <form action="" method="post">
        <select name="actionSelect">
            <option value="co_block">Действия с блоками</option>
            <option value="co_chat">Сообщения в чате</option>
            <option value="co_command">Команды в чате</option>
            <option value="co_container">Действия с контейнерами</option>
            <option value="co_entity">Существа</option>
            <option value="co_session">Сессии</option>
            <option value="co_sign">Таблички</option>
            <option value="co_skull">Головы</option>
            <option value="co_user">Пользователи</option>
            <option value="co_username_log">Имя пользователя</option>
            <option value="co_version">Версия</option>
            <option value="co_world">Миры</option>
        </select>
        <input type="submit" name="actionSubm" class="button inline" value="Найти">
    </form>
    <br>
<?php
if (empty ($alerts)) {
?>
    <!--<textarea>////<?=$actions?></textarea>-->
<?php
}
?>
</div>