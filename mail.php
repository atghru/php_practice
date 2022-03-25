<?php

    $message = "
    В таблицу пользователей внесена следующая запись:
    User id: $current_uid
    Firstname: $firstname
    Lastname: $lastname
    Email: $email
    Gender: $gender
    Userlisting: $userlisting

    В таблицу сообщений внесена следующая запись:
    Message id: $msgid
    User id: $current_uid
    Filepath: $fileName
    Message text: $textarea
    ";

    $from = "site@example.com";
    $to = "admin@example.com";
    $subject = "Добавлено новое сообщение";
    $headers = "From: " . $from;
    if (mail($to,$subject,$message, $headers)){
        $success_msg ="👍 Сообщение о внесении записей отправлено администратору.";
        require('page_parts/2.2_AlertSuccess.php');
    }
