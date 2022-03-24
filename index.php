<?php
require("page_parts/0_header.html");

$mode = $_REQUEST['mode'];

if (!isset($mode)) {
    include("page_parts/1_index.html");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $mode == 'add') {
    require('page_parts/3_form.html');
}
if (($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'add')) {
    include('page_parts/3.1_add_data_from_form.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $mode == 'auth') {
    require("page_parts/6_auth.html");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode == 'auth') {
    require("page_parts/6.1_list_data.php");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print('<div class="form-group"><pre>');
    print('$_REQUEST =><br>');
    print_r($_REQUEST);
    if (isset($_FILES)) {
        print('$_FILES =><br>');
        print_r($_FILES);
    }
    print('</pre></div>');
}

include("page_parts/5_return_button.html");

require('page_parts/99_footer.html');
