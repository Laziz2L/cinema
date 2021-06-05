<?php
$connect = mysqli_connect('localhost', 'root', 'root', 'cinema_db');

if (!$connect) {
    echo 'Error connect to database';
}