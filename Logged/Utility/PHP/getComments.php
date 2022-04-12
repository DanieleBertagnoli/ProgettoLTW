<?php

    require "initConnection.php";
    $connection = initConnection();

    if(!isset($_GET["tripID"]))
    { echo "Errore nei parametri fra"; }

    $tripID = $_GET['tripID'];

    $query = $connection -> prepare('SELECT text, datetime, username FROM users, comments WHERE user = email AND trip=?');
    $query -> bind_param("i", $tripID);
    $query -> execute();
    $result = $query -> get_result();

    $comments = "";
    while($row = $result -> fetch_assoc())
    { $comments = $comments . $row['username'] . "~(~~)~" . $row['datetime'] . "~(~~)~" . $row['text'] . "~(~~)~"; }
    echo $comments;

?>