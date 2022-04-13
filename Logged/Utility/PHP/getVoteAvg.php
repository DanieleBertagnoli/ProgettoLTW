<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['tripID']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $tripID = $_GET['tripID'];

    $query = $connection -> prepare("SELECT AVG(vote) as avg FROM votes WHERE trip=?");
    $query -> bind_param("i", $tripID);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();

    echo $row['avg'];

    mysqli_close($connection);

?>