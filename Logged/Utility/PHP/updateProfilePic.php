<?php

    require "initConnection.php";
    $connection = initConnection();

    session_start();

    if(!$connection) //Essendo questo un file chiamato esclusivamente da richieste AJAX, il redirect viene effettuato tramite JavaScript
    { return; }

    $email = $_SESSION['email'];

    $profilePic = $_FILES['imagePicker']['tmp_name'][0];
    
    move_uploaded_file($profilePic, "../../../ProfilePics/$email");
    echo "finito";

    header("Location: ../../myProfilePage.php");
?>