<?php
    require "Utility/PHP/initConnection.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se utente e' loggato.
    
    if(!$connection || !isset($_GET['user']))
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di inserimento di un nuovo itinerario a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    session_start(); //Comincio la sessione

    $email = $_SESSION['email'];

    $user = $_GET["user"];

    $query = $connection -> prepare("SELECT * FROM trip WHERE user=?"); //Prendo dal database tutti i viaggi dell'utente.
    $query -> bind_param("s", $user);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina con i risultati della ricerca. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    $result = $query -> get_result();
    $trips = array();
    while($row = $result -> fetch_assoc())
    { $trips[] = $row; }

    $query = $connection -> prepare("SELECT * FROM users WHERE email=?"); //Prendo tutti gli utenti con quella particolare email.
    $query -> bind_param("s", $user);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina con i risultati della ricerca. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    $username = $row['username'];
    
    $active = "active";

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
        <link rel="stylesheet" href="../CSS/searchResultStyle.css">

        <!-- Bundle con le informazioni JS di bootstrap -->
        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <title>User trips</title>

    </head>

    <body>

        <!-- API per il carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.pkgd.min.js" integrity="sha512-achKCfKcYJg0u0J7UDJZbtrffUwtTLQMFSn28bDJ1Xl9DWkl/6VDT3LMfVTo09V51hmnjrrOTbtg4rEgg0QArA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Navigation bar -->

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
                            <a class="nav-link" href="#">Contatti</a> <!-- Link alla pagina dei contatti -->
                        </li>
        
                    </ul>

                    <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll">

                        <li class="nav-item">
                            <a class="nav-link" href="myFriends.php" aria-disabled="true">I miei amici</a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="myRequestsPage.php" aria-disabled="true">Richieste di amicizia</a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if($email == $user){ echo $active; } ?>" href="userTripsPage.php?user=<?php echo $email; ?>" aria-disabled="true">I miei viaggi</a> 
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

            <?php

                $tripsNum = count($trips); //Conto quanti trip ha effettuato l'utente.

                if($tripsNum == 0)
                { echo "<h1 class=\"text-center\"> Nessun viaggio di $username</h1>"; }
                else if($tripsNum == 1)
                { echo "<h1 class=\"text-center\"> $tripsNum viaggio di $username</h1>"; }
                else
                { echo "<h1 class=\"text-center\"> $tripsNum viaggi di $username</h1>"; }

            ?>

        <div class="general-container">

            <?php

                for($i=0; $i<count($trips); $i++) //Per ogni trip dell'utente.
                {
                    $temp = $trips[$i];
                    
                    //creo una superficie "cliccabile" che se premuta mi porta alla pagina dello specifico trip.
                    $tripDiv = "<a class=\"mt-5\" href=\"tripViewer.php?tripID=" . $temp['id'] . "\"><div class=\"trip\">";

                    //Preparo la descrizione togliendo dalla stringa tutti i caratteri usati dal sistema.
                    $description = $temp['description'];
                    $description = substr_replace($description, "", 0, 6);
                    $description = str_replace("~(~~)~", "<br>", $description);
                    if(strlen($description) > 250) // Se la stringa risulta troppo lunga la termino con un ...
                    { $description = substr($description, 0, 250) . "..."; }

                    //Creo il suo elemento da mostrare.
                    $tripDiv = $tripDiv . "<img class=\"trip-img\" src=\"../TripImages/" . $temp['id'] . "/thumbnail\"> 
                                            <div class=\"trip-text\">
                                                <h2 class=\"trip-title\">" . $temp['title'] . "</h2>
                                                <p class=\"trip-description\">" . $description . "</p>
                                            </div>";
                
                    $tripDiv = $tripDiv . "</div></a>";
                    echo $tripDiv;
                }

            ?>

        </div>

    </body>

</html>