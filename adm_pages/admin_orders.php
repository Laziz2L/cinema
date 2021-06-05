<?php

require_once '../config/connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Кинотеатр</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../script.js"></script>
</head>
<body>
<header class="header">
    <div class="container">
        <div class="header-inner">
            <h1 class="header-title">Смотри Кино</h1>
            <div class="header-links">
                <div class="nav-links">
                    <a href="admin_films.php">Фильмы</a>
                    <a href="admin_halls.php">Залы</a>
                    <a href="admin_sessions.php">Сеансы</a>
                    <a class="active" href="#">Бронирования</a>
                </div>
                <a href="../sessions_page.php">Выйти</a>
            </div>
        </div>
    </div>
</header>

<main class="main">
    <div class="container">
        <h2 class="page-title">Бронирования</h2>

        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>ID сеанса</th>
                <th>Время</th>
                <th>Название фильма</th>
                <th>Номер зала</th>
                <th>Телефон</th>
                <th>Имя</th>
                <th>Ряд</th>
                <th>Место</th>
            </tr>


            <?php
            if (isset($_GET['ses_id'])) {
                $session_id = $_GET['ses_id'];
                $orders = mysqli_query($connect, "SELECT * FROM `order_t` WHERE  `session_id` = '$session_id'");
            }
            else $orders = mysqli_query($connect, "SELECT * FROM `order_t`");
            $orders = mysqli_fetch_all($orders);
            foreach ($orders as $o) {
                $session_id = $o[5];

                $session = mysqli_query($connect, "SELECT * FROM `session_t` WHERE  `id` = '$session_id'");
                $session = mysqli_fetch_assoc($session);

                $film_id = $session['film_id'];
                $film = mysqli_query($connect, "SELECT * FROM `film_t` WHERE  `id` = '$film_id'");
                $film = mysqli_fetch_assoc($film);

                $hall_id = $session['hall_id'];
                $hall = mysqli_query($connect, "SELECT * FROM `hall_t` WHERE  `id` = '$hall_id'");
                $hall = mysqli_fetch_assoc($hall);
                ?>

                <tr>
                    <td><?= $o[0] ?></td>
                    <td><?= $session['id'] ?></td>
                    <td><?= $session['date_time'] ?></td>
                    <td><?= $film['name'] ?></td>
                    <td><?= $hall['public_number'] ?></td>
                    <td><?= $o[1] ?></td>
                    <td><?= $o[2] ?></td>
                    <td><?= $o[3] ?></td>
                    <td><?= $o[4] ?></td>
                    <td><a href="../vendor/delete_order.php?id=<?= $o[0] ?>"
                           onclick="return confirmDelete(3);">Удалить</a></td>
                </tr>

                <?php
            }
            ?>
        </table>

    </div>
</main>
</body>
</html>
