<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="./styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_GET['id'])) {
    if ($id = intval ($_GET['id'])) {
        $q = $db->query ("SELECT * FROM `forumCategories` WHERE `id` = '$id' LIMIT 1");
        if ($q) {
            if ($q->num_rows) {
                $category = $q->fetch_assoc ();
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Категория с таким идентификатором не найдена.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Невозможно выполнить запрос к базе данных.</div>';
        }
        if (isset ($_POST['submit'])) {
            $title = $db->real_escape_string ($_POST['title']);
            $description = $db->real_escape_string ($_POST['description']);
            $q = $db->query ("UPDATE `forumCategories` SET `title` = '$title', `description` = '$description' WHERE `id` = '$id'");
            $_SESSION['alerts'][] = '<div class="alert alertSuccess"><b>Выполнено!</b> Категория изменена.</div>';
            header ('Location: /admin/?page=forumSettings&module=fsCategories');
            die ();
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор категории должен быть числом!</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор категории не найден.</div>';
}
?>
<div class="pgTitle">Редактирование категории</div>
<?php
echo alertsShow ();
if (empty ($alerts)) {
?>
<div class="pgBody" id="forumCategoryEdit">
    <form action="" method="post">
        <label>Название: <input type="text" name="title" value="<?=htmlspecialchars ($category['title'])?>"></label>
        <br>
        <textarea name="description"><?=htmlspecialchars ($category['description'])?></textarea>
        <input type="submit" name="submit" class="button inline" value="Изменить">
    </form>
</div>
<?php
}