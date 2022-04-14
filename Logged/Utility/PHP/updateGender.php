<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['newGender']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];
    $newGender = $_GET['newGender'];

    $query = $connection -> prepare("UPDATE users SET gender=? WHERE email=?");
    $query -> bind_param("ss", $newGender, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "update"; 
        return;
    }

    echo "ok";

?>