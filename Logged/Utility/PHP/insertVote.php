<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza connessione al DB

    session_start(); //Fai partire la sessione.

    if(!isset($_POST['vote']) || !isset($_POST['tripID']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        echo "parametri";
        return; 
    }

    $email = $_SESSION['email'];
    $vote = $_POST['vote'];
    $tripID = $_POST['tripID'];

    $query = $connection -> prepare("SELECT * FROM votes WHERE user=? AND trip=?"); //Seleziona la riga con il voto creato dall'utente corrispondente al trip corrente.
    $query -> bind_param("si", $email, $tripID);
    $success = $query -> execute();

    if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    {
        echo "select"; 
        return; 
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();

    if($row == 0)
    {
        //Se non esiste un voto a nome di quell'utente per quel viaggio inseriscilo nel data base
        $query = $connection -> prepare('INSERT INTO votes (VOTE, USER, TRIP) VALUES ("' . $vote . '", "' . $email . '", "' . $tripID . '")');
        $success = $query -> execute();

        if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
        { 
            echo "insert";
            return;
        }
    }
    else
    {
        //Se gia' esiste un voto a nome di quell'utente per quel viaggio aggiorna il vecchio con il nuovo.
        $query = $connection -> prepare("UPDATE votes SET vote=? WHERE user=? AND trip=?");
        $query -> bind_param("isi", $vote, $email, $tripID);
        $success = $query -> execute();

        if(!$success) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
        { 
            echo "update";
            return; 
        }
    }

    mysqli_close($connection);

?>