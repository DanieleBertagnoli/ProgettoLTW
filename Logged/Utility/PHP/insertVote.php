<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['vote']) || !isset($_GET['tripID']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];
    $vote = $_GET['vote'];
    $tripID = $_GET['tripID'];

    $query = $connection -> prepare("SELECT * FROM votes WHERE user=? AND trip=?");
    $query -> bind_param("si", $email, $tripID);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();

    if($row == 0)
    {
        $query = $connection -> prepare('INSERT INTO votes (VOTE, USER, TRIP) VALUES ("' . $vote . '", "' . $email . '", "' . $tripID . '")');
        $success = $query -> execute();

        if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
        { return; }
    }
    else
    {
        $query = $connection -> prepare("UPDATE votes SET vote=? WHERE user=? AND trip=?");
        $query -> bind_param("isi", $vote, $email, $tripID);
        $success = $query -> execute();

        if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
        { return; }
    }

    mysqli_close($connection);

?>