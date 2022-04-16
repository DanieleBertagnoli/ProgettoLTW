<?php

    //Distruggi la sessione e torna alla pagina iniziale.
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../../../index.html");
    exit();
?>