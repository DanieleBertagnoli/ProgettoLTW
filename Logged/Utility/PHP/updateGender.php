<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza la connessione

    session_start(); //Inizializza la sessione

    if(!isset($_POST['newGender']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "parametri";
        return;
    }

    $email = $_SESSION['email'];
    $newGender = $_POST['newGender'];

    $query = $connection -> prepare("UPDATE users SET gender=? WHERE email=?"); //Aggiorna la riga nel DB con l'utente corrispondente.
    $query -> bind_param("ss", $newGender, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "update"; 
        return;
    }

    echo "ok";

?>