<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
echo alertsShow ();
if (autorized ()) {
?>
<div class="pgTitle">Скин и плащ</div>
<div class="pgBlockBody">
    <center>
        <input type="file" id="pcSkinFile">
        <br>
        <button type="button" id="pcSkinSubm">Сменить скин</button>
        <br>
        <input type="file" id="pcCloakFile">
        <br>
        <button type="button" id="pcCloakSubm">Сменить плащ</button>
        <br>
        <img id="pcSkinImg" src="./skins/SamyRed.png" alt="SamyRed">
    </center>
</div>
<?php
}
?>