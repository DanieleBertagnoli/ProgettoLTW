<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    if(!$connection) //Se la connessione non va a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il l'invio della richiesta di amicizia a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit(); 
    }

    session_start(); //Avvio la sessione

    if(!isset($_GET['user'])) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il l'invio della richiesta di amicizia a causa della mancanza di alcuni parametri necessari. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $email = $_SESSION['email'];
    $user = $_GET['user'];

    $query = $connection -> prepare('SELECT * FROM friends WHERE (user1=? AND user2=?) OR (user1=? AND user2=?)'); //Controllo se è già presente una richiesta di amicizia tra i due utenti
    $query -> bind_param("ssss", $user, $email, $email, $user);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'invio della richiesta di amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row != 0) //Se è già presente un'amicizia 
    {
        header("Location: ../../externalProfilePage.php?user=$user"); //Redirect alla pagina dell'utente
        exit(); 
    }

    $query = $connection -> prepare('INSERT INTO friends (USER1, USER2, PENDING) VALUES (?, ?, 1)'); //Aggiungo la nuova richiesta
    $query -> bind_param("ss", $email, $user);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'invio della richiesta di amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    header("Location: ../../externalProfilePage.php?user=$user"); 

    mysqli_close($connection);

?>