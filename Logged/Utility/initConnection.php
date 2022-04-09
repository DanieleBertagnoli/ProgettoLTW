<?php

    function initConnection()
    {
        session_start();
        if(!isset($_SESSION['email']))
        { header("Location: ../Login/loginPage.html"); }

        $host = "localhost";
        $username = "localuser";
        $psw = "local";
        $dbname = "ltw";
        $connection = mysqli_connect($host, $username, $psw, $dbname);

        if(!$connection)
        { echo "Errore durante la connesione: " . mysqli_connect_error(); }

        return $connection;
    }

?>