<?php
session_start();
/**
 * Контроллер типа данных 'альбом(галерея)'
 *    подчиненный контроллер indexPicture - редактирование картинок
 * Обрабатываются данные формы view/galleryEdit
 * выполняются операции: добавитьАльбом. удалитьАльбом ,
 */
header('Content-type: text/html; charset=utf-8');
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
$errors = [];
$_SESSION['modelMessage'] = [];   // сообщения модели
include_once __DIR__ . '/local.php';
include_once MODEL_DIR . '/modelPicture.php';    //  подключение модели(работа с БД)
include_once __DIR__ . '/controlService/indexGalleryService.php';    //  функции контроллера
?>
<?php
define('URL_HOME', '/' . TOP_HTML_DIR . '/index.php');       // главная
define('URL_OWN_FORM', '/' . VIEW_HTML_DIR . '/galleryEdit.php'); // форма редактирования списка альбомов(галерей)
define('URL_PICT_EDIT', '/' . CONTROLLER_HTML_DIR . '/indexPicture.php?edit=1'); // переход на редактирование картинок
define('URL_PICT_SHOW', '/' . CONTROLLER_HTML_DIR . '/indexPicture.php?show=1'); // переход на проосмотр картинок
?>
<?php
$url = URL_OWN_FORM;
// режим редактирования сохраняется во временной переменной
$galleryEditStat = (!isset($_SESSION['tmp_galleryEditStat'])) ? GALLERY_STAT_SHOW :
    $_SESSION['tmp_galleryEditStat'];
$currentGallery = (empty($_SESSION['currentGallery']) ? '' : $_SESSION['currentGallery']);   // массив - дескриптор галереи

if (!isset($_POST) && !isset($_GET)) {
    $galleryEditStat = GALLERY_STAT_SHOW;
}

if (isset($_POST['exit'])) {      // выход (в "главный" index )
    $url = URL_HOME;
}

if (isset($_POST['changeStat'])) {       // сменить режим ( SHOW <-> EDIT )
    $galleryEditStat = galleryChangeStat();
}

// строим список доступных галерей
$owner = (GALLERY_STAT_SHOW == $galleryEditStat) ? '' : $_SESSION['userName'];
$galleryList = getGallery($pdo, $owner);

if (isset($_POST['currentGalleryId'])) {          // текущая галерея
    $currentGallery = $galleryList[$_POST['currentGalleryId']];
    $_SESSION['currentGallery'] = $currentGallery;
}

if (isset($_POST['goShow'])) {   //   в просмотр
    $url = URL_PICT_SHOW;
}
if (isset($_POST['editGallery'])) {   //   редактировать
    $url = URL_PICT_EDIT;
}
?>
<?php
if (!empty($_POST['addGallery'])) {   //   добавить в список новыйАльбом
    $galleryList = addGallery($pdo);
}

$_SESSION['galleryList'] = $galleryList;

header("Location: " . $url);   // собственная форма

