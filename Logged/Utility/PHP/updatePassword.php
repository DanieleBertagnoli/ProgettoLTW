<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza la connessione al DB

    session_start(); //Inizializza la connessione 

    if(!isset($_POST['oldPassword']) || !isset($_POST["newPassword"]) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];

    $query = $connection -> prepare("SELECT * FROM users WHERE EMAIL=?"); //Seleziono l'utente che corrisponde all'email.
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success)
    {
        echo "select";  
        return;
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row == 0 || !password_verify($oldPassword, $row['password'])) //Se non esiste un utente corrispondente o se la password non coincide ritorniamo un errore con il codice password. 
    { 
        echo "password";
        return;
    }

    //Altrimenti criptiamo la passowrd e la aggiorniamo all'interno del DB
    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $query = $connection -> prepare("UPDATE users SET password=? WHERE email=?");
    $query -> bind_param("ss", $newPassword, $email);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "update"; 
        return;
    }

    echo "ok";
?>