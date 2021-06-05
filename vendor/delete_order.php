<?php

require_once '../config/connect.php';
$id = $_GET['id'];

$order = mysqli_query($connect, "SELECT * FROM `order_t` WHERE  `id` = '$id'");
$order = mysqli_fetch_assoc($order);

$session_id = $order['session_id'];
$session = mysqli_query($connect, "SELECT * FROM `session_t` WHERE  `id` = '$session_id'");
$session = mysqli_fetch_assoc($session);

$seats = json_decode($session['seats']);
$seats[$order['row'] - 1][$order['seat'] - 1] = true;
$seats = json_encode($seats);

mysqli_query($connect, "UPDATE `session_t` SET
                         `seats` = '$seats'
                        WHERE `session_t`.`id` = '$session_id'");

mysqli_query($connect, "DELETE FROM `order_t` WHERE `order_t`.`id` = '$id'");

header('Location: ../adm_pages/admin_orders.php');
?>