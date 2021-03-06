<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    if(!isset($_POST['start']) || !isset($_POST['end']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "parametri";
        return;
    }

    $start = $_POST['start'];
    $end = $_POST['end'];

    $query = $connection -> prepare('SELECT * FROM problems WHERE date <= ? AND date >=?'); //Ottengo tutti i problemi pubblicati nel periodo indicato
    $query -> bind_param("ss", $end, $start);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    {
        echo "select";
        return; 
    }

    $result = $query -> get_result();

    $problems = "";
    while($row = $result -> fetch_assoc()) //Per ogni commento creo una stringa da poter suddividere tramite regex
    { 
        $problems = $problems . '<div class="problem">' .
                                    '<h1>Da: ' . $row['email'] . '</h1>' .
                                    '<h1>Il: ' . $row['date']  . '</h1>' .
                                    '<h2>Oggetto: ' . $row['subject'] . '</h2>' .
                                    '<p>Problema: ' . $row['text'] . '</p>' .
                                '</div>';
    }
    echo $problems; //Invio i problemi

?>