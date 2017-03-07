<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="./styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['submit'])) {
    if (!empty ($_POST['title'])) {
        $title = $db->real_escape_string ($_POST['title']);
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели название категории.</div>';
    }
    if (!empty ($_POST['description'])) {
        $description = $db->real_escape_string ($_POST['description']);
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели описание категории.</div>';
    }
    if (empty ($alerts)) {
        $q = $db->query ("INSERT INTO `forumCategories` VALUES (NULL, '$title', '$description')");
        if (!$q) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
        } else {
            $_SESSION['alerts'][] = '<div class="alert alertSuccess"><b>Выполнено!</b> Категория успешно добавлена.</a></div>';
            header ('Location: /admin/?page=forumSettings&module=fsCategories');
            die ();
        }
    }
}
?>
<div class="pgTitle">Создание категории</div>
<?php
echo alertsShow ();
if (empty ($alerts)) {
?>
<div class="pgBody" id="forumCategoryAdd">
    <form action="" method="post">
        <label>Название: <input type="text" name="title"></label>
        <br>
        <textarea name="description"></textarea>
        <input type="submit" name="submit" class="button inline" value="Добавить">
    </form>
</div>
<?php
}
?>