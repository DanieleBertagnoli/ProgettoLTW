<?php

    if(!isset($_GET['errorMessage']))
    { $errorMessage = "Siamo spiacenti, si è verificato un errore ma non è specificato cosa è andato storto. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
    else
    { $errorMessage = $_GET['errorMessage']; }

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/errorPageStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <title>ErrorPage</title>

    </head>

    <body>

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
                            <a class="nav-link" aria-current="page" href="index.html">Home</a> <!-- Link alla home -->
                        </li>
        
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contatti</a> <!-- Link alla pagina dei contatti -->
                        </li>
        
                    </ul>

                </div>
            </div>
        </nav>

        <div class="general-container">
            
            <div class="alert alert-danger d-flex flex-column mb-0 mx-auto" id="errorMessage" style="width: 90%; height: auto; align-self: center;"> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

                <strong><?php echo $errorMessage; ?></strong>
                
                <div class="d-flex justify-content-around">
                    <a class="mt-5 text-center" href="signupPage.html">Clicca qui per tornare indietro</a>
                    <a class="mt-5 text-center" href="../Login/loginPage.html">Clicca qui per effettuare il login</a>
                </div>

            </div> 

        </div>

    </body>
    
</html>