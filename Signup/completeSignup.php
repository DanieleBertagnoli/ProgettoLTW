<?php

    if(!isset($_POST['email']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['passwordRepeated']))
    { header("Location: ../index.html"); }

    $host = "localhost";
    $username = "localuser";
    $psw = "local";
    $dbname = "ltw";
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    if(!$connection)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di registrazione a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPageSignup.php?errorMessage=" . $errorMessage);     
    }

    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordRepetead = $_POST['passwordRepetead'];

    $query = $connection -> prepare("SELECT * FROM users WHERE EMAIL=?");
    $query -> bind_param("s", $email);
    $query -> execute();
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row != 0)
    { 
        $errorMessage = "Attenzione, l'email inserita risulta già registrata.";
        header("Location: errorPageSignup.php?errorMessage=" . $errorMessage); 
    }
    else
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = $connection -> prepare('INSERT INTO users (EMAIL, PASSWORD, USERNAME) VALUES (?,?,?)');
        $query -> bind_param("sss", $email, $password, $username);
        $success = $query -> execute();
        if($success)
        { header("Location: successPage.html"); }
        else
        {
            $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di registrazione a causa di un errore durante l'inserimento dei dati nel database. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
            header("Location: errorPageSignup.php?errorMessage=" . $errorMessage);    
        }
    }

    mysqli_close($connection);

?>