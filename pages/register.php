<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
?>
<div class="pgTitle">Регистрация</div>
<div class="pgBody" id="registerBody">
<?php
echo alertsShow ();
?>
    <i><b>Внимание!</b> Перед регистрацией рекомендуется ознакомиться с правилами проекта. Запомните, незнание правил не освобождает от ответственности.</i>
    <form action="../engine/handlers/register.php" method="post">
        <div class="regBlock" id="regUnameWrap">
            <label for="regUname">Придумайте логин:
                <input type="text" id="regUname" name="regUname" maxlength="30">
            </label>
        </div>
        <div class="regBlock" id="regEmailWrap">
            <label for="regEmail">Введите Ваш E-mail:
                <input type="email" id="regEmail" name="regEmail" maxlength="100">
            </label>
        </div>
        <div class="regBlock" id="regPassWrap">
            <label for="regPass">Придумайте пароль:
                <input type="password" id="regPass" name="regPass" maxlength="50">
            </label>
        </div>
        <div class="regBlock" id="regRepassWrap">
            <label for="regRepass">Повторите пароль:
                <input type="password" id="regRepass" name="regRepass" maxlength="50">
            </label>
        </div>
        <div class="regBlock" id="regCheckboxWrap">
            <label for="regCheckbox">
                <input type="checkbox" id="regCheckbox" name="regCheckbox">
                Я согласен с правилами проекта и обязуюсь выполнять их!
            </label>
        </div>
        <div class="regBlock" id="regSubmitWrap">
            <input type="submit" id="regSubmit" name="regSubmit" value="Регистрация" class="button">
        </div>
    </form>
</div>