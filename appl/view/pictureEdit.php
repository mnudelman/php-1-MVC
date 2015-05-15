<?php
session_start();
/**
 *   форма редактирования спискаКартинок
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
?>
<div id="contentShow">
    <?php
    include_once VIEW_DIR . '/messageForm.php';    // вывод сообщений
    ?>

    <?php
        ?>
        <form action="<?php echo '/' . CONTROLLER_HTML_DIR ?>/indexPicture.php" method="post"
              enctype="multipart/form-data">

            <table border="4"
                   cellspacing="1"
                   cellpadding=“1” class="galFformEdit">

                <tr>
                    <th>Изображение</th>
                    <th>Комментарий</th>
                    <th>отметка</th>
                </tr>
                <?php
                $dirPict = '/'.TOP_HTML_DIR.'/pictureHeap' ;
                if (!empty($imgFiles)) {
                    foreach ($imgFiles as $imgFile) {
                        $file = $imgFile['file'];
                        $comment = $imgFile['comment'];
                        echo '<tr>' . LINE_END;
                        echo '<td>' . LINE_END;
                        echo '<img src=" ' . $dirPict . '/' . $file . '" class="imgGal" name="file-' . $file . '">';
                        echo '</td>' . LINE_END;
                        echo '<td class="comment">' . LINE_END;
                        echo '<input type="text" class="commentGal"
                      name="comment-' . $file . '" value="' . $comment . '"">';
                        echo '</td>' . LINE_END;
                        echo '<td>' . LINE_END;
                        echo '<input type="checkbox" class="checkGal" name="check-' . $file . '">';
                        echo '</td>' . LINE_END;
                        echo '</tr>' . LINE_END;
                    }
                }
                ?>

            </table>
            <br>
            <label>

                Выбор изображения
                <input type="file" name="pictures[]" accept="image/jpeg,image/png" multiple>
            </label>
        <span style="margin-left:52px">
        <button class="btGalEdit" name="add">Добавить в альбом</button>
        </span>
            <?php

            if (!empty($_SESSION['galleryBuffer'])) {
                echo '<button class="btGalEdit" name="addFrom">Добавить из буфера</button>' . LINE_END;
            }
            ?>
            <br>

            <button class="btGalEdit" name="save">Сохранить</button>
            <button class="btGalEdit" name="del">Удалить отмеченные</button>
            <button class="btGalEdit" name="copyTo">Копировать в буфер</button>

            <button class="btGalEdit" name="show">В просмотр</button>
        </form>
</div>
</body>
</html>