<?php

    require_once '../config/connect.php';

    $id = $_GET['id'];
    $film = mysqli_query($connect, "SELECT * FROM `film_t` WHERE  `id` = '$id'");
    $film = mysqli_fetch_assoc($film);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Изменить фильм</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <form action="../vendor/edit_film.php" method="post" class="add-form">
            <h2 class="add-header">Изменить фильм <span class="edit-title">"<?= $film['name'] ?>"</span></h2>
            <input type="hidden" name="id" value="<?= $film['id'] ?>">
            <p>Название</p>
            <input type="text" name="title" value="<?= $film['name'] ?>">
            <p>Режиссер</p>
            <input type="text" name="director" value="<?= $film['director'] ?>">
            <p>Дата выхода в прокат</p>
            <input type="date" name="release" value="<?= $film['release_date'] ?>">
            <p>Продолжительность в минутах</p>
            <input type="number" name="duration" value="<?= $film['duration'] ?>">
            <button type="submit">Изменить</button>
        </form>
    </div>
</body>
</html>