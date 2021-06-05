<?php

require_once '../config/connect.php';

$show_date = null;

if (isset($_GET['date'])) {
    $show_date = $_GET['date'];
}

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
                    <a class="active" href="#">Сеансы</a>
                    <a href="admin_orders.php">Бронирования</a>
                </div>
                <a href="../sessions_page.php">Выйти</a>
            </div>
        </div>
    </div>
</header>

<main class="main">
    <div class="container">

        <form action=".././vendor/admin_show_sessions_by_day.php" method="post" class="form">
            <div class="select">
                <select name="date">
                    <?php
                    $getDate = function ($s) {
                        return substr($s[1], 0, 10);
                    };

                    $sortDates = function ($a, $b) {
                        $a_val = strtotime($a);
                        $b_val = strtotime($b);
                        if ($a_val == $b_val) {
                            return 0;
                        }
                        return ($a_val < $b_val) ? -1 : 1;
                    };

                    function convertDay($d)
                    {
                        $r = '';
                        switch ($d) {
                            case 'Mon':
                                $r = 'Пн';
                                break;
                            case 'Tue':
                                $r = 'Вт';
                                break;
                            case 'Wed':
                                $r = 'Ср';
                                break;
                            case 'Thu':
                                $r = 'Чт';
                                break;
                            case 'Fri':
                                $r = 'Пт';
                                break;
                            case 'Sat':
                                $r = 'Сб';
                                break;
                            case 'Sun':
                                $r = 'Вс';
                                break;
                        }
                        return $r;
                    }

                    function convertDate($d)
                    {
                        $day_week = convertDay(substr(strftime("%a, %d/%m/%Y", strtotime($d)), 0, 3));
                        $r = $day_week . ' ' . strftime("%d/%m/%Y", strtotime($d));
                        return $r;
                    }

                    $ses = mysqli_query($connect, "SELECT * FROM `session_t`");
                    $ses = mysqli_fetch_all($ses);
                    $dates = array_map($getDate, $ses);
                    $dates = array_unique($dates);
                    usort($dates, $sortDates);
                    if (!isset($show_date)) $show_date = $dates[0];
                    foreach ($dates as $d) {
                        if ($show_date && $d == $show_date) {
                            ?>
                            <option selected value="<?= $d ?>"><?= convertDate($d) ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="<?= $d ?>"><?= convertDate($d) ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="get_hall_id" value="<?php if (isset($_GET['hall_id'])) echo $_GET['hall_id'] ?>" />
            <input type="hidden" name="get_film_id" value="<?php if (isset($_GET['film_id'])) echo $_GET['film_id'] ?>" />
            <button type="submit">Показать</button>
        </form>

        <h2 class="page-title">Сеансы</h2>

        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Дата и время</th>
                <th>Номер зала</th>
                <th>Свободные места</th>
                <th>Название фильма</th>
                <th>Цена</th>
            </tr>


            <?php
            if (isset($_GET['film_id'])) {
                $id = $_GET['film_id'];
                $sessions = mysqli_query($connect, "SELECT * FROM `session_t` WHERE  `film_id` = '$id'");
            } elseif (isset($_GET['hall_id'])) {
                $id = $_GET['hall_id'];
                $sessions = mysqli_query($connect, "SELECT * FROM `session_t` WHERE  `hall_id` = '$id'");
            } else $sessions = mysqli_query($connect, "SELECT * FROM `session_t`");

            $sessions = mysqli_fetch_all($sessions);

            if (isset($_GET['date'])) {
                $ses1 = array();
                foreach ($sessions as $s) {
                    if (substr($s[1], 0, 10) == $show_date) array_push($ses1, $s);
                }
                $sessions = $ses1;
            }

            foreach ($sessions as $s) {
                $id = $s[5];
                $hall = mysqli_query($connect, "SELECT * FROM `hall_t` WHERE  `id` = '$id'");
                $hall = mysqli_fetch_assoc($hall);

                $all_seats = 0;
                $free_seats = 0;
                $seats = json_decode($s[2], true);

                foreach ($seats as $row) {
                    foreach ($row as $seat) {
                        if ($seat) $free_seats++;
                        $all_seats++;
                    }
                }

                $id = $s[4];
                $film = mysqli_query($connect, "SELECT * FROM `film_t` WHERE  `id` = '$id'");
                $film = mysqli_fetch_assoc($film);
                ?>

                <tr>
                    <td><?= $s[0] ?></td>
                    <td><?= $s[1] ?></td>
                    <td><?= $hall['public_number'] ?></td>
                    <td><?= $free_seats ?> / <?= $all_seats ?></td>
                    <td><?= $film['name'] ?></td>
                    <td><?= $s[3] ?></td>
                    <td><a href="admin_orders.php?ses_id=<?= $s[0] ?>">Бронирования</a></td>
                    <td><a href="edit_session.php?id=<?= $s[0] ?>">Изменить</a></td>
                    <td><a href="../vendor/delete_session.php?id=<?= $s[0] ?>"
                           onclick="return confirmDelete(4);">Удалить</a></td>
                </tr>

                <?php
            }
            ?>
        </table>

        <form action="../vendor/add_session.php" method="post" class="add-form">
            <h2 class="add-header">Добавить новый сеанс</h2>
            <p>Дата и время</p>
            <input type="datetime-local" name="date_time">
            <p>Номер зала</p>
            <select name="hall_number">
                <?php
                $halls = mysqli_query($connect, "SELECT * FROM `hall_t`");
                $halls = mysqli_fetch_all($halls);
                foreach ($halls as $h) {
                    ?>
                    <option value="<?= $h[0] ?>"><?= $h[3] ?></option>
                    <?php
                }
                ?>
            </select>
            <p>Название фильма</p>
            <select name="film_name">
                <?php
                $films = mysqli_query($connect, "SELECT * FROM `film_t`");
                $films = mysqli_fetch_all($films);
                foreach ($films as $f) {
                    ?>
                    <option value="<?= $f[0] ?>"><?= $f[1] ?></option>
                    <?php
                }
                ?>
            </select>
            <p>Цена в рублях</p>
            <input type="number" name="price">
            <br>
            <button type="submit">Добавить</button>
        </form>
    </div>
</main>
</body>
</html>
