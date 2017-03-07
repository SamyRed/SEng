<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="./styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['submit'])) {
    if (!empty ($_POST['theme'])) {
        $title = $db->real_escape_string ($_POST['theme']);
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели тему для новости.</div>';
    }
    if (!empty ($_POST['shortNew'])) {
        $shortNew = $db->real_escape_string ($_POST['shortNew']);
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели краткое описание.</div>';
    }
    if (!empty ($_POST['fullNew'])) {
        $fullNew = $db->real_escape_string ($_POST['fullNew']);
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Вы не ввели полную новость.</div>';
    }
    if (!empty ($_POST['tags'])) {
        $tags = serialize (explode (',', $db->real_escape_string ($_POST['tags'])));
    } else {
        $tags = '';
    }
    if (empty ($alerts)) {
        $author = user ('username');
        $date = time ();
        $q = $db->query ("INSERT INTO `news` VALUES (NULL, '$title', '$shortNew', '$fullNew', '$author', '$date', '$tags')");
        if (!$q) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
        } else {
            $alerts[] = '<div class="alert alertSuccess"><b>Выполнено!<b> Новость успешно добавлена. <a href="/admin/?page=newsAdd">[Добавить ещё].</a></div>';
        }
    }
}
?>
<div class="pgTitle">Добавление новости</div>
<?php
echo alertsShow ();
if (empty ($alerts)) {
?>
<div class="pgBody" id="adminNews">
    <form action="/admin/?page=newsAdd" method="post">
        <label>Тема: <input type="text" name="theme"></label>
        <br>
        <input type="file" name="image">
        <textarea name="shortNew"></textarea>
        <br><br>
        <textarea name="fullNew"></textarea>
        <label>Теги: <input type="text" name="tags"></label>
        <input type="submit" name="submit" class="button inline" value="Добавить">
    </form>
</div>
<?php
}
?>