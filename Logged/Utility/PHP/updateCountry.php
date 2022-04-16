<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza la connessione al DB

    session_start(); //Inizializza la sessione

    if(!isset($_POST['newCountry']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];
    $newCountry = $_POST['newCountry'];

    $query = $connection -> prepare("UPDATE users SET country=? WHERE email=?"); //Aggiorna il campo country nel DB dell'utente corrispondente.
    $query -> bind_param("ss", $newCountry, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "update"; 
        return;
    }

    echo "ok";

?>