<?php
session_start();
/**
 *   форма ввода  редактирования профиля
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

    <link rel="stylesheet" type="text/css" href="<?php echo '/' . TOP_HTML_DIR ?>/styles/task.css">
    <link rel="stylesheet" type="text/css" href="<?php echo '/' . TOP_HTML_DIR ?>/styles/formStyle-1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo '/' . TOP_HTML_DIR ?>/styles/galleryStyle-1.css">

</head>
<body>
<?php
include_once VIEW_DIR . '/topMenu.php';
?>
<div id="content-1">
    <?php
    include_once VIEW_DIR . '/messageForm.php';    // вывод сообщений
    ?>
    <?php
    $successfulRegistration = (!empty($_SESSION['successfulRegistration'])) ;
    $profile = $_SESSION['profile'] ;
    $profileEditFlag = isset($_SESSION['tmp_profileEditFlag']) ;
    if ($successfulRegistration && !$profileEditFlag) {
        ?>
        <a href="<?php echo '/'.TOP_HTML_DIR.'/index.php' ?>"><strong>Регистрация завершена успешно</strong></a>
    <?php
    } else {   // выводить форму
        ?>
        <?php
        if (!empty($_SESSION['tmp_profileEditFlag'])) {
            echo '<h3>Ваш профиль</h3>' . LINE_END;
        } else {
            echo '<h3>Заполните карту регистрации</h3>' . LINE_END;
        }
        ?>
        <form action="<?php echo '/'.CONTROLLER_HTML_DIR ?>/indexProfile.php" method="post" class="formColor">

            <label> <span class="label"><strong>Ваша фамилия:</strong></span>
                <input class="field" type="text" name="lastname"
                <?php echo 'value="' . $profile['lastname'] . '"> ' ?>
            </label> <br>

            <label> <span class="label"> <strong>имя:</strong> </span>
                <input class="field" type="text" name="firstname"
                <?php echo 'value="' . $profile['firstname'] . '"> ' ?>
            </label> <br>

            <label> <span class="label"> <strong>Отчество:</strong></span>
                <input class="field" type="text" name="middlename"
                <?php echo 'value="' . $profile['middlename'] . '"> ' ?>
            </label> <br>


            <label> <span class="label"><strong>Эл.почта:</strong>  </span>
                <input class="field1" type="email" name="email"
                <?php echo 'value="' . $profile['email'] . '"> ' ?>
            </label> <br>
            <?php
            if (!isset($_SESSION['tmp_profileEditFlag'])) { // при проосмотре профиля login,passw убираю
                // для простоты
                ?>
            <label> <span class="label"><strong>login*:</strong></span>
                <input class="field1" type="text" name="login"
                <?php echo 'value="' . $profile['login'] . '"> ' ?>
            </label> <br>
            </label>    <span class="label"><strong>пароль*:</strong></span>
            <input class="field1" type="password" name="password"
            <?php echo 'value="' . $profile['password'] . '"> ' ?>
            </label> <br>
            <?php
            }
            ?>
            <p><span class="label"><strong>пол:</strong></span>

            <div class="group_item">
                <label>
                    <input type="radio" name="sex" value="m"
                        <?php echo ("m" == $profile['sex']) ? "checked" : ''; ?>
                        >
                    мужской </label>
                <label>
                    <input type="radio" name="sex" value="w"
                        <?php echo ("w" == $profile['sex']) ? "checked" : ''; ?>
                        >
                    женский</label></br>
            </div>
            </p>
            <p><span class="label"><strong>Дата рождения:</strong></span>

            <div class="group_item">
                &nbsp;&nbsp;год:
                <select name="birthday_year">
                    <?php
                    for ($i = 1920; $i <= 2010; $i++) {
                        $selected = ($i == $profile['birthday_year']) ? "selected" : '';
                        echo '<option value="' . $i . '"  ' . $selected . '>' . $i . '</option>';
                    }
                    ?>
                </select>
                &nbsp;&nbsp;месяц:
                <select name="birthday_month">
                    <?php
                    $monthList = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь',
                        'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
                    foreach ($monthList as $i => $month) {
                        $selected = ($i == $profile['birthday_month']) ? "selected" : '';
                        echo '<option value="' . $i . '"  ' . $selected . '>' . $month . '</option>';
                    }
                    ?>
                </select>
                &nbsp;&nbsp;день:
                <select name="birthday_day">
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                        $selected = ($i == $profile['birthday_day']) ? "selected" : '';
                        echo '<option value="' . $i . '"  ' . $selected . '>' . $i . '</option>';
                    }
                    ?>

                </select><br>
            </div>
            <p>
                <strong>Добавьте произвольную информацию о себе:</strong> <br>
        <textarea width=“1000px” height=“150px” name="info" value="дополнительная информация">
            <?php echo $profile['info'] ?>
          </textarea>

            </p>
            <?php
            if (!$profileError) {   // при ошибке кнопки не выводятся
                ?>

                <p><input type="submit" name="exec" value="Сохранить">
                    <input type="reset" value="Сбросить">
                    <button name="exit">Прервать</button>
                </p>
            <?php
            }
            ?>

        </form>
    <?php
    }
    ?>
</div>

</body>
</html>