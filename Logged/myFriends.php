<?php
    require "Utility/PHP/initConnection.php";
    require "Utility/PHP/isAdmin.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato
    
    if(!$connection) //Se la connessione con il database non è andata a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina delle richieste. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore 
        exit();
    }

    session_start(); //Avvio la sessione

    $email = $_SESSION['email']; //Salvo  l'email dell'utente loggato

    $query = $connection -> prepare("SELECT user1 as user, username FROM friends, users WHERE user2=? AND pending=0 AND user1=email"); //Ottengo gli utenti che sono amici con l'utente loggato
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della lista degli amici. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $result = $query -> get_result();
    $friends = array(); //Creo un array 
    while($row = $result -> fetch_assoc())
    { $friends[] = $row; } //Inserisco tutti gli utenti in un array indicizzato

    $query = $connection -> prepare("SELECT user2 as user, username FROM friends, users WHERE user1=? AND pending=0 AND user2=email");
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della lista degli amici. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore 
        exit();
    }

    $result = $query -> get_result();
    $friends = array(); //Creo un array 
    while($row = $result -> fetch_assoc())
    { $friends[] = $row; } //Inserisco tutti gli utenti in un array indicizzato
    
    $admin = isAdmin(); //1 se l'utente è admin, 0 altrimenti

    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Link ai CSS -->
        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/myRequestStyle.css">

        <!-- Bundle con le funzioni JS di bootstrap -->
        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <title>Friends</title>

    </head>

    <body>

        <!-- API per il carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.pkgd.min.js" integrity="sha512-achKCfKcYJg0u0J7UDJZbtrffUwtTLQMFSn28bDJ1Xl9DWkl/6VDT3LMfVTo09V51hmnjrrOTbtg4rEgg0QArA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                            <a class="nav-link active" href="#" aria-disabled="true">I miei amici</a> <!-- Link alla pagina delle amicizie -->
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

            <!-- Stampo il numero di amici -->
            <?php

                $friendsNum = count($friends);

                if($friendsNum == 0)
                { echo "<h1 class=\"text-center\"> Nessun amico</h1>"; }
                else if($friendsNum == 1)
                { echo "<h1 class=\"text-center\"> $friendsNum amico</h1>"; }
                else
                { echo "<h1 class=\"text-center\"> $friendsNum amici</h1>"; }

            ?>

        <!-- General container -->
        <div class="general-container">

            <?php

                for($i=0; $i<count($friends); $i++) //Per ogni amico trovato, stampo una div personalizzata
                {
                    $temp = $friends[$i]['user'];

                    $friendDiv = "<a href=\"externalProfilePage.php?user=$temp\"><div class=\"user\">";
                    $friendDiv = $friendDiv . '<img class="user-img" src="../ProfilePics/' . $temp . '"><h1 class="text-center">' . $friends[$i]["username"] . '</h1>';
                    $friendDiv = $friendDiv . "</div></a>";
                    echo $friendDiv;
                }

            ?>

        </div>

    </body>

</html>