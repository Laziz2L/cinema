<?php

require_once './config/connect.php';

$id = $_GET['id'];
$ses = mysqli_query($connect, "SELECT * FROM `session_t` WHERE  `id` = '$id'");
$ses = mysqli_fetch_assoc($ses);

$film_id = $ses['film_id'];
$film = mysqli_query($connect, "SELECT * FROM `film_t` WHERE `id` = '$film_id'");
$film = mysqli_fetch_assoc($film);

$hall_id = $ses['hall_id'];
$hall = mysqli_query($connect, "SELECT * FROM `hall_t` WHERE `id` = '$hall_id'");
$hall = mysqli_fetch_assoc($hall);

$title = ' по фильму ' . '<span class="golden">' . $film['name'] . '</span>' . ' в '
    . '<span class="golden">' . $ses['date_time'] . '</span>' . ' в зале ' . '<span class="golden">'
    . $hall['public_number'] . '</span>';

$seats = json_decode($ses['seats']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Кинотеатр</title>
    <link rel="stylesheet" href="style.css">
    <script>
        let chosenCount = 0;
        let price = <?= $ses['price'] ?>;
        let totalPrice = 0;
        let chosenSeats = [];

        function chooseSeat(id) {
            let seatInfo = {row: Math.floor(id / 1000) + 1, seat: id % 1000};
            let seat = document.getElementById(id);
            if (seat.getAttribute("value") === "free") {
                if (chosenCount >= 5) {
                    alert("Максимальное количество мест для одного бронирования - 5.");
                    return;
                }
                seat.setAttribute("value", "chosen");
                seat.style.backgroundColor = '#A582FF';
                totalPrice += price;
                chosenCount++;
                chosenSeats.push(seatInfo);
            } else if (seat.getAttribute("value") === "chosen") {
                seat.setAttribute("value", "free");
                seat.style.backgroundColor = '#CC0033';
                totalPrice -= price;
                chosenCount--;
                deleteSeat(seatInfo);
            }
            update();
        }

        function deleteSeat(seatInfo) {
            for (let i = 0; i < chosenSeats.length; i++) {
                if (chosenSeats[i]['row'] == seatInfo['row'] && chosenSeats[i]['seat'] == seatInfo['seat']) {
                    chosenSeats.splice(i, 1);
                    return;
                }
            }
        }

        function update() {
            let container = document.getElementById("chosen_seats");
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }
            document.getElementById("chosen_count").innerText = "Выбрано мест: " + chosenCount;
            document.getElementById("total_price").innerText = "Стоимость: " + totalPrice + " рублей";
            if (chosenCount > 0) {
                for (let i = 0; i < chosenSeats.length; i++) {
                    let p = document.createElement("p");
                    p.innerHTML = `Ряд ${chosenSeats[i]["row"]} место ${chosenSeats[i]['seat']}`;
                    container.appendChild(p);

                    let input = document.createElement("input");
                    input.name = "booked_seat_" + i;
                    input.value = chosenSeats[i]['row']*1000 + chosenSeats[i]['seat'];
                    input.style.display = "none";
                    container.appendChild(input);
                }
            }
        }

        function checkQuery() {
            if (chosenCount <= 0) {
                alert("Не выбраны места!");
                return false;
            }
            let customerName = document.getElementsByName("name")[0].value;
            if (customerName == "") {
                alert("Введите ваше имя!");
                return false;
            }
            let customerTel = document.getElementsByName("tel")[0].value;
            if (customerTel == "") {
                alert("Введите ваш телефонный номер!");
                return false;
            }

            let seats = chosenSeats.map(s => `Ряд ${s["row"]} место ${s['seat']}`);
            seats = seats.reduce((prev, cur) => (prev + "\n" + cur));
            let msg = "Проверьте введенные данные:\nВаше имя: " + customerName +
                "\nВаш номер телефона: " + customerTel + "\nВыбранные места:\n" + seats;
            return confirm(msg);
        }
    </script>
</head>
<body>
<div class="container">
    <form action="./vendor/booking.php" method="post" class="booking-form" onsubmit="return checkQuery();">
        <input type="hidden" name="session_id" value="<?= $id ?>">
        <h2 class="add-header">Забронировать сеанс<?= $title ?></h2>
        <div class="booking_form_wrapper">
            <div>
                <?php
                foreach ($seats as $i => $row) {
                    ?>
                    <p>Ряд <?= ($i + 1) ?></p>
                    <?php
                    foreach ($row as $j => $seat) {
                        $seat_id = $i * 1000 + $j + 1;
                        if ($seat) {
                            ?>
                            <input id=<?= $seat_id ?> type="text" class="booking_input" value="free"
                                   onclick=chooseSeat(<?= $seat_id ?>)>
                            <?php
                        } else {
                            ?>
                            <input id=<?= $seat_id ?> type="text" class="booking_input booked" value="booked">
                            <?php
                        }
                    }
                    ?>
                    <br>
                    <?php
                }
                ?>
            </div>
            <div class="choice-info">
                <p id="chosen_count">Выбрано мест: 0</p>
                <div id="chosen_seats"></div>
                <p id="total_price">Стоимость: 0</p>
            </div>
        </div>
        <br>
        <p>Ваше имя</p>
        <input type="text" name="name">
        <br>
        <br>
        <p>Ваш номер телефона</p>
        <input type="tel" name="tel">
        <br>
        <button type="submit">Забронировать</button>
        <br>
        <br>
    </form>
</div>
</body>
</html>