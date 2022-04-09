function check()
{
    var errorMessage = ""; //Errore da stampare

    if(document.getElementById("password").value == "") //Se la password è vuota
    { 
        document.getElementById("password").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Inserire la della password"; 
    }

    if(document.getElementById("email").value == "") //Se l'email è vuota
    { 
        document.getElementById("email").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Inserire una email"; 
    }

    if(errorMessage != "") //Se c'è almeno un errore
    { 
        document.getElementById("generalContainerLogin").classList.add("error-login"); //Aggiungo la classe CSS error che permette di modificare la vh al general-container-login
        document.getElementById("errorMessage").style.visibility = "visible"; //Rendo visibile la div che contiene l'errore
        document.getElementById("errorMessage").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible()\'></button>"; //Aggiungo l'HTML interno alla div
        return false;
    }

    return true;
    
}