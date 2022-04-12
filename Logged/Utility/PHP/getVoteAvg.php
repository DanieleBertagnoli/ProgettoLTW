<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['tripID']))
    { echo "ops"; }

    $tripID = $_GET['tripID'];

    $query = $connection -> prepare("SELECT AVG(vote) as avg FROM votes WHERE trip=?");
    $query -> bind_param("i", $tripID);
    $query -> execute();
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();

    echo $row['avg'];

    mysqli_close($connection);

?>