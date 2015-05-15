<?php
session_start();
/**
 * Created by PhpStorm.
 * Исполнить sql script
 * User: mnudelman@yandex.ru
 * Date: 30.04.15
 * Time: 14:30
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>php-1-lesson-7</title>
    <meta name="description" content="ШП-php-1-lesson_7 ">
    <meta name="author" content="mnudelman@yandex.ru">
    <link rel="stylesheet" type="text/css" href="styles/task.css">
</head>
<body>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL) ;
//error_reporting(E_ALL ^ E_NOTICE);


<<<<<<< HEAD
include_once __DIR__ . '/local.php';
include_once __DIR__ .'/dbService.php' ;
=======
include_once __DIR__ . '/sessionVars.php';
include_once __DIR__ . '/dbService.php';
>>>>>>> 29b4783f17f831b7be04a47ce35dd5e3370a54b2
$dbSuccessful = include(__DIR__ . '/dbConnect.php');
echo 'dbSuccessful:' . (($dbSuccessful) ? 'true' : 'false') . LINE_FEED;
echo 'PDO:' . LINE_FEED;
var_dump($pdo);
if (!$dbSuccessful) {
    die('EXIT');
}
?>
<?php
define('STAT_PREPARE', 1);   // подготовка текста из скрипта
define('STAT_GO', 2);        // исполнить запрос
define('STAT_EXIT', 9);      // прервать
$stat = '';
?>
<?php
if (isset($_POST['refuse'])) {
    $stat = STAT_EXIT;
    die('EXIT');
}
if (isset($_POST['sqlPrepare'])) {    // подготовка: файл --> списокЗапросов
    $stat = STAT_PREPARE;
    $sqlScript = $_FILES['sqlScript']['tmp_name'];  // файл с текстами запросов
    $sqlLines = scriptParser($sqlScript);
    $_SESSION['sqlLines'] = $sqlLines;   // сохранить списокЗапросов

}
if (isset($_POST['sqlExample'])) {    // подготовка: файл --> списокЗапросов
    $stat = STAT_PREPARE;
    $sqlScript = 'example' ;
    $sqlLines = [] ;
    $sqlLines[] = [
        'text'   => 'SHOW TABLES',
        'result' => '',
        'error'  => '',
        'name'   => 'SHOW',
        'count'  => 0 ]   ;
    $sqlLines[] = [
        'text'   => 'SELECT * FROM users',
        'result' => '',
        'error'  => '',
        'name'   => 'SELECT',
        'count'  => 0 ]   ;
    $sqlLines[] = [
        'text'   => 'SELECT * FROM userProfile',
        'result' => '',
        'error'  => '',
        'name'   => 'SELECT',
        'count'  => 0 ]   ;
    $sqlLines[] = [
        'text'   => 'SELECT * FROM gallery',
        'result' => '',
        'error'  => '',
        'name'   => 'SELECT',
        'count'  => 0 ]   ;
    $sqlLines[] = [
        'text'   => 'SELECT * FROM galleryContent',
        'result' => '',
        'error'  => '',
        'name'   => 'SELECT',
        'count'  => 0 ]   ;
    $_SESSION['sqlLines'] = $sqlLines ;     // сохранить списокЗапросов

}

if (isset($_POST['sqlGo'])) {   // Выполнение запросов
    $stat = STAT_GO;
    $sqlLines = $_SESSION['sqlLines'];
    foreach ($sqlLines as $key => $l) {
        $sql = $l['text'];
        $sqlName = $l['name'];

        try {
            $result = $pdo->prepare($sql);
            $result->execute();
        } catch (PDOException $e) {
            $sqlLines[$key]['error'] = $e->getMessage() . LINE_FEED;
            echo 'ERR-MESSAGE:' . $e->getMessage() . LINE_FEED;
        }
        $sqlLines[$key]['result'] = $result;
        $sqlLines[$key]['count'] = $result->rowCount(); // считает с 0 для SELECT!!
    }
}
?>

<h2>Исполнитель sql - скриптов</h2>

<form action="sqlExecute.php" method="post" enctype="multipart/form-data">
    <label>
        Выберите файл-sqlscript &nbsp;&nbsp;
        <input type="file" name="sqlScript" id="script">
    </label>
    <button name="sqlExample">пример запроса</button>
    <br>


        <textarea name="sqlText" style="width:620px;height:200px;">
        <?php
        echo chr(10);
        if (!empty($stat)) {
            foreach ($sqlLines as $l) {
                echo $l['text'] . chr(10);
                echo 'ERROR:' . $l['error'] . chr(10);
                if (empty($l['error'])) {
                    echo 'Кол записей:' . $l['count'] . chr(10);
                }
                echo '===================================' . chr(10);

            }
        }
        ?>
    </textarea><br>

    <?php
    if (empty($sqlScript)) {
        ?>
        <input type="submit" name="sqlPrepare" value="просмотр">
    <?php
    } else {
        ?>
        <input type="submit" name="sqlGo" value="исполнить">
    <?php
    }
    ?>
    <button name="refuse">отказ</button>
    <!---------------------------------------------->
    <?php
    if ($stat == STAT_GO) {  // если есть оператор SELECT,SHOW  выводим таблицу

        foreach ($sqlLines as $key => $l) {
            $sqlName = $l['name'];
            $err = $l['error'];
            if (false === strpos(',SHOW,SELECT,', ',' . $l['name'] . ',') || !empty($err)) {
                continue;
            }
            ?>
            <!-----только для SELECT ---------------------------->
            <br><strong>Запрос:</strong>
            <?php echo $l['text'] . LINE_FEED; ?>
            <strong>Результат-записей: <?php echo $l['count'] ; ?></strong><br>

            <table border="4"
                   cellspacing="1"
                   cellpadding=“1”>

                <?php
                $res = $l['result'];
                $n = $l['count'];
                $k = 0;
                foreach ($res as $row) {
                    if (0 == $k++) {                   // Шапка таблицы
                        foreach ($row as $name => $mean) {
                            echo '<th>' . $name . '</th>';
                        }
                    }
                    echo '<tr>' . LINE_END;
                    foreach ($row as $name => $mean) {
                        echo '<td>' . $mean . '</td>';
                    }
                    echo '</tr>' . LINE_END;
                }
                ?>

            </table>
        <?php
        }
    }
    ?>
</form>
</body>
</html>