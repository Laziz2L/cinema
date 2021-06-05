<?php

require_once '../config/connect.php';


$title = $_POST['title'];
$director = $_POST['director'];
$release = $_POST['release'];
$duration = $_POST['duration'];
$genre = $_POST['genre'];

mysqli_query($connect, "INSERT INTO `film_t` (`id`, `name`, `director`, `release_date`, `duration`, `genre`) 
VALUES (NULL, '$title', '$director', '$release', '$duration', '$genre')");

header('Location: ../adm_pages/admin_films.php');
?>