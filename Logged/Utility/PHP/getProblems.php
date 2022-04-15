<?php

    require "initConnection.php";
    $connection = initConnection();

    if(!isset($_POST['start']) || !isset($_POST['end']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $start = $_POST['start'];
    $end = $_POST['end'];

    $query = $connection -> prepare('SELECT * FROM problems WHERE date <= ? AND date >=?');
    $query -> bind_param("ss", $end, $start);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $result = $query -> get_result();

    $problems = "";
    while($row = $result -> fetch_assoc())
    { $problems = $problems . $row['email'] . "~(~~)~" . $row['subject'] . "~(~~)~" . $row['text'] . "~(~~)~" . $row['date'] . "~(~~)~"; }
    echo $problems;

?>