<?php
session_start();
/**
 *   форма ввода  login
 */
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
?>
<?php
include_once __DIR__ . '/local.php';
?>
<html>
<head>
    <meta charset="utf-8">
    <title>php-1-MVC</title>
    <meta name="description" content="ШП-php-1-lesson_MVC">
    <meta name="author" content="mnudelman@yandex.ru">

    <link rel="stylesheet" type="text/css" href="<?php echo '/'.TOP_HTML_DIR ?>/styles/task.css">
    <link rel="stylesheet" type="text/css" href="<?php echo '/'.TOP_HTML_DIR ?>/styles/formStyle-1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo '/'.TOP_HTML_DIR ?>/styles/galleryStyle-1.css">

</head>
<body>
<?php
include_once VIEW_DIR . '/topMenu.php';
?>

<div id="content">
    <?php
    include_once VIEW_DIR .'/messageForm.php' ;    // вывод сообщений
    ?>
</div>
<div id="footer">

    <div>
        ВХОД. Войдите под своим login,password или
        <a href="<?php echo '/'.CONTROLLER_HTML_DIR ?>/indexProfile.php"> пройдите регистрацию</a><br>
    </div>
    <form action="<?php echo '/'.CONTROLLER_HTML_DIR ?>/indexUser.php" method="post">

        <label><span class="label"><strong>Имя:</strong></span>
            <input class="field" type="text" name="login"> </label> </br>

        <label><span class="label"> <strong>Пароль:</strong></span>
            <span> <input class="field" type="password" name="password"></label> </br>


        <label>
            <input type="checkbox" name="savePassword" class="bt">Запомнить пароль </label><br>
        <button name="exec" class="bt">ВОЙТИ</button>
        <button name="profile">ПРОФИЛЬ</button>
        <button name="exit">ПРЕРВАТЬ</button>

    </form>

</div>
</body>
</html>

