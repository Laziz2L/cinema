<?php

    require_once '../config/connect.php';

    $id = $_GET['id'];
    $hall = mysqli_query($connect, "SELECT * FROM `hall_t` WHERE  `id` = '$id'");
    $hall = mysqli_fetch_assoc($hall);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Изменить зал</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <form action="../vendor/edit_hall.php" method="post" class="add-form">
            <h2 class="add-header">Изменить зал <span class="edit-title">"<?= $hall['public_number'] ?>"</span></h2>
            <input type="hidden" name="id" value="<?= $hall['id'] ?>">
            <p>Количество рядов</p>
            <input type="number" name="rows" value="<?= $hall['rows'] ?>">
            <p>Количество сидений в каждом ряду</p>
            <input type="number" name="row_seats" value="<?= $hall['row_seats'] ?>">
            <p>Номер зала</p>
            <input type="number" name="public_number" value="<?= $hall['public_number'] ?>">
            <br>
            <button type="submit">Изменить</button>
        </form>
    </div>
</body>
</html>