<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['newDate']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];
    $newDate = $_GET['newDate'];

    $query = $connection -> prepare("UPDATE users SET birthday=? WHERE email=?");
    $query -> bind_param("ss", $newDate, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "update"; 
        return;
    }

    echo "ok";

?>