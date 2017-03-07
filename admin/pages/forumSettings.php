<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_GET['module'])) {
    $module = $_GET['module'];
} else {
    header ('Location: /admin/?page=forumSettings&module=fsCategories');
    die ();
}
?>
<div class="pgTitle">Настройки форума</div>
<nav class="pgMenu">
    <a href="/admin/?page=forumSettings&module=fsCategories">Категории</a>
    <a href="/admin/?page=forumSettings&module=fsThemes">Темы</a>
</nav>
<div class="pgBody">
<?php
try {
    $file = "./pages/modules/$module.php";
    if (!file_exists($file)) {
        throw new Exception ("<b>Ошибка!</b> Страница $module не найдена");
    }
    include $file;

} catch(Exception $e) {
    echo '<div class="alert alertDanger">'.$e->getMessage().'</div>';
}
?>
</div>