<?php

    if(!isset($_POST['email']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['passwordRepeated'])) //Se uno dei paramentri non è impostato
    { 
        header("Location: ../index.html"); //Redirect alla home page
        exit();
    } 

    /* Connessione al database */

    $host = "localhost";
    $username = "localuser";
    $psw = "local";
    $dbname = "ltw";
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    if(!$connection) //Se la connessione al database fallisce
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di registrazione a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPageSignup.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore  
        exit();
    }

    $email = $_POST['email']; //Salvo i parametri della richiesta
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordRepetead = $_POST['passwordRepetead'];

    $query = $connection -> prepare("SELECT * FROM users WHERE EMAIL=?"); //Controllo se l'email passata è già presente nel database
    $query -> bind_param("s", $email);
    $query -> execute();
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row != 0) //Se è già presente 
    { 
        $errorMessage = "Attenzione, l'email inserita risulta già registrata.";
        header("Location: errorPageSignup.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }
    else
    {
        $password = password_hash($password, PASSWORD_DEFAULT); //Cripto la password
        $query = $connection -> prepare('INSERT INTO users (EMAIL, PASSWORD, USERNAME, PRIVACY) VALUES (?,?,?,1)'); //Inserisco i dati nel database
        $query -> bind_param("sss", $email, $password, $username);
        $success = $query -> execute();

        if($success) //Se la query va a buon fine
        { 
            header("Location: successPage.html"); //Redirect alla pagina di successo
            exit();
        } 
        else
        {
            $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di registrazione a causa di un errore durante l'inserimento dei dati nel database. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
            header("Location: errorPageSignup.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
            exit();
        }
    }

    mysqli_close($connection); //Chiudo la connessione al database

?>