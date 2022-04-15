<?php

    session_start();

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../Bootstrap/bootstrap.css">
        <link rel="stylesheet" href="../CSS/contactsStyle.css">

        <script src="contactsScript.js"></script>

        <title>Login</title>

    </head>

    <body>

        <script src="../Bootstrap/js/bootstrap.bundle.js"></script>

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
        
                    <?php

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
                        else
                        {
                            echo '
                                    <ul class="navbar-nav ms-3 me-auto my-2 my-lg-0 navbar-nav-scroll">

                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="Logged/homePage.php">Home</a> <!-- Link alla home -->
                                        </li>
                            
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Contatti</a> <!-- Link alla pagina dei contatti -->
                                        </li>
                            
                                    </ul>
                    
                                    <ul class="navbar-nav ms-3 me-2 my-2 my-lg-0 navbar-nav-scroll">
                    
                                        <li class="nav-item">
                                            <a class="nav-link" href="Logged/myFriends.php" aria-disabled="true">I miei amici</a> 
                                        </li>
                    
                                        <li class="nav-item">
                                            <a class="nav-link" href="Logged/myRequestsPage.php" aria-disabled="true">Richieste di amicizia</a> 
                                        </li>
                    
                                        <li class="nav-item">
                                            <a class="nav-link" href="Logged/userTripsPage.php?user=<?php echo $email; ?>" aria-disabled="true">I miei viaggi</a> 
                                        </li>
                    
                                        <li class="nav-item">
                                            <a class="nav-link" href="Logged/myProfilePage.php" aria-disabled="true">Profilo</a> <!-- Link al profilo utente -->
                                        </li>
                    
                                        <li class="nav-item">
                                            <a class="nav-link" href="Logged/Utility/PHP/logout.php" aria-disabled="true">Disconnettiti</a> <!-- Link alla pagina di logout -->
                                        </li>
                    
                                    </ul>';
                        }
                    ?>

                </div>
            </div>
        </nav>

        <div class="background">

                <div class="form-container" id="form">
                            
                    <div class="mb-3">
                                
                        <label for="email" class="form-label">Indirizzo Email: </label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                            
                    </div>
                            
                    <div class="mb-3">
                            
                        <label for="subject" class="form-label">Oggetto del problema: </label>
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Oggetto" maxlength="30">
                            
                    </div>

                    <div class="mb-3">
                            
                        <label for="description" class="form-label">Descrizione dettagliata del problema: </label>
                        <textarea class="form-control" name="description" id="description" rows="7" cols="70" placeholder="Inserisci qui la descrizione del problema" maxlength="500"></textarea>
                            
                    </div>
                            
                    <button type="submit" onclick="return check()" class="btn-contacts">Invia</button>
                        
                </div>

            </div>

        </div>

    </body>

</html>