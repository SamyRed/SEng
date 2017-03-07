<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_GET['id'])) {
    if (!$id = intval ($_GET['id'])) {
        $alerts[] = '<div class="alert alertSuccess"><b>Ошибка!</b> Идентификатор группы должен быть числом!<div class="alert alertSuccess">';
    } else {
        if (isset ($_POST['gSubmit'])) {
            $gTitle = $db->real_escape_string ($_POST['gTitle']);
            $gTBefore = $db->real_escape_string ($_POST['gTBefore']);
            $gTAfter = $db->real_escape_string ($_POST['gTAfter']);
            $newPerms = [];
            foreach ($_POST['perms'] as $key => $item) {
                if ($item = intval ($item)) {
                    $newPerms[$key] = $item;
                } else {
                    $newPerms[$key] = 0;
                }
            }
            $newPermsStr = serialize ($newPerms);
            $q = $db->query ("UPDATE `groups` SET `title` = '$gTitle', `tBefore` = '$gTBefore', `tAfter` = '$gTAfter', `permissions` = '$newPermsStr' WHERE `id` = '$id'");
            header ('Location: '.$_SERVER['HTTP_REFERER']);
            die ();
        }
?>
<div class="pgTitle"><?=groups ('id', $id)['tBefore'].groups ('id', $id)['title'].groups ('id', $id)['tAfter']?></div>
<div class="pgBody" id="groupEdit">
<?php
echo alertsShow ();
?>
    <form action="" method="post">
        <table width="100%">
            <tr>
                <td width="65%" class="tableLeft">Идентификатор группы:</td>
                <td class="tableRight">
                    <?=$id?>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Название группы:</td>
                <td class="tableRight">
                    <input type="text" value="<?=htmlspecialchars (groups ('id', $id)['title'])?>" name="gTitle" class="inputText">
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Текст перед названием:</td>
                <td class="tableRight">
                    <input type="text" value="<?=htmlspecialchars (groups ('id', $id)['tBefore'])?>" name="gTBefore" class="inputText">
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Текст после названия:</td>
                <td class="tableRight">
                    <input type="text" value="<?=htmlspecialchars (groups ('id', $id)['tAfter'])?>" name="gTAfter" class="inputText">
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Доступ к админпанели:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[adminAccess]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['adminAccess'])?> name="perms[adminAccess]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Просмотр логов:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[seeLogs]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['seeLogs'])?> name="perms[seeLogs]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Редактирование логов:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[editLogs]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['editLogs'])?> name="perms[editLogs]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Создание учётых записей пользователей:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[usersCreate]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['usersCreate'])?> name="perms[usersCreate]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Редактирование учётных записей пользователей:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[usersEdit]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['usersEdit'])?> name="perms[usersEdit]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Блокировка аккаунтов пользователей:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[usersBan]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['usersBan'])?> name="perms[usersBan]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Перманентная блокировка аккаунтов пользователей:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[usersPermban]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['usersPermban'])?> name="perms[usersPermban]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Блокировка ip адросов:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[usersIPban]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['usersIPban'])?> name="perms[usersIPban]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Создание групп пользователей:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[groupsCreate]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['groupsCreate'])?> name="perms[groupsCreate]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Редактирование групп пользователей:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[groupsEdit]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['groupsEdit'])?> name="perms[groupsEdit]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Создание новостей:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[newsCreate]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['newsCreate'])?> name="perms[newsCreate]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Редактирование новостей:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[newsEdit]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['newsEdit'])?> name="perms[newsEdit]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Написание комментариев:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[commentsCreate]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['commentsCreate'])?> name="perms[commentsCreate]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Редактирование своих комментариев:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[commentsOwnEdit]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['commentsOwnEdit'])?> name="perms[commentsOwnEdit]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Редактирование комментариев:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[commentsEdit]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['commentsEdit'])?> name="perms[commentsEdit]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Загрузка файлов:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[loadFiles]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['loadFiles'])?> name="perms[loadFiles]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Скачивание файлов:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[downloadFiles]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['downloadFiles'])?> name="perms[downloadFiles]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Изменение скина:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[changeSkin]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['changeSkin'])?> name="perms[changeSkin]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Изменение префикса:</td>
                <td class="tableRight">
                    <input type="hidden" name="perms[changePrefix]" value=0>
                    <input type="checkbox" <?=checked (groups ('id', $id, 1)['changePrefix'])?> name="perms[changePrefix]" value=1>
                </td>
            </tr>
            <tr>
                <td class="tableLeft">Скидка в онлайн-магазине предметов:</td>
                <td class="tableRight">
                    <input type="number" name="perms[shopDiscount]" class="inputNumber inline" value="<?=groups ('id', $id, 1)['shopDiscount']?>" min="0" max="100">&nbsp;%
                </td>
            </tr>
        </table>
        <hr><br>
        <input type="submit" name="gSubmit" class="button">
        <br>
    </form>
</div>
<?php
    }
}