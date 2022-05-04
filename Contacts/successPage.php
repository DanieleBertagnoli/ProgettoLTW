<?php

    session_start(); //Avvio la sessione
    $email = $_SESSION['email'];

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Link ai CSS -->
        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/errorPageStyle.css">
        
        <!-- CSS icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <!-- Link allo script JS -->
        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

        <title>Success page</title>

    </head>

    <body>

    <div class="collapse navbar-collapse" id="navbarScroll">
        
        <?php

            /* Se il parametro email della sesisone non è impostato l'utente non è loggato, quindi stampo la navbar per gli non utenti loggati */

            if(!isset($_SESSION['email']))
            {
                echo '
                        <ul class="navbar-nav ms-3 me-auto my-2 my-lg-0 navbar-nav-scroll">

                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="../index.html">Home</a> <!-- Link alla home -->
                            </li>
                
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Contatti</a> <!-- Link alla pagina dei contatti -->
                            </li>
                
                        </ul>

                        <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll">

                            <li class="nav-item">
                                <a class="nav-link" href="../Login/loginPage.html" aria-disabled="true">Accedi</a> <!-- Link alla pagina di login -->
                            </li>
            
                            <li class="nav-item">
                                <a class="nav-link" href="../Signup/signupPage.html" aria-disabled="true">Registrati</a> <!-- Link alla pagina di registrazione -->
                            </li>

                        </ul>';
            }

            /* Altrimenti l'utente è loggato, quindi stampo la navbar per gli utenti loggati */
            
            else
            {
                echo '
                        <ul class="navbar-nav ms-3 me-auto my-2 my-lg-0 navbar-nav-scroll">

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../Logged/homePage.php">Home</a> <!-- Link alla home -->
                            </li>
                
                            <li class="nav-item">
                                <a class="nav-link" href="#">Contatti</a> <!-- Link alla pagina dei contatti -->
                            </li>
                
                        </ul>
        
                        <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll">
        
                            <li class="nav-item">
                                <a class="nav-link" href="../Logged/myFriends.php" aria-disabled="true">I miei amici</a> 
                            </li>
        
                            <li class="nav-item">
                                <a class="nav-link" href="../Logged/myRequestsPage.php" aria-disabled="true">Richieste di amicizia</a> 
                            </li>
        
                            <li class="nav-item">
                                <a class="nav-link" href="../Logged/userTripsPage.php?user=' . $email . '" aria-disabled="true">I miei viaggi</a> 
                            </li>
        
                            <li class="nav-item">
                                <a class="nav-link" href="../Logged/myProfilePage.php" aria-disabled="true">Profilo</a> <!-- Link al profilo utente -->
                            </li>
        
                            <li class="nav-item">
                                <a class="nav-link" href="../Logged/Utility/PHP/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
                            </li>
        
                        </ul>';
            }
        ?>

    </div>
</div>
</nav>


        <!-- General container -->
        <div class="general-container">
            
            <!-- Box del messaggio di successo -->
            <div class="alert alert-success d-flex flex-column mb-0 mx-auto" id="errorMessage" style="width: 90%; height: auto; align-self: center;"> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

                <strong>Segnalazione completata con successo!</strong>

                <?php

                        /* Se il parametro email della sessione non è impostato l'utente non è loggato, quindi stampo il link per index */

                        if(!isset($_SESSION['email']))
                        { echo '<a class="mt-5 text-center" href="../index.html">Clicca qui per tornare alla home</a>'; }

                        /* Altrimenti l'utente è loggato, quindi stampo il link per la home */
                        
                        else
                        { echo '<a class="mt-5 text-center" href="../Logged/homePage.php">Clicca qui per tornare alla home</a>'; }
                    ?>

            </div> 

        </div>

    </body>
    
</html>