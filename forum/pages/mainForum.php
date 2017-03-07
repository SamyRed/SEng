<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
$q = $db->query ("SELECT * FROM `forumCategories`");
if ($q) {
    if ($q->num_rows) {
        $cats = $q;
    } else {
        $alerts[] = '<div class="alert alertSuccess">Категорий пока нет.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
}
$q = $db->query ("SELECT * FROM `forumThemes` WHERE `level` = 1");
if ($q) {
    if ($q->num_rows) {
        while ($item = $q->fetch_assoc ()) {
            $themes[$item['parent']][] = $item;
        }
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
}
echo alertsShow ();
if (isset ($cats) && !empty ($cats)) {
?>
<div class="pgBody">
<?php
    while ($item = $cats->fetch_assoc ()) {
?>
    <div class="pgTitle"><?=$item['title']?></div>
<?php
        if (isset ($themes[$item['id']]) && !empty ($themes[$item['id']])) {
            foreach ($themes[$item['id']] as $theme) {
                if ($theme['link'] != NULL) {
                    $link = $theme['link'];
                } else {
                    $link = '/forum/?page=topic&id='.$theme['id'];
                }
?>
    <div class="forumThemePre">
        <p class="title inline"><img align="middle" title="Ссылка" src="/forum/images/linkIcon.png"><a href="<?=$link?>"><?=$theme['title']?></a></p>
            
        <p class="desc"><a href="/?page=user&u=<?=users ('id', $theme['author'])['username']?>"><?=users ('id', $theme['author'])['username']?>, </a><?=seDateStr ($theme['createDate'])?></p>
    </div>
<?php
            }
        } else {
            echo '<div class="alert">Тем в этой категории пока нет!</div>';
        }
    }
?>
</div>
<?php
}
?>