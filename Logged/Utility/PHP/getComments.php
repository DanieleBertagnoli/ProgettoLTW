<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    if(!isset($_POST["tripID"]) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "parametri"; 
        return;
    }

    $tripID = $_POST['tripID'];

    $query = $connection -> prepare('SELECT text, datetime, username, user FROM users, comments WHERE user = email AND trip=?'); //Seleziona i commenti del trip
    $query -> bind_param("i", $tripID);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "select"; 
        return;
    }

    $result = $query -> get_result();

    $comments = "";
    while($row = $result -> fetch_assoc()) //Per ogni commento creo una stringa da poter suddividere tramite regex
    { 
        $comments = $comments . '<div class="comment">' .  
                                '<h2><a href="externalProfilePage.php?user=' . $row['user'] . '">' . $row['username'] . '</a></h2>' .
                                '<p class="text">' . $row['text'] . '</p>' .
                                '<p class="date">' . $row['datetime'] . '</p>' .
                                '</div>';
    }
    echo $comments; //Invio i commenti

?>