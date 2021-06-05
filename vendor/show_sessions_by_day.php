<?php

require_once '../config/connect.php';


$date = $_POST['date'];

header('Location: ../sessions_page.php?date=' . $date);
?>