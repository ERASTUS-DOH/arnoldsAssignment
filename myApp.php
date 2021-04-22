<?php
session_start();
require './account_class.php';

$account = new Account();


try
{
    $newId = $account->addAccount('erastusdoh', 'erastus','doh','erastusdoh@gmail.com','1071500267');
    var_dump($newId);
}
catch (Exception $e)
{
    /* Something went wrong: echo the exception message and die */
    print_r($e);
    die();
}

//echo 'The new account ID is ' . $newId;
