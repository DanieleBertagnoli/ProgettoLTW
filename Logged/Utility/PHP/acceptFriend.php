<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['user']) || !$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il l'invio della richiesta di amicizia, sono presenti alcuni parametri necessari. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $email = $_SESSION['email'];
    $user = $_GET['user'];

    $query = $connection -> prepare('SELECT * FROM friends WHERE user1=? AND user2=? AND pending=1');
    $query -> bind_param("ss", $user, $email);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'invio della richiesta di amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $row = $query -> get_result() -> fetch_assoc();
    if($row != 0)
    { header("../../privateProfilePage.php?user=$user"); }

    $query = $connection -> prepare('UPDATE friends SET pending=0 WHERE user1=? AND user2=?');
    $query -> bind_param("ss", $user, $email);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante l'invio della richiesta di amicizia. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    header("Location: ../../myRequestsPage.php"); 

    mysqli_close($connection);

?>