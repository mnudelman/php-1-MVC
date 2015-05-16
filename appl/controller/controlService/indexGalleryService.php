<?php
/**
*/
include_once __DIR__ . '/controlMessage.php' ;
/**
 * меняет режим редактирования SHOW <-> EDIT
 * @return int -
 */
function galleryChangeStat(){
    $statName = $_POST['statName'] ;         //  текущий режим
    $userStat = $_SESSION['userStatus'];
    if ($userStat < USER_STAT_USER) {        //  если не зарегистрирован, то только просмотр
        $galleryEditStat = GALLERY_STAT_SHOW;
    }else {
        $galleryEditStat = ($statName == STAT_SHOW_NAME) ? GALLERY_STAT_EDIT : GALLERY_STAT_SHOW ;
    }
    $_SESSION['tmp_galleryEditStat'] = $galleryEditStat ;
    return $galleryEditStat ;
}
function addGallery($pdo) {
    $owner = $_SESSION['userLogin'] ;
    $newG = $_POST['addGallery'] ;
    putGallery ($pdo,$owner,$newG) ;
    $galleryList = getGallery($pdo,$owner) ;
    return $galleryList ;
}
/**
 * из общего списка файлов выбирает отмеченные в форме
 * @param $listFiles - общий список
 * @return array - списокОтмеченных
 */
function getCheckedList($listFiles)
{   // список отмеченных файлов
    $checkedFiles = [];     // отмеченные файлы
    if (empty($listFiles)) {
        return $checkedFiles;
    }
    foreach ($listFiles as $imgFile) {  // оставим только отмеченные check-<file>

        $file = $imgFile['file'];
        $file_ = str_replace('.', '_', $file);
        $chkName = 'check-' . $file_;

        if (isset($_POST[$chkName])) {
            $cmtName = 'comment-' . $file_;    // имя поля комментария
            $comment = $_POST[$cmtName];
            $checkedFiles[] = ['file' => $file, 'comment' => $comment];
        }

    }
    return $checkedFiles;
}

/**
 * Сохранить отмеченные картинки
 * @param $pdo
 * @param $galleryId
 */
function savePict($pdo,$galleryId) {
    $imgFiles = getImages($pdo, $galleryId); //
    $fileForSave = getCheckedList($imgFiles);
    putImages($pdo, $galleryId, $fileForSave);
}


function addPict($pdo,$galleryId) {
    $addImages = []; // ['file' => file,'comment' => comment] -  для загрузки в БД
    $errors = [];
    $filesNorm = filesTransform('pictures');  // преобразовать в нормальную форму

    $dirHeap = TOP_DIR.'/pictureHeap';
    $nLoaded = 0;

    foreach ($filesNorm as $fdes) {
        $name = $fdes['name'];
        $tmpName = $fdes['tmp_name'];
        $error = $fdes['error'];

        if (!0 == $error) {
            addControlMessage("ERROR: Ошибка выбора файла:" . $name . " код ошибки: " . $error) ;
            continue;
        }
        $addImages[] = ['file' => $name, 'comment' => NEW_COMMENT];
        if (doubleLoad($dirHeap, $name)) {
            addControlMessage("INFO: Попытка повторной загрузки файла :" . $name) ;
        }
        $fileTo = $dirHeap . '/' . basename($name);
        if (is_uploaded_file($tmpName)) {
            $res = move_uploaded_file($tmpName, $fileTo);
            $nLoaded++;
        }

    }
    $newOnly = true;   // блокирует изменение имеющихся в БД
    putImages($pdo, $galleryId, $addImages, $newOnly);    // добавить в БД/обновить комментарий
    addControlMessage('INFO:Загружено  файлов:' . $nLoaded) ;

}
function addFromBuffer($pdo, $galleryId) {
    $newOnly = true;   // блокирует изменение имеющихся в БД
    $addImages = $_SESSION['galleryBuffer'];
    putImages($pdo, $galleryId, $addImages, $newOnly);    // добавить в БД/обновить комментарий
}
function delCheckedPict($pdo, $galleryId) {
    $imgFiles = getImages($pdo, $galleryId); //
    $fileForSave = getCheckedList($imgFiles);
    delImages($pdo, $galleryId, $fileForSave);
}
function copyPictToBuffer($pdo, $galleryId) {
    $imgFiles = getImages($pdo, $galleryId); //
    $_SESSION['galleryBuffer'] = getCheckedList($imgFiles);
}