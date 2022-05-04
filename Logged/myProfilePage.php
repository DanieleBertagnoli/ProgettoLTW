<?php

    require "Utility/PHP/initConnection.php";
    require "Utility/PHP/isAdmin.php";
    $connection = initConnection(); //Inizializzo la connessione con il database e controllo se l'utente è loggato

    if(!$connection) //Se la connessione con il database non è andata a buon fine
    {
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del profilo. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore 
        exit();
    }

    session_start(); //Avvio la sessione

    $email = $_SESSION['email']; //Ottengo i parametri dell'utente loggato

    $query = $connection -> prepare('SELECT username, gender, birthday, country FROM `users` WHERE email=?'); //Ottengo le informazioni dell'utente
    $query -> bind_param("s", $email);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina del profilo. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit();
    }

    $row = $query -> get_result() -> fetch_assoc();

    /* Estraggo dalla riga della query le informazioni */

    $username = $row['username'];

    $birthDay = $row['birthday'];
    if($birthDay == "0000-00-00")
    { $birthDay = "N/S"; }

    $gender = $row['gender'];
    if($gender == "")
    { $gender = "N/S"; }

    $country = $row['country'];
    if($country == "")
    { $country = "N/S"; }

    $privacy = $row['privacy'];
    if($privacy == "0")
    { $privacy = "Pubblico"; }
    else
    { $privacy = "Privato"; }

    $profilePic = "../ProfilePics/" . $email;

    $admin = isAdmin(); //1 se l'utente è admin, 0 altrimenti

    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Link ai CSS -->
        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/myProfileStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <!-- Bundle con le funzioni JS di bootstrap -->
        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <!-- Link allo script JS -->
        <script src="Utility/JS/myProfileScript.js"></script>

        <title>Profilo</title>

    </head>

    <body>

        <!-- API per il carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.pkgd.min.js" integrity="sha512-achKCfKcYJg0u0J7UDJZbtrffUwtTLQMFSn28bDJ1Xl9DWkl/6VDT3LMfVTo09V51hmnjrrOTbtg4rEgg0QArA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Navbar -->

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
                            <a class="nav-link" href="../Contacts/contactsPage.php">Contatti</a> <!-- Link alla pagina dei contatti -->
                        </li>

                        <?php

                            if($admin == 1) //Se l'utente è admin, allora aggiungo alla navbar anche la voce per le segnalazioni
                            {
                                echo    '<li class="nav-item">
                                            <a class="nav-link" href="showProblems.php">Segnalazioni</a> 
                                        </li>';
                            }

                        ?>

                    </ul>

                    <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll">

                        <li class="nav-item">
                            <a class="nav-link" href="myFriends.php" aria-disabled="true">I miei amici</a> <!-- Link alla pagina delle amicizie -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="myRequestsPage.php" aria-disabled="true">Richieste di amicizia</a> <!-- Link alla pagina delle richieste di amicizia -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="userTripsPage.php?user=<?php echo $email; ?>" aria-disabled="true">I miei viaggi</a> <!-- Link alla pagina dei miei viaggi -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="#" aria-disabled="true">Profilo</a> <!-- Link al profilo utente -->
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="Utility/PHP/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <!-- Container generale -->
        <div class="general-container">

            <!-- Container del profilo -->
            <div class="profile-container">

                <!-- Container dell'immagine di profilo -->
                <div class="image-container">

                    <!-- Immagine di profilo salvata all'interno del path TripImages/emailUtente -->
                    <img class="profile-pic" src="<?php echo $profilePic; ?>" alt="">

                    <!-- Form per il cambio di immagine profilo -->
                    <form action="Utility/PHP/updateProfilePic.php" method="POST" id="imageForm" enctype="multipart/form-data">
                        <label class="btn-image text-nowrap" for="imagePicker">Cambia Immagine</label>
                        <input style="visibility: hidden; height: 0px; width: 0px;" type="file" name="imagePicker[]" id="imagePicker" accept="image/png, image/jpg, image/jpeg" onchange='document.getElementById("imageForm").submit()'>
                    </form>

                </div> 

                <!-- Container delle informazioni -->
                <div class="info-container">

                    <!-- Username -->
                    <h1 style="align-self: center; margin-bottom: 0"> <?php echo $username; ?> </h1>
                    
                    <!-- Email -->
                    <p class="profile-label">Email: <?php echo $email; ?> </p>
                    <hr class="profile-separator">

                    <!-- Password -->
                    <div class="profile-element" id="passwordElement">

                        <p class="profile-label">Password: ******** </p>
                        <button class="btn-change ms-3" onclick="changePassword()">Cambia password</button> <!-- Bottone per il cambio della password -->

                    </div>       
                    <hr class="profile-separator">
                    
                    <!-- Gender -->
                    <div class="profile-element" id="genderElement">

                        <p class="profile-label">Genere: <?php echo $gender; ?> </p>
                        <button class="btn-change ms-3" onclick='changeGender(<?php echo "\"$gender\""; ?>)'>Cambia</button> <!-- Bottone per il cambio del gender -->

                    </div>
                    <hr class="profile-separator">

                    <!-- Nazione -->
                    <div class="profile-element" id="countryElement">

                        <p class="profile-label">Nazione: <?php echo $country; ?> </p>
                        <button class='btn-change ms-3' onclick='changeCountry(<?php echo "\"$country\"" ?>)'>Cambia</button> <!-- Bottone per il cambio della nazione -->

                    </div>
                    <hr class="profile-separator">

                    <!-- Data di nascita -->
                    <div class="profile-element" id="dateElement">

                        <p class="profile-label">Data di nascita: <?php echo $birthDay; ?> </p>
                        <button class='btn-change ms-3' onclick='changeDate(<?php echo "\"$birthDay\"" ?>)'>Cambia</button>

                    </div>
                    <hr class="profile-separator">

                    <!-- Privacy del profilo -->
                    <div class="profile-element" id="privacyElement">

                        <p class="profile-label">Visibilità: <?php echo $privacy; ?> </p>
                        <button class='btn-change ms-3' onclick='changePrivacy(<?php echo "\"$privacy\"" ?>)'>Cambia</button> <!-- Bottone per il cambio della pravicy -->

                    </div>
                    <hr class="profile-separator">

                </div>

            </div>

        </div>

    </body>

</html>