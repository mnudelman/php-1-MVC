<?php
/**
 * Набор функций, обеспечивающих работу с изображениями
 */

/**
 * Взять из БД списокФайлов-изображений
 *
 * @param $galleryId
 * @return array - список изображений
 */
include_once __DIR__ .'/service.php' ;

function getImages($pdo,$galleryId)
{
    $images = [];   // ['file' => $file,'comment' => $comment]
    $sql = 'SELECT fileimg,
                        comment
                        FROM galleryContent
                        WHERE galleryid = :galleryId';

    try {
        $smt = $pdo->prepare($sql);
        $smt->execute(['galleryId' => $galleryId]);
    } catch (PDOException  $e) {
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false;
    }
    if ( 0 == $smt->rowCount() ){
        return false ;
    }
    foreach ($smt as $row) {
        $images[] = ['file' => $row['fileimg'],
            'comment' => $row['comment']
        ];
    }
    return $images ;

}
function findFileImg($pdo,$galleryId,$fileImg) {
    $sql = 'SELECT * FROM galleryContent
                WHERE galleryid = :galleryId AND fileimg = :fileImg ' ;
    try {
        $smt = $pdo->prepare($sql) ;
        $smt->execute(['galleryId' => $galleryId,
            'fileImg'   => $fileImg]) ;
        $row = $smt->fetch(PDO::FETCH_ASSOC) ;

    }catch (PDOException  $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    return ( false === $row) ? false : true ;
}
/**
 * Помещает в БД списокФайлов-изображений
 *
 * @param $galleryId
 * @param $images
 * @return int
 */
function putImages($pdo,$galleryId,$images,$newOnly = false) {
    $n = 0 ;
    $sqlInsert = 'INSERT INTO galleryContent (galleryid,fileImg,comment )
                               VALUES (:galleryId ,:file ,:comment)';
    $sqlUpdate = 'UPDATE galleryContent
                        SET comment = :comment
                        WHERE galleryId = :galleryId AND
                              fileImg = :file ' ;
    try{
        $smtInsert = $pdo->prepare($sqlInsert) ;
        $smtUpdate = $pdo->prepare($sqlUpdate) ;
        foreach($images as $img) {
            $file = $img['file'] ;
            $comment = $img['comment'] ;
            $n = 0 ;
            if (false === findFileImg($pdo,$galleryId,$file)) {
                $smtInsert->execute(['galleryId' => $galleryId,
                    'file'      => $file,
                    'comment'   => $comment]) ;
            }elseif ($newOnly){     // только добавление новых
                continue ;
            }else {
                $smtUpdate->execute(['galleryId' => $galleryId,
                    'file'     => $file,
                    'comment'  => $comment]) ;
            }
            $n ++ ;
        }
    }catch (PDOException $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    return $n ;
}

/**
 * Удалить из БД списокФайлов
 * @param $userId
 * @param $galleryId
 * @param $images
 * @return int
 */
function delImages($pdo,$galleryId,$images) {
    $n = 0 ;
    $sql = 'DELETE FROM galleryContent WHERE galleryId = :galleryId AND fileImg = :file' ;
    try {
        $smt = $pdo->prepare($sql);
        foreach ($images as $img) {
            $file = $img['file'];
            if (true === findFileImg($pdo,$galleryId, $file)) {
                $smt->execute(['galleryId'=> $galleryId,
                    'file'      => $file] ) ;
                $n++ ;
            }
        }
    }catch (PDOException $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    return $n ;
}

/**
 * копировать список файловИзображений в другую галерею
 * @param $userFrom
 * @param $galleryFrom
 * @param $images
 * @param $userTo
 * @param $galleryTo
 * @return int
 */
function copyImages($pdo,$galleryIdFrom,$userTo,$images) {
    $n = 0 ;
    return $n ;
}

/**
 * возвращает список галерей(альбомов), принадлежащих $userOwner
 * если empty($userOwner), то все галереи из БД (для просмотра доступны все)
 * @param $userOwner - это userLogin
 * @return array
 */
function getGallery ($pdo,$userOwner) {
    $galleryList = [] ; // ['owner' => $userlogin,'galleryid' => $galleryId,'galleryname' =>..]
    $sql = 'SELECT users.login,
                 gallery.galleryid,
                 gallery.themeName AS galleryname,
                 gallery.comment
                 from gallery,users
                 where gallery.userid = users.userid  '.
        ( (!empty($userOwner)) ?
            ' AND gallery.userid in (SELECT userid from users where login = :userOwner )' : ''
        ) .
        '  order by users.login' ;
    try{
        $smt = $pdo->prepare($sql) ;
        if (empty($userOwner)){
            $smt->execute() ;
        }else {
            $smt->execute(['userOwner'=>$userOwner]) ;
        }

    }catch (PDOException $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    foreach ($smt as $row) {
        $galleryId = $row['galleryid'] ;
        $galleryList[$galleryId] = ['owner' => $row['login'],
            'galleryid' => $row['galleryid'],
            'galleryname' => $row['galleryname']
        ] ;
    }
    return $galleryList ;
}

/**
 * наличие галереи с заданным именем у пользователя
 * @param $userOwner
 * @param $galleryName
 * @return bool
 */
function findGallery($pdo,$userOwner,$galleryName){
    $sql = 'SELECT gallery.galleryid from gallery where gallery.themeName = :galleryName
                 AND gallery.userid IN (SELECT userid FROM users WHERE login = :userOwner )' ;
    try{
        $smt = $pdo->prepare($sql) ;
        $smt->execute(['galleryName' => $galleryName,
            'userOwner'  => $userOwner]) ;
    }catch (PDOException $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    return     $smt->rowCount() > 0 ;
}

/**
 * опрелить userid по  login
 * @param $login
 * @return userid
 */
function getUserid($pdo,$login) {
    $sql = 'SELECT * FROM users where login = :login' ;
    try{
        $smt = $pdo->prepare($sql) ;
        $smt->execute(['login'  => $login]) ;
    }catch (PDOException $e){
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false ;
    }
    $row = $smt->fetch(PDO::FETCH_ASSOC);

    return  (false === $row) ? false : $row['userId']  ;
}


/**
 * Добавить галерею пользователя
 * @param $userOwner
 * @param $galleryName
 * @return bool
 */
function putGallery ($pdo,$userOwner,$galleryName) {
    if (findGallery($pdo,$userOwner, $galleryName)) {
        return true;
    }
    $userid = getUserId($pdo,$userOwner);
    $sql = 'INSERT INTO gallery (userid,themeName) VALUES (:userid,:galleryName)';
    try {
        $smt = $pdo->prepare($sql);
        $smt->execute(['userid'     => $userid,
            'galleryName'=> $galleryName]);
    } catch (PDOException $e) {
        addMessage('ERROR:'.__FUNCTION__.':' . $e->getMessage() ) ;
        return false;
    }
    return  true;
}

/**
 * удалить галерею пользователя
 * @param $userOwner
 * @param $galleryName
 * @return bool
 */
function delGallery ($pdo,$userOwner,$galleryName) {
    return true ;
}

/**
 * преобразует  $_FILES в нормальную форму
 * @param $topName
 * @return array
 */
function filesTransform($topName)
{
    /** переведем $_FILES в нормальную форму */
    $filesNorm = [];
    $names = $_FILES[$topName]['name'];
    $n = count($names);      // количество файлов
    for ($i = 0; $i < $n; $i++) {
        $fName = $_FILES[$topName]['name'][$i];
        $fType = $_FILES[$topName]['type'][$i];
        $fTmpName = $_FILES[$topName]['tmp_name'][$i];
        $fError = $_FILES[$topName]['error'][$i];
        $fSize = $_FILES[$topName]['size'][$i];
        $filesNorm[] = [
            'name' => $fName,
            'type' => $fType,
            'tmp_name' => $fTmpName,
            'error' => $fError,
            'size' => $fSize

        ];
    }
    return $filesNorm;
}

function doubleLoad($dirName,$fName) {     // повторная загрузка
    return (file_exists($dirName.'/'.$fName)) ;
}
