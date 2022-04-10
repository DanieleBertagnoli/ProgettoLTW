<?php
    require "Utility/PHP/initConnection.php";
    $connection = initConnection();

    session_start();

    $email = $_SESSION['email'];
    $username = $_SESSION['username'];

    $query = $connection -> prepare("SELECT * FROM trip");
    $query -> execute();
    $result = $query -> get_result();

    $rows = array();
    while($row = $result -> fetch_assoc())
    { $rows[] = $row; }

    $cellCount = 7;
    if(count($rows) < $cellCount) //Ho meno itinerari nel DB di quante celle voglio creare
    { $cellCount = count($rows); } //Ne creo tante quanti sono gli itinerari

    for($i=0; $i<$cellCount; $i++)
    {
        $carouselCells = $carouselCells . " " . "<a href=\"tripViewer.php?tripID=" . $rows[$i]['id'] . "\"><div class=\"carousel-cell\" style=\"background: linear-gradient(0deg, rgba(0,0,0,.2), rgba(0,0,0,.7)), 
                                                    url('../TripImages/" . $rows[$i]['id'] . "/" . $rows[$i]['id'] . "-1') no-repeat center center; 
                                                    background-size: cover; overflow: hidden;\">" . $rows[$i]['title'] . "</div></a>";
                
    }

    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/homeStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <!-- CSS base per il carousel -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.min.css" integrity="sha512-fJcFDOQo2+/Ke365m0NMCZt5uGYEWSxth3wg2i0dXu7A1jQfz9T4hdzz6nkzwmJdOdkcS8jmy2lWGaRXl+nFMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>
        <script src="Utility/JS/homeScript.js"></script>

        <!-- API per lo scroll reveal -->
        <script src="https://unpkg.com/scrollreveal"></script> 

        <title>HomePage</title>

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
                            <a class="nav-link active" aria-current="page" href="#">Home</a> <!-- Link alla home -->
                        </li>
        
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contatti</a> <!-- Link alla pagina dei contatti -->
                        </li>
        
                    </ul>

                    <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">

                        <li class="nav-item">
                            <a class="nav-link" href="Utility/PHP/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <h1 class="text-center mt-2">Benvenuto <?php echo $username; ?></h1>

        <section class="general-container">

            <div class="tools-container">

                <a href="newTripPage.php"><button class="btn-add">Crea itinerario</button></a> <!-- Bottone per creare un nuovo itinerario -->

                <form id="search-form" class="search-form" action="searchResult.php" method="POST" onsubmit="return checkForm();"> <!-- Form per la ricerca di itinerari -->
                    <input id="search-box" name="search-box" class="search-box" type="text" placeholder="Cerca un itinerario...">
                    <button class="btn-search" type="submit"><i class="bi bi-search"></i></button>
                </form>

            </div>

            <div class="main-carousel" data-flickity='{ "cellAlign": "left", "contain": true }'> <!-- Carousel generato dinamicamente in base ai cookie se presenti -->

                <?php echo $carouselCells; ?>

            </div>
              
        </section>

        <section class="poster mt-5"> <!-- Poster section -->

            <div class="poster-img reveal"> <!-- La classe reveal permette all'API dello scroll reveal di mostrare gli elementi in cascata -->
                <img src="../Media/camping.jpg" alt="">
            </div>

            <div class="poster-content reveal">
                <h1 class="my-4">Immergiti nella natura!</h1>
                <p>Se sei un amante della natura, adorerai gli itinerari immersi nel verde proposti dai nostri utenti. Sprofonda nel verde di una foresta o rilassati nei prati sconfinati in cornici idillache.</p>
                <button href="" class="btn btn-primary my-3">Scopri gli itinerari</button>
            </div>

        </section>

        <section class="poster mt-5"> <!-- Poster section -->

            <div class="poster-img reveal"> <!-- La classe reveal permette all'API dello scroll reveal di mostrare gli elementi in cascata -->
                <img src="../Media/city.jpg" alt="">
            </div>

            <div class="poster-content reveal">
                <h1 class="my-4">Ammira lo splendore delle città!</h1>
                <p>Diventa un turista per le strade di una città. Visita musei, monumenti, e luoghi suggeriti dai nostri utenti. Lasciati guidare dalla cuoriosità di scoprire nuove culture e usanze.</p>
                <button href="" class="btn btn-primary my-3">Scopri gli itinerari</button>
            </div>

        </section>

        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

        <script>

            $( document ).ready(function()  //Quando il documento è caricato, viene applicata la ScrollReveal
            { ScrollReveal().reveal('.reveal', {distance: '100px', duration: 1500, easing: 'cubic-bezier(.215, .61, .355, 1)', interval: 600}); });

        </script>

    </body>

</html>