<?php

    require "initConnection.php";
    $connection = initConnection();

    if(!isset($_POST['comment-text']) || !isset($_POST['trip-id']) || !isset($_POST['timestamp']))
    { echo ""; }

    $username = $_SESSION['email'];
    $tripID = $_POST['trip-id'];
    $text = $_POST['comment-text'];
    $timestamp = $_POST['timestamp'];

    $query = $connection -> prepare("INSERT INTO comments (USER, TEXT, TRIP, DATE) VALUES (?, ?, ?, ?)");
    $query -> bind_param("ssis", $username, $text, $tripID, $user, $timestamp);
    $result = $query -> execute();

    header("Location: ../tripViewer.php?tripID=" . $tripID);
?>