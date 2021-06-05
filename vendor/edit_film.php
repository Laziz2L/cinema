<?php

require_once '../config/connect.php';

$id = $_POST['id'];
$title = $_POST['title'];
$director = $_POST['director'];
$release = $_POST['release'];
$duration = $_POST['duration'];

mysqli_query($connect, "UPDATE `film_t` SET `name` = '$title', `director` = '$director', 
                            `release_date` = '$release', `duration` = '$duration' WHERE `film_t`.`id` = '$id'");

header('Location: ../adm_pages/admin_films.php');
?>