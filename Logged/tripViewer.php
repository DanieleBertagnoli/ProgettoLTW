<?php
    require "Utility/PHP/initConnection.php";
    $connection = initConnection();
    
    session_start();
    
    $id = $_GET['tripID'];
    $query = $connection -> prepare("SELECT * FROM trip WHERE ID={$id}");
    $query -> execute();
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();

    $title = $row['title'];

    $wholeDescription = $row['description'];
    $descriptions = explode("~(~~)~", $wholeDescription);
    $numPeriods = count($descriptions) - 1;
    $carouselCells = "";
    $counter = 1;

    $images = array(array());

    //Break it up based on symbols.
    
    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/tripViewerStyle.css">
        
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
                            <a class="nav-link" aria-current="page" href="#">Home</a> <!-- Link alla home -->
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

        <div class="general-container">
            <h1 class="text-center" style="padding-bottom: 2rem"> <?php echo $title; ?> </h1>
            <?php
                for($i = 1; $i <= $numPeriods; $i++)
                {
                    
                    while(true)
                    {
                        $currentImage = "../TripImages/" . $id . "/period-" . $i . "/" . $id . "-" . $counter;
                        if(!is_file($currentImage))
                        { break; }
                        $counter = $counter + 1;
                        $carouselCells = $carouselCells . " " . "<div class=\"carousel-cell\" style=\"background: url('" . $currentImage . "') no-repeat center center; 
                        background-size: 100% 100%; overflow: hidden;\"></div>";
                    }
                    
                    echo "<div class=\"view-container\">";
                    
                    echo "<div style=\"heigth: 80%\" class=\"main-carousel\" data-flickity='{ \"cellAlign\": \"left\", \"contain\": true }'>";
                    echo $carouselCells;
                    echo "</div>";
                    
                    echo "<p class=\"my-label\">" . $descriptions[$i] . "</p>";
                    echo "</div>";
                }
            ?>

            <div class="comment-container">
                <i class="bi bi-plus" style="font-size: 20px"></i>
                <label>Ciao mondooooooooooooooooo</label>
                <br><br><br><br><br><br><br><br><br><br>
            </div>

        </div>
    </body>
</html>