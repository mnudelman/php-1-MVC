<?php
session_start() ;
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
$errors = [] ;
$_SESSION['modelMessage'] = [] ;   // сообщения модели
include_once __DIR__ .'/local.php' ;
include_once MODEL_DIR.'/modelPicture.php' ;    //  подключение модели(работа с БД)
?>
<?php
$url = '/'.VIEW_HTML_DIR.'/galleryEdit.php' ;
// режим редактирования сохраняется во временной переменной
$galleryEditStat = ( !isset($_SESSION['tmp_galleryEditStat'])) ? GALLERY_STAT_SHOW  :
    $_SESSION['tmp_galleryEditStat'] ;      // режим редактирования

$currentGallery = ( empty($_SESSION['currentGallery'] ) ? '' : $_SESSION['currentGallery'])  ;   // массив - дескриптор галереи

if (!isset($_POST) && !isset($_GET) ) {
    $galleryEditStat = GALLERY_STAT_SHOW ;
}
if (isset($_POST['exit'])) {      // выход (в "главный" index )
    $url = '/'.TOP_HTML_DIR.'/index.php' ;
}

if (isset($_POST['changeStat'])) {       // сменить режим ( SHOW <-> EDIT )
    $statName = $_POST['statName'] ;
    $userName = $_SESSION['userName'];
    $userStat = $_SESSION['userStatus'];
    if ($userStat < USER_STAT_USER) {
        $galleryEditStat = GALLERY_STAT_SHOW;
    }else {
        $galleryEditStat = ($statName == STAT_SHOW_NAME) ? GALLERY_STAT_EDIT : GALLERY_STAT_SHOW ;
    }
    $_SESSION['tmp_galleryEditStat'] = $galleryEditStat ;
}
// строим список доступных галерей
$owner = (GALLERY_STAT_SHOW == $galleryEditStat ) ? '' : $_SESSION['userName'] ;
$galleryList = getGallery($pdo,$owner) ;

if(isset($_POST['currentGalleryId'])){          // текущая галерея
    $currentGallery = $galleryList[$_POST['currentGalleryId']] ;
    $_SESSION['currentGallery'] = $currentGallery ;

}
if (isset($_POST['goShow'])) {   //   в просмотр
    $url = '/'.CONTROLLER_HTML_DIR.'/indexPicture.php?show=1' ;
    $errors[] = 'DEBUG:переход:' .'/'.CONTROLLER_HTML_DIR.'/indexPicture.php?show=1';
}
if (isset($_POST['editGallery'])) {   //   редактировать
    $url = '/'.CONTROLLER_HTML_DIR.'/indexPicture.php?edit=1' ;
    $errors[] = 'DEBUG:переход:' .'/'.CONTROLLER_HTML_DIR.'/indexPicture.php?edit=1';
}
?>
<?php
if (!empty($_POST['addGallery'])) {   //   добавить в список новыйАльбом
    $owner = $_SESSION['userLogin'] ;
    $newG = $_POST['addGallery'] ;
    putGallery ($pdo,$owner,$newG) ;
    $galleryList = getGallery($pdo,$owner) ;
}
$_SESSION['galleryList'] = $galleryList ;
$_SESSION['controlMessage'] = $errors ;
header("Location: ".$url) ;   // собственная форма

