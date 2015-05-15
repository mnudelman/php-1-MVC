<?php
session_start();
/**
 *   Вывод на экран изображений альбома
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

    <link rel="stylesheet" type="text/css" href="<?php echo '/' . TOP_HTML_DIR ?>/styles/task.css">
    <link rel="stylesheet" type="text/css" href="<?php echo '/' . TOP_HTML_DIR ?>/styles/formStyle-1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo '/' . TOP_HTML_DIR ?>/styles/galleryStyle-1.css">

</head>
<body>
<?php
include_once VIEW_DIR . '/topMenu.php';
?>

<?php
$error = false;
$imgFiles = $_SESSION['imgFiles'];

$dirPict = '/'.TOP_HTML_DIR.'/pictureHeap';
?>
<div id="contentShow">
    <?php
    include_once VIEW_DIR . '/messageForm.php';    // вывод сообщений
    ?>
    <div id="contentShow">
        <?php
        if (false === $imgFiles) {

        }else {
            foreach ($imgFiles as $imgFile) {
                $file = $imgFile['file'];
                $comment = $imgFile['comment'];
                echo '<div class="imgBlock">' . LINE_END;
                echo '<img src="' . $dirPict . '/' . $file . '" class="imgGal" title="'.$file.'" alt="'.$file.'" >' . LINE_END;
                echo '<div >' . $comment . '</div>' . LINE_END;
                echo '</div>';
            }
        }
        ?>

    </div>
</body>
</html>