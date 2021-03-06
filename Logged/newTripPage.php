<?php
    
    require "Utility/PHP/initConnection.php";
    require "Utility/PHP/isAdmin.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    if(!$connection) //Se la connessione con il database non è andata a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di inserimento di un nuovo itinerario a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $admin = isAdmin(); //1 se l'utente è admin, 0 altrimenti
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charsetb="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Link ai CSS -->
        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/newTripStyle.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <!-- Bundle con le funzioni JS di bootstrap -->
        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <!-- Link allo script JS -->
        <script src="Utility/JS/newTripScript.js"></script>

        <title>Nuovo itinerario</title>

    </head>

    <body>

        <!-- API per il carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.pkgd.min.js" integrity="sha512-achKCfKcYJg0u0J7UDJZbtrffUwtTLQMFSn28bDJ1Xl9DWkl/6VDT3LMfVTo09V51hmnjrrOTbtg4rEgg0QArA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- JS per il selettore multiplo -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

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

        <!-- Background -->
        <div class="background-img">

            <!-- Form -->
            <form class="general-container" action="Utility/PHP/insertNewTrip.php" method="POST" enctype="multipart/form-data" onsubmit="return checkForm();">

                <h1 class="text-center mt-2">Crea un nuovo itinerario!</h1>

                <!-- Campo hidden che quantifica il numero di period creati -->
                <input type="hidden" name="periodNum" value="1" id="periodNum">

                <!-- Titolo -->
                <div class="title">
                    <label for="title">Titolo:</label>
                    <input type="text" name="title" id="title" placeholder="Inserire il titolo dell'itinerario" maxlength="30">
                </div>

                <!-- Luoghi -->
                <div class="title">
                    <label for="place">Luoghi visitati(separati da una virgola):</label>
                    <textarea type="text" name="place" id="place" placeholder="Inserire i luoghi visitati" maxlength="190" rows="3"></textarea>
                </div>

                <!-- Tags -->
                <div class="tag-selector" id="tagPickerContainer">
                    <select id="tagPicker" name="tagPicker[]" class="selectpicker" multiple title="Segli i tag">
                        <option value="mare">Mare</option>
                        <option value="montagna">Montagna</option>
                        <option value="città">Città</option>
                        <option value="relax">Relax</option>
                        <option value="avventura">Avventura</option>
                    </select>
                </div>

                <!-- Immagine di copertina -->
                <input type="file" name="thumbnail" id="thumbnail" multiple="false" accept="image/png, image/jpg, image/jpeg" onchange="changeThumbnail()">
                <label class="thumbnail-picker" for="thumbnail" id="thumbnailLabel">Seleziona un'immagine di copertina <i class="bi bi-images"></i></label>

                <!-- Period default -->
                <div class="period" id="period1">

                    <div class="top-elements">

                        <!-- Inizio e fine del periodo -->
                        <div class="date-elements">
                            <div>
                                <label class="period-label-start" for="start1">Da: </label>
                                <input type="date" name="start1" id="start1">
                            </div>

                            <div>
                                <label class="period-label-end" for="end1">A: </label>
                                <input type="date" name="end1" id="end1">
                            </div>
                        </div>

                        <!-- Immagini del periodo -->
                        <input type="file" name="images1[]" id="images1" multiple="true" accept="image/png, image/jpg, image/jpeg">
                        <label class="file-picker" for="images1" id="imagesLabel1"><i class="bi bi-plus pe-1"></i><i class="bi bi-images"></i></label>

                    </div>

                    <!-- Descrizione -->
                    <textarea class="period-description" placeholder="Descrivi le attività svolte durante questo periodo" name="description1" id="description1"></textarea>

                </div>

                <!-- Bottone per aggiungere un periodo -->
                <button class="btn-add" type="button" onclick="addPeriod()"><i class="bi bi-plus-circle"></i></button>

                <button class="btn btn-primary btn-submit" type="submit">Conferma</button>

            </form>

        </div>

    </body>

</html>