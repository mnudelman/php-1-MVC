<?php
/**
 * Привязка текущейДиректории к корневойПроекта
 */
define('CURRENT_DIR',__DIR__) ;
$pi = pathinfo($_SERVER['PHP_SELF']) ;
define('CURRENT_HTML_DIR',$pi['dirname']) ; // относительный адрес для HTML-ссылок



// определяем верхний уровень
$topDir = realpath(CURRENT_DIR.'/../..') ;
// подключаем общие константы из  корневогоДиректрия
include_once $topDir.'/sessionVars.php' ;

$topHtmlDir = upLevelDir(constant('CURRENT_HTML_DIR'),-2) ;
dirConfig($topDir,$topHtmlDir) ;

$dbSuccessful = include(TOP_DIR . '/dbConnect.php');
if (!$dbSuccessful) {
    die('EXIT');
}
// для передачи управления нужны относительные адреса всех компонентов MVC
$htmlDirs = $_SESSION['htmlDirs'] ;
define('TOP_HTML_DIR',$htmlDirs['top']) ;
define('MODEL_HTML_DIR',$htmlDirs['model']) ;
define('VIEW_HTML_DIR',$htmlDirs['view']) ;
define('CONTROLLER_HTML_DIR',$htmlDirs['controller']) ;
// для подключения модели нужны  realpath
$realDirs = $_SESSION['realDirs'] ;
define('TOP_DIR',$realDirs['top']) ;
define('MODEL_DIR',$realDirs['model']) ;
define('VIEW_DIR',$realDirs['view']) ;
define('CONTROLLER_DIR',$realDirs['controller']) ;