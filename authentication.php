<?php
session_start();
require './account_class.php';

$account = new Account();


if($account->login(trim($_POST['email']),trim($_POST['password']))){
    header("location: home.php");

}
