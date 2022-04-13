<?php

    if(!isset($_POST['email']) || !isset($_POST['password']))
    { header("Location: ../index.html"); }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $host = "localhost";
    $username = "localuser";
    $psw = "local";
    $dbname = "ltw";
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    if(!$connection)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di login a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage);     
    }

    $query = $connection -> prepare("SELECT * FROM users WHERE EMAIL=?");
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success)
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di login a causa di un errore durante il procedimento. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPageLogin.php?errorMessage=" . $errorMessage . "&redirectPage=Login/loginPage.html");   
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row == 0 || !password_verify($password, $row['password']))
    { 
        $errorMessage = "Attenzione, non esiste nessun utente registrato con le credenziali inserite.";
        header("Location: errorPageLogin.php?errorMessage=" . $errorMessage); 
    }
    else
    {
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $row['username'];
        header("Location: ../Logged/homePage.php");
    }

    mysqli_close($connection);

?>