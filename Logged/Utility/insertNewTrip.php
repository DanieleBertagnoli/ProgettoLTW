<?php

    $host = "localhost";
    $username = "localuser";
    $psw = "local";
    $dbname = "ltw";
    $connection = mysqli_connect($host, $username, $psw, $dbname);

    $description = "";
    
    $title = $_POST['title'];
    $numPeriods = $_POST['period-num'];

    for($i = 1; $i <= $numPeriods; $i++)
    {
        $start = $_POST["start-{$i}"];
        $end = $_POST["start-{$i}"];
        $localDescription = $_POST["description-{$i}"];

        $description = $description . "Dal " . $start . " al " . $end. ": " . $localDescription . "\n";

        $file = $_FILES["images-{$i}"];
        
        $fileName = $_FILES["images-{$i}"]['name'];
        $fileTmpName = $_FILES["images-{$i}"]['tmp_name'];
        $fileSize = $_FILES["images-{$i}"]['size'];
        $fileError = $_FILES["images-{$i}"]['error'];
        $fileType = $_FILES["images-{$i}"]['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        ///Get file name
        $query = $connection -> prepare("SELECT count(*) FROM trip");
        $query -> execute();
        $nextId = ($query -> get_result() -> fetch_assoc())['count(*)'] + 1;

        $fileNameNew = $nextId . "-" . $i;
        $directoryName = "../../TripImages/{$nextId}";
        
        mkdir($directoryName, 0777);

        move_uploaded_file($fileTmpName, "../../TripImages/{$nextId}/{$fileNameNew}");
    }

    echo $description;
?>