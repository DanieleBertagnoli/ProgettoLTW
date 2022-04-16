<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza la connessione al DB

    session_start(); //Fai partire la sessione

    if(!$connection)
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il l'invio della richiesta di amicizia, a cause di una mancata connessione al DataBase. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        exit();

    }

    if(!isset($_GET['user'])) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il l'invio della richiesta di amicizia, non sono presenti alcuni parametri necessari. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    $email = $_SESSION['email'];
    $user = $_GET['user'];

    $query = $connection -> prepare('SELECT * FROM friends WHERE user1=? AND user2=? AND pending=1'); //Seleziono la riga con i due utenti con la richiesta in corso
    $query -> bind_param("ss", $user, $email);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'esecuzione dell'operazione. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row == 0) //Se non e' presente alcuna richiesta riporta l'utente alla pagina di errore.
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'esecuzione dell'operazione, non e' stato possibile trovare la richiesta cercata. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    $query = $connection -> prepare('DELETE FROM friends WHERE user1 = ? AND user2 = ?'); //Togli la richiesta dal database se rifiuti.
    $query -> bind_param("ss", $user, $email);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'esecuzione dell'operazione. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    header("Location: ../../myRequestsPage.php"); //Porta l'utente alla pagine delle sue richieste. 

    mysqli_close($connection); //Chiudi connessione al DB.
?>