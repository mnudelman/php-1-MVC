<?php
session_start();
/**
 * Контроллер типа данных 'картинки'
 * Обрабатываются данные формы view/pictureEdit
 * выполняются операции: добавитьКртинки. удалитьКартинки,ЗаписатьвБуфер.добавитьИзБуфера.добавитьИзДиректории
 */
header('Content-type: text/html; charset=utf-8');
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
?>
<?php
include_once __DIR__ . '/local.php';
include_once MODEL_DIR . '/modelPicture.php';    //  подключение модели(работа с БД)
?>
<?php
/**
 * из общего списка файлов выбирает отмеченные в форме
 * @param $listFiles - общий список
 * @return array - списокОтмеченных
 */
function getCheckedList($listFiles)
{   // список отмеченных файлов
    $checkedFiles = [];     // отмеченные файлы
    if (empty($listFiles)) {
        return $checkedFiles;
    }
    foreach ($listFiles as $imgFile) {  // оставим только отмеченные check-<file>

        $file = $imgFile['file'];
        $file_ = str_replace('.', '_', $file);
        $chkName = 'check-' . $file_;

        if (isset($_POST[$chkName])) {
            $cmtName = 'comment-' . $file_;    // имя поля комментария
            $comment = $_POST[$cmtName];
            $checkedFiles[] = ['file' => $file, 'comment' => $comment];
        }

    }
    return $checkedFiles;
}

?>


<?php

$url = '/' . VIEW_HTML_DIR . '/pictureEdit.php';

$errors = [] ;
$_SESSION['modelMessage'] = [] ;   // сообщения модели

define('NEW_COMMENT', 'новое!');  // комментарий для вновь добавленных изображений
$imgFiles = [];
$currentGallery = $_SESSION['currentGallery'];
$galleryId = $currentGallery['galleryid'];
$userName = $_SESSION['userName'];
$userStat = $_SESSION['userStatus'];

if (isset($_POST['show']) || isset($_GET['show'])) {   // просмотр
    $url = '/' . VIEW_HTML_DIR . '/pictureShow.php';
} elseif ($userStat < USER_STAT_USER) {
    $error = true;
    ?>
    <a href="<?php echo '/' . TOP_HTML_DIR . '/index.php'; ?>">У вас нет полномочий для редактирования альбома!</a>
<?php
}
?>
<?php
if (empty($galleryId)) {
    ?>
    <a href="<?php echo '/' . TOP_HTML_DIR . '/index.php'; ?>">Выберите альбом и повторите действие</a>
<?php
}
?>


<?php
if (isset($_POST['save'])) {   // сохранить и выйти
// сохраняем только отмеченные по  checkbox
    $imgFiles = getImages($pdo, $galleryId); //
    $fileForSave = getCheckedList($imgFiles);
    putImages($pdo, $galleryId, $fileForSave);
}

if (isset($_POST['add'])) {   // добавить картинки

    $addImages = []; // ['file' => file,'comment' => comment] -  для загрузки в БД
    $errors = [];
    $filesNorm = filesTransform('pictures');  // преобразовать в нормальную форму

    $dirHeap = TOP_DIR.'/pictureHeap';
    $nLoaded = 0;

    foreach ($filesNorm as $fdes) {
        $name = $fdes['name'];
        $tmpName = $fdes['tmp_name'];
        $error = $fdes['error'];

        if (!0 == $error) {
            $errors[] = "ERROR: Ошибка выбора файла:" . $name . " код ошибки: " . $error . LINE_FEED;
            continue;
        }
        $addImages[] = ['file' => $name, 'comment' => NEW_COMMENT];
        if (doubleLoad($dirHeap, $name)) {
            $errors[] = "INFO: Попытка повторной загрузки файла :" . $name . LINE_FEED;

        }
        $fileTo = $dirHeap . '/' . basename($name);
        if (is_uploaded_file($tmpName)) {
            $res = move_uploaded_file($tmpName, $fileTo);
            $nLoaded++;
        }

    }
    $newOnly = true;   // блокирует изменение имеющихся в БД
    putImages($pdo, $galleryId, $addImages, $newOnly);    // добавить в БД/обновить комментарий
    $errors[] = 'INFO:Загружено  файлов:' . $nLoaded;
}


if (isset($_POST['addFrom'])) {   // добавить картинки из буфера
    $newOnly = true;   // блокирует изменение имеющихся в БД
    $addImages = $_SESSION['galleryBuffer'];
    putImages($pdo, $galleryId, $addImages, $newOnly);    // добавить в БД/обновить комментарий
}


if (isset($_POST['del'])) {   // удалить отмеченные
    $imgFiles = getImages($pdo, $galleryId); //
    $fileForSave = getCheckedList($imgFiles);
    delImages($pdo, $galleryId, $fileForSave);
}

if (isset($_POST['copyTo'])) {   // копировать отмеченные в буфер
    $imgFiles = getImages($pdo, $galleryId); //
    $_SESSION['galleryBuffer'] = getCheckedList($imgFiles);
}
$imgFiles = getImages($pdo, $galleryId); // [ galleryid => [

$_SESSION['imgFiles'] = $imgFiles;
$_SESSION['controlMessage'] = $errors;
if (!$error) {
    //   header("Location: " . '/' . VIEW_HTML_DIR . '/pictureEdit.php');   // собственная форма
    header("Location: " . $url);   // собственная форма
}
?>
<!--   <a href="<?php echo '/' . VIEW_HTML_DIR ?>/pictureEdit.php">переход на Edit</a> -->
