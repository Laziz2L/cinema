<?php

require_once '../config/connect.php';
$id = $_GET['id'];

mysqli_query($connect, "DELETE FROM `session_t` WHERE `session_t`.`id` = '$id'");

header('Location: ../adm_pages/admin_sessions.php');
?>