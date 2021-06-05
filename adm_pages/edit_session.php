<?php

    require_once '../config/connect.php';

    $id = $_GET['id'];
    $ses = mysqli_query($connect, "SELECT * FROM `session_t` WHERE  `id` = '$id'");
    $ses = mysqli_fetch_assoc($ses);

    $film_id = $ses['film_id'];
    $film = mysqli_query($connect, "SELECT * FROM `film_t` WHERE `id` = '$film_id'");
    $film = mysqli_fetch_assoc($film);

    $hall_id = $ses['hall_id'];
    $hall = mysqli_query($connect, "SELECT * FROM `hall_t` WHERE `id` = '$hall_id'");
    $hall = mysqli_fetch_assoc($hall);

    $info = ' по фильму ' . '"' . $film['name'] . '"' . ' в ' . $ses['date_time'] . ' в зале ' . $hall['public_number'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Изменить сеанс</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">

        <form action="../vendor/edit_session.php" method="post" class="add-form">

            <h2 class="add-header">Изменить сеанс<?= $info ?></h2>
                <input type="hidden" name="id" value="<?= $ses['id'] ?>">
                <p>Дата и время</p>
                <input type="datetime-local" name="date_time" value="<?= date('Y-m-d\TH:i:s', strtotime($ses['date_time']));?>">
                <p>Номер зала</p>
                <select name="hall_number">
                    <?php  
                        $halls = mysqli_query($connect, "SELECT * FROM `hall_t`");
                        $halls = mysqli_fetch_all($halls);
                        foreach ($halls as $h) {
                            if ($h[0] == $ses['hall_id']) {
                                ?>
                                <option selected value="<?= $h[0] ?>"><?= $h[3] ?></option>
                                <?php
                            }
                            else {
                                ?>
                                <option value="<?= $h[0] ?>"><?= $h[3] ?></option>
                                <?php
                            }
                        }
                    ?>
                </select>
                <p>Название фильма</p>
                <select name="film_name">
                    <?php  
                        $films = mysqli_query($connect, "SELECT * FROM `film_t`");
                        $films = mysqli_fetch_all($films);
                        foreach ($films as $f) {
                            if ($f[0] == $ses['film_id']) {
                                ?>
                                <option selected value="<?= $f[0] ?>"><?= $f[1] ?></option>
                                <?php
                            }
                            else {
                                ?>
                                <option value="<?= $f[0] ?>"><?= $f[1] ?></option>
                                <?php
                            }
                        }
                    ?>
                </select>
                <p>Цена в рублях</p>
                <input type="number" name="price" value="<?= $ses['price']?>">
                <button type="submit">Изменить</button>
        </form>
    </div>
</body>
</html>