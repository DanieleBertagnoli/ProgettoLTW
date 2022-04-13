<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['oldPassword']) || !isset($_GET["newPassword"]) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];
    $oldPassword = $_GET['oldPassword'];
    $newPassword = $_GET['newPassword'];

    $query = $connection -> prepare("SELECT * FROM users WHERE EMAIL=?");
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success)
    {
        echo "select";  
        return;
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row == 0 || !password_verify($oldPassword, $row['password']))
    { 
        echo "password";
        return;
    }

    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $query = $connection -> prepare("UPDATE users SET password=? WHERE email=?");
    $query -> bind_param("ss", $newPassword, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "insert"; 
        return;
    }

    echo "ok";

?>