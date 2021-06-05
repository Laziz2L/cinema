<?php

require_once '../config/connect.php';

$id = $_POST['id'];
$date_time = $_POST['date_time'];
$hall_id = $_POST['hall_number'];
$film_id = $_POST['film_name'];
$price = $_POST['price'];
// $seats = $_POST['seats'];

$hall = mysqli_query($connect, "SELECT * FROM `hall_t` WHERE  `id` = '$hall_id'");
$hall = mysqli_fetch_assoc($hall);

// for ($r = 0; $r < $hall['rows']; $r++) {
//     for ($s = 0; $s < $hall['row_seats']; $s++) {
//         $seats[$r][$s] = true;
//     }
// }

// $seats = json_encode($seats);

mysqli_query($connect, "UPDATE `session_t` SET `date_time` = '$date_time', 
                        `hall_id` = '$hall_id',
                        -- `seats` = '$booked_seats', 
                        `film_id` = '$film_id', `price` = '$price' 
                        WHERE `session_t`.`id` = '$id'");

header('Location: ../adm_pages/admin_sessions.php');
?>