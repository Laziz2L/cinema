<?php

require_once '../config/connect.php';

$id = $_POST['id'];
$rows = $_POST['rows'];
$row_seats = $_POST['row_seats'];
$public_number = $_POST['public_number'];

mysqli_query($connect, "UPDATE `hall_t` SET `rows` = '$rows', `row_seats` = '$row_seats', 
                        `public_number` = '$public_number' WHERE `hall_t`.`id` = '$id'");

header('Location: ../adm_pages/admin_halls.php');
?>