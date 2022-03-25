<?php
// phpinfo();
require('page_parts/0.0_Header.html');
define('BEEPBOOP', true);
$mode = $_REQUEST['mode'];

if (!isset($mode)) {
    include('page_parts/1.0_Index.html');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $mode === 'add') {
    require('page_parts/3.0_Form.html');
}
if (($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'add')) {
    include('page_parts/3.1_AddDataFromForm.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $mode === 'auth') {
    require('page_parts/6.0_Auth.html');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'auth') {
    require('page_parts/6.1_ListData.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && BEEPBOOP) {
    print('<div><pre>');
    print('$_REQUEST =><br>');
    print_r($_REQUEST);
    print('$_FILES =><br>');
    print_r($_FILES);
    print('</pre></div>');
}

include('page_parts/99.1_ReturnButton.html');

require('page_parts/99.0_Footer.html');
