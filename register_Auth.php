<?php
session_start();
require './account_class.php';

$account = new Account();

$username = $_POST['username'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if($password == $confirm_password){

    try {
        $newId = $account->addAccount($username, $fname,$lname,$email,$password);
        if(!is_null($newId)){
            if($account->login($email,$password)){
                header("location: home.php");
            }

        }
    }
    catch ( Exception $e){
//        header("location: register.php");
        print_r($e);
        die();
    }

}
