<?php
session_start();
/**
 *   страница - приглашение запускается из главного index
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
$inviteMessage = [] ;
$inviteMessage[] = 'Для начала работы из меню выберите для просмотра любой альбом из имеющихся';
$inviteMessage[] = 'Для создания собственных альбомов надо пройти регисрацию';
$inviteMessage[] = 'Подробности о работе сайта пункт меню about';
?>

<div id="content">
    <?php
    $_SESSION['infoMessage'] = $inviteMessage ;
    include_once VIEW_DIR .'/messageForm.php' ;    // вывод сообщений
    ?>
</div>
<div id="footer">

</div>
</body>
</html>

