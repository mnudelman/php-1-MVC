<?php
/**
 * Ввод переменных сессии
 * Created by PhpStorm.
 * User: mnudelman@yandex.ru
 * Date: 27.04.15
 * Time: 16:38
 */
<<<<<<< HEAD
/**
 * определяет структуру директориев по отношению в корневому
 * @param $topDir
 * @param $topHtmlDir
 */
function dirConfig($topDir,$topHtmlDir) {
    $modelDir = $topDir.'/appl/model' ;
    $contrDir = $topDir.'/appl/controller' ;
    $viewDir = $topDir.'/appl/view' ;
    $_SESSION['realDirs'] = ['top'        => $topDir,
                             'model'      => $modelDir,
                             'controller' => $contrDir,
                             'view'       => $viewDir] ;

    $modelHtmlDir = $topHtmlDir.'/appl/model' ;
    $contrHtmlDir = $topHtmlDir.'/appl/controller' ;
    $viewHtmlDir  = $topHtmlDir.'/appl/view' ;
    $_SESSION['htmlDirs'] = ['top'        => $topHtmlDir,
                             'model'      => $modelHtmlDir,
                             'controller' => $contrHtmlDir,
                             'view'       => $viewHtmlDir] ;


}
//-----------------------------------------------------------
/**
 * пересчет директории
 * @param $dir      - исходный  dir
 * @param $upLevel  - относительный уровень для пересчета(-1 - один уровень вверх, -2 - два ....)
 * @return $newDir
 */
function upLevelDir($dir,$upLevel) {
    $dirParts = explode('/',$dir) ;
    $n = count($dirParts) ;
    $upLevel = abs($upLevel) ;
    if ($upLevel >= $n) {
        return false ;
    }
    $newDir = '' ;
    for ($i = 0; $i < $n - $upLevel; $i++) {
        $newDir .= ( (empty($newDir)) ? '' : '/' ).$dirParts[$i] ;
    }
    return $newDir ;
}
//-----------------------------------------------------------
=======

>>>>>>> 29b4783f17f831b7be04a47ce35dd5e3370a54b2
define("LINE_FEED", "<br>");
define("LINE_END", "\n");
define("COOKIE_LIFE_TIME",3600) ; // время жизни куки для автоАвторизации
define("MAX_LOGIN",5) ;           // Max число попыток подключения
define("ADMIN_LOGIN","ADMIN") ;
/** статус определяет функциональные возможности */
define("USER_STAT_ADMIN",99) ;     // создание новых разделов, групповое добавление картинок
define("USER_STAT_USER",10) ;      // добавление картинок по одной
define("USER_STAT_GUEST",5) ;      // только просмотр

define("GALLERY_STAT_SHOW",1) ;    // только просмотр
define("GALLERY_STAT_EDIT",2) ;    // редактирование
<<<<<<< HEAD
define('STAT_SHOW_NAME','только просмотр') ;
define('STAT_EDIT_NAME','редактирование') ;


$sessionVarList = ['realDirs',       // Директории разделов
                   'htmlDirs' ,      // относительный адреса разделов по отношению к host
                   'dirPictureHeap', // куча картинок
=======

$sessionVarList = ['dirStart',       // стартовый директорий
                   'htmlDir' ,       // относительный адрес по отношению к host
                   'dirPictureHeap', // куча картинок
                   'userList',       // массив - список зарегистрированных пользователей
>>>>>>> 29b4783f17f831b7be04a47ce35dd5e3370a54b2
                   'userName',       // Имя пользователя
                   'userLogin',      // login
                   'userPassword',   // пароль
                   'userStatus',     // статус пользователя (определяет доступные операции)
                   'currentGallery', // текущая галерея ['id'=> ,'name' =>,'owner'=>,'editStat'
                   'imgFileExt',     // допустимые расширения графических файлов
                   'enterSuccessful', // успешный вход
<<<<<<< HEAD
                   'modelMessage',    // сообщения модели
                   'controlMessage',  // сообщения контроллера
                   'infoMessage'      // произвольные сообщения
];

foreach ($sessionVarList as $var ) {
    if (!isset($_SESSION[$var])) {
        $_SESSION[$var] = false;
    }
}

if (false === $_SESSION['userName'] ){
=======
                   'dbDataBase',      // имя схемы БД
                   'dbUserName',      //
                   'dbPassword',      //
                   'dbPDO',            // объект PDO через который выполнено подключение к хостуБД
                   'sqlLines'
] ;

foreach ($sessionVarList as $var ) {
    if ( !isset($_SESSION[$var] ) ) {
        $_SESSION[$var] = false ;
    }

}
if (empty( $_SESSION['userName'] )){
>>>>>>> 29b4783f17f831b7be04a47ce35dd5e3370a54b2
    $_SESSION['userName'] = 'Гость' ;
    $_SESSION['userLogin'] = 'guest' ;
    $_SESSION['userPassword'] = '12345' ;
    $_SESSION['userStatus'] = USER_STAT_GUEST ;
}

<<<<<<< HEAD
=======
if (empty( $_SESSION['dirStart'])) {
    $_SESSION['dirStart'] = __DIR__ ;
}
if (empty( $_SESSION['htmlDir'])) {
    $pi = pathinfo($_SERVER['PHP_SELF']) ;
    $_SESSION['htmlDir'] = $pi['dirname'] ;
}
>>>>>>> 29b4783f17f831b7be04a47ce35dd5e3370a54b2
$_SESSION['dirPictureHeap'] = 'pictureHeap' ;
if (empty($_SESSION['imgFileExt'])) {
    $_SESSION['imgFileExt'] = ['png','jpg','gif','jpeg'] ;
}
<<<<<<< HEAD
$currentGallery = $_SESSION['currentGallery'] ;
if (false === $currentGallery){
    $currentGallery = ['id' => false,'name' => false,'owner' => false,'editStat' => false] ;

}
// сообщения выводятся в любой  view-процедуре. После вывода обнуляются.
if (false === $_SESSION['controlMessage']) {
    $_SESSION['modelMessage'] = [];     // сообщения модели(например, ошибки запросов к БД)
    $_SESSION['controlMessage'] = [];   // сообщения контроллера о данных(например, путой login,password)
    $_SESSION['infoMessage'] = [] ;      // произвольные сообщения
}
$realDirs = $_SESSION['realDirs'] ;
$htmlDirs = $_SESSION['htmlDirs'] ;


//  Структура директорий проекта
// Если первый вызов из local.php ( например, во время автономного запуска), то
// $topDir,$toHtmlDir  уже определены 
if (false === $realDirs ) {
    $topDir = (defined('TOP_DIR')) ? TOP_DIR : __DIR__ ;
    $pi = pathinfo($_SERVER['PHP_SELF']) ;
    $topHtmlDir    = $pi['dirname'] ;
    dirConfig($topDir,$topHtmlDir) ;
}

=======
$currentGalary = $_SESSION['currentGallery'] ;
if (!$currentGalary){
    $currentGalary = ['id' => false,'name' => false,'owner' => false,'editStat' => false] ;

}
>>>>>>> 29b4783f17f831b7be04a47ce35dd5e3370a54b2
