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
    echo "👍 Установлено подключение к БД!<br>";
    echo "<br>";
}

$query = "SELECT id, firstname, lastname, email, salt FROM records";

if ($result = mysqli_query($mysqli_link, $query)) {
    mysqli_close($mysqli_link);
    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "  <th scope='col'>id</th>";
    echo "  <th scope='col'>Firstname</th>";
    echo "  <th scope='col'>Lastname</th>";
    echo "  <th scope='col'>Email</th>";
    echo "  <th scope='col'>Hashed password</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = mysqli_fetch_row($result)) {
        printf("<tr>
        <th scope='row'>%s</th>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        </tr>",
        $row[0], $row[1], $row[2], $row[3], $row[4]);
    }
    echo "</tbody>";
    echo "</table>";
} else {
    printf(" 👎 Что-то пошло не так.\n");
}
echo "</div>";
