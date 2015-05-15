<?php

session_start() ;
/**
 * главныйКонтроллер
 * по $_GET определяет передачу управления
 * контроллерам 2 уровня
 *  параметр: определяет имя контроллера 2 уровня {?user | ?gallery}
 */
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');
include_once __DIR__ . '/local.php';
?>


<?php
$isGoFlag = true ;
$errors = [] ;
$url = '/'.VIEW_HTML_DIR.'/invite.php' ;

if (isset($_GET['exit'])) {
    die('EXIT') ;
}
if (isset($_GET['user'])) {
    $url = '/'.CONTROLLER_HTML_DIR.'/indexUser.php' ;
    //header("Location: ".'/'.CONTROLLER_HTML_DIR.'/indexUser.php') ;
}
if (isset($_GET['gallery'])) {
    $url = '/'.CONTROLLER_HTML_DIR.'/indexGallery.php' ;
    //header("Location: ".'/'.CONTROLLER_HTML_DIR.'/indexGallery.php') ;
}
echo $_SERVER['HTTP_HOST'] ;
//$_SESSION['modelMessage'] = [] ;
$_SESSION['controlMessage'] = $errors ;
header("Location: ".$url) ;
?>
