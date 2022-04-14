function changeGender(oldGender)
{
    $("#genderElement").html(   '<div class="d-flex">' +
                                    '<select id="genderSelect" name="genderSelect" class="form-select" style ="width: fit-content;" onchange="confirmGender();">' +
                                        '<option value = "N/S" selected>Seleziona il tuo genere</option>' +
                                        '<option value="Uomo">Uomo</option>' +
                                        '<option value="Donna">Donna</option>' +
                                        '<option value="Altro">Altro</option>' +
                                    '</select>' +
                                    '<button class="btn-change ms-2" onclick=\'return abortChangeGender("' + oldGender + '");\'>Annulla</button>' +
                                '</div>');
}

function confirmGender()
{
    var newGender = $("#genderSelect").val();

    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/updateGender.php?newGender=" + newGender, true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio del genere. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("errorMessageGender") == null)
            { document.getElementById("genderElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageGender" style="height: fit-content"></div>'); }
            $("#errorMessageGender").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageGender\")\'></button>"); //Aggiungo l'HTML interno alla div
            return ;
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        {
            var errorMessage = "";
            
            if(httpRequest.responseText == "update")
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio del genere. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
            
            if(httpRequest.responseText != "ok")
            {
                if(document.getElementById("errorMessageGender") == null)
                { document.getElementById("genderElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageGender" style="height: fit-content"></div>'); }
                $("#errorMessageGender").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageGender\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else
            {
               $("#genderElement").html(   '<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessageGender" style="height: fit-content"><strong class=\'mx-2\'>Il cambio del genere è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageGender\")\'></button></div>' +
                                            '<div class="d-flex">' +
                                            '<p class="profile-label">Genere: ' + newGender + '</p>' +
                                            '<button class="btn-change ms-3" onclick="changeGender()">Cambia</button>' +
                                            '</div>');      
            }
        }
    }
}

function abortChangeGender(oldGender)
{
    $("#genderElement").html(  '<p class="profile-label">Genere: ' + oldGender + ' <?php echo $gender; ?> </p>' +
                               '<button class="btn-change ms-3" onclick=\'changeGender("' + oldGender + '")\'>Cambia</button>');
}

function changePassword()
{
    $("#passwordElement").html( '<div style="display: flex; flex-direction: column; width: 100%"><label for="oldPassword">Password attuale:</label>' +
                                '<input type="password" name="oldPassword" id="oldPassword" maxlength="25">' +
                                '<label for="newPassword">Nuova password:</label>' +
                                '<input type="password" name="newPassword" id="newPassword" maxlength="25">' +
                                '<label for="newPasswordRepeated">Ripeti la nuova password:</label>' +
                                '<input type="password" name="newPasswordRepeated" id="newPasswordRepeated" maxlength="25">' +
                                '<div><button class="btn-change mt-3" onclick="return confirmPassword();">Conferma</button>' +
                                '<button class="btn-change mt-3 ms-2" onclick="return abortChangePassword();">Annulla</button></div></div>');
}

function abortChangePassword()
{
    $("#passwordElement").html( '<p class="profile-label">Password: ******** </p>' +
                                '<button class="btn-change ms-3" onclick="changePassword()">Cambia password</button>');
}

function confirmPassword()
{
    var errorMessage = ""; //Errore da stampare

    if($("#newPasswordRepeated").val() != $("#newPassword").val()) //Se le due password non coincidono
    { 
        $("#newPasswordRepeated").css("border-color", "rgba(200, 37, 37, 0.9)");
        $("#newPassword").css("border-color", "rgba(200, 37, 37, 0.9)");
        errorMessage = "Le password inserite non coincidono"; 
    }

    if($("#newPasswordRepeated").val() == "") //Se la password ripetuta è vuota
    { 
        $("#newPasswordRepeated").css("border-color", "rgba(200, 37, 37, 0.9)");
        errorMessage = "Inserire la conferma della password"; 
    }

    if(!$("#newPassword").val().match(/^[a-zA-Z0-9_\-\$@#!]{5,25}$/)) //Se la password non rispetta la regex
    { 
        $("#newPassword").css("border-color", "rgba(200, 37, 37, 0.9)");
        errorMessage = "La password deve essere lunga dai 5 ai 25 caratteri, può contenere lettere, numeri, _ \- \$ @ # !"; 
    }

    if($("#oldPassword").val() == "") //Se la password è vuota
    { 
        $("#oldPassword").css("border-color", "rgba(200, 37, 37, 0.9)");
        errorMessage = "Inserire la password attuale"; 
    }

    if(errorMessage != "") //Se c'è almeno un errore
    { 
        if(document.getElementById("errorMessagePassword") == null)
        { document.getElementById("passwordElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"></div>'); }
        $("#errorMessagePassword").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"); //Aggiungo l'HTML interno alla div
        return false;
    }

    var oldPassword = $("#oldPassword").val();
    var newPassword = $("#newPassword").val();

    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/updatePassword.php?oldPassword=" + oldPassword + "&newPassword=" + newPassword, true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della password. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("errorMessagePassword") == null)
            { document.getElementById("passwordElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"></div>'); }
            $("#errorMessagePassword").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"); //Aggiungo l'HTML interno alla div
            return ;
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
                { document.getElementById("passwordElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"></div>'); }
                $("#errorMessagePassword").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else
            {
               $("#passwordElement").html(  '<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"><strong class=\'mx-2\'>Il cambio della password è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button></div>' +
                                            '<p class="profile-label">Password: ******** </p>' +
                                            '<button class="btn-change ms-3" onclick="changePassword()">Cambia password</button>');      
            }
        }
    }     

    return true;
}

function changeCountry(oldCountry)
{
    $("#countryElement").html(  '<select class="form-select" style ="width: fit-content; heigth: fit-content;" name="newCountry" id="newCountry" onchange="confirmCountry()">' +
                                '<option value="N/S" selected>Seleziona la tua nazione</option>' +
                                '<option value="Italia">Italia</option>' +
                                '<option value="Germania">Germania</option>' +
                                '<option value="Francia">Francia</option>' +
                                '</select><button class="btn-change ms-2" onclick=\'return abortChangeCountry("' + oldCountry + '");\'>Annulla</button>');
}

function confirmCountry()
{
    var newCountry = $("#newCountry").val();

    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/updateCountry.php?newCountry=" + newCountry, true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della nazione. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("errorMessageCountry") == null)
            { document.getElementById("countryElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageCountry" style="height: fit-content"></div>'); }
            $("#errorMessageCountry").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageCountry\")\'></button>"); //Aggiungo l'HTML interno alla div
            return ;
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        {
            var errorMessage = "";
            
            if(httpRequest.responseText == "update")
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della nazione. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
            
            if(httpRequest.responseText != "ok")
            {
                if(document.getElementById("errorMessageCountry") == null)
                { document.getElementById("countryElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageCountry" style="height: fit-content"></div>'); }
                $("#errorMessageCountry").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageCountry\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else
            {
               $("#countryElement").html(   '<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessageCountry" style="height: fit-content"><strong class=\'mx-2\'>Il cambio della nazione è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageCountry\")\'></button></div>' +
                                            '<p class="profile-label">Nazione: ' + newCountry + '</p>' +
                                            '<button class="btn-change ms-3" onclick="changeCountry()">Cambia</button>');      
            }
        }
    }     

    return true;
}

function abortChangeCountry(oldCountry)
{
    $("#countryElement").html(  '<p class="profile-label">Nazione: ' + oldCountry + '</p>' +
                                '<button class="btn-change ms-3" onclick=\'changeCountry("' + oldCountry + '")\'>Cambia</button>');
}

function changeDate(oldDate)
{
    $("#dateElement").html(     '<input type="date" id="newDate" onchange="confirmDate();">' +
                                '<button class="btn-change ms-2" onclick=\'return abortChangeDate("' + oldDate + '");\'>Annulla</button>');
}

function confirmDate()
{
    var newDate = $("#newDate").val();

    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/updateDate.php?newDate=" + newDate, true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della data di nascita. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("errorMessageDate") == null)
            { document.getElementById("dateElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageDate" style="height: fit-content"></div>'); }
            $("#errorMessageDate").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageDate\")\'></button>"); //Aggiungo l'HTML interno alla div
            return ;
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        {
            var errorMessage = "";
            
            if(httpRequest.responseText == "update")
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della data di nascita. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
            
            if(httpRequest.responseText != "ok")
            {
                if(document.getElementById("errorMessageDate") == null)
                { document.getElementById("dateElement").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageDate" style="height: fit-content"></div>'); }
                $("#errorMessageDate").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageDate\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else
            {
               $("#dateElement").html(   '<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessageDate" style="height: fit-content"><strong class=\'mx-2\'>Il cambio della data di nascita è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageDate\")\'></button></div>' +
                                            '<p class="profile-label">Data di nascita: ' + newDate + '</p>' +
                                            '<button class="btn-change ms-3" onclick="changeDate()">Cambia</button>');      
            }
        }
    }     

    return true;
}

function abortChangeDate(oldDate)
{
    $("#dateElement").html( '<p class="profile-label">Data di nascita: ' + oldDate + '</p>' +
                            '<button class="btn-change ms-3" onclick=\'changeDate("' + oldDate + '")\'>Cambia</button>');
}

function setInvisible(element)
{ $("#" + element).remove(); }