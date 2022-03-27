<?php
print("<div class=\"container-fluid bg-primary text-white p-3 shadow\">");
print("    <div class=\"row \">");
print("        <div class=\"col\">");
print("                <a href=\"/index.php\"><button class=\"btn btn-secondary shadow\">Вернуться на главную страницу</button></a>");
print("        </div>");
if ($_SESSION['user_is_authorized'] === true){
    print("        <div class=\"col d-flex justify-content-end\">");
    print("                <a href=\"/index.php?stop_session=1\" class=\"text-white\"><button class=\"btn btn-secondary shadow\">Завершить сессию $_SESSION[user_email]</button></a>");
    print("        </div>");
}
print("    </div>");
print("</div>");
