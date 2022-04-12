<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_POST['vote']))
    { echo "ops"; }

    $email = $_GET['email'];
    $vote = $_GET['vote'];
    $tripID = $_GET['tripID'];

    $query = $connection -> prepare("SELECT * FROM votes WHERE user=? AND trip=?");
    $query -> bind_param("si", $email, $tripID);
    $query -> execute();
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();

    if($row == 0)
    {
        $query = $connection -> prepare('INSERT INTO votes (VOTE, USER, TRIP) VALUES ("' . $vote . '", "' . $email . '", "' . $tripID . '")');
        $query -> execute();
    }
    else
    {
        $query = $connection -> prepare("UPDATE votes SET vote=? WHERE user=? AND trip=?");
        $query -> bind_param("isi", $vote, $email, $tripID);
        $query -> execute();
    }

    mysqli_close($connection);

?>