<?php
    require "Utility/PHP/initConnection.php";
    $connection = initConnection();
    
    if(!$connection)
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina delle richieste. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    session_start();

    $email = $_SESSION['email'];

    $query = $connection -> prepare("SELECT * FROM friends, users WHERE user2=? AND pending=1 AND user1=email");
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina con i risultati della ricerca. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $result = $query -> get_result();
    $requests = array();
    while($row = $result -> fetch_assoc())
    { $requests[] = $row; }
    
    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/myRequestStyle.css">

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
                            <a class="nav-link active" href="#" aria-disabled="true">Richieste di amicizia</a> 
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

                $requestNum = count($requests);

                if($requestNum == 0)
                { echo "<h1 class=\"text-center\"> Nessuna richiesta di amicizia</h1>"; }
                else if($requestNum == 1)
                { echo "<h1 class=\"text-center\"> $requestNum richiesta di amicizia</h1>"; }
                else
                { echo "<h1 class=\"text-center\"> $requestNum richieste di amicizia</h1>"; }

            ?>

        <div class="general-container">

            <?php

                for($i=0; $i<count($requests); $i++)
                {
                    $sender = $requests[$i]['user1'];

                    $requestDiv = "<div class=\"user\">";
                    $requestDiv = $requestDiv . '<img class="user-img" src="../ProfilePics/' . $sender . '"><h1 class="text-center">' . $requests[$i]["username"] . '</h1>';
                    $requestDiv = $requestDiv . '<div class="btn-container">
                                                <a class="btn-accept" href="Utility/PHP/acceptFriend.php?user=' . $sender . '">Accetta</a>
                                                <a class="btn-profile" href="externalProfilePage.php?user=' . $sender . '">Vedi profilo</a>
                                                <a class="btn-refuse" href="Utility/PHP/refuseFriend.php?user=' . $sender . '">Rifiuta</a></div>';
                    $requestDiv = $requestDiv . "</div></a>";
                    echo $requestDiv;
                }

            ?>

        </div>

    </body>

</html>