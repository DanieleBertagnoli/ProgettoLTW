<?php

    require "Utility/PHP/initConnection.php";
    require "Utility/PHP/isAdmin.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    if(!$connection) //Se la connessione con il database non è andata a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina per richiedere l'amicizia all'utente. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore ù
        exit();
    }

    session_start(); //Avvio la sessione

    if(!isset($_GET['user'])) //Se il parametro non è impostati
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina a causa di alcuni parametri mancati. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; 
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }
    
    $user = $_GET['user']; //Salvo il parametro della richiesta
    $email = $_SESSION['email'];

    $query = $connection -> prepare('SELECT * FROM `friends` WHERE (user1=? AND user2=?) OR (user1=? AND user2=?)'); //Controllo se già esiste una richiesta tra i due utenti
    $query -> bind_param("ssss", $user, $email, $email, $user);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina per richiedere l'amicizia all'utente. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore 
        exit();
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row == 0) //Se non sono presenti richieste imposto il code a 0
    { $code = 0; }
    else //Altrimenti imposto il code al valore del campo pending della richiesta
    { $code = $row['pending']; }

    $admin = isAdmin(); //1 se l'utente è admin, 0 altrimenti

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Link ai CSS -->
        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/errorPageStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <!-- Bundle con le funzioni JS di bootstrap -->
        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <title>Friends</title>

    </head>

    <body>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
    
                <img src="../Media/logo.png" alt="" style="height: 40px;" class="ms-2"> <!-- Logo -->
    
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> <!-- Hamburger che sostituisce la navbar in schermi piccoli -->

    
                <div class="collapse navbar-collapse" id="navbarScroll">
      
                    <ul class="navbar-nav ms-3 me-auto my-2 my-lg-0 navbar-nav-scroll">

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="homePage.php">Home</a> <!-- Link alla home -->
                        </li>
        
                        <li class="nav-item">
                            <a class="nav-link" href="../Contacts/contactsPage.php">Contatti</a> <!-- Link alla pagina dei contatti -->
                        </li>

                        <?php
                        
                            if($admin == 1) //Se l'utente è admin, allora aggiungo alla navbar anche la voce per le segnalazioni
                            {
                                echo    '<li class="nav-item">
                                            <a class="nav-link" href="showProblems.php">Segnalazioni</a> 
                                        </li>';
                            }
                        
                        ?>
        
                    </ul>

                    <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll">

                        <li class="nav-item">
                            <a class="nav-link" href="myFriends.php" aria-disabled="true">I miei amici</a> <!-- Link alla pagina delle amicizie -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="myRequestsPage.php" aria-disabled="true">Richieste di amicizia</a> <!-- Link alla pagina delle richieste di amicizia -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="userTripsPage.php?user=<?php echo $email; ?>" aria-disabled="true">I miei viaggi</a> <!-- Link alla pagina dei miei viaggi -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="myProfilePage.php" aria-disabled="true">Profilo</a> <!-- Link al profilo utente -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="Utility/PHP/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <!-- General container -->
        <div class="general-container">
            
            <!-- Box contenente il messagigio di errore -->
            <div class="alert alert-success d-flex flex-column mb-0 mx-auto" id="info" style="width: 90%; height: auto; align-self: center;"> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

                <?php
                    
                    if($code == "0") //Personalizzo il messaggio in base al valore di code
                    {
                        echo    '<strong>Il profilo di ' . $username . ' è privato, per poterlo visualizzare è necessario essere amici. <a href="Utility/PHP/insertFriend.php?user=' . $user . '">Clicca qui per inviare la richiesta di amicizia</a></strong>
                                <a class="mt-5 text-center" href="homePage.php">Clicca qui per tornare indietro</a>';
                    }
                    else
                    {
                        echo    '<strong>Il profilo di ' . $username . ' è privato, per poterlo visualizzare è necessario essere amici. La richiesta di amicizia è stata inviata, attendi che l\'utente accetti.</strong>
                                <a class="mt-5 text-center" href="homePage.php">Clicca qui per tornare indietro</a>';
                    }
                ?>
                
            </div> 

        </div>

    </body>
    
</html>