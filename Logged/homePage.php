<?php
    
    require "Utility/initConnection.php";
    $connection = initConnection();

    session_start();

    $host = "localhost";
    $username = "localuser";
    $psw = "local";
    $dbname = "ltw";
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    if(!$connection)
    { echo "Errore durante la connesione: " . mysqli_connect_error(); }

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
        $carouselCells = $carouselCells . " " . "<div class=\"carousel-cell\" style=\"background: linear-gradient(0deg, rgba(0,0,0,.2), rgba(0,0,0,.7)), 
                                                    url('../TripImages/" . $rows[$i]['id'] . "/" . $rows[$i]['id'] . "-1') no-repeat center center; 
                                                    background-size: cover; overflow: hidden;\">" . $rows[$i]['title'] . "</div>";
                
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
                            <a class="nav-link" href="Utility/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <h1 class="text-center mt-2">Benvenuto <?php echo $username; ?></h1>

        <div class="general-container">

            <div class="tools-container">

                <a href="newTripPage.php"><button class="btn-add">Crea itinerario</button></a> <!-- Bottone per creare un nuovo itinerario -->

                <form class="search-form"> <!-- Form per la ricercad i itinerari -->
                    <input id="searchbox" class="search-box" type="text" name="search" id="search" placeholder="Cerca un itinerario...">
                    <button class="btn-search" type="submit"><i class="bi bi-search"></i></button>
                </form>

            </div>

            <div class="main-carousel" data-flickity='{ "cellAlign": "left", "contain": true }'> <!-- Carousel generato dinamicamente in base ai cookie se presenti -->

                <?php echo $carouselCells; ?>

            </div>
              
        </div>

    </body>

</html>