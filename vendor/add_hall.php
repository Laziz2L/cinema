<?php

require_once '../config/connect.php';

$rows = $_POST['rows'];
$row_seats = $_POST['row_seats'];
$public_number = $_POST['public_number'];

mysqli_query($connect, "INSERT INTO `hall_t` (`id`, `rows`, `row_seats`, `public_number`) 
                        VALUES (NULL, '$rows', '$row_seats', '$public_number')");

header('Location: ../adm_pages/admin_halls.php');
?>