<?php

    if(!isset($_POST['email']) || !isset($_POST['password']))
    { header("Location: ../index.html"); }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $host = "localhost";
    $username = "localuser";
    $psw = "local";
    $dbname = "ltw";
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    if(!$connection)
    { echo "Errore durante la connesione: " . mysqli_connect_error(); }

    $query = $connection -> prepare("SELECT * FROM users WHERE EMAIL=?");
    $query -> bind_param("s", $email);
    $query -> execute();
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row == 0 || !password_verify($password, $row['password']))
    { echo "Attenzione non esiste nessun utente con queste credenziali, registrati: <a href='../Signup/signupPage.html'>Clicca qui</a>"; }
    else
    {
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $row['username'];
        header("Location: ../Logged/homePage.php");
    }

    mysqli_close($connection);

?>