<?php

require_once '../config/connect.php';


$date_time = $_POST['date_time'];
$hall_id = $_POST['hall_number'];
$film_id = $_POST['film_name'];
$price = $_POST['price'];

$sessions = mysqli_query($connect, "SELECT * FROM `session_t` WHERE  `date_time` = '$date_time'");
$sessions = mysqli_fetch_all($sessions);

$flag = true;

foreach ($sessions as $s) {
    if ($s[2] == $hall_id) {
        $flag = false;
        echo "<script>alert('В это время в этом зале уже есть сеанс!');</script>";
    }
}

if ($flag) {
    $hall = mysqli_query($connect, "SELECT * FROM `hall_t` WHERE  `id` = '$hall_id'");
    $hall = mysqli_fetch_assoc($hall);

    for ($r = 0; $r < $hall['rows']; $r++) {
        for ($s = 0; $s < $hall['row_seats']; $s++) {
            $seats[$r][$s] = true;
        }
    }

    $seats = json_encode($seats);

    mysqli_query($connect, "INSERT INTO `session_t` (`id`, `date_time`, `hall_id`, `seats`, `film_id`, `price`)
                                    VALUES (NULL, '$date_time', '$hall_id', '" . $seats . "', '$film_id', '$price')");

    header('Location: ../adm_pages/admin_sessions.php');
}
?>