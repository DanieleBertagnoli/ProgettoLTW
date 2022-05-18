<?php

    if(!isset($_POST['email']) || !isset($_POST['password'])) //Se l'email e la password non sono presenti
    { 
        header("Location: ../index.html"); //Redirect alla home
        exit();
    } 

    $email = $_POST['email']; //Ottengo l'email
    $password = $_POST['password']; //Ottengo la password

    /* Connessione al database */
    
    $host = "localhost";                                          
    $username = "localuser";                                      
    $psw = "local";                                               
    $dbname = "ltw";                                              
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    if(!$connection) //Se la connessione non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di login a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPageLogin.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $query = $connection -> prepare("SELECT * FROM users WHERE EMAIL=?"); //Cerco l'utente nel database
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di login a causa di un errore durante il procedimento. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPageLogin.php?errorMessage=" . $errorMessage . "&redirectPage=Login/loginPage.html"); //Redirect alla pagina di errore
        exit();
    }

    $result = $query -> get_result(); //Ottengo il risultato della query.
    $row = $result -> fetch_assoc();  //Lo scompongo in un array 
    if($row == 0 || !password_verify($password, $row['password'])) //Se la password inserita non coincide con quella inserita oppure non è presente l'utente nel database
    { 
        $errorMessage = "Attenzione, non esiste nessun utente registrato con le credenziali inserite.";
        header("Location: errorPageLogin.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }
    else
    {
        //Comincio la sessione e imposto i vari campi nella sessione dell'utente.
        session_start(); 
        $_SESSION['email'] = $email; //Salvo come parametri di sessione l'email e lo username dell'utente
        $_SESSION['username'] = $row['username'];
        header("Location: ../Logged/homePage.php"); //Redirect alla home page
        exit();
    }

    mysqli_close($connection); //Chiudo la connessione con il database

?>