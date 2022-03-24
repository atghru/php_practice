<?php

print('<div class="container-fluid">
<div class="row mb-3 bg-info text-white">
    <div class="col text-center">
        <h2>Просмотр существующих обращений</h2>
    </div>
</div>
</div>');

print('<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa nihil eveniet assumenda sequi consectetur labore in, illo alias. Voluptatibus ratione repudiandae eum magni! Quibusdam aspernatur nemo, explicabo dolores doloribus corrupti.</p>');

require_once('dbconfig.php');
$mysqli_link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($mysqli_link, 'utf8mb4');

if (mysqli_connect_errno()) {
    throw new RuntimeException('mysqli connection error: ' . mysqli_connect_error());
} else {
    echo '👍 Установлено подключение к БД!<br>';
    echo '<br>';
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
                echo "Здравствуйте, $row[firstname] 👋";
                echo '<table class="table">';
                echo '<thead>';
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
            } else {
                printf(' 👎 Что-то пошло не так.\n');
            }
            echo '</div>';
        }
        else {
            echo " <script>alert('Введен неверный пароль.');</script>  ";
        }
    }
    else {
        echo " <script>alert('Доступ запрещен!');</script>  ";
    }
