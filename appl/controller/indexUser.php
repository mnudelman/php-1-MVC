<?php
session_start() ;
/**
 * Контроллер подключенияПользователя
 *      подчиненный контроллер indexProfile -  управление профилемПользователя
 * Обрабатываются данные формы view/userLogin
 *
 */
header('Content-type: text/html; charset=utf-8');
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
include_once __DIR__ . '/local.php';
include_once MODEL_DIR . '/modelUser.php';    //  подключение модели(работа с БД)
include_once CONTROLLER_DIR .'/controlService/indexUserService.php' ; // функции контроллера
?>
<?php
define('URL_OWN_FORM','/'.VIEW_HTML_DIR.'/userLogin.php') ; // переход на собственную форму
define('URL_EXIT','/'.TOP_HTML_DIR.'/index.php') ; // переход по кнопке выход
define('URL_PROFILE','/'.CONTROLLER_HTML_DIR.'/indexProfile.php?edit=1') ; // редактирование профиля
define('URL_SUCCESSFUL','/'.TOP_HTML_DIR.'/index.php') ; // переход при удаче login,password
$errors = [] ;
$_SESSION['modelMessage'] = [] ;   // сообщения модели
$_SESSION['controlMessage'] = [] ;
$url = URL_OWN_FORM ;
?>
<?php
if (isset($_POST['exit'])) {              // выход - возврат на главную
    $url = URL_EXIT ;
}
if (isset($_POST['profile']) ) {
    $url = ( isGoProfile() ) ? URL_PROFILE : $url ;
}
if (isset($_POST['exec'])  ) {
    $url = (isUserLoginSuccessful($pdo,$errors) ) ? URL_SUCCESSFUL : $url ;
}
$_SESSION['controlMessage'] = $errors ;
header("Location: ".$url) ;


