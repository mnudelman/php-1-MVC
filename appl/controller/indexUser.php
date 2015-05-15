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
?>
<?php
$isGoFlag = true ;
$errors = [] ;
$_SESSION['modelMessage'] = [] ;   // сообщения модели

$url = '/'.VIEW_HTML_DIR.'/userLogin.php' ;
if (isset($_POST['exit'])) {              // выход - возврат на главную
    $url = '/'.TOP_HTML_DIR.'/index.php' ;
}
if (isset($_POST['profile']) ) {
    if( $_SESSION['userStatus'] >= USER_STAT_USER &&
        !empty($_SESSION['userLogin'])) {           // редактирование профиля для зарегистрированных пользователей
        $url = '/'.CONTROLLER_HTML_DIR.'/indexProfile.php?edit=1' ;
    }
}
if (isset($_POST['exec'])  ) {
    $login = $_POST['login'] ;
    $password = $_POST['password'] ;
    if (empty($login) || empty($password)) {
        $errors[] = 'ERROR:Поля "Имя:" и "Пароль:" должны быть заполнены !' ;
    }else {
        $userPassw = getUser($pdo,$login) ;
        if (!$userPassw) { // $login отсутствует в БД
            $errors[] = 'ERROR: Недопустимое имя пользователя.Повторите ввод !' ;
        }else {  // проверяем пароль
            $fromDBPassw = $userPassw['password'] ;
            if ( $fromDBPassw != md5($password)) {
                $errors[] = 'ERROR: Неверный пароль.Повторите ввод !' ;
            }else {
                $_SESSION['userLogin'] = $login ;      // login
                $_SESSION['userName'] = $login ;      // login
                $_SESSION['userPassword'] = $password ;
                $_SESSION['enterSuccessful'] = true ;
                $_SESSION['userStatus'] = USER_STAT_USER ;
                if ('admin' == $login) {
                    $_SESSION['userStatus'] = USER_STAT_ADMIN ;
                }
                $url = '/'.TOP_HTML_DIR.'/index.php' ;    // возврат на главную
            }
        }
    }
}
$_SESSION['controlMessage'] = $errors ;
header("Location: ".$url) ;
?>

