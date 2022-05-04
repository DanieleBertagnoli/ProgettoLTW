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
                                '</div>'); //Sostitusco l'elemento presente con un menù per la scelta del nuovo gender
}

function confirmGender()
{
    var newGender = $("#genderSelect").val(); //Ottengo il nuovo gender

    $.post("Utility/PHP/updateGender.php",
        {
            newGender: newGender //Imposto i parametri della richiesta
        },
        function(data, status)
        {
            if(status != "success") //Se la richiesta non va a buon fine
            {  
                var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio del genere. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
                if(document.getElementById("errorMessageGender") == null) //Se non esiste l'elemento lo creo
                { document.getElementById("genderElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageGender" style="height: fit-content"></div>'); }
                $("#errorMessageGender").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageGender\")\'></button>"); //Aggiungo l'HTML interno alla div
                return ;
            }
            else //Se la richiesta va a buon fine
            {
                var errorMessage = "";
                
                if(data == "update") //Se la risposta è la stringa "update" allora c'è stato un errore durante l'update nel database
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio del genere a causa del mancato aggiornamento del database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
                
                if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio del genere a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

                if(data != "ok") //Se la risposta non è stata la stringa ok, creo il popup di errore
                {
                    if(document.getElementById("errorMessageGender") == null) //Se non esiste l'elemento lo creo
                    { document.getElementById("genderElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageGender" style="height: fit-content"></div>'); }
                    $("#errorMessageGender").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageGender\")\'></button>"); //Aggiungo l'HTML interno alla div
                }
                else //Altrimenti creo il popup di successo
                {
                    $("#genderElement").html(   '<p class="profile-label">Genere: ' + newGender + '</p>' +
                                                '<button class="btn-change ms-3" onclick="changeGender()">Cambia</button>');  
                    
                    document.getElementById("genderElement").insertAdjacentHTML("beforebegin",'<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessageGender" style="height: fit-content"><strong class=\'mx-2\'>Il cambio del genere è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageGender\")\'></button></div>');
                }
            }
        });
}

function abortChangeGender(oldGender)
{
    $("#genderElement").html(  '<p class="profile-label">Genere: ' + oldGender + ' <?php echo $gender; ?> </p>' +
                               '<button class="btn-change ms-3" onclick=\'changeGender("' + oldGender + '")\'>Cambia</button>'); //Ripristino l'elemento precedente con il gender precedente
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
                                '<button class="btn-change mt-3 ms-2" onclick="return abortChangePassword();">Annulla</button></div></div>'); //Sostitusco l'elemento presente con un form per il cambio della password
}

function abortChangePassword()
{
    $("#passwordElement").html( '<p class="profile-label">Password: ******** </p>' +
                                '<button class="btn-change ms-3" onclick="changePassword()">Cambia password</button>'); //Ripristino l'elemento precedente
}

function confirmPassword()
{
    var errorMessage = ""; //Errore da stampare

    if($("#newPasswordRepeated").val() != $("#newPassword").val()) //Se le due password non coincidono
    { 
        $("#newPasswordRepeated").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di al campo newPasswordRepeated
        $("#newPassword").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di al campo newPassword
        errorMessage = "Le password inserite non coincidono"; 
    }

    if($("#newPasswordRepeated").val() == "") //Se la password ripetuta è vuota
    { 
        $("#newPasswordRepeated").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di al campo newPasswordRepeated
        errorMessage = "Inserire la conferma della password"; 
    }

    if(!$("#newPassword").val().match(/^[a-zA-Z0-9_\-\$@#!]{5,25}$/)) //Se la password non rispetta la regex
    { 
        $("#newPassword").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di al campo newPassword
        errorMessage = "La password deve essere lunga dai 5 ai 25 caratteri, può contenere lettere, numeri, _ \- \$ @ # !"; 
    }

    if($("#oldPassword").val() == "") //Se la password è vuota
    { 
        $("#oldPassword").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di al campo oldPassword
        errorMessage = "Inserire la password attuale"; 
    }

    if(errorMessage != "") //Se c'è almeno un errore
    { 
        if(document.getElementById("errorMessagePassword") == null) //Se non esiste l'elemento lo creo
        { document.getElementById("passwordElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"></div>'); }
        $("#errorMessagePassword").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"); //Aggiungo l'HTML interno alla div
        return false;
    }

    var oldPassword = $("#oldPassword").val(); //Salvo la vecchia password
    var newPassword = $("#newPassword").val(); //Salvo la nuova password

    $.post("Utility/PHP/updatePassword.php",
        {
            oldPassword: oldPassword, //Imposto i parametri della richiesta
            newPassword: newPassword
        },
        function(data, status)
        {
            if(status != "success") //Se la richiesta non va a buon fine
            {  
                var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della password. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
                if(document.getElementById("errorMessagePassword") == null) //Se non esiste l'elemento lo creo
                { document.getElementById("passwordElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"></div>'); }
                $("#errorMessagePassword").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"); //Aggiungo l'HTML interno alla div
                return ;
            }
            else //Se la richiesta va a buon fine
            {
                var errorMessage = "";
                
                if(data == "password") //Se la risposta è la stringa "password" allora la vecchia password non è corretta
                { errorMessage = "Attenzione la password attuale inserita non è corretta"; }
                
                else if(data == "update") //Se la risposta è la stringa "insert" allora c'è stato un errore durante l'update
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della password. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
                
                else if(data == "select") //Se la risposta è la stringa "select" allora c'è stato un errore durante la verifica della password
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante la verifica della password attuale. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

                if(data != "ok") //Se la risposta non è stata la stringa ok, creo il popup di errore
                {
                    if(document.getElementById("errorMessagePassword") == null)
                    { document.getElementById("passwordElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"></div>'); }
                    $("#errorMessagePassword").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button>"); //Aggiungo l'HTML interno alla div
                }
                else //Altrimenti creo il popup di successo
                {
                    $("#passwordElement").html( '<p class="profile-label">Password: ******** </p>' +
                                                '<button class="btn-change ms-3" onclick="changePassword()">Cambia password</button>');      
                    
                    document.getElementById("passwordElement").insertAdjacentHTML("beforebegin",'<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessagePassword" style="height: fit-content"><strong class=\'mx-2\'>Il cambio della password è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePassword\")\'></button></div>');
                }
            }
        });

    return true;
}

function changeCountry(oldCountry)
{
    $("#countryElement").html(  '<select class="form-select" style ="width: fit-content; heigth: fit-content;" name="newCountry" id="newCountry" onchange="confirmCountry()">' +
                                '<option value="N/S" selected>Seleziona la tua nazione</option>' +
                                '<option value="Italia">Italia</option>' +
                                '<option value="Germania">Germania</option>' +
                                '<option value="Francia">Francia</option>' +
                                '</select><button class="btn-change ms-2" onclick=\'return abortChangeCountry("' + oldCountry + '");\'>Annulla</button>'); //Sostitusco l'elemento presente con un menù per la selezione del nuova nazione
}

function confirmCountry()
{
    var newCountry = $("#newCountry").val();

    $.post("Utility/PHP/updateCountry.php",
        {
            newCountry: newCountry //Imposto i parametri della richiesta
        },
        function(data, status)
        {
            if(status != "success") //Se la richiesta non va a buon fine
            {  
                var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della nazione. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
                if(document.getElementById("errorMessageCountry") == null) //Se non esiste l'elemento lo creo
                { document.getElementById("countryElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageCountry" style="height: fit-content"></div>'); }
                $("#errorMessageCountry").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageCountry\")\'></button>"); //Aggiungo l'HTML interno alla div
                return ;
            }
            else //Se la richiesta va a buon fine
            {
                var errorMessage = "";
                
                if(data == "update") //Se la risposta è la stringa "update" allora c'è stato un errore durante l'update
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della nazione a causa del mancato aggiornamento del database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
                
                if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della nazione a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

                if(data != "ok") //Se la risposta non è stata la stringa ok, creo il popup di errore
                {
                    if(document.getElementById("errorMessageCountry") == null) //Se non esiste l'elemento lo creo
                    { document.getElementById("countryElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageCountry" style="height: fit-content"></div>'); }
                    $("#errorMessageCountry").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageCountry\")\'></button>"); //Aggiungo l'HTML interno alla div
                }
                else //Altrimenti creo il popup di successo
                {
                    $("#countryElement").html(  '<p class="profile-label">Nazione: ' + newCountry + '</p>' +
                                                '<button class="btn-change ms-3" onclick="changeCountry()">Cambia</button>');
                    
                    document.getElementById("countryElement").insertAdjacentHTML("beforebegin",'<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessageCountry" style="height: fit-content"><strong class=\'mx-2\'>Il cambio della nazione è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageCountry\")\'></button></div>');  
                }
            }
        });

    return true;
}

function abortChangeCountry(oldCountry)
{
    $("#countryElement").html(  '<p class="profile-label">Nazione: ' + oldCountry + '</p>' +
                                '<button class="btn-change ms-3" onclick=\'changeCountry("' + oldCountry + '")\'>Cambia</button>');  //Ripristino l'elemento precedente
}

function changeDate(oldDate)
{
    $("#dateElement").html(     '<input type="date" id="newDate" onchange="confirmDate();">' +
                                '<button class="btn-change ms-2" onclick=\'return abortChangeDate("' + oldDate + '");\'>Annulla</button>');  //Sostituisco l'elemento attuale con un form per il cambiamento della data
}

function confirmDate()
{
    var newDate = $("#newDate").val();

    $.post("Utility/PHP/updateDate.php",
        {
            newDate: newDate //Imposto i parametri della richiesta
        },
        function(data, status)
        {
            if(status != "success") //Se la richiesta non va a buon fine
            {  
                var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della data di nascita. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
                if(document.getElementById("errorMessageDate") == null)
                { document.getElementById("dateElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageDate" style="height: fit-content"></div>'); }
                $("#errorMessageDate").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageDate\")\'></button>"); //Aggiungo l'HTML interno alla div
                return ;
            }
            else //Se la richiesta va a buon fine
            {
                var errorMessage = "";
                
                if(data == "update") //Se la risposta è la stringa "update" allora c'è stato un errore durante l'update
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della data di nascita a causa del mancato aggiornamento del database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
                
                if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della data di nascita a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

                if(data != "ok") //Se la risposta non è stata la stringa ok, creo il popup di errore
                {
                    if(document.getElementById("errorMessageDate") == null)
                    { document.getElementById("dateElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessageDate" style="height: fit-content"></div>'); }
                    $("#errorMessageDate").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageDate\")\'></button>"); //Aggiungo l'HTML interno alla div
                }
                else //Altrimenti creo il popup di successo
                {
                    $("#dateElement").html( '<p class="profile-label">Data di nascita: ' + newDate + '</p>' +
                                            '<button class="btn-change ms-3" onclick="changeDate()">Cambia</button>');     

                    document.getElementById("dateElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessageDate" style="height: fit-content"><strong class=\'mx-2\'>Il cambio della data di nascita è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessageDate\")\'></button></div>');
                }
            }
        });

    return true;
}

function abortChangeDate(oldDate)
{
    $("#dateElement").html( '<p class="profile-label">Data di nascita: ' + oldDate + '</p>' +
                            '<button class="btn-change ms-3" onclick=\'changeDate("' + oldDate + '")\'>Cambia</button>');  //Ripristino l'elemento precedente
}

function changePrivacy(oldPrivacy)
{
    $("#privacyElement").html(  '<select class="form-select" style ="width: fit-content; heigth: fit-content;" name="newPrivacy" id="newPrivacy" onchange="confirmPrivacy()">' +
                                '<option value="N/S" selected>Seleziona la tua privacy</option>' +                            
                                '<option value="Pubblico">Pubblico</option>' +
                                '<option value="Privato">Privato</option>' +
                                '</select><button class="btn-change ms-2" onclick=\'return abortChangePrivacy("' + oldPrivacy + '");\'>Annulla</button>'); //Sostitusco l'elemento presente con un menù per la selezione della nuova privacy
}

function confirmPrivacy()
{
    var newPrivacy = $("#newPrivacy").val();

    $.post("Utility/PHP/updatePrivacy.php",
        {
            newPrivacy: newPrivacy //Imposto i parametri della richiesta
        },
        function(data, status)
        {
            if(status != "success") //Se la richiesta non va a buon fine
            {  
                var errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della privacy. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
                if(document.getElementById("errorMessagePrivacy") == null) //Se non esiste l'elemento lo creo
                { document.getElementById("privacyElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePrivacy" style="height: fit-content"></div>'); }
                $("#errorMessagePrivacy").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePrivacy\")\'></button>"); //Aggiungo l'HTML interno alla div
                return ;
            }
            else //Se la richiesta va a buon fine
            {
                var errorMessage = "";
                
                if(data == "update") //Se la risposta è la stringa "update" allora c'è stato un errore durante l'update
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della privacy a causa del mancato aggiornamento del database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }
                
                if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il cambio della privacy a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

                if(data != "ok") //Se la risposta non è stata la stringa ok, creo il popup di errore
                {
                    if(document.getElementById("errorMessagePrivacy") == null) //Se non esiste l'elemento lo creo
                    { document.getElementById("privacyElement").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessagePrivacy" style="height: fit-content"></div>'); }
                    $("#errorMessagePrivacy").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePrivacy\")\'></button>"); //Aggiungo l'HTML interno alla div
                }
                else //Altrimenti creo il popup di successo
                {
                    $("#privacyElement").html(  '<p class="profile-label">Visibiltà: ' + newPrivacy + '</p>' +
                                                '<button class="btn-change ms-3" onclick="changePrivacy()">Cambia</button>');
                    
                    document.getElementById("privacyElement").insertAdjacentHTML("beforebegin",'<div class="alert alert-success d-flex align-items-end alert-dismissible" id="errorMessagePrivacy" style="height: fit-content"><strong class=\'mx-2\'>Il cambio della privacy è stato effettuato con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessagePrivacy\")\'></button></div>');  
                }
            }
        });

    return true;
}

function abortChangePrivacy(oldPrivacy)
{
    $("#privacyElement").html(  '<p class="profile-label">Visibilità: ' + oldPrivacy + '</p>' +
                                '<button class="btn-change ms-3" onclick=\'changePrivacy("' + oldPrivacy + '")\'>Cambia</button>');  //Ripristino l'elemento precedente
}

function setInvisible(element)
{ $("#" + element).remove(); } //Rimuovo l'elemento