<?php
  $filename=$_REQUEST['filename'];
  $msgid=$_REQUEST['msgid'];
  header("Content-disposition: attachment;filename=$filename");

  header("Cache-Control: public");
  header("Content-Description: File Transfer");
  header("Content-Type: application/zip");
  header("Content-Transfer-Encoding: binary");

  readfile(getcwd().'/upload/'.$msgid.'/'.$filename);
?>
