<?php
session_start();
/**
 *  Меню - шапка страницы
 */
?>
<div id="topMenu">
    <strong>ШП. PHP-1.Занятие -7(MVC)</strong> <br>

    <a href="<?php echo '/'.CONTROLLER_HTML_DIR ?>/indexGallery.php" class="menu">
        <img src="<?php echo '/'.TOP_HTML_DIR ?>/images/folder-image.png" title="Альбом(владелец : имя)" alt="Альбом">
        <?php
        $currentG = $_SESSION['currentGallery'];

        $gName = $currentG['galleryname'] ;
        $owner = $currentG['owner'] ;
        echo  ( empty($gName)) ? 'альбом не выбран' : $owner,':'.$gName ;
        ?>
    </a>&nbsp;&nbsp;

    <a href="<?php echo '/'.CONTROLLER_HTML_DIR ?>/indexUser.php" class="menu">
        <img src="../../images/people.png"
             title="пользователь" alt="пользователь">
        <?php
           echo $_SESSION['userName'] ;
        ?>
    </a> &nbsp;&nbsp;
    <a href="<?php echo '/'.TOP_HTML_DIR ?>/about.php" class="menu">
        <img src="<?php echo '/'.TOP_HTML_DIR ?>/images/help-about.png" title="about" alt="about"></a>

</div>
&nbsp;&nbsp;
