<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza la connessione

    session_start(); //Inizializza la sessione

    if(!isset($_POST['newPrivacy']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "parametri";
        return;
    }

    $email = $_SESSION['email'];
    $newPrivacy = $_POST['newPrivacy'];

    if($newPrivacy == "Privato")
    { $newPrivacy = "1"; }
    else
    { $newPrivacy = "0"; }

    $query = $connection -> prepare("UPDATE users SET privacy=? WHERE email=?"); //Aggiorna la riga nel DB con l'utente corrispondente.
    $query -> bind_param("ss", $newPrivacy, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "update"; 
        return;
    }

    echo "ok";

?>