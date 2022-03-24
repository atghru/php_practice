<?php

#Создание таблиц
$createtable = "CREATE TABLE `6711f799_users` (
    `id` int(8) NOT NULL AUTO_INCREMENT,
    `firstname` varchar(256) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    `lastname` varchar(256) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    `email` varchar(256) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    `gender` enum('male','female') COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
    `salt` varchar(256) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
  )";

require_once("dbconfig.php");

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($link, 'utf8mb4');

if ($link === false) {
    die("Ошибка подключения к БД!");
}
else {
    $query_insert = "insert into records (firstname, lastname, email, gender, salt) values('$firstname', '$lastname', '$email', '$gender','$password')";

    if (mysqli_query($link, $query_insert)) {
        require('page_parts/2.2_alert-success.php');
    }
    else {
        $err_mesg = "👎 Ошибка: ".mysqli_error($link);
        require('page_parts/2.1_alert-danger.php');
    }
}
