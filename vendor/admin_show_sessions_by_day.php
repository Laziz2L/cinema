<?php

require_once '../config/connect.php';


$date = $_POST['date'];
$get_string = "";

if ($_POST['get_film_id']) {
    $get_string = $get_string . '&' . 'film_id=' . $_POST['get_film_id'];
}

if ($_POST['get_hall_id']) {
    $get_string = $get_string . '&' . 'hall_id=' . $_POST['get_hall_id'];
}

header('Location: ../adm_pages/admin_sessions.php?date=' . $date . $get_string);
?>