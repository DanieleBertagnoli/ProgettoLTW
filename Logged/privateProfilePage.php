<?php

    require "Utility/PHP/initConnection.php";
    $connection = initConnection();

    session_start();

    if(!isset($_GET['user']) || !$connection)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina a causa di alcuni parametri mancati. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; 
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }
    
    $user = $_GET['user'];
    $code = $_GET['code'];
    $email = $_SESSION['email'];

    $query = $connection -> prepare('SELECT * FROM `friends` WHERE (user1=? AND user2=?) OR (user1=? AND user2=?)');
    $query -> bind_param("ssss", $user, $email, $email, $user);
    $success = $query -> execute();

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento della pagina. Se l'errore persiste contattare gli sviluppatori tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $row = $query -> get_result() -> fetch_assoc();
    if($row == 0)
    { $code = 0; }
    else
    { $code = $row['pending']; }

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

        <title>Friends</title>

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
            
            <div class="alert alert-success d-flex flex-column mb-0 mx-auto" id="info" style="width: 90%; height: auto; align-self: center;"> <!-- Div all'interno della quale viene inserito un messaggio di errore da check() -->

                <?php
                    
                    if($code == "0")
                    {
                        echo    '<strong>Il profilo di ' . $username . ' è privato, per poterlo visualizzare è necessario essere amici. <a href="Utility/PHP/insertFriend.php?user=' . $user . '">Clicca qui per inviare la richiesta di amicizia</a></strong>
                                <a class="mt-5 text-center" href="homePage.php">Clicca qui per tornare indietro</a>';
                    }
                    else
                    {
                        echo    '<strong>Il profilo di ' . $username . ' è privato, per poterlo visualizzare è necessario essere amici. La richiesta di amicizia è stata inviata, attendi che l\'utente accetti.</strong>
                                <a class="mt-5 text-center" href="homePage.php">Clicca qui per tornare indietro</a>';
                    }
                ?>
            </div> 

        </div>

    </body>
    
</html>