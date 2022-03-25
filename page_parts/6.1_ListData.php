<?php

print('<div class="container-fluid shadow">
<div class="row bg-info text-white">
    <div class="col text-center">
        <h2>Просмотр существующих обращений</h2>
    </div>
</div>
</div>');

require_once('dbconfig.php');
$mysqli_link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($mysqli_link, 'utf8mb4');

if (mysqli_connect_errno()) {
    throw new RuntimeException('mysqli connection error: ' . mysqli_connect_error());
}

$auth_email = trim($_REQUEST['email']);

$query = "SELECT firstname, email, salt, userlisting from 6711f799_users where email='$auth_email' and userlisting=true";
$result_arr = mysqli_query($mysqli_link, $query);
    if (mysqli_num_rows($result_arr) === 1) {
        $row = mysqli_fetch_assoc($result_arr);
        if (password_verify($_POST['password'], $row['salt'])) {
            $query = "SELECT uid, firstname, lastname, email FROM 6711f799_users";
            if ($result = mysqli_query($mysqli_link, $query)) {
                mysqli_close($mysqli_link);
                $success_msg = "Здравствуйте, $row[firstname]! 👋";
                require('page_parts/2.2_AlertSuccess.php');
                echo '<div class="container p-0 mb-0 shadow">';
                echo '<table class="table table-striped border border-secondary">';
                echo '<thead class="table-secondary">';
                echo '<tr>';
                echo '  <th scope="col">id</th>';
                echo '  <th scope="col">Firstname</th>';
                echo '  <th scope="col">Lastname</th>';
                echo '  <th scope="col">Email</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_row($result)) {
                    printf('<tr>
                    <th scope="row">%s</th>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    </tr>',
                    $row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
            else {
                printf(' 👎 Что-то пошло не так.\n');
            }
        }
        else {
            $error_msg = '👎 Ошибка: введен неверный пароль.';
            require('page_parts/2.1_AlertDanger.php');
        }
    }
    else {
        $error_msg = '👎 Ошибка: пользователь не зарегистрирован либо отсутствует доступ к списку пользователей.';
        require('page_parts/2.1_AlertDanger.php');
    }
