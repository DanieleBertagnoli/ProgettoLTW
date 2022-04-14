<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['newCountry']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];
    $newCountry = $_GET['newCountry'];

    $query = $connection -> prepare("UPDATE users SET country=? WHERE email=?");
    $query -> bind_param("ss", $newCountry, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "update"; 
        return;
    }

    echo "ok";

?>