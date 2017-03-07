<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['logout'])) {
    unset ($_SESSION['id']);
    header ('Location: '.$_SERVER['HTTP_REFERER']);
    die ();
}
$menuItems = [
    ['Система', '/admin/?page=system', 'system'],
    ['Пользователи', '/admin/?page=users&pn=1', 'user'],
    ['Группы', '/admin/?page=groups', 'groups'],
    ['Логи сайта', '/admin/?page=logsSite', 'logsSite'],
    ['Логи серверов', '/admin/?page=logsServers&serverID=1', 'logsServer'],
    ['Настройки форума', '/admin/?page=forumSettings&module=fsCategories', 'forumSettings'],
    ['Новости', '/admin/?page=news&pn=1', 'news'],
    ['Правила', '/admin/?page=rules', 'rules'],
    ['[На сайт]', '/?page=main&pn=1', '']
];
?>
<nav id="topMenu">
    <form action="" method="post">
<?php
foreach ($menuItems as $item) {
    //echo $item[1].'<br>'.$_SERVER['REQUEST_URI'].'<br>';
    if ($item[2] == $_GET['page']) {
        echo '<a href="'.$item[1].'" class="menuActive">'.$item[0].'</a>';
    } else {
        echo '<a href="'.$item[1].'">'.$item[0].'</a>';
    }
}
?>
        <input type="submit" value="[Выход]" name="logout">
    </form>
</nav>