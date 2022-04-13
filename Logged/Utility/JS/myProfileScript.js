function changePassword()
{
    document.getElementById("passwordElement").innerHTML =      '<label for="oldPassword">Password attuale:</label>' +
                                                                '<input type="password" name="oldPassword" id="oldPassword" maxlength="25">' +
                                                                '<label for="newPassword">Nuova password:</label>' +
                                                                '<input type="password" name="newPassword" id="newPassword" maxlength="25">' +
                                                                '<label for="newPasswordRepeated">Ripeti la nuova password:</label>' +
                                                                '<input type="password" name="newPasswordRepeated" id="newPasswordRepeated" maxlength="25">' +
                                                                '<button class="btn-change mt-3" onclick="return confirmPassword();" style="font-size: 20px">Conferma</button>';
}

function confirmPassword()
{
    var errorMessage = ""; //Errore da stampare

    if(document.getElementById("newPasswordRepeated").value != document.getElementById("newPassword").value) //Se le due password non coincidono
    { 
        document.getElementById("newPasswordRepeated").style.borderColor = "rgba(200, 37, 37, 0.9)";
        document.getElementById("newPassword").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Le password inserite non coincidono"; 
    }

    if(document.getElementById("newPasswordRepeated").value == "") //Se la password ripetuta è vuota
    { 
        document.getElementById("newPasswordRepeated").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Inserire la conferma della password"; 
    }

    if(!document.getElementById("newPassword").value.match(/^[a-zA-Z0-9_\-\$@#!]{5,25}$/)) //Se la password non rispetta la regex
    { 
        document.getElementById("newPassword").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "La password deve essere lunga dai 5 ai 25 caratteri, può contenere lettere, numeri, _ \- \$ @ # !"; 
    }

    if(document.getElementById("oldPassword").value == "") //Se la password è vuota
    { 
        document.getElementById("oldPassword").style.borderColor = "rgba(200, 37, 37, 0.9)";
        errorMessage = "Inserire la password attuale"; 
    }

    if(errorMessage != "") //Se c'è almeno un errore
    { 
        if(document.getElementById("errorMessagePassword") == null)
        { document.getElementById("passwordElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="visibility: hidden; height: fit-content"></div>'); }
        document.getElementById("errorMessagePassword").style.visibility = "visible"; //Rendo visibile la div che contiene l'errore
        document.getElementById("errorMessagePassword").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"; //Aggiungo l'HTML interno alla div
        return false;
    }

    var oldPassword = document.getElementById("oldPassword").value;
    var newPassword = document.getElementById("newPassword").value;

    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/updatePassword.php?oldPassword=" + oldPassword + "&newPassword=" + newPassword, true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della password. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("errorMessagePassword") == null)
            { document.getElementById("passwordElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="visibility: hidden; height: fit-content"></div>'); }
            document.getElementById("errorMessagePassword").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"; //Aggiungo l'HTML interno alla div
            document.getElementById("errorMessagePassword").style.visibility = "visible";
            return;
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        {
            var errorMessage = "";
            
            if(httpRequest.responseText == "password")
            { errorMessage = "Attenzione la password attuale inserita non è corretta"; }
            
            else if(httpRequest.responseText == "insert")
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della password. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
            
            else if(httpRequest.responseText == "select")
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante la verifica della password attuale. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(httpRequest.responseText != "ok")
            {
                if(document.getElementById("errorMessagePassword") == null)
                { document.getElementById("passwordElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="visibility: hidden; height: fit-content"></div>'); }
                document.getElementById("errorMessagePassword").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"; //Aggiungo l'HTML interno alla div
                document.getElementById("errorMessagePassword").style.visibility = "visible";
            }
            else
            {
                document.getElementById("passwordElement").innerHTML =      '<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"><strong class=\'mx-2\'>Il cambio della password è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button></div>' +
                                                                            '<div class="d-flex">' + 
                                                                            '<p class="profile-label">Password: ******** </p>' +
                                                                            '<button class="btn-change ms-3" onclick="changePassword()">Cambia password</button>' +
                                                                            '</div>';      
            }
        }
    }     

    return true;
}

function setInvisible(element)
{ document.getElementById(element).remove(); }