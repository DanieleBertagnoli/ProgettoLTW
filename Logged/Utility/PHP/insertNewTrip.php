 <?php

    require "initConnection.php";
    $connection = initConnection();

    $query = $connection -> prepare("SELECT MAX(id) from trip");
    $success = $query -> execute();

    if(!$success || !$connection)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo itinerario. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }

    $nextId = ($query -> get_result() -> fetch_assoc())['MAX(id)'] + 1;
    $oldumask = umask(0);
    mkdir("../../../TripImages/{$nextId}", 0777);
    umask($oldumask);

    if(!isset($_POST['place']) || !isset($_POST['title']) || !isset($_POST['period-num']))
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo itinerario, non sono presenti alcuni parametri necessari. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }
    
    $user = $_SESSION['email'];

    $visitedPlaces = $_POST['place'];
    $title = $_POST['title'];
    $numPeriods = $_POST['period-num'];

    $imageCounter = 1;
    $description = "";

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

    $thumbnail = $_FILES["thumbnail"]['tmp_name'][0];
    move_uploaded_file($thumbnail, "../../../TripImages/{$nextId}/thumbnail");


    $keywords = " " . strtolower($title) . " ";
    $toRemove = array(" di ", " a ", " da ", " in ", " con ", " su ", " per ", " tra ", " fra ", " il ", " lo ", " la ", " i ", " gli ", " le ", " l' ", " un ", " un' ", " una ", " nel ", " nella ");
    $keywords = str_replace($toRemove, " ", $keywords);

    $query = $connection -> prepare("INSERT INTO trip (ID, TITLE, DESCRIPTION, VISITED_PLACES, USER, KEYWORDS) VALUES (?, ?, ?, ?, ?, ?)");
    $query -> bind_param("isssss", $nextId, $title, $description, $visitedPlaces, $user, $keywords);
    $error = $query -> execute();

    if(!$error)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo itinerario. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: errorPage.php?errorMessage=" . $errorMessage); 
    }
    else
    { header("Location: ../../tripViewer.php?tripID=" . $nextId); }

?>