<?php
session_start() ;
/**
 * Форма редактирования списка альбомов(галерей) пользователя
 */
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
?>

<?php
include_once __DIR__ . '/local.php';
?>

<html>
<head>
    <meta charset="utf-8">
    <title>php-1-MVC</title>
    <meta name="description" content="ШП-php-1-lesson_MVC">
    <meta name="author" content="mnudelman@yandex.ru">

    <link rel="stylesheet" type="text/css" href="<?php echo '/'.TOP_HTML_DIR ?>/styles/task.css">
    <link rel="stylesheet" type="text/css" href="<?php echo '/'.TOP_HTML_DIR ?>/styles/formStyle-1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo '/'.TOP_HTML_DIR ?>/styles/galleryStyle-1.css">

</head>

<body>
<?php
include_once VIEW_DIR . '/topMenu.php';
?>


<div id="content">
    <?php
    include_once VIEW_DIR .'/messageForm.php' ;    // вывод сообщений
    ?>
</div>
<div id="footer">
    <?php

    $currentGallery = $_SESSION['currentGallery'] ;
    $galleryEditStat = $_SESSION['tmp_galleryEditStat']  ;
    $galleryStatName = ($galleryEditStat == GALLERY_STAT_EDIT) ? STAT_EDIT_NAME : STAT_SHOW_NAME   ;
    $editFlag = ($galleryEditStat == GALLERY_STAT_EDIT) ;
    $galleryList = $_SESSION['galleryList']  ;
    ?>
<form action="<?php echo '/'.CONTROLLER_HTML_DIR ?>/indexGallery.php" method="post">
    <label>
        <span class="label">текущий режим:</span>
        <input type="text" readonly="readonly" name="statName" class="field"
               value="<?php echo $galleryStatName ?>">
    </label>&nbsp;&nbsp;
    <button name="changeStat" class="btGal">изменить режим</button>
    <br>
    <label>
        <span class="label">выбрать альбом:</span>
        <select name="currentGalleryId" class="field">
            <?php
            $currentGalleryId = (!empty($currentGallery['galleryid'])) ?
                $currentGallery['galleryid'] : '' ;
            foreach($galleryList as $gallery) {
                $owner      = $gallery['owner'] ;
                $galleryid  = $gallery['galleryid'] ;
                $galleryName= $gallery['galleryname'] ;
                $text = $owner.':'.$galleryName ;
                $selected = ( $galleryid == $currentGalleryId ) ? 'selected' : '' ;
                echo '<option value="'.$galleryid.'"  '.$selected.' >'.$text.'</option>'.LINE_END ;
            }
            ?>
        </select>
    </label>&nbsp;&nbsp;
    <button name="goShow" class="bt btGal">Просмотр</button>&nbsp;&nbsp;
    <?php
    if ($editFlag) {
        ?>
        <button name="editGallery" class="btGal">Редактировать</button><br>
        <label>
            <span class="label">Новый альбом:</span>
            <input type="text" name="addGallery" class="field">
        </label>&nbsp;&nbsp;
        <button name="addGalleryExec" class="btGal">Добавить</button>
    <?php
    }
    ?>
    <br>
    <div style="margin-left:451px;">
        <button name="exit" class="btGal">Прервать</button
    </div>
</form>
</div>
</body>
</html>