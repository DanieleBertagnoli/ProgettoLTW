<?php
    
    require "Utility/PHP/initConnection.php";
    $connection = initConnection();

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charsetb="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/newTripStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>
        <script src="Utility/JS/newTripScript.js"></script>

        <title>Aggiungi itinerario</title>

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
      
                    <ul class="navbar-nav ms-3 me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="homePage.php">Home</a> <!-- Link alla home -->
                        </li>
        
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contatti</a> <!-- Link alla pagina dei contatti -->
                        </li>
        
                    </ul>

                    <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">

                        <li class="nav-item">
                            <a class="nav-link" href="Utility/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <div class="background-img">

            <form class="general-container" action="Utility/PHP/insertNewTrip.php" method="POST" enctype="multipart/form-data" onsubmit="return checkForm();">

                <h1 class="text-center mt-2">Crea un nuovo itinerario!</h1>

                <input type="hidden" name="period-num" value="1" id="period-num">

                <div class="title">
                    <label for="title">Titolo:</label>
                    <input type="text" name="title" id="title" placeholder="Inserire il titolo dell'itinerario" maxlength="30">
                </div>

                <div class="title">
                    <label for="place">Luoghi visitati:</label>
                    <textarea type="text" name="place" id="place" placeholder="Inserire i luoghi visitati" maxlength="190" rows="3"></textarea>
                </div>

                <input type="file" name="thumbnail" id="thumbnail" multiple="false" accept="image/png, image/jpg, image/jpeg">
                <label class="thumbnail-picker" for="thumbnail" id="thumbnail-label">Seleziona un'immagine di copertina <i class="bi bi-images"></i></label>

                <div class="alert alert-danger d-flex align-items-end alert-dismissible mt-5" id="errorMessage" style="visibility: hidden; width: fit-content; align-self: center;"></div> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

                <div class="period" id="period-1">

                    <div class="top-elements">
                        <div class="date-elements">
                            <div>
                                <label class="period-label-start" for="start">Da: </label>
                                <input type="date" name="start-1" id="start-1">
                            </div>

                            <div>
                                <label class="period-label-end" for="end">A: </label>
                                <input type="date" name="end-1" id="end-1">
                            </div>
                        </div>

                        <input type="file" name="images-1[]" id="images-1" multiple="true" accept="image/png, image/jpg, image/jpeg">
                        <label class="file-picker" for="images-1" id="images-label-1"><i class="bi bi-plus pe-1"></i><i class="bi bi-images"></i></label>
                    </div>

                    <textarea class="period-description" placeholder="Descrivi le attività svolte durante questo periodo" name="description-1" id="description-1"></textarea>

                </div>

                <button class="btn-add" type="button" onclick="addPeriod()"><i class="bi bi-plus-circle"></i></button>

                <button class="btn btn-primary btn-submit" type="submit">Conferma</button>

            </form>

        </div>

    </body>

</html>