<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
$q = $db->query ("SELECT * FROM `rules`");
if ($q) {
    if ($q->num_rows) {
        $rules = [];
        $rulesHTML = [];
        while ($item = $q->fetch_assoc ()) {
            if ($item['type'] == 1) {
                $rules[] = $item;
            } else {
                $rulesHTML[$item['rule']] = $item['punish'];
            }
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Ни одного пункта пока нет. <a href="/admin/?page=ruleAdd">[Добавить]</a>.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Невозможно полнить запрос к базе данных.</div>';
}
?>
<div class="pgTitle">Правила</div>
<?php
echo alertsShow ();
?>
<div class="pgBody">
    <p id="rulesBefore"><?=$rulesHTML['before']?></p>
<?php
if (alertsShow () === false) {
    $i = 1;
    foreach ($rules as $item) {
        if ($i%2 == 0) {
            $color = ' class="tableLight"';
        } else {
            $color = ' class="tableDark"';
        }
        if ($i != 1) {
?>
        <hr>    
<?php
        }
?>
        <div<?=$color?>>
           <p class="ruleText"><?=$item['rule']?></p>
           <p class="rulePunish"><?=$item['punish']?></p>
        </div>
<?php
        $i++;
    }
}
?>
        <p id="rulesAfter"><?=$rulesHTML['after']?></p>
</div>