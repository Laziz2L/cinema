<?php

require_once '../config/connect.php';
$id = $_GET['id'];

mysqli_query($connect, "DELETE FROM `film_t` WHERE `film_t`.`id` = '$id'");
mysqli_query($connect, "DELETE FROM `session_t` WHERE `session_t`.`film_id` = '$id'");

header('Location: ../adm_pages/admin_films.php');
?>