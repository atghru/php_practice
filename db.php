<?php

$mysqli_link = mysqli_connect("mysql5.7", "crudboy", "crudpass", "crudb");
mysqli_set_charset($mysqli_link, 'utf8mb4');

if (mysqli_connect_errno()) {
    throw new RuntimeException('mysqli connection error: ' . mysqli_connect_error());
} else {
    mysqli_close($mysqli_link);
    echo "👍 Установлено подключение к БД!<br>" . mysqli_get_host_info($mysqli_link);
    echo "<br>";
    // mysqli_close($mysqli_link);
}
