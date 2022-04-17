<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza la connessione

    session_start(); //Inizializza la sessione.

    if(!isset($_POST['newDate']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    {
        echo "parametri";
        return;
    }

    $email = $_SESSION['email'];
    $newDate = $_POST['newDate'];

    $query = $connection -> prepare("UPDATE users SET birthday=? WHERE email=?"); //Aggiorna la data di nascita nel DB all'utente corrisponente.
    $query -> bind_param("ss", $newDate, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "update"; 
        return;
    }

    echo "ok";

?>