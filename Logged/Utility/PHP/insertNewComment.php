<?php

    require "initConnection.php";
    $connection = initConnection();

    if(!isset($_GET['comment-text']) || !isset($_GET["tripID"]))
    { echo "Errore nei parametri fra"; }

    $username = $_GET['email'];
    $tripID = $_GET['tripID'];
    $text = $_GET['comment-text'];
    
    $currentDate = new DateTime();

    $currentDate = $currentDate -> format('Y-m-d H:i:s');
    $query = $connection -> prepare('INSERT INTO comments (USER, TRIP, TEXT, DATETIME) VALUES (?, ?, ?, ?)');
    $query -> bind_param("siss", $username, $tripID, $text, $currentDate);
    $result = $query -> execute();

?>