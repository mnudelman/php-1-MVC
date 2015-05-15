<?php
/**
 *  Вывод сообщений полученных от контроллера или модели
 */
function messageShow($messages,$title)
{
    if (!empty($messages)) {
        ?>
        <form>
            <h3><?php echo $title?></h3>
            <textarea name="sqlText" readonly="readonly"
                      style="width:620px;height:200px;font-size:15px ;background-color: #d6e356; color:blue">
        <?php
        echo chr(10);

        foreach ($messages as $erTxt) {
            echo $erTxt . CHR(10);
            echo '' . CHR(10);
        }

        ?>
    </textarea><br>
        </form> <br>
    <?php
    }
}
?>
<?php
// вывести сообщения модели
$title = 'Сообщения модели' ;
messageShow($_SESSION['modelMessage'],$title) ;
// вывести сообщения контроллера
$title = 'Контроль данных' ;
messageShow($_SESSION['controlMessage'],$title) ;

// любая информация
$title = '' ;
messageShow($_SESSION['infoMessage'],$title) ;
$_SESSION['modelMessage']=[] ;
$_SESSION['controlMessage'] = [] ;
$_SESSION['infoMessage'] = [] ;