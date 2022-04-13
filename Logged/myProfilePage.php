<?php

    require "Utility/PHP/initConnection.php";
    $connection = initConnection();

    session_start();

    $email = $_SESSION['email'];

    $query = $connection -> prepare('SELECT username FROM `users` WHERE email=?');
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina del suo profilo. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $username = $query -> get_result() -> fetch_assoc()['username'];
    $profilePic = "../ProfilePics/" . $email;

    $country = "N/S";
    $dateOfBirth = null;
    $gender = "N/S";

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/myProfilePageStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>
        <script src="Utility/JS/myProfileScript.js"></script>

        <title>Profilo</title>

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
                            <a class="nav-link" aria-disabled="true" href="homePage.php">Home</a> <!-- Link alla home -->
                        </li>
        
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contatti</a> <!-- Link alla pagina dei contatti -->
                        </li>
        
                    </ul>

                    <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">

                        <li class="nav-item">
                            <a class="nav-link active" href="" aria-current="page">Profilo</a> <!-- Link al profilo utente -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="Utility/PHP/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <!-- 
            TODO:
            Immagine tonda.

        -->

        <div class="general-container">

            <div class="profile-container">

                <div class="image-container" style='background: url("<?php echo $profilePic; ?>") no-repeat center center; background-size: cover; overflow: hidden;'>
                    <form action="Utility/PHP/updateProfilePic.php" method="POST" id="imageForm" enctype="multipart/form-data">
                        <label class="btn-image" for="imagePicker">Cambia Immagine</label>
                        <input style="visibility: hidden; height: 0px; width: 0px;" type="file" name="imagePicker[]" id="imagePicker" accept="image/png, image/jpg, image/jpeg" onchange=''>
                    </form>
                </div> 

                <div class="info-container">

                    <h1 style="align-self: center; margin-bottom: 0"> <?php echo $username; ?> </h1>
                    
                    <p class="profile-label">Email: <?php echo $email; ?> </p>
                    <hr class="profile-separator">

                    <div class="profile-element" id="passwordElement">

                        <div class="d-flex"> 
                            <p class="profile-label">Password: ******** </p>
                            <button class="btn-change ms-3" onclick="changePassword()">Cambia password</button>
                        </div>

                    </div>       

                    <hr class="profile-separator">

                    <div class="profile-element">
                        <p class="profile-label">Genere: <?php echo $gender; ?> </p>

                        <!-- <button class="btn-change" onclick="">Cambia gender</buton> -->
                    </div>
                    <hr class="profile-element">

                    <div class="profle-element">
                        <p class="profile-label">Nazionalita': <?php echo $country; ?> </p>
                    </div>
                    <hr class="profile-element">
                </div>

            </div>

        </div>

    </body>

</html>