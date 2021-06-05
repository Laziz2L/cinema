<?php

require_once '../config/connect.php';

$ses_id = $_POST["session_id"];
$phone = $_POST["tel"];
$name = $_POST["name"];
$booked_seats[0] = $_POST["booked_seat_0"];
if (isset($_POST["booked_seat_1"]))
    $booked_seats[1] = $_POST["booked_seat_1"];
if (isset($_POST["booked_seat_2"]))
    $booked_seats[2] = $_POST["booked_seat_2"];
if (isset($_POST["booked_seat_3"]))
    $booked_seats[3] = $_POST["booked_seat_3"];
if (isset($_POST["booked_seat_4"]))
    $booked_seats[4] = $_POST["booked_seat_4"];

//загрузить и декодировать массив сидений по сеансу
$ses = mysqli_query($connect, "SELECT * FROM `session_t` WHERE  `id` = '$ses_id'");
$ses = mysqli_fetch_assoc($ses);
$seats_array = json_decode($ses['seats']);

foreach ($booked_seats as $s) {
    $row = intdiv($s, 1000);
    $seat = $s % 1000;
    //в массиве где row=row и seat = seat - 1 поменять значение на false
    $seats_array[$row-1][$seat-1] = false;
    mysqli_query($connect, "INSERT INTO `order_t` (`id`, `session_id`, `phone`, `name`, `row`, `seat`)VALUES (NULL, '$ses_id', '$phone', '$name', '$row', '$seat')");
}

$seats_array = json_encode($seats_array);
mysqli_query($connect, "UPDATE `session_t` SET `seats` = '$seats_array' WHERE `session_t`.`id` = '$ses_id'");

header('Location: ../sessions_page.php');
?>