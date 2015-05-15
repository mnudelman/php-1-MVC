<?php
/**
* Привязка текущейДиректории к корневойПроекта
*/
?>
<?php
$currentDir = __DIR__ ;
$pi = pathinfo($_SERVER['PHP_SELF']) ;
$currentHtmlDir = $pi['dirname'] ; // относительный адрес для HTML-ссылок



// определяем верхний уровень
$topDir = realpath($currentDir.'/../..') ;
// подключаем общие константы из  корневогоДиректрия
include_once $topDir.'/sessionVars.php' ;

$topHtmlDir = upLevelDir($currentHtmlDir,-2) ;

dirConfig($topDir,$topHtmlDir) ; // пересчет директорий проекта

$dbSuccessful = include($topDir . '/dbConnect.php');
if (!$dbSuccessful) {
   die('EXIT');
}


// для передачи управления нужны относительные адреса всех компонентов MVC
//if (!defined('TOP_HTML_DIR')) {
    $htmlDirs = $_SESSION['htmlDirs'];
    define('TOP_HTML_DIR', $htmlDirs['top']);
    define('MODEL_HTML_DIR', $htmlDirs['model']);
    define('VIEW_HTML_DIR', $htmlDirs['view']);
    define('CONTROLLER_HTML_DIR', $htmlDirs['controller']);
//}

// для подключения модели нужны  realpath
//if (!defined('TOP_DIR')) {
    $realDirs = $_SESSION['realDirs'];
    define('TOP_DIR', $realDirs['top']);
    define('MODEL_DIR', $realDirs['model']);
    define('VIEW_DIR', $realDirs['view']);
    define('CONTROLLER_DIR', $realDirs['controller']);
//}

