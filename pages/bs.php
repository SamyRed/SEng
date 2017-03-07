<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (autorized ()) {
$menuItems = [
    ['Магазин блоков', '/?page=bs&mode=bsShop', 'bsShop'],
    ['Склад', '/?page=bs&mode=bsStorage', 'bsStorage']
];
?>
<nav class="pgMenu">
<?php
foreach ($menuItems as $item) {
    if ($item[2] == $_GET['mode']) {
        echo '<a href="'.$item[1].'" class="menuActive">'.$item[0].'</a>';
    } else {
        echo '<a href="'.$item[1].'">'.$item[0].'</a>';
    }
}
?>
</nav>
<div class="pgBody">
<?php
echo alertsShow ();
?>
<?php
if (isset ($_GET['mode'])) {
    $mode = $_GET['mode'];
} else {
    $mode = 'bsShop';
}
try {
    $file = './pages/modules/'.$mode.'.php';
    if (!file_exists($file)) {
        throw new Exception ('<b>Ошибка!</b> Модуль "<b>'.$mode.'</b>" не найден.');
    }
    include $file;
} catch(Exception $e) {
?>
    <div class="alert alertDanger"><?=$e->getMessage();?></div>
<?php
}
?>
</div>
<?php
} else {
?>
<div class="alert alertDanger"><b>Ошибка!</b> К этой странице есть доступ только у авторизованых пользователей.</div>
<?php
}
?>