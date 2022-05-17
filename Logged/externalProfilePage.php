<?php

    require "Utility/PHP/initConnection.php";
    require "Utility/PHP/isAdmin.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    if(!$connection) //Se la connessione con il database non è andata a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del profilo a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    session_start(); //Avvio la sessione

    if(!isset($_GET['user']) || $_GET['user'] == "") //Se il paramentro non è impostato
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina del profilo dell'utente a causa di paramentri mancanti. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage);  //Redirect alla pagina di errore
        exit();
    }

    $user = $_GET['user']; //Salvo l'email dell'utente di cui è stata richiesta la pagina
    $email = $_SESSION['email']; //Salvo  l'email dell'utente loggato

    $query = $connection -> prepare('SELECT username, gender, birthday, country, privacy FROM `users` WHERE email=?'); //Ottengo le informazioni dell'utente
    $query -> bind_param("s", $user);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina del profilo dell'utente. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage);  //Redirect alla pagina di errore
        exit();
    }

    /* Estraggo dalla riga della query le informazioni */

    $row = $query -> get_result() -> fetch_assoc();
    if($row == 0) //Se non esiste l'utente richiesto
    { 
        $errorMessage = "Siamo spiacenti, l'utente non esiste all'interno del database. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage);  //Redirect alla pagina di errore
        exit();
    }

    $username = $row['username'];

    $gender = $row['gender'];
    if($gender == "")
    { $gender = "N/S"; }

    $country = $row['country'];
    if($country == "")
    { $country = "N/S"; }

    $birthDay = $row['birthday'];
    if($birthDay == "0000-00-00")
    { $birthDay = "N/S"; }

    $privacy = $row['privacy'];

    $profilePic = "../ProfilePics/" . $user;

    $query = $connection -> prepare('SELECT * FROM `friends` WHERE (user1=? AND user2=?) OR (user1=? AND user2=?)'); //Controllo se i due utenti sono amici
    $query -> bind_param("ssss", $user, $email, $email, $user);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina del profilo dell'utente. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage);  //Redirect alla pagina di errore
        exit();
    }

    $friendRequest = $query -> get_result() -> fetch_assoc();
    if(($friendRequest == 0 || ($friendRequest['pending'] == "1" && $friendRequest['user2'] == $user)) && $email != $user && $privacy == 1) //Se i due utenti non sono amici(oppure è la richiesta è ancora in attesa di essere accettata) e l'utente ha impostato la privacy a 1
    { $hideProfile = 1; } 

    $admin = isAdmin(); //1 se l'utente è admin, 0 altrimenti

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Link ai css -->
        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/externalProfileStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        
        <!-- Bundle con le funzioni JS di bootstrap -->
        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <title>Profilo</title>

    </head>

    <body>

        <!-- API per il carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.pkgd.min.js" integrity="sha512-achKCfKcYJg0u0J7UDJZbtrffUwtTLQMFSn28bDJ1Xl9DWkl/6VDT3LMfVTo09V51hmnjrrOTbtg4rEgg0QArA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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

            <?php
            
                if($hideProfile == 1) //Il profilo va nascosto
                { echo '<div class="private-profile">Il profilo dell\'utente è privato, effettua la richiesta e attendi che l\'utente la accetti per visualizzarlo</div>'; }
                else
                {
                    echo '
                    <!-- Profile container -->
                    <div class="profile-container">

                        <!-- Container dell\'immagine di profilo -->
                        <div class="image-container">
                            <img class="profile-pic" src="' . $profilePic.'" alt="">
                        </div> 

                        <!-- Container delle informazioni -->
                        <div class="info-container">

                            <!-- Username -->
                            <h1 style="align-self: center"> ' . $username . ' </h1>
                                
                            <!-- Email -->
                            <p class="profile-label mt-5">Email: ' . $user . '</p>
                            <hr class="profile-separator">
                                
                            <!-- Gender -->
                            <p class="profile-label">Genere: ' . $gender . '</p>
                            <hr class="profile-separator">

                            <!-- Nazione -->
                            <p class="profile-label">Nazione: ' . $country . '</p>
                            <hr class="profile-separator">

                            <!-- Data di nascita -->
                            <p class="profile-label">Data di nascita: ' . $birthDay . ' </p>
                            <hr class="profile-separator">

                        </div>

                    </div>
                    
                    <!-- Bottone che porta ai viaggi pubblicati dall\'utente -->
                    <button class="btn-trip"><a href="userTripsPage.php?user=' . $user . '">Viaggi dell\'utente</a></button>';
                }
            ?>

            <!-- Bottone che permette di richiedere l'amicizia all'utente / rimuoverla -->
            <?php
            
                if($friendRequest == 0 && $email != $user) //I due utenti non sono amici e non sono lo stesso utente
                { echo "<button class=\"btn-trip mt-4\" id=\"requestButton\"><a href=\"Utility/PHP/insertFriend.php?user=$user\">Invia richiesta di amicizia</a></button>"; }
                elseif($friendRequest != 0 && $friendRequest['pending'] == 1 && $email != $user) //C'è una richiesta di amicizia in sospeso
                { echo "<button class=\"btn-disabled mt-4\" id=\"requestButton\">Richiesta di amicizia in attesa</button>"; }
                elseif($friendRequest != 0 && $friendRequest['pending'] == 0 && $email != $user) //I due utenti sono amici
                { echo "<button class=\"btn-delete mt-4\" id=\"requestButton\"><a href=\"Utility/PHP/removeFriend.php?user=$user\">Rimuovi dagli amici</a></button>"; }
            
            ?>

        </div>

    </body>

</html>