<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    if(!$connection) //Se la connessione con il database non è andata a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il l'accettazione dell'amicizia a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    session_start(); //Avvio la sessione

    if(!isset($_GET['user'])) //Se il paramentro non è impostato
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il l'accettazione dell'amicizia a causa della mancanza alcuni parametri necessari. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $email = $_SESSION['email'];
    $user = $_GET['user'];

    $query = $connection -> prepare('SELECT * FROM friends WHERE user1=? AND user2=? AND pending=1'); //Controllo se per questi due utenti è presente una richiesta di amicizia pending
    $query -> bind_param("ss", $user, $email);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'invio della richiesta di amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $row = $query -> get_result() -> fetch_assoc();
    if($row != 0) //Se non esiste una richiesta in sospeso
    { 
        header("../../privateProfilePage.php?user=$user"); //Redirect alla pagina dell'utente
        exit(); 
    }

    $query = $connection -> prepare('UPDATE friends SET pending=0 WHERE user1=? AND user2=?'); //Aggiorno la richiesta pending
    $query -> bind_param("ss", $user, $email);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'invio della richiesta di amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    header("Location: ../../myRequestsPage.php"); //Redirect alla pagina delle richieste

    mysqli_close($connection);

?>