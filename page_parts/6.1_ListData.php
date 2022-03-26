<?php

print('<div class="container-fluid shadow">
<div class="row bg-info text-white">
    <div class="col text-center">
        <h2>Просмотр существующих обращений</h2>
    </div>
</div>
</div>');

if ($mode==='list') {
    require_once('dbconfig.php');
    $mysqli_link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_set_charset($mysqli_link, 'utf8mb4');

    if (mysqli_connect_errno()) {
        throw new RuntimeException('mysqli connection error: ' . mysqli_connect_error());
    }

    // $current_page = 2;
    $current_page = $_REQUEST['current_page'] ? $_REQUEST['current_page'] : 1;
    print("current_page=$current_page");

    // $auth_email = (isset($_SESSION['user_is_authorized'])) ? ($_SESSION['user_email']) : (trim($_REQUEST['email']));

    if (!isset($_SESSION['user_is_authorized'])) {
    $auth_email = trim($_REQUEST['email']);
    $query = "SELECT firstname, email, salt, userlisting from 6711f799_users where email='$auth_email' and userlisting=true";
    $result_arr = mysqli_query($mysqli_link, $query);
        if (mysqli_num_rows($result_arr) === 1) {
            $row = mysqli_fetch_assoc($result_arr);
            if (password_verify($_POST['password'], $row['salt'])) {
                $_SESSION['user_is_authorized'] = true;
                $_SESSION['user_email'] = $auth_email;
                $_SESSION['user_name'] = $row['firstname'];
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
    }
    if ($_SESSION['user_is_authorized'] === true) {
        $num_msgid_query = "select count(msgid) from 6711f799_messages";
        $num_msgid_query_result = mysqli_fetch_row(mysqli_query($mysqli_link, $num_msgid_query));
        $num_msgids = $num_msgid_query_result[0];
        $results_on_page = 3;
        $num_pages = ceil($num_msgids / $results_on_page);
        print("total number of pages = $num_pages");
        //find out lower and upper limits for query
        $lower_limit = $current_page*$results_on_page-$results_on_page;
        // $upper_limit = $current_page*$results_on_page;
        print("lower_limit=$lower_limit and upper_limit=$upper_limit");
        //send to next page
        $next_page = $current_page + 1;
        if ($current_page>1) {
            $previous_page = $current_page - 1;
            print("<a href=\"index.php?mode=list&current_page=$previous_page\">BUTTON TO THE PREVIOUS PAGE</a>");
        }
        if ($next_page <= $num_pages) {
            print("<a href=\"index.php?mode=list&current_page=$next_page\">BUTTON TO THE NEXT PAGE</a>");
        }
        $query = "select m.msgid, u.firstname, u.lastname, u.email, m.msgtext, m.filepath from 6711f799_messages as m inner join 6711f799_users as u on m.uid=u.uid LIMIT $lower_limit, $results_on_page";
        if ($result = mysqli_query($mysqli_link, $query)) {
            mysqli_close($mysqli_link);
            $success_msg = "Здравствуйте, ". $_SESSION['user_name'] ."! 👋";
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
                <td>$row[4]</td>
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
}
