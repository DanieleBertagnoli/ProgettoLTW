function setInvisible()
{ document.getElementById("errorMessage").remove(); } //Elimino la div contenente il messaggio di errore

function check()
{
    var errorMessage = ""; //Errore da stampare

    if($("#passwordRepeated").val() != $("#password").val()) //Se le due password non coincidono
    { 
        $("#passwordRepeated").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo passwordRepeated
        $("#passwordRepeated").css("border-width", "2px");
        $("#password").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo password
        $("#password").css("border-width", "2px");
        errorMessage = "Le password inserite non coincidono"; 
    }

    if(!$("#password").val().match(/^[a-zA-Z0-9_\-\$@#!]{5,25}$/)) //Se la password non rispetta la regex
    { 
        $("#password").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo password
        $("#password").css("border-width", "2px");
        errorMessage = "La password deve essere lunga dai 5 ai 25 caratteri, può contenere lettere, numeri, _ \- \$ @ # !"; 
    }

    if($("#passwordRepeated").val() == "") //Se la password ripetuta è vuota
    { 
        $("#passwordRepeated").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo passwordRepeated
        $("#passwordRepeated").css("border-width", "2px"); 
        errorMessage = "Inserire la conferma della password"; 
    }

    if(!$("#username").val().match(/^[a-zA-Z0-9_\-]{4,20}$/)) //Se lo username non rispetta la regex
    {
        $("#username").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo username
        $("#username").css("border-width", "2px");
        errorMessage = "Lo username deve essere lungo dai 4 ai 20 caratteri, può contenere lettere, numeri, _ \-"; 
    }

    if(document.getElementById("email").value == "") //Se l'email è vuota
    { 
        $("#email").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo email
        $("#email").css("border-width", "2px");
        errorMessage = "Inserire una email"; 
    }

    if(errorMessage != "") //Se c'è almeno un errore
    { 
        if(document.getElementById("errorMessage") == null) //Se non esiste l'elemento lo creo
        { document.getElementById("form").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessage"></div>'); }
        $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible()\'></button>"); //Aggiungo l'HTML interno alla div
        return false;
    }

    return true;
}
