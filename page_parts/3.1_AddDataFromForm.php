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
    if (strlen($textarea = mysqli_real_escape_string($link, $_REQUEST['form_textarea']))>255) {
        $error_msg = '👎 Ошибка: текст сообщения превышает 255 символов';
        require('page_parts/2.1_AlertDanger.php');
    }
    else {
        $query_insert_user = "insert into 6711f799_users (firstname, lastname, email, gender, salt, userlisting) values('$firstname', '$lastname', '$email', '$gender','$password', $userlisting)";
    if (mysqli_query($link, $query_insert_user)) {
        $current_uid = mysqli_insert_id($link);
        $success_msg ="👍 Запись № $current_uid добавлена в таблицу пользователей.";
        require('page_parts/2.2_AlertSuccess.php');

        $errors = [];
        $fileExtensionsAllowed = ['txt', 'jpg', 'jpeg', 'png'];
        $fileName = $_FILES['input_file']['name'];
        $fileSize = $_FILES['input_file']['size'];
        $fileTmpName  = $_FILES['input_file']['tmp_name'];
        $fileType = $_FILES['input_file']['type'];
        $fileExtension = strtolower(end(explode('.',$fileName)));
        $query_insert_msg = "insert into 6711f799_messages (uid, msgtext, filepath) values ($current_uid, '$textarea', '$fileName')";
        $currentDirectory = getcwd();
        $uploadDirectory = "/upload/";
        if (mysqli_query($link, $query_insert_msg)) {
            $folder_name=strval(mysqli_insert_id($link))."/";
            if (!file_exists($currentDirectory.$uploadDirectory.$folder_name))
            {
                mkdir($currentDirectory.$uploadDirectory.$folder_name, 0777);
            }
            $uploadPath = $currentDirectory . $uploadDirectory . $folder_name . basename($fileName);
            // print("upload path = $uploadPath");
            if (! in_array($fileExtension, $fileExtensionsAllowed)) {
                $errors[] = "Недопустимый тип файла.";
            }
            if ($fileSize > 16000000) {
                $errors[] = "Превышен максимально допустимый размер файла.";
            }
            if (empty($errors)) {
                $isUploaded = move_uploaded_file($fileTmpName, $uploadPath);
            }
            else {
                foreach ($errors as  $error) {
                    echo "Ошибка - $error \n";
                }
            }

            $success_msg ="👍 Запись № ".mysqli_insert_id($link)." добавлена в таблицу сообщений.";
            require('page_parts/2.2_AlertSuccess.php');
        }
        else {
            $error_msg = '👎 Ошибка: '.mysqli_error($link);
            require('page_parts/2.1_AlertDanger.php');
        }

    }
    else {
        $error_msg = '👎 Ошибка: '.mysqli_error($link);
        require('page_parts/2.1_AlertDanger.php');
    }
    }
}
