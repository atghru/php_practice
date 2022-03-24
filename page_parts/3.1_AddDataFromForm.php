<?php
require_once("dbconfig.php");

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($link, 'utf8mb4');

if ($link === false) {
    die("Ошибка подключения к БД!");
}
else {
    $firstname = trim($_REQUEST['firstname']);
    $lastname = trim($_REQUEST['lastname']);
    $email = trim($_REQUEST['email']);
    $gender = trim($_REQUEST['gender']);
    $password = password_hash(trim($_REQUEST['password']), PASSWORD_DEFAULT);

    $query_insert = "insert into records (firstname, lastname, email, gender, salt) values('$firstname', '$lastname', '$email', '$gender','$password')";

    if (mysqli_query($link, $query_insert)) {
        require('page_parts/2_alert-success.php');
    }
    else {
        $err_mesg = "👎 Ошибка: ".mysqli_error($link);
        require('page_parts/1_alert-danger.php');
    }


}
