function check()
{
    var errorMessage = ""; //Errore da stampare

    if(document.getElementById("passwordRepeated").value != document.getElementById("password").value) //Se le due password non coincidono
    { 
        document.getElementById("passwordRepeated").style.borderColor = "rgba(200, 37, 37, 0.9)";
        document.getElementById("password").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Le password inserite non coincidono"; 
    }

    if(!document.getElementById("password").value.match(/^[a-zA-Z0-9_\-\$@#!]{5,20}$/)) //Se la password non rispetta la regex
    { errorMessage = "La password deve essere lunga dai 5 ai 20 caratteri, può contenere lettere, numeri, _ \- \$ @ # !"; }

    if(document.getElementById("passwordRepeated").value == "") //Se la password ripetuta è vuota
    { 
        document.getElementById("passwordRepeated").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Inserire la conferma della password"; 
    }

    if(document.getElementById("username").value == "") //Se l'username è vuoto
    { 
        document.getElementById("username").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Inserire un username"; 
    }

    if(document.getElementById("email").value == "") //Se l'email è vuota
    { 
        document.getElementById("email").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Inserire una email"; 
    }

    if(errorMessage != "") //Se c'è almeno un errore
    { 
        document.getElementById("generalContainerSignup").classList.add("error"); //Aggiungo la classe CSS error che permette di modificare la vh al general-container-signup
        document.getElementById("errorMessage").style.visibility = "visible"; //Rendo visibile la div che contiene l'errore
        document.getElementById("errorMessage").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible()\'></button>"; //Aggiungo l'HTML interno alla div
        return false;
    }

    return true;
    
}