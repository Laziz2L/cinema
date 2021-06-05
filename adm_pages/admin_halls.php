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
                        <a class="active" href="#">Залы</a>
                        <a href="admin_sessions.php">Сеансы</a>
                        <a href="admin_orders.php">Бронирования</a>
                    </div>
                    <a href="../sessions_page.php">Выйти</a>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <h2 class="page-title">Залы</h2>

            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Количество рядов</th>
                    <th>Количество сидений в каждом ряду</th>
                    <th>Номер зала</th>
                </tr>

                <?php
                    $halls = mysqli_query($connect, "SELECT * FROM `hall_t`");
                    $halls = mysqli_fetch_all($halls);
                    foreach ($halls as $h) {
                        ?>

                        <tr>
                            <td><?= $h[0] ?></td>
                            <td><?= $h[1] ?></td>
                            <td><?= $h[2] ?></td>
                            <td><?= $h[3] ?></td>
                            <td><a href="admin_sessions.php?hall_id=<?= $h[0] ?>">Сеансы</a></td>
                            <td><a href="edit_hall.php?id=<?= $h[0] ?>">Изменить</a></td>
                            <td><a href="../vendor/delete_hall.php?id=<?= $h[0] ?>" onclick="return confirmDelete(2);">Удалить</a></td>
                        </tr>

                        <?php
                    }
                ?>
            </table>   

            <form action="../vendor/add_hall.php" method="post" class="add-form">
                <h2 class="add-header">Добавить новый зал</h2>
                <p>Количество рядов</p>
                <input type="number" name="rows">
                <p>Количество сидений в каждом ряду</p>
                <input type="number" name="row_seats">
                <p>Номер зала</p>
                <input type="number" name="public_number">
                <br>
                <button type="submit">Добавить</button>
            </form>
        </div>
    </main>
</body>
</html>
