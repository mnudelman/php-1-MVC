<?php
include_once __DIR__ .'/controlMessage.php' ; // Вывод сообщений
/**
 *  Функции, обслуживающие контроллер  indexUser
 */
/**
 * проверяет допустимость login,password  для работы на сайте
 * @return bool -
 */
function isUserLoginSuccessful($pdo) {
    $isSuccessful = false ;
    $login = $_POST['login'];
    $password = $_POST['password'];
    if (empty($login) || empty($password)) {
        addControlMessage('ERROR:Поля "Имя:" и "Пароль:" должны быть заполнены !') ;
    } else {
        $userPassw = getUser($pdo, $login);
        if (!$userPassw) { // $login отсутствует в БД
            addControlMessage('ERROR: Недопустимое имя пользователя.Повторите ввод!') ;
        } else {  // проверяем пароль
            $fromDBPassw = $userPassw['password'];
            if ($fromDBPassw != md5($password)) {
                addControlMessage('ERROR: Неверный пароль.Повторите ввод !') ;
            } else {
                $isSuccessful = true ;
                $_SESSION['userLogin'] = $login;
                $_SESSION['userName'] = $login;
                $_SESSION['userPassword'] = $password;
                $_SESSION['enterSuccessful'] = true;
                $_SESSION['userStatus'] = USER_STAT_USER;
                if ('admin' == $login) {
                    $_SESSION['userStatus'] = USER_STAT_ADMIN;
                }
            }
        }
    }
    return $isSuccessful ;
}

/**
 * проверяет возможность для пользователя перейти к редактированию своего профиля
 * @return bool
 */
function isGoProfile() {
    return  ( $_SESSION['userStatus'] >= USER_STAT_USER  &&  !empty($_SESSION['userLogin'])) ;
}

function getProfileForEdit($pdo) {
    if (empty($_SESSION['enterSuccessful'])) {
        return false ;
    } else {      // читаем существующий профиль
        $_SESSION['tmp_profileEditFlag'] = true;
        $userLogin = $_SESSION['userLogin'];
        return  getProfile($pdo, $userLogin);
    }
}

/**
 * сохраняет профиль при первичной регистрации или изменениях
 * @param $pdo
 */
function saveProfile($pdo) {
    $profile = profileIni();   // массив - список полей
    $profileEditFlag = isset($_SESSION['tmp_profileEditFlag']);  // изменить существующий профиль
    $login = ($profileEditFlag) ? $_SESSION['userLogin'] : $_POST['login'];
    $password = ($profileEditFlag) ? $_SESSION['userPassword'] : $_POST['password'];

    if ((empty($login) || empty($password)) && !$profileEditFlag) { // при редактировании не учитывается
        addControlMessage('ERROR: Поля "login", "password" обязательны для заполнения! ') ;
        return false ;

    } else {
        $userPassw = getUser($pdo, $login);
        if (!$userPassw || $profileEditFlag) {    // новый пользователь - это хорошо
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
            $profile['birthday'] = date('c', $tm);       // это хранится в БД

             $_SESSION['successfulRegistration'] = putProfile($pdo, $login, $profile);   // profile БД
            // эти поля не заносятся
            $profile['birthday_year'] = $_POST['birthday_year'];
            $profile['birthday_month'] = $_POST['birthday_month'];
            $profile['birthday_day'] = $_POST['birthday_day'];

        } else {   //
            addControlMessage('ERROR: Введенный "login" зарегистрирован ранее. Измените "login" !') ;
            return false ;
        }

    }
    return $profile  ;
}