<?php
// phpinfo();
session_start();

require('page_parts/0.0_Header.html');

define('BEEPBOOP', false);
$mode = $_REQUEST['mode'];

if (!isset($mode)) {
    include('page_parts/1.0_Index.html');
}

if ($_REQUEST['stop_session']==1) {
    session_unset();
    session_destroy();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $mode === 'list') {
    require('page_parts/6.1_ListData.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'list') {
    require('page_parts/6.1_ListData.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $mode === 'add') {
    require('page_parts/3.0_Form.html');
}
if (($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'add')) {
    include('page_parts/3.1_AddDataFromForm.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $mode === 'auth') {
    if ($_SESSION['user_is_authorized'] === true) {
        $mode = 'list';
        require('page_parts/6.1_ListData.php');
    }
    else {
        require('page_parts/6.0_Auth.html');
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && BEEPBOOP) {
    print('<div><pre>');
    print('$_REQUEST =><br>');
    print_r($_REQUEST);
    print('$_FILES =><br>');
    print_r($_FILES);
    print('</pre></div>');
}

// if ($_SESSION['user_is_authorized'] === true) {
//     include('page_parts/0.1_UserIsAuthorized.php');
// }

include('page_parts/99.1_ReturnButton.php');

require('page_parts/99.0_Footer.html');
