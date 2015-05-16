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
include_once __DIR__ . '/controlService/indexGalleryService.php';    //  функции контроллера
?>
<?php
define('URL_HOME','/' . TOP_HTML_DIR . '/index.php') ;
define('URL_PICTURE_SHOW','/' . VIEW_HTML_DIR . '/pictureShow.php') ;
define('URL_PICTURE_EDIT','/' . VIEW_HTML_DIR . '/pictureEdit.php') ;
?>


<?php
define('NEW_COMMENT', 'новое!');  // комментарий для вновь добавленных изображений
$url = URL_PICTURE_EDIT ;

$_SESSION['modelMessage'] = [] ;     // сообщения модели
$_SESSION['controlMessage'] = [] ;   // сообщения контроллера


$imgFiles = [];
$currentGallery = $_SESSION['currentGallery'];
$galleryId = $currentGallery['galleryid'];
$userName = $_SESSION['userName'];
$userStat = $_SESSION['userStatus'];

if (isset($_POST['show']) || isset($_GET['show'])) {   // просмотр
    $url = URL_PICTURE_SHOW ;
}elseif ($userStat < USER_STAT_USER) {
    $error = true;
    ?>
    <a href="<?php echo URL_HOME ; ?>">Зарегистрируйтесь.У вас нет полномочий для редактирования альбома!</a>
<?php
}
?>
<?php
if (empty($galleryId)) {
    $error = true ;
    ?>
    <a href="<?php echo '/' . TOP_HTML_DIR . '/index.php'; ?>">Выберите альбом и повторите действие</a>
<?php
}
?>
<?php
if (isset($_POST['save'])) {   // сохранить и выйти
// сохраняем только отмеченные по  checkbox
    savePict($pdo,$galleryId) ;
}

if (isset($_POST['add'])) {   // добавить картинки
    addPict($pdo,$galleryId) ;
}

if (isset($_POST['addFrom'])) {   // добавить картинки из буфера
    addFromBuffer($pdo, $galleryId) ;
}


if (isset($_POST['del'])) {   // удалить отмеченные
    delCheckedPict($pdo, $galleryId) ;
}

if (isset($_POST['copyTo'])) {   // копировать отмеченные в буфер
    copyPictToBuffer($pdo, $galleryId) ;
}
$imgFiles = getImages($pdo, $galleryId); // [ galleryid => [

$_SESSION['imgFiles'] = $imgFiles;
if (!$error) {
    header("Location: " . $url);   // собственная форма
}


