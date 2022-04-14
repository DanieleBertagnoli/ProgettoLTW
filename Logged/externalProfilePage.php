<?php

    require "Utility/PHP/initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['user']) || $_GET['user'] == "" || !$connection)
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina del profilo dell'utente a causa di paramentri mancanti. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $user = $_GET['user'];
    $email = $_SESSION['email'];

    $query = $connection -> prepare('SELECT username, gender, birthday, country FROM `users` WHERE email=?');
    $query -> bind_param("s", $user);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina del profilo dell'utente. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $row = $query -> get_result() -> fetch_assoc();
    $username = $row['username'];

    $gender = $row['gender'];
    if($gender == "")
    { $gender = "N/S"; }

    $country = $row['country'];
    if($country == "")
    { $country = "N/S"; }

    $birthDay = $row['birthday'];
    if($birthDay == "0000-00-00")
    { $birthDay = "N/S"; }

    $profilePic = "../ProfilePics/" . $user;

    $query = $connection -> prepare('SELECT * FROM `friends` WHERE (user1=? AND user2=?) OR (user1=? AND user2=?)');
    $query -> bind_param("ssss", $user, $email, $email, $user);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina del profilo dell'utente. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $row = $query -> get_result() -> fetch_assoc();
    if($row == 0 || ($row['pending'] == "1" && $row['user2'] == $user))
    { header("Location: privateProfilePage.php?user=$user"); }

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/externalProfileStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <title>Profilo</title>

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
        
                    </ul>

                    <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll">

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

            <div class="profile-container">

                <div class="image-container">
                    <img class="profile-pic" src="<?php echo $profilePic; ?>" alt="">
                </div> 

                <div class="info-container">

                    <h1 style="align-self: center"> <?php echo $username; ?> </h1>
                        
                    <p class="profile-label mt-5">Email: <?php echo $user; ?> </p>
                    <hr class="profile-separator">
                        
                    <p class="profile-label">Genere: <?php echo $gender; ?> </p>
                    <hr class="profile-separator">

                    <p class="profile-label">Nazione: <?php echo $country; ?> </p>
                    <hr class="profile-separator">

                    <p class="profile-label">Data di nascita: <?php echo $birthDay; ?> </p>
                    <hr class="profile-separator">

                </div>

            </div>

            <button class="btn-trip"><a href="userTripsPage.php?user=<?php echo $user; ?>">Viaggi dell'utente</a></button>

        </div>

    </body>

</html>