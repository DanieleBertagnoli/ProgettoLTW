function check()
{
    var errorMessage = ""; //Errore da stampare

    if($("#password").val() == "") //Se la password è vuota
    { 
        $("#password").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo password
        $("#password").css("border-width", "2px");
        errorMessage = "Inserire la password"; 
    }

    if($("#email").val() == "") //Se l'email è vuota
    { 
        $("#email").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo email
        $("#email").css("border-width", "2px");
        errorMessage = "Inserire una email"; 
    }

    if(errorMessage != "") //Se c'è almeno un errore
    { 
        if(document.getElementById("errorMessage") == null) //Se non esiste l'elemento lo creo
        { document.getElementById("form").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessage" style="height: fit-content"></div>'); }
        $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible()\'></button>"); //Aggiungo l'HTML interno alla div
        return false;
    }

    return true;   
}

function setInvisible()
{ document.getElementById("errorMessage").remove(); } //Elimino la div contenente il messaggio di errore