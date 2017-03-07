<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['submit'])) {
    foreach ($_POST['config'] as $key => $item) {
        if ($item == '0' || $item == '1') {
            $item = intval ($item);
        }
        $q = $db->query ("SELECT * FROM `config` WHERE `title` = '".$key."'");
        if ($q) {
            if ($q->num_rows) {
                $q = $db->query ("UPDATE `config` SET `value` = '$item' WHERE `title` = '$key'");
            } else {
                $q = $db->query ("INSERT INTO `config` VALUES (NULL, '$key', '$item')");
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
        }
    }
    if (alertsShow() === false) {
        $_SESSION['alerts'][] = '<div class="alert alertSuccess"><b> Выполнено!</b> Настройки изменены.</div>';
        header ('Location: '.$_SERVER['HTTP_REFERER']);
        die ();
    }
}
$q = $db->query ("SELECT * FROM `config`");
if ($q) {
    if ($q->num_rows) {
        $config = [];
        while ($item = $q->fetch_assoc ()) {
            $config[$item['title']] = $item['value'];
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Отсутствуют настройки системы в таблице.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
}
?>
<div class="pgTitle">Настройки системы</div>
<?php
echo alertsShow ();
if (isset ($config) && !empty ($config)) {
?>
<div class="pgBody" id="systemSettings">
    <form action="" method="post">
        <table width="100%">
            <tr>
                <td class="tableLeft">Название сайта:</td>
                <td class="tableRight">
                    <input type="text" value="<?=$config['siteTitle']?>" name="config[siteTitle]" class="inputText">
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Описание сайта:</td>
                <td class="tableRight">
                    <input type="text" value="<?=$config['siteDesc']?>" name="config[siteDesc]" class="inputText">
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Часовой пояс:</td>
                <td class="tableRight">
                    <input type="text" value="<?=$config['timeZone']?>" name="config[timeZone]" class="inputText">
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Курс обмена рублей на монеты в личном кабинете:</td>
                <td class="tableRight">
                    <input type="number" value="<?=$config['exchangeRate']?>" name="config[exchangeRate]" class="inputText inline"> м. за 1 р.
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Включить анимацию заголовка страницы:</td>
                <td class="tableRight">
                    <input type="hidden" name="config[animateTitle]" value=0>
                    <input type="checkbox" <?=checked ($config['animateTitle'])?> name="config[animateTitle]" value=1>
                </td>
            </tr>
        </table>
        <hr><br>
        <input type="submit" name="submit" class="button">
        <br>
    </form>
</div>
<?php
}
?>