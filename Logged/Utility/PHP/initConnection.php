<?php

    function initConnection()
    {
        session_start(); //Avvio la sessione
        if(!isset($_SESSION['email'])) //Se l'email non è un parametro di sessione impostato
        { 
            header("Location: ../Login/loginPage.html"); //Redirect alla home page
            exit(); 
        }

        $host = "localhost";
        $username = "localuser";
        $psw = "local";
        $dbname = "ltw";
        $connection = mysqli_connect($host, $username, $psw, $dbname); //Ottengo la connessione al database

        return $connection;
    }

?>