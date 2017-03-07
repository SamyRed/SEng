<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_GET['id'])) {
    if ($id = intval ($_GET['id'])) {
        $q = $db->query ("SELECT * FROM `news` WHERE `id` = '$id' LIMIT 1");
        if ($q) {
            if ($q->num_rows) {
                $new = $q->fetch_assoc ();
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Новость с таким идентификатором не найдена.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Невозможно выполнить запрос к базе данных.</div>';
        }
        if (isset ($_POST['newEditSubmit'])) {
            $title = $db->real_escape_string ($_POST['title']);
            $shortNew = $db->real_escape_string ($_POST['shortNew']);
            $fullNew = $db->real_escape_string ($_POST['fullNew']);
            $tags = serialize (explode (',', $db->real_escape_string ($_POST['tags'])));
            $q = $db->query ("UPDATE `news` SET `title` = '$title', `shortNew` = '$shortNew', `fullNew` = '$fullNew', `tags` = '$tags' WHERE `id` = '$id'");
            if ($q) {
                $_SESSION['alerts'][] = '<div class="alert alertSuccess"><b>Выполнено!</b> Новость <b>"'.$title.'"</b> изменена.</div>';
                header ('Location: /admin/?page=news');
                die ();
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Невозможно выполнить запрос к базе данных.</div>';
            }
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор группы должен быть числом!</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор новости не найден.</div>';
}
?>
<div class="pgTitle">Редактирование новости</div>
<div class="pgBody" id="newEdit">
<?php
echo alertsShow ();
?>
    <form action="" method="post">
        <label>Тема: <input type="text" name="title" value="<?=htmlspecialchars ($new['title'])?>"></label>
        <br>
        <input type="file" name="image">
        <textarea name="shortNew"><?=htmlspecialchars ($new['shortNew'])?></textarea>
        <br><br>
        <textarea name="fullNew"><?=htmlspecialchars ($new['fullNew'])?></textarea>
        <label>Теги: <input type="text" name="tags" value="<?=implode (', ', unserialize($new['tags']))?>"></label>
        <input type="submit" name="newEditSubmit" class="button inline" value="Редактированть">
    </form>
</div>