<?php
    require "Utility/PHP/initConnection.php";
    $connection = initConnection();
    
    if(!$connection || !isset($_POST['searchBox']))
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di inserimento di un nuovo itinerario a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    session_start();

    $keywords = $_POST["searchBox"];
    $keywords = strtolower($keywords);

    $query = $connection -> prepare("SELECT * FROM trip WHERE LOWER(title)=?");
    $query -> bind_param("s", $keywords);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina con i risultati della ricerca. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $result = $query -> get_result();

    $trips = array();
    while($row = $result -> fetch_assoc())
    { $trips[] = $row; }

    $splitKeywords = explode(" ", $keywords);
    $similarTrips = array();

    for($i=0; $i<count($splitKeywords); $i++)
    {
        $temp = "% ".$splitKeywords[$i]." %";
        $temp2 = "%,".$splitKeywords[$i].",%";
        $temp3 = "%,".$splitKeywords[$i]." %";
        $temp4 = "% ".$splitKeywords[$i].",%";
        $query = $connection -> prepare("SELECT * FROM trip WHERE LOWER(keywords) LIKE ? OR LOWER(visited_places) LIKE ? OR LOWER(visited_places) LIKE ? OR LOWER(visited_places) LIKE ? OR LOWER(visited_places) LIKE ?");
        $query -> bind_param("sssss", $temp, $temp, $temp2, $temp3, $temp4);
        $success = $query -> execute();

        if(!$success)
        { 
            $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina con i risultati della ricerca. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            header("Location: errorPage.php?errorMessage=" . $errorMessage); 
        }

        $result = $query -> get_result();

        while($row = $result -> fetch_assoc())
        {     
            $in = false;
            for($j=0; $j<count($similarTrips); $j++)
            {
                if($similarTrips[$j]['id'] == $row['id'])
                { 
                    $in = true; 
                    break; 
                }
            }

            for($j=0; $j<count($trips); $j++)
            {
                if($trips[$j]['id'] == $row['id'])
                { 
                    $in = true; 
                    break; 
                }
            }

            if(!$in)
            { $similarTrips[] = $row; } 
        }
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
        <link rel="stylesheet" href="../CSS/searchResultStyle.css">

        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <title>Search result</title>

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
                            <a class="nav-link" href="userTripsPage.php?user=<?php echo $email; ?>" aria-disabled="true">I miei viaggi</a> 
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

                $tripsNum = count($trips) + count($similarTrips);

                if($tripsNum == 0)
                { echo "<h1 class=\"text-center\"> Nessun risultato per: \"$keywords\"</h1>"; }
                else
                { echo "<h1 class=\"text-center\"> $tripsNum risultati per: \"$keywords\"</h1>"; }

            ?>

        <div class="general-container">

            <?php

                for($i=0; $i<count($trips); $i++)
                {
                    $temp = $trips[$i];

                    $tripDiv = "<a class=\"mt-5\" href=\"tripViewer.php?tripID=" . $temp['id'] . "\"><div class=\"trip\">";

                    $description = $temp['description'];
                    $description = substr_replace($description, "", 0, 6);
                    $description = str_replace("~(~~)~", "<br>", $description);
                    if(strlen($description) > 250)
                    { $description = substr($description, 0, 250) . "..."; }

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

        <div class="general-container">

            <?php

                for($i=0; $i<count($similarTrips); $i++)
                {
                    $temp = $similarTrips[$i];

                    $tripDiv = "<a class=\"mt-5\" href=\"tripViewer.php?tripID=" . $temp['id'] . "\"><div class=\"trip\">";

                    $description = $temp['description'];
                    $description = substr_replace($description, "", 0, 6);
                    $description = str_replace("~(~~)~", "<br>", $description);
                    if(strlen($description) > 250)
                    { $description = substr($description, 0, 250) . "..."; }

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