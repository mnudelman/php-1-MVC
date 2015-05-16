<?php
session_start() ;
/**
 * Контроллер профиляПользователя
 * Обрабатываются данные формы view/userProfile
 */
header('Content-type: text/html; charset=utf-8');
?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL) ;
//error_reporting(E_ALL ^ E_NOTICE);
include_once __DIR__ . '/local.php';
include_once MODEL_DIR . '/modelUser.php';    //  подключение модели(работа с БД)
include_once CONTROLLER_DIR .'/controlService/indexUserService.php' ; // функции контроллера
?>
<?php
define('URL_OWN_FORM','/'.VIEW_HTML_DIR.'/userProfile.php') ; // переход на собственную форму
define('URL_HOME', '/'.TOP_HTML_DIR.'/index.php' ) ;

$_SESSION['controlMessage'] = [] ; // сообщения контроллера
$_SESSION['modelMessage'] = [] ;   // сообщения модели
$profile = [];
$profileError = false;
$url = URL_OWN_FORM ;
?>
<?php
if (isset($_GET['edit'])) {  // Изменить существующий профиль
    $profile = getProfileForEdit($pdo) ;   // получить профиль из БД
    if ( false == $profile ) {
        $profileError = true;
        echo '<a href="'.URL_HOME.'">Не пройдена регистрация.Профиль не доступен!</a>';
    }
}

if (isset($_POST['exit'])) {    // выйти
    $url = URL_HOME  ;
}

if (isset($_POST['exec'])) {    // создать / обновить профиль пользователя
   $profile = saveProfile($pdo) ;

}

if (!$profileError) {
    $_SESSION['profile'] = $profile ;
    header("Location: " . $url);
}



