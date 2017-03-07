<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['logout'])) {
    unset ($_SESSION['id']);
    header ('Location: '.$_SERVER['HTTP_REFERER']);
}
?>
<side>
<?php if (autorized ()) {?>
    <div class="sideTitle">Профиль</div>
<?php } else {?>
    <div class="sideTitle">Авторизуйтесь</div>
<?php }?>
    <div class="sideBody" id="Profile">
<?php
if (autorized ()) {
?>
        <h3 id="sideProfileUname"><img src="<?=user ('avatar')?>" alt="<?=user ('username')?>" id="sideProfileImg"><?=user ('username')?></h3>
<?php
if (adminAccess() === true) {
?>
        <a href="./admin/">[Админпанель]</a>
<?php
}
?>
        <form action="" method="post">
            <input type="submit" value="Выход" name="logout" id="logoutBtn" class="button">
        </form>
<?php
} else {
?>
        <center>
            <form action="./engine/handlers/autorization.php" method="post">
                <input type="text" id="authLogin" name="authLogin" placeholder="Логин...">
                <input type="password" id="authPass" name="authPass" placeholder="Пароль...">
                <a href="/?page=forgotPass" id="authForgotPass">Забыли пароль?</a>
                <a href="/?page=register" id="authForgotPass">Регистрация</a>
                <input type="submit" id="authSubm" name="authSubm" value="Войти" class="button">
            </form>
        </center>
<?php
}
?>
    </div>
</side>