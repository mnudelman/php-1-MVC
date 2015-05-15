<?php
/**
 *  Функции общего назначения
 */
/**
 * Добавление сообщения в список
 * сообщения выводятся в окноСообщений любого  viewer'a.
 * @param $message - текстСообщения
 */
function addMessage($message) {
    $messageList = (isset($_SESSION['modelMessage'])) ? $_SESSION['modelMessage'] : [] ;
    $messageList[] = $message ;
    $_SESSION['modelMessage'] = $messageList ;
    return true ;
}