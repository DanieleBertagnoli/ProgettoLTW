<?php

    if(!isset($_POST['email']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['passwordRepeated']))
    { header("Location: ../index.html"); }

    $host = "localhost";
    $username = "localuser";
    $psw = "local";
    $dbname = "ltw";
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordRepetead = $_POST['passwordRepetead'];

    if(!$connection)
    { echo "Errore durante la connesione: " . mysqli_connect_error(); }

    $query = $connection -> prepare("SELECT * FROM users WHERE EMAIL=?");
    $query -> bind_param("s", $email);
    $query -> execute();
    $result = $query -> get_result();
    $row = $result -> fetch_assoc();
    if($row != 0)
    { echo "Attenzione l'utente con questa email risulta già registrato, effettua il login: <a href='../Login/loginPage.html'>Clicca qui</a>"; }
    else
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = $connection -> prepare("INSERT INTO users (EMAIL, PASSWORD, USERNAME) VALUES (?,?,?)");
        $query -> bind_param("sss", $email, $password, $username);
        $result = $query -> execute();
        if($result)
        { echo "Registrazione avvenuta con successo, effettua il login: <a href='../Login/loginPage.html'>Clicca qui</a> "; }
        else
        { echo "Errore durante la registrazione, riprova più tardi"; }
    }

    mysqli_close($connection);

?>