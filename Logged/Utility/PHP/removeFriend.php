<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza la connessione al DB

    session_start(); //Fai partire la sessione

    if(!$connection) 
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante la rimozione dell'amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    if(!isset($_GET['user']))
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante la rimozione dell'amicizia a causa di alcuni parametri mancanti. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $email = $_SESSION['email'];
    $user = $_GET['user'];

    $query = $connection -> prepare('SELECT * FROM friends WHERE ((user1=? AND user2=?) OR (user1=? AND user2=?)) AND pending=0'); //Seleziono la riga con i due utenti 
    $query -> bind_param("ssss", $user, $email, $email, $user);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante la rimozione dell'amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row == 0) //Se non e' presente alcuna amicizia
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante la rimozione dell'amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $query = $connection -> prepare('DELETE FROM friends WHERE ((user1=? AND user2=?) OR (user1=? AND user2=?))'); //Togli la richiesta dal database se rifiuti.
    $query -> bind_param("ssss", $user, $email,$email,$user);
    $success = $query -> execute();

    if(!$success)
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante la rimozione dell'amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    header("Location: ../../externalProfilePage.php?user=$user");

    mysqli_close($connection); //Chiudi connessione al DB.
?>