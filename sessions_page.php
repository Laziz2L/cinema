<?php

require_once './config/connect.php';

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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-inner">
                <h1 class="header-title">Смотри Кино</h1>
                <div class="header-links">
<!--                    <div class="nav-links">-->
<!--                        <a href="#">Фильмы</a>-->
<!--                        <a class="active" href="#">Сеансы</a>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="content">
                <form action="./vendor/show_sessions_by_day.php" method="post" class="form">
                    <div class="select">
                        <select name="date">
                            <?php  
                                $getDate = function($s) {
                                    return substr($s[1], 0, 10);
                                };

                                $filterDate = function($d) {
                                    $current_date = date("Y-m-d");
                                    return (strtotime($d) >= strtotime($current_date));
                                };

                                $sortDates = function($a, $b) {
                                    $a_val = strtotime($a);
                                    $b_val = strtotime($b);
                                    if ($a_val == $b_val) {
                                        return 0;
                                    }
                                    return ($a_val < $b_val) ? -1 : 1;
                                };

                                function convertDay($d) {
                                    $r = '';
                                    switch ($d) {
                                        case 'Mon': $r = 'Пн'; break;
                                        case 'Tue': $r = 'Вт'; break;
                                        case 'Wed': $r = 'Ср'; break;
                                        case 'Thu': $r = 'Чт'; break;
                                        case 'Fri': $r = 'Пт'; break;
                                        case 'Sat': $r = 'Сб'; break;
                                        case 'Sun': $r = 'Вс'; break;
                                    }
                                    return $r;
                                }

                                function convertDate($d) {
                                    $day_week = convertDay(substr(strftime("%a, %d/%m/%Y", strtotime($d)), 0, 3));
                                    $r = $day_week . ' ' . strftime("%d/%m/%Y", strtotime($d));
                                    return $r;
                                }

                                $ses = mysqli_query($connect, "SELECT * FROM `session_t`");
                                $ses = mysqli_fetch_all($ses);
                                $dates = array_map($getDate, $ses);
                                $dates = array_unique($dates);
                                $dates = array_filter($dates, $filterDate);
                                usort($dates, $sortDates);
                                if (count($dates) > 8) {
                                    $dates = array_slice($dates, 0, 8);
                                }
                                if (!isset($show_date)) $show_date = $dates[0];
                                foreach ($dates as $d) {
                                     if ($show_date && $d == $show_date) {
                                        ?>
                                        <option selected value="<?= $d ?>"><?= convertDate($d) ?></option>
                                        <?php
                                     }
                                     else {
                                        ?>
                                        <option value="<?= $d ?>"><?= convertDate($d) ?></option>
                                        <?php
                                     }
                                }
                            ?>
                        </select>
                    </div>
                    <button type="submit">Показать</button>
                </form>
                
                <?php
                    $ses = mysqli_query($connect, "SELECT * FROM `session_t`");
                    $ses = mysqli_fetch_all($ses);
                    $ses1 = array();
                    foreach ($ses as $s) {
                        if (substr($s[1], 0, 10) == $show_date) array_push($ses1, $s);
                    }

                    $ses = $ses1;

                    $all_films =  mysqli_query($connect, "SELECT * FROM `film_t`");
                    $all_films = mysqli_fetch_all($all_films);

                    $film_sections = array();

                    foreach ($all_films as $f) {
                        $price = 9999;
                        $times = array();
                        foreach ($ses as $s) {
                            if ($s[4] == $f[0]) {
                                if ($s[3] < $price) $price = $s[3];
                                $time = ['id' => $s[0], 'time' => $s[1]];
                                array_push($times, $time);
                            }
                        }
                        $film_sec = ['film_name' => $f[1],
                                        'genre' => $f[5], 
                                        'duration' => $f[4],
                                        'director' => $f[2],
                                        'price' => $price,
                                        'times' => $times];
                        if (!($times == array())) array_push($film_sections, $film_sec);
                    }

                    foreach ($film_sections as $f) {
                        ?>
                        <section class="film">
                            <div class="film-info">
                                <h1 class="film-title"><?= $f['film_name'] ?></h1>
                                <p class="film-genre"><?= $f['genre'] ?></p>
                                <p class="film-length"><?= (string)floor($f['duration']/60)  . " ч " . (string)($f['duration']%60) . " мин" ?></p>
                                <p class="film-author">режиссер: <?= $f['director'] ?></p>
                            </div>

                            <div class="film-session-info">
                                <p class="film-session-price">от <?= $f['price'] ?>&#8381;</p>
                            </div>

                            <div class="film-sessions">
                                <?php
                                    foreach ($f['times'] as $t) {
                                    ?>
                                         <a href="./booking_page.php?id=<?= $t['id'] ?>" class="film-session-book"><?= substr($t['time'], 11, 5) ?></a>
                                    <?php
                                    }
                                ?>
                            </div>
                        </section>
                        <?php
                    }
                ?>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <a href="adm_pages/admin_films.php" class="footer-link">Вход для администрации</a>
        </div>
    </footer>

</body>
</html>
