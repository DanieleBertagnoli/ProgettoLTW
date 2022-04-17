<?php

    function isAdmin()
    {
        $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

        session_start();
        
        if(!$connection)
        { 
            $errorMessage = "Siamo spiacenti, si è verificato un errore durante la connessione al DB. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); 
            exit();
        }
        
        /* Controllo se l'utente è admin */

        $email = $_SESSION['email'];
        $query = $connection -> prepare("SELECT * FROM admin WHERE email=?"); //seleziono tutti gli utenti che sono admin
        $query -> bind_param("s", $email);
        $success = $query -> execute();

        if(!$success) //Se la query è andata a buon fine
        { 
            $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della Home page. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
            exit();
        }

        $result = $query -> get_result();
        $row = $result -> fetch_assoc();
        if($row != 0) //Se l'utente è presente nella tabella
        { $admin = 1; } //È admin
        else
        { $admin = 0; } //Altrimenti non è admin
        
        return $admin;
    }
?>