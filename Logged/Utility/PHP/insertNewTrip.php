<?php

    require "initConnection.php";
    $connection = initConnection();

    $query = $connection -> prepare("SELECT MAX(id) from trip");
    $query -> execute();
    $nextId = ($query -> get_result() -> fetch_assoc())['MAX(id)'] + 1;
    $oldumask = umask(0);
    mkdir("../../../TripImages/{$nextId}", 0777);
    umask($oldumask);

    $description = "";
    
    $title = $_POST['title'];
    $user = $_SESSION['email'];
    $visitedPlaces = $_POST['place'];

    $numPeriods = $_POST['period-num'];
    $imageCounter = 1;

    for($i = 1; $i <= $numPeriods; $i++)
    {
        if(!isset($_POST["start-{$i}"]))
        { continue; }
        
        $start = $_POST["start-{$i}"];
        $end = $_POST["start-{$i}"];
        $localDescription = $_POST["description-{$i}"];

        $description = $description . "~(~~)~Dal " . $start . " al " . $end. ": " . $localDescription . "\n";

        $fileCount = 0;
        if(isset($_FILES["images-{$i}"]['name']))
        {
            $oldumask2 = umask(0);
            mkdir("../../../TripImages/{$nextId}/period-{$i}", 0777);
            umask($oldumask2);

            $fileCount = count($_FILES["images-{$i}"]['name']);
        }

        for($j = 0; $j < $fileCount; $j++)
        {
            $fileTmpName = $_FILES["images-{$i}"]['tmp_name'][$j];

            $fileNameNew = $nextId . "-" . $imageCounter;
            move_uploaded_file($fileTmpName, "../../../TripImages/{$nextId}/period-{$i}/{$fileNameNew}");
            $imageCounter = $imageCounter + 1;
        }
    }

    $thumbnail = $_FILES["thumnail"]['tmp_name'][0];
    move_uploaded_file($thumbnail, "../../../TripImages/{$nextId}/thumbnail");


    $keywords = " " . strtolower($title) . " ";
    $toRemove = array("\bdi\s", "\ba\s", "\bda\s", "\bin\s", "\bcon\s", "\bsu\s", "\bper\s", "\btra\s", "\bfra\s", "\bil\s", "\blo\s", "\bla\s", "\bi\s", "\bgli\s", "\ble\s", "\bl'\b", "\bun\s", "\bun'\s", "\buna\s", "\bnel\s", "\bnella\s");
    $keywords = str_replace($toRemove, "", $keywords);

    $query = $connection -> prepare("INSERT INTO trip (TITLE, DESCRIPTION, VISITED_PLACES, USER, KEYWORDS) VALUES (?, ?, ?, ?, ?)");
    $query -> bind_param("sssss", $title, $description, $visitedPlaces, $user, $keywords);
    $result = $query -> execute();

    if($result)
    { echo "Caricamento avvenuto con successo: <a href='../../tripViewer.php?tripID=" . $nextId . "'>Clicca qui per visualizzare il tuo viaggio!</a> "; }
    else
    { echo "Errore durante il caricamento, riprova più tardi"; }
?>