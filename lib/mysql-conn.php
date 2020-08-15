<?php
  $srv = "localhost";
  $dtb = "charvid";
  $usr = "admin";
  $pwd = "admin123";

  $db = mysqli_connect($srv, $usr, $pwd, $dtb);
  //check connection
  if(!$db){
  echo 'Connection error:' .mysqli_connect_error();
  }
?>
