<?php
session_start() ;
/**
 * Контроллер профиляПользователя
 * Обрабатываются данные формы view/userProfile
 *
 */
header('Content-type: text/html; charset=utf-8');
?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL) ;
//error_reporting(E_ALL ^ E_NOTICE);
include_once __DIR__ . '/local.php';
include_once MODEL_DIR . '/modelUser.php';    //  подключение модели(работа с БД)
?>
<?php
$errors = [];
$_SESSION['modelMessage'] = [] ;   // сообщения модели

$successfulRegistration = false;
$profile = [];
$profileError = false;
$url = '/'.VIEW_HTML_DIR.'/userProfile.php' ;
if (isset($_GET['edit'])) {  // Изменить существующий профиль
    if (empty($_SESSION['enterSuccessful'])) {
        $profileError = true;
        echo '<a href="index.php">Не пройдена регистрация.Профиль не доступен!</a>';
    } else {      // читаем существующий профиль
        $_SESSION['tmp_profileEditFlag'] = true;
        $userLogin = $_SESSION['userLogin'];
        $profile = getProfile($pdo, $userLogin);
    }
}
if (isset($_POST['exit'])) {    // выйти
    if (isset($_SESSION['tmp_profileEditFlag'])) {
        unset($_SESSION['tmp_profileEditFlag']);
    }
    $url = '/'.TOP_HTML_DIR.'/index.php' ;
}

if (isset($_POST['exec'])) {    // заполнено

    $profile = profileIni();   // массив - список полей
    $profileEditFlag = isset($_SESSION['tmp_profileEditFlag']);
    $login = ($profileEditFlag) ? $_SESSION['userLogin'] : $_POST['login'];
    $password = ($profileEditFlag) ? $_SESSION['userPassword'] : $_POST['password'];

    if ((empty($login) || empty($password)) && !$profileEditFlag) { // при редактировании не учитывается
        $errors[] = 'ERROR: Поля "login", "password" обязательны для заполнения! ' . LINE_FEED;
    } else {
        $userPassw = getUser($pdo, $login);
        if (!$userPassw || $profileEditFlag) {    // новый пользователь - это хорошо
            //'firstname,middlename,lastname,fileFoto,tel,email,sex,birthday'
            if (!$profileEditFlag) {
                putUser($pdo, $login, md5($password));    // пользователя занести в БД

                $_SESSION['userLogin'] = $login;
                $_SESSION['userName'] = $login;
                $_SESSION['userPassword'] = $password;
                $_SESSION['enterSuccessful'] = true;
                $_SESSION['userStat'] = USER_STAT_USER;
            } else {
                $login = $_SESSION['userLogin'];
            }
            $profile['firstname'] = $_POST['firstname'];
            $profile['middlename'] = $_POST['middlename'];
            $profile['lastname'] = $_POST['lastname'];
            $profile['email'] = $_POST['email'];
            $profile['sex'] = $_POST['sex'];
            $year = $_POST['birthday_year'];
            $month = $_POST['birthday_month'];
            $day = $_POST['birthday_day'];
            $tm = mktime(0, 0, 0, $month, $day, $year);
            $profile['birthday'] = date('c', $tm);
            $profile['birthday_year'] = $_POST['birthday_year'];
            $profile['birthday_month'] = $_POST['birthday_month'];
            $profile['birthday_day'] = $_POST['birthday_day'];

            $successfulRegistration = putProfile($pdo, $login, $profile);   // profile БД
            $_SESSION['successfulRegistration'] = $successfulRegistration ;

        } else {   //
            $errors[] = 'ERROR: Введенный "login" зарегистрирован ранее. Измените "login" !';
        }
    }
}
// сделать массив сообщений для передачи в форму
$_SESSION['controlMessage'] = $errors ;
$_SESSION['profile'] = $profile ;
header("Location: ".$url) ;
?>



