<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
$menuItems = [
    ['Главная', '/?page=main&pn=1', 'main'],
    ['Форум', '/forum/?page=mainForum', 'mainForum'],
    ['Донат', '/?page=donate', 'donate'],
    ['Правила', '/?page=rules', 'rules'],
    ['Сервера', '/?page=servers', 'servers'],
    ['Личный кабинет', '/?page=pc&mode=pcMoney', 'pc'],
    ['Магазин блоков', '/?page=bs&mode=bsShop', 'bs'],
    ['Скачать', '/?page=download', 'download']
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
    </form>
</nav>