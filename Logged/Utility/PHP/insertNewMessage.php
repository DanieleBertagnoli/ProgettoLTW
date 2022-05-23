<?php

    $host = "localhost";
    $username = "localuser";
    $psw = "local";
    $dbname = "ltw";
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    if(!isset($_POST['email']) || !isset($_POST['subject']) || !isset($_POST['description'])) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $currentDate = new DateTime();
    $currentDate = $currentDate->format('Y-m-d');

    $query = $connection -> prepare('INSERT INTO problems (email, subject, text, date) VALUES (?, ?, ?, ?)'); //Inserisco il commento nel database
    $query -> bind_param("ssss", $email, $subject, $description, $currentDate);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "insert";
        return; 
    }