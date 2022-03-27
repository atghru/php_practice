<?php

print('<div class="container-fluid shadow mb-3">
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

    $current_page = $_REQUEST['current_page'] ? $_REQUEST['current_page'] : 1;
    // print("current_page=$current_page");

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
                $_SESSION['results_on_page'] = 10;
            }
            else {
                $error_msg = '👎 Ошибка: введен неверный пароль.';
                require('page_parts/2.1_AlertDanger.php');
            }
        }
        else {
            $error_msg = '👎 Ошибка: пользователь не авторизован или отсутствует доступ к списку пользователей.';
            require('page_parts/2.1_AlertDanger.php');
        }
    }
    if ($_SESSION['user_is_authorized'] === true) {
        $num_msgid_query = "select count(msgid) from 6711f799_messages";
        $num_msgid_query_result = mysqli_fetch_row(mysqli_query($mysqli_link, $num_msgid_query));
        $num_msgids = $num_msgid_query_result[0];
        $results_on_page = (isset($_POST['results_on_page'])) ? $_POST['results_on_page'] : $_SESSION['results_on_page'];
        $array_results_on_page = array(10,15,25,50);
        $_SESSION['results_on_page'] = $results_on_page;

        echo '<form method="post" action="index.php?mode=list">';
        echo '<div class="input-group mb-3">';
        // echo '<label class="input-group-text" for="results_on_page">Количество строк</label>';
        echo '<select class="form-select" name="results_on_page">';
        echo '<option selected>Количество строк</option>';
        // for ($i = 1; $i < 10; $i++ ){
        foreach ($array_results_on_page as $i) {
            echo '<option value="'.$i.'">'.$i.'</option>';
        }
        echo '<option value="'.$num_msgids.'">Все ('.$num_msgids.')</option>';
        echo '</select>';
        echo '<input type="submit" value="Изменить">';
        echo '</div>';
        echo '</form>';

        $num_pages = ceil($num_msgids / $results_on_page);
        // print("total number of pages = $num_pages");
        //find out lower and upper limits for query
        $lower_limit = $current_page*$results_on_page-$results_on_page;
        // $upper_limit = $current_page*$results_on_page;
        // print("lower_limit=$lower_limit and upper_limit=$upper_limit");
        $query = "select m.msgid, u.firstname, u.lastname, u.email, m.msgtext, m.filepath from 6711f799_messages as m inner join 6711f799_users as u on m.uid=u.uid LIMIT $lower_limit, $results_on_page";
        if ($result = mysqli_query($mysqli_link, $query)) {
            mysqli_close($mysqli_link);
            // $success_msg = "Здравствуйте, ". $_SESSION['user_name'] ."! 👋";
            // require('page_parts/2.2_AlertSuccess.php');
            echo '<div class="container p-0 mb-0">';
            echo '<table class="table table-striped border border-secondary shadow">';
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
                <td class=\"d-inline-block text-truncate\" style=\"max-width: 300px;\">$row[4]</td>
                <td><a href=\"/download.php?msgid=$row[0]&filename=$row[5]\">$row[5]</a></td>
                </tr>");
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';

            //send to next page
            $next_page = $current_page + 1;
            if ($next_page <= $num_pages) {
                // print("<a href=\"index.php?mode=list&current_page=$next_page\">BUTTON TO THE NEXT PAGE</a>");
            }
            else {
                $next_page_disabled = 'disabled';
            }
            if ($current_page>1) {
                $previous_page = $current_page - 1;
                // print("<a href=\"index.php?mode=list&current_page=$previous_page\">BUTTON TO THE PREVIOUS PAGE</a>");
            }
            else {
                $previouse_page_disabled = 'disabled';
            }
            echo '<nav aria-label="Page navigation example">';
            echo '<ul class="pagination justify-content-center">';
              echo '<li class="page-item shadow '. $previouse_page_disabled .'">';
                echo '<a class="page-link" href="index.php?mode=list&current_page='.$previous_page.'" aria-label="Previous">';
                  echo '<span aria-hidden="true">&laquo;</span>';
                echo '</a>';
              echo '</li>';
              for ($i = 1; $i <= $num_pages; $i++) {
                  $current_page_active = ($i == $current_page) ? 'active' : '';
                  echo '<li class="page-item shadow '.$current_page_active.'"><a class="page-link" href="index.php?mode=list&current_page=' . $i . '">' . $i . '</a></li>';
              }
              echo '<li class="page-item shadow '. $next_page_disabled .'">';
                echo '<a class="page-link" href="index.php?mode=list&current_page='.$next_page.'" aria-label="Next">';
                  echo '<span aria-hidden="true">&raquo;</span>';
                echo '</a>';
              echo '</li>';
            echo '</ul>';
            echo '</nav>';
            if ($_POST['remember_me'] == 'no') {
                session_unset();
                session_destroy();
            }
        }
        else {
            printf(' 👎 Что-то пошло не так.\n');
        }
    }
}
