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
            // $num_pages_query = "select count(msgid) from 6711f799_messages";
            // $num_pages_query_count = mysqli_query($mysqli_link, $num_pages_query);
            // print_r($num_pages_query_count);
            // print($num_pages_query_count[0]['id']);
            $query = "select m.msgid, u.firstname, u.lastname, u.email, m.msgtext, m.filepath from 6711f799_messages as m inner join 6711f799_users as u on m.uid=u.uid LIMIT 0, 10";
            if ($result = mysqli_query($mysqli_link, $query)) {
                mysqli_close($mysqli_link);
                $success_msg = "Здравствуйте, $row[firstname]! 👋";
                require('page_parts/2.2_AlertSuccess.php');
                echo '<div class="container p-0 mb-0 shadow">';
                echo '<table class="table table-striped border border-secondary">';
                echo '<thead class="table-secondary">';
                echo '<tr>';
                echo '  <th scope="col">Message id</th>';
                echo '  <th scope="col">Author</th>';
                echo '  <th scope="col">Author email</th>';
                echo '  <th scope="col">Message text</th>';
                echo '  <th scope="col">Uploaded file</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_row($result)) {
                    print("<tr>
                    <th scope=\"row\">$row[0]</th>
                    <td>$row[1] $row[2]</td>
                    <td>$row[3]</td>
                    <td><p>$row[4]</p></td>
                    <td><a class=\"bi bi-cloud-arrow-down\" href=\"/download.php?msgid=$row[0]&filename=$row[5]\">$row[5] <img src=\"cloud-arrow-down.svg\"></a></td>
                    </tr>");
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
