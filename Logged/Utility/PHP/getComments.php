<?php

    require "initConnection.php";
    $connection = initConnection();

    if(!isset($_GET["tripID"]) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $tripID = $_GET['tripID'];

    $query = $connection -> prepare('SELECT text, datetime, username FROM users, comments WHERE user = email AND trip=?');
    $query -> bind_param("i", $tripID);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $result = $query -> get_result();

    $comments = "";
    while($row = $result -> fetch_assoc())
    { $comments = $comments . $row['username'] . "~(~~)~" . $row['datetime'] . "~(~~)~" . $row['text'] . "~(~~)~"; }
    echo $comments;

?>