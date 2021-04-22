<?php
session_start();
require './account_class.php';
$account = new Account();


 if($account->logout()){
     var_dump("welcome home");
     header("location: index.php");
 };