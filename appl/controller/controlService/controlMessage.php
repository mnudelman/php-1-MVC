<?php
/**
 *  Функции общего назначения
 */
/**
 * Добавление сообщения в список
 * сообщения выводятся в окноСообщений любого  viewer'a.
 * @param $message - текстСообщения
 */
function addControlMessage($message) {
    $messageList = (isset($_SESSION['controlMessage'])) ? $_SESSION['controlMessage'] : [] ;
    $messageList[] = $message ;
    $_SESSION['controlMessage'] = $messageList ;
    return true ;
}