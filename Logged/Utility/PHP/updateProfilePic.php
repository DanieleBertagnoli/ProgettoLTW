<?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializzo la connessione.

    session_start();

    if(!$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];

    $profilePic = $_FILES['imagePicker']['tmp_name'][0]; //Prendi il nome temporaneo dal file che abbiamo inviato a questa pagina.
    
    move_uploaded_file($profilePic, "../../../ProfilePics/$email"); //Sposta l'immagine dal path temporaneo a quello definitivo.
    echo "finito";

    header("Location: ../../myProfilePage.php"); //Si torna alla pagina del profilo
?>