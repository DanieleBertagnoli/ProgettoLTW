<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    session_start(); //Avvio la sessione

    if(!isset($_POST['commentText']) || !isset($_POST["tripID"]) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    {
        echo "parametri"; 
        return; 
    }

    $email = $_SESSION['email'];
    $tripID = $_POST['tripID'];
    $text = $_POST['commentText'];
    
    $currentDate = new DateTime();

    $currentDate = $currentDate -> format('Y-m-d H:i:s');
    $query = $connection -> prepare('INSERT INTO comments (USER, TRIP, TEXT, DATETIME) VALUES (?, ?, ?, ?)'); //Inserisco il commento nel database
    $query -> bind_param("siss", $email, $tripID, $text, $currentDate);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "insert";
        return; 
    }
    
    echo "ok";
?>