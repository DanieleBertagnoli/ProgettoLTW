<?php
    require "Utility/PHP/initConnection.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato
   
    if(!$connection) //Se la connessione al database non è andata a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di inserimento di un nuovo itinerario a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    session_start(); //Avvio la sessione

    if(!isset($_GET['tripID'])) //Se il parametro non è settato
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina dell'itinerario selezionato a causa di alcuni parametri mancanti nella richiesta. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla paginda di errore
        exit();
    }

    $id = $_GET['tripID']; //Salvo il valore passato dalla richiesta

    $query = $connection -> prepare("SELECT * FROM trip WHERE ID={$id}"); //Seleziono il trip con l'id che ho passato alla pagina.
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina dell'itinerario selezionato. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();

    $title = $row['title'];

    //Prendo tutti i relativi campi della query, e toglo i caratteri di sistema.
    $wholeDescription = $row['description']; 
    $descriptions = explode("~(~~)~", $wholeDescription);
    $numPeriods = count($descriptions) - 1;
    $carouselCells = ""; //stringa usata per accumulare le stringhe dei caroselli.
    $counter = 1; //Contatore di immagini all'interno di tutti i periodi.

    $images = array(array()); //Creo una matrice vuota

    $star = array(); //Creo un array vuoto
    $email = $_SESSION['email']; 

    $query = $connection -> prepare("SELECT * FROM votes WHERE user=? AND trip=?"); //Seleziono tutti i voti relativi alla pagina.
    $query -> bind_param("si", $email, $id);
    $success = $query -> execute();

    if(!$success) //Se la query non è andata a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina dell'itinerario selezionato. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }
    
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    $userVote = $row['vote'];

    //Graficamente imposto le stelline del voto a quanto l'utente ha votato. Default 0.
    for($i=1; $i<=5; $i++)
    {
        if($i > $userVote)
        { $star[$i] = "&#9734"; }
        else
        { $star[$i] = "&#9733"; }
    }

    /* Controllo se l'utente è admin */

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
        <link rel="stylesheet" href="../CSS/tripViewerStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <!-- CSS base per il carousel -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.min.css" integrity="sha512-fJcFDOQo2+/Ke365m0NMCZt5uGYEWSxth3wg2i0dXu7A1jQfz9T4hdzz6nkzwmJdOdkcS8jmy2lWGaRXl+nFMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Bundle con le funzioni JS di bootstrap -->
        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <!-- Link allo script JS -->
        <script src="Utility/JS/tripViewerScript.js"></script>

        <title>Trip viewer</title>

    </head>

    <body>

        <!-- API per il carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.pkgd.min.js" integrity="sha512-achKCfKcYJg0u0J7UDJZbtrffUwtTLQMFSn28bDJ1Xl9DWkl/6VDT3LMfVTo09V51hmnjrrOTbtg4rEgg0QArA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script>$(document).ready(function (){refreshVoteAvg(<?php echo "\"$id\"" ?>);});</script> <!-- Chiamo le due funzioni che inizializzano i commenti e la media voti -->
        <script>$(document).ready(function() {reloadComments(<?php echo "\"$id\"" ?>);});</script>

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
        <div id="generalContainer" class="general-container">

            <h1 class="text-center" style="padding-bottom: 2rem"> <?php echo $title; ?> </h1>

            <?php

                for($i = 1; $i <= $numPeriods; $i++) //Per ogni periodo nel viaggio.
                {
                    $carouselCells = "";
                    while(true)
                    {
                        //Non potendo estrapolare il numero preciso di immagini, iteriamo finche' non troviamo piu' immagini corrispondenti.
                        $currentImage = "../TripImages/" . $id . "/period-" . $i . "/" . $id . "-" . $counter;
                        if(!is_file($currentImage)) //Se non e' un file (abbiamo finite le immagini del period) esci.
                        { break; }
                        $counter = $counter + 1; //Aumento il counter e creo la singola cella del carosello.
                        $carouselCells = $carouselCells . " " . "<div id=\"carouselCell-" . $counter . "\" class=\"carousel-cell\" onclick=\"openPopup(" . $counter . ")\" style=\"name: ciao; background: url('" . $currentImage . "') no-repeat center center; 
                        background-size: cover; overflow: hidden;\"></div>"; //Cliccando sulla cella si potra' ingrandire l'immagine.
                    }
                    
                    echo "<div class=\"view-container\">";
                    
                    echo "<div style=\"heigth: 80%\" data-flickity='{ \"cellAlign\": \"left\", \"contain\": true }'>"; //Inizializzo il carosello del relativo periodo.
                    echo $carouselCells; //E lo popolo con le celle.
                    echo "</div>";
                    
                    echo "<p class=\"trip-description\">" . $descriptions[$i] . "</p>"; //Infine aggiungo la descrizione come didascalia. 
                    echo "</div>";
                }
            ?>

            <div id="myPopup" class="popup"> <!-- Creazione del popup a schermo intero per zoom immagine. -->
              <span class="close" onclick="closePopup()">&times;</span>
              <img class="img-popup" id="bigImage">
            </div>

            <div class="alert alert-danger d-flex align-items-end alert-dismissible" id="voteError" style="visibility: hidden;  width:90%; align-self:center;"></div> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

            <!-- Sezione con le stelle -->
            <section name="star-section" class="star-container">

                <div style="display: flex; align-items:center; margin-bottom: 30px; flex-wrap: wrap">

                    <h2>Vota questo itinerario: </h2>

                    <!-- Ogni stella richiama la funzione che invia il voto -->
                    <div>
                        <button type="button" class="star" id="star1" name="vote" value="1" onclick='return sendVote(1, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[1] ?> </button>
                        <button type="button" class="star" id="star2" name="vote" value="2" onclick='return sendVote(2, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[2] ?> </button>
                        <button type="button" class="star" id="star3" name="vote" value="3" onclick='return sendVote(3, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[3] ?> </button>
                        <button type="button" class="star" id="star4" name="vote" value="4" onclick='return sendVote(4, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[4] ?> </button>
                        <button type="button" class="star" id="star5" name="vote" value="5" onclick='return sendVote(5, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[5] ?> </button>
                    </div>
                </div> 

                <h2 id="voteAvg">Gli utenti hanno votato: ?</h2>

            </section>

            <div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="commentError" style="visibility: hidden; width:90%; align-self:center;"></div> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

            <!-- Sezione con i commenti -->
            <div class="comments-container">

                <div class="comment-editor" id="commentCreator">
                    <textarea id="commentText" name="comment-text" class="comment-text" maxlength="500" placeholder="Inserisci un commento..." oninput='this.style.height="auto"; this.style.height = this.scrollHeight + "px"; checkButton()'></textarea>
                    <button id="commentButton" class="plus-btn btn-disabled" onclick='return sendComment(<?php echo "\"$id\"" ?>);'>Commenta</button></i>
                </div>

                <div class="old-comments" id="oldComments">

                </div>

            </div>
        </div>

    </body>

</html>