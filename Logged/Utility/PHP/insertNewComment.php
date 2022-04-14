<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['commentText']) || !isset($_GET["tripID"]) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];
    $tripID = $_GET['tripID'];
    $text = $_GET['commentText'];
    
    $currentDate = new DateTime();

    $currentDate = $currentDate -> format('Y-m-d H:i:s');
    $query = $connection -> prepare('INSERT INTO comments (USER, TRIP, TEXT, DATETIME) VALUES (?, ?, ?, ?)');
    $query -> bind_param("siss", $email, $tripID, $text, $currentDate);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

?>