<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['deleteCategory'])) {
    if (!empty ($_POST['categoryID'])) {
        if ($categoryDelID = intval ($_POST['categoryID'])) {
            $q = $db->query ("SELECT * FROM `forumCategories` WHERE `id` = '$categoryDelID'");
            if ($q) {
                if ($q->num_rows) {
                    $res = $q->fetch_assoc ();
                    $q = $db->query ("DELETE FROM `forumCategories` WHERE `id` = '$categoryDelID'");
                    if ($q) {
                        $_SESSION['alerts'][] = '<div class="alert alertSuccess"><b>Выполнено!</b> Категория "<b>'.$res['title'].'</b>" удалена.</div>';
                        header ('Location: /admin/?page=forumSettings&module=fsCategories');
                        die ();
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Категория с таким идентификатором не найдена.</div>';
                }
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор категории должен быть числом.</div>';
        }
    }
}
$q = $db->query ("SELECT * FROM `forumCategories`");
if ($q) {
    if ($q->num_rows) {
        $cats = $q;
    } else {
        $alerts[] = '<div class="alert alertSuccess">Категорий пока нет. <a href="/admin/?page=forumCategoryAdd">[Добавить]</a>.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Невозможно выполнить запрос к базе данных.</div>';
}
?>
<div class="pgTitle">Категории</div>
<div class="pgBody" id="fsCategories">
    <form action="/admin/?page=forumCategoryAdd" method="post">
        <input type="submit" class="button inline" value="Добавить">
    </form>
<?php
echo alertsShow ();
if (isset ($cats) && !empty ($cats)) {
?>
    <table width="100%">
        <tr>
            <td width="5%">id</td>
            <td width="25%">Название</td>
            <td width="25%">Описание</td>
            <td width="45%"></div>
        </tr>
<?php
$i = 0;
while ($item = $cats->fetch_assoc ()) {
    if ($i%2 == 0) {
        $color = ' class="tableLight"';
    } else {
        $color = ' class="tableDark"';
    }
    if (strlen ($item['description']) >= 25) {
        $desc = mb_substr ($item['description'], 0, 20).'...';
    } else {
        $desc = $item['description'];
    }
?>
        <tr<?=$color?>>
            <td><?=$item['id']?></td>
            <td><?=$item['title']?></td>
            <td title="<?=$item['description']?>"><?=$desc?></td>
            <td>
                <a href="/admin/?page=forumCategoryEdit&id=<?=$item['id']?>" class="button inline">Редактировать</a>
                <form action="" method="post" class="inline">
                    <input type="hidden" value="<?=$item['id']?>" name="categoryID">
                    <input type="submit" value="Удалить" class="button inline" name="deleteCategory">
                </form>
            </td>
        </tr>
<?php
$i++;
}
?>
    </table>
<?php
}
?>
</div>