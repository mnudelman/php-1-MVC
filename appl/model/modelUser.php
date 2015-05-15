<?php
/**
 * Набор функций, обслуживающих авторизацию
 * Created by PhpStorm.
 * User: michael
 * Date: 03.05.15
 * Time: 11:09
 */
/**
 * при наличии в БД возвращает [login,password] иначе false
 * @param $userLogin
 * @return array
 */
include_once __DIR__ .'/service.php' ;

function getUser($pdo,$userLogin) {
    $userPassw = [] ;  // ['login' => login, 'password' => $password ]

    $sql = 'SELECT * FROM users WHERE login = :login' ;
    try {
        $smt = $pdo->prepare($sql) ;
        $smt->execute(['login'=>$userLogin]) ;
        $row = $smt->fetch(PDO::FETCH_ASSOC) ;
        if (!(false === $row)){
            return ['login   ' => $row['login'],
                'password' => $row['password'] ] ;
        }else {
            return false ;
        }
    }catch (PDOException  $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
}

/**
 * запоминает пользователя в БД
 * @param $userLogin
 * @param $password
 * @return bool
 */
function putUser($pdo,$userLogin,$password) {
    $logPassw = getUser($pdo,$userLogin) ;
    if (!(false === $logPassw)) {      // уже есть в БД
        return ($logPassw['password'] == $password) ;
    }
    $sql = 'INSERT INTO  users (login,password) VALUES (:login,:password)' ;
    try {
        $smt = $pdo->prepare($sql) ;
        $smt->execute(['login'=>$userLogin,
            'password'=>$password]) ;

    }catch (PDOException  $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    return true ;
}
function updatePassword($pdo,$userLogin,$newPassword){
    $logPassw = getUser($pdo,$userLogin) ;
    if ( false === $logPassw ) {      // нет в БД - это ошибка !!
        return false ;
    }
    $sql = 'UPDATE  users set password = :password where login = :login' ;
    try {
        $smt = $pdo->prepare($sql) ;
        $smt->execute(['login'=>$userLogin,
            'password'=>$newPassword]) ;

    }catch (PDOException  $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    return true ;
}

/**
 * возвращает  profile пользователя
 * @param $userLogin
 * @return array
 */
function getProfile($pdo,$userLogin) {
    $profile = []; // ['fieldName' => fieldMean, .....]
    $sql = 'SELECT * FROM userprofile where userprofile.userid IN
(SELECT userid FROM users WHERE login = :login )';
    try {
        $smt = $pdo->prepare($sql);
        $smt->execute(['login' => $userLogin]);
        $row = $smt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException  $e) {
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false;
    }
    if (false === $row) {
        return false;
    }
    foreach ($row as $fldName => $fldMean) {
        $profile[$fldName] = $fldMean;
    }
// birthday : YYYY-mm_ddT....
    $bD = $profile['birthday'];
    $arr = explode('-', $bD);
    $profile['birthday_year'] = $arr[0];
    $profile['birthday_month'] = $arr[1];
    $dT = explode('T', $arr[2]);
    $profile['birthday_day'] = $dT[0];

    return $profile;
}


/**
 * сохраняет profile пользователя
 * @param $userLogin
 * @param $profile
 * @return bool
 */
function putProfile($pdo,$userLogin,$profile) {
// ['fieldName' => fieldMean, .....]
    $sqlQuery = 'UPDATE userprofile SET ' ;
    $setLine = '' ;
    foreach ($profile as $fldName => $fldMean) {
        $tp = gettype($fldMean) ;
        if ('string' == $tp) {
            $li = $fldName.'='.'"'.$fldMean.'"' ;
        }else {
            $li = $fldName.'='.$fldMean ;
        }
        $setLine .= (empty($setLine)) ? $li : ','.$li ;
    }
    $where = 'where userprofile.userid IN
(SELECT userid FROM users WHERE login = :login )' ;
    $sql = $sqlQuery.$setLine.$where ;
//$sql = 'INSERT INTO  users (login,password) VALUES (:login,:password)' ;
    try {
        $smt = $pdo->prepare($sql) ;
        $smt->execute(['login'=>$userLogin]) ;

    }catch (PDOException  $e){

        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    return true ;
}

/**
 * Формирует список полей profile
 * @return array
 */
function profileIni() {
    $varList = 'firstname,middlename,lastname,fileFoto,tel,email,sex,birthday' ;   // список полей
    $arrName = explode(',', $varList);
    $fields = [] ;      // массив полей с их значением
    foreach ($arrName as $fieldName ) {
        $fields[$fieldName] = '' ;
    }
    return $fields ;
}


