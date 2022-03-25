<?php
require_once('dbconfig.php');

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($link, 'utf8mb4');

if ($link === false) {
    die('Ошибка подключения к БД!');
}
else {
    $firstname = trim($_REQUEST['firstname']);
    $lastname = trim($_REQUEST['lastname']);
    $email = trim($_REQUEST['email']);
    $gender = trim($_REQUEST['gender']);
    $password = password_hash(trim($_REQUEST['password']), PASSWORD_DEFAULT);
    (trim($_REQUEST['userlisting'])==='disabled') ? ($userlisting = 0) : ($userlisting = 1);

    $query_insert = "insert into 6711f799_users (firstname, lastname, email, gender, salt, userlisting) values('$firstname', '$lastname', '$email', '$gender','$password', $userlisting)";
    if (mysqli_query($link, $query_insert)) {
        $success_msg = '👍 Запись № '.mysqli_insert_id($link).' добавлена в таблицу.';
        require('page_parts/2.2_AlertSuccess.php');
    }
    else {
        $error_msg = '👎 Ошибка: '.mysqli_error($link);
        require('page_parts/2.1_AlertDanger.php');
    }


}
