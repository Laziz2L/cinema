<?php

require_once '../config/connect.php';
$id = $_GET['id'];

mysqli_query($connect, "DELETE FROM `hall_t` WHERE `hall_t`.`id` = '$id'");
mysqli_query($connect, "DELETE FROM `session_t` WHERE `session_t`.`hall_id` = '$id'");

header('Location: ../adm_pages/admin_halls.php');
?>