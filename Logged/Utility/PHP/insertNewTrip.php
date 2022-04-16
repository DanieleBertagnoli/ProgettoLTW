 <?php

    require "initConnection.php";
    $connection = initConnection(); //Inizializza connessione al database

    $query = $connection -> prepare("SELECT MAX(id) from trip"); //Seleziono l'ID piu grande all'interno della tabella per usarlo come riferimento di quale ID dovra' utilizzare il prossimo trip.
    $success = $query -> execute(); 

    if(!$connection)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante la connessione al DB. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    if(!$success)
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo itinerario. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }

    $nextId = ($query -> get_result() -> fetch_assoc())['MAX(id)'] + 1; //MaxID + 1 sara' il prossimo ID.
    $oldumask = umask(0);
    mkdir("../../../TripImages/{$nextId}", 0777); //Creiamo la cartella dove immagazzinare tutto e si impostano i permessi giusti.
    umask($oldumask);

    if(!isset($_POST['place']) || !isset($_POST['title']) || !isset($_POST['periodNum']) || !isset($_POST['tagPicker']))
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo itinerario, non sono presenti alcuni parametri necessari. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); 
        exit();
    }
    
    $user = $_SESSION['email'];

    $visitedPlaces = $_POST['place']; //Estrapolo i posti visitati e li separo in base alla virgola.
    $visitedPlaces = explode(",",$visitedPlaces);
    $newVisitedPlaces = ",";

    for($i=0;$i<count($visitedPlaces); $i++)
    { 
        $visitedPlaces[$i] = trim($visitedPlaces[$i]); 
        $newVisitedPlaces = $newVisitedPlaces . $visitedPlaces[$i] . ",";
    }

    $title = $_POST['title'];
    $numPeriods = $_POST['periodNum'];

    $imageCounter = 1;
    $description = "";

    //Per ogni periodo nel viaggio.
    for($i = 1; $i <= $numPeriods; $i++)
    {
        //Se non abbiamo l'argomento start continua, questo perche' gli ID dei periodi possono non essere lineari (creazione e cancellazione di periodi) dunque gli ID "vuoti" vengono saltati.
        if(!isset($_POST["start{$i}"]))
        { continue; }
        
        $start = $_POST["start{$i}"];
        $end = $_POST["end{$i}"];
        $localDescription = $_POST["description{$i}"];

        $description = $description . "~(~~)~Dal " . $start . " al " . $end. ": " . $localDescription . "\n";
        
       //Prendo tutti gli elementi e mi preparo a caricarli nel DB. Inoltre metto una sequenza di caratteri speciali per separare le descrizioni dei vari periodi. 

        $fileCount = 0;
        if(isset($_FILES["images{$i}"]['name']))
        {
            //Creo le cartelle per le immagini, separate in base ai periodi.
            $oldumask2 = umask(0);
            mkdir("../../../TripImages/{$nextId}/period-{$i}", 0777);
            umask($oldumask2);

            $fileCount = count($_FILES["images{$i}"]['name']);
        }

        for($j = 0; $j < $fileCount; $j++)
        {
            //Una volta creata la cartella spostiamo dentro le immagini.
            $fileTmpName = $_FILES["images{$i}"]['tmp_name'][$j];

            $fileNameNew = $nextId . "-" . $imageCounter;
            move_uploaded_file($fileTmpName, "../../../TripImages/{$nextId}/period-{$i}/{$fileNameNew}");
            $imageCounter = $imageCounter + 1;
        }
    }

    $thumbnail = $_FILES["thumbnail"]['tmp_name']; 
    move_uploaded_file($thumbnail, "../../../TripImages/{$nextId}/thumbnail"); //Sposto l'immagine di copertina nella directory dell'itinerario

    $keywords = " " . strtolower($title) . " "; //Inserisco il titolo all'interno delle keywords
    $toRemove = array(" di ", " a ", " da ", " in ", " con ", " su ", " per ", " tra ", " fra ", " il ", " lo ", " la ", " i ", " gli ", " le ", " l' ", " un ", " un' ", " una ", " nel ", " nella ");
    $keywords = str_replace($toRemove, " ", $keywords); //Rimuovo le parole dell'array toRemove per creare una stringa di sole parole significative

    $tags = $_POST['tagPicker']; 
    for($i=0; $i<count($tags); $i++) //Inserisco i tag scelti all'interno delle keywords
    { $keywords = $keywords . " " . $tags[$i] . " "; }

    $query = $connection -> prepare("INSERT INTO trip (ID, TITLE, DESCRIPTION, VISITED_PLACES, USER, KEYWORDS) VALUES (?, ?, ?, ?, ?, ?)"); //Inserisco il nuovo itinerario
    $query -> bind_param("isssss", $nextId, $title, $description, $newVisitedPlaces, $user, $keywords);
    $success = $query -> execute();

    if(!$success) //Se la query non va a buon fine
    { 
        $errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo itinerario. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
        header("Location: ../../errorPage.php?errorMessage=" . $errorMessage); //Redirect alla pagina di errore
        exit(); 
    }
    else
    { 
        header("Location: ../../tripViewer.php?tripID=" . $nextId); //Redirect alla pagina dell'itinerario appena creato
        exit();
    }

?>