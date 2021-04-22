<?php

/* Host name of the MySQL server */
$host = 'localhost';

/* MySQL account username */
$user = 'root';

/* MySQL account password */
$passwd = '';

/* The database you want to use */
$schema = 'driverless';

/* The PDO object */
$pdo = NULL;

/* Connection string, or "data source name" */
$dsn = 'mysql:host=' . $host . ';port=3306;dbname=' . $schema;

/* Connection inside a try/catch block */
try
{
    /* PDO object creation */
    $pdo = new \PDO($dsn, $user,  $passwd, [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_PERSISTENT => true
    ]);
    /* Enable exceptions on errors */
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'connected';
    print_r($pdo);
}
catch (PDOException $e)
{
    /* If there is an error an exception is thrown */
    echo 'Database connection failed.' . $e->getMessage();
    die();
}


