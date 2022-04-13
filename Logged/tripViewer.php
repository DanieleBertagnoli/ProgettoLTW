<?php
    require "Utility/PHP/initConnection.php";
    $connection = initConnection();
    
    if(!$connection)
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina di inserimento di un nuovo itinerario a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    session_start();

    if(!isset($_GET['tripID']))
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina dell'itinerario selezionato a causa di alcuni parametri mancanti nella richiesta. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $id = $_GET['tripID'];

    $query = $connection -> prepare("SELECT * FROM trip WHERE ID={$id}");
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina dell'itinerario selezionato. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();

    $title = $row['title'];

    $wholeDescription = $row['description'];
    $descriptions = explode("~(~~)~", $wholeDescription);
    $numPeriods = count($descriptions) - 1;
    $carouselCells = "";
    $counter = 1;

    $images = array(array());

    $star = array();
    $email = $_SESSION['email'];

    $query = $connection -> prepare("SELECT * FROM votes WHERE user=? AND trip=?");
    $query -> bind_param("si", $email, $id);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina dell'itinerario selezionato. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }
    
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    $userVote = $row['vote'];

    for($i=1; $i<=5; $i++)
    {
        if($i > $userVote)
        { $star[$i] = "&#9734"; }
        else
        { $star[$i] = "&#9733"; }
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
        <link rel="stylesheet" href="../CSS/tripViewerStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <!-- CSS base per il carousel -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.min.css" integrity="sha512-fJcFDOQo2+/Ke365m0NMCZt5uGYEWSxth3wg2i0dXu7A1jQfz9T4hdzz6nkzwmJdOdkcS8jmy2lWGaRXl+nFMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>
        <script src="Utility/JS/tripViewerScript.js"></script>

        <title>HomePage</title>

    </head>

    <body>

        <script>refreshVoteAvg(<?php echo "\"$id\"" ?>);</script> 
        <script>reloadComments(<?php echo "\"$id\"" ?>);</script>

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
                            <a class="nav-link" href="Utility/PHP/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
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
                    
                    echo "<p class=\"trip-description\">" . $descriptions[$i] . "</p>";
                    echo "</div>";
                }
            ?>

            <div class="alert alert-danger d-flex align-items-end alert-dismissible" id="vote-error" style="visibility: hidden;  width:90%; align-self:center;"></div> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

            <section name="star-section" class="star-container">

                <div style="display: flex; align-items:center; margin-bottom: 30px; flex-wrap: wrap">

                    <h2>Vota questo itinerario: </h2>

                    <div>
                        <button type="button" class="star" id="star-1" name="vote" value="1" onclick='return sendVote(1, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[1] ?> </button>
                        <button type="button" class="star" id="star-2" name="vote" value="2" onclick='return sendVote(2, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[2] ?> </button>
                        <button type="button" class="star" id="star-3" name="vote" value="3" onclick='return sendVote(3, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[3] ?> </button>
                        <button type="button" class="star" id="star-4" name="vote" value="4" onclick='return sendVote(4, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[4] ?> </button>
                        <button type="button" class="star" id="star-5" name="vote" value="5" onclick='return sendVote(5, <?php echo "\"$id\"" ?>, <?php echo "\"$email\"" ?>);'> <?php echo $star[5] ?> </button>
                    </div>
                </div> 

                <h2 id="vote-avg">Gli utenti hanno votato: ?</h2>

            </section>

            <div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="comment-error" style="visibility: hidden; width:90%; align-self:center;"></div> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

            <div class="comments-container">

                <div class="comment-editor" id="comment-creator">
                    <textarea id="comment-text" name="comment-text" class="comment-text" maxlength="500" placeholder="Inserisci un commento..." oninput='this.style.height="auto"; this.style.height = this.scrollHeight + "px"; checkButton()'></textarea>
                    <button id="comment-button" class="plus-btn btn-disabled" onclick='return sendComment(<?php echo "\"$id\"" ?>);'>Commenta</button></i>
                </div>

                <div class="old-comments" id="old-comments">

                </div>

            </div>
        </div>

    </body>

</html>