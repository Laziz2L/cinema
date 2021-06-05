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
                        <a class="active" href="#">Фильмы</a>
                        <a href="admin_halls.php">Залы</a>
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
            <h2 class="page-title">Фильмы</h2>

            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Режиссёр</th>
                    <th>Дата выхода в прокат</th>
                    <th>Продолжительность</th>
                    <th>Жанр</th>
                </tr>

                
                <?php
                    $films = mysqli_query($connect, "SELECT * FROM `film_t`");
                    $films = mysqli_fetch_all($films);
                    foreach ($films as $f) {
                        ?>

                        <tr>
                            <td><?= $f[0] ?></td>
                            <td><?= $f[1] ?></td>
                            <td><?= $f[2] ?></td>
                            <td><?= $f[3] ?></td>
                            <td><?= (string)floor($f[4]/60)  . " ч " . (string)($f[4]%60) . " мин" ?></td>
                            <td><?= $f[5] ?></td>
                            <td><a href="admin_sessions.php?film_id=<?= $f[0] ?>">Сеансы</a></td>
                            <td><a href="edit_film.php?id=<?= $f[0] ?>">Изменить</a></td>
                            <td><a href="../vendor/delete_film.php?id=<?= $f[0] ?>"
                                    onclick="return confirmDelete(1);">Удалить</a></td>
                        </tr>

                        <?php
                    }
                ?>
            </table>   

            <form action="../vendor/add_film.php" method="post" class="add-form">
                <h2 class="add-header">Добавить новый фильм</h2>
                <p>Название</p>
                <input type="text" name="title">
                <p>Режиссер</p>
                <input type="text" name="director">
                <p>Дата выхода в прокат</p>
                <input type="date" name="release">
                <p>Продолжительность в минутах</p>
                <input type="number" name="duration">
                <p>Жанр</p>
                <input type="text" name="genre">
                <br>
                <button type="submit">Добавить</button>
            </form>
        </div>
    </main>
</body>
</html>
