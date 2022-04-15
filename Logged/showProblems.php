<?php
    
    require "Utility/PHP/initConnection.php";
    $connection = initConnection();

    if(!$connection)
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della Home page a causa della mancata connessione con il database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    session_start();

    $email = $_SESSION['email'];

    $query = $connection -> prepare("SELECT * FROM admin WHERE email=?");
    $query -> bind_param("s", $email);
    $error = $query -> execute();

    if(!$error)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della Home page. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row != 0)
    { $admin = 1; }
    else
    { $admin = 0; }

    if($admin == 0)
    { header("Location: homePage.php"); }

    $query = $connection -> prepare("SELECT * FROM problems WHERE email=?");
    $query -> bind_param("s", $email);
    $error = $query -> execute();

    if(!$error)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della Home page. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $result = $query -> get_result();

    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/showProblemsStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <!-- CSS base per il carousel -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.min.css" integrity="sha512-fJcFDOQo2+/Ke365m0NMCZt5uGYEWSxth3wg2i0dXu7A1jQfz9T4hdzz6nkzwmJdOdkcS8jmy2lWGaRXl+nFMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <script src="../Bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="Utility/JS/showProblemsScript.js"></script>

        <!-- API per lo scroll reveal -->
        <script src="https://unpkg.com/scrollreveal"></script> 

        <title>HomePage</title>

    </head>

    <body>

        <!-- API per il carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.pkgd.min.js" integrity="sha512-achKCfKcYJg0u0J7UDJZbtrffUwtTLQMFSn28bDJ1Xl9DWkl/6VDT3LMfVTo09V51hmnjrrOTbtg4rEgg0QArA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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

                        <li class="nav-item">
                            <a class="nav-link active" href="#">Segnalazioni</a> 
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

        <div class="general-container">

            <div class="period" id="period">
                
                <label for="date">Scegli il periodo:</label>
                
                <div>
                    <input type="date" name="start" id="start">
                    <input type="date" name="end" id="end">
                </div>

                <button class="btn-update" onclick='return update()'>Cerca</button>

            </div>


            <div class="problem-container" id="problemContainer">

            </div>

        </div>

    </body>

</html>