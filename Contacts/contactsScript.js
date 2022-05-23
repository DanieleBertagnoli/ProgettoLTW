function check()
{
	var errorMessage = ""; //Errore da stampare

    if($("#description").val() == "") //Se la descrizione è vuota
	{
        $("#description").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo descripion
        $("#description").css("border-width", "2px");
		errorMessage = "Inserire la descrizione del problema";
	}

	if($("#subject").val() == "") //Se l'oggetto del problema è vuoto
	{
        $("#subject").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo subject
        $("#subject").css("border-width", "2px");
		errorMessage = "Inserire l'oggetto del problema";
	}

    if($("#email").val() == "") //Se l'email del problema è vuota
	{
        $("#email").css("border-color", "rgba(200, 37, 37, 0.9)"); //Aggiungo i bordi rossi di 2px al campo email
        $("#email").css("border-width", "2px");
		errorMessage = "Inserire l'email";
	}

	if(errorMessage != "") //Se c'è almeno un errore
    { 
        if(document.getElementById("errorMessage") == null) //Se non esiste l'elemento lo creo
        { document.getElementById("form").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessage" style="height: fit-content"></div>'); }
        $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
        return false;
    }

    sendProblem($("#email").val(), $("#subject").val(), $("#description").val()); //Aggiungo il problema al database

    return true;
}

function sendProblem(email, subject, description)
{
    $.post("../Logged/Utility/PHP/insertNewMessage.php",
        
        {
            email: email, //Imposto i parametri della richiesta
            subject: subject,
            description: description,
        },
        
        function(data, status)
        {
            if(status != "success") //Se la richiesta non è andata buon fine
            {  
                var errorMessage = "Siamo spiacenti, si è verificato un errore durante delle segnalazioni. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
                if(document.getElementById("errorMessage") == null) //Se non esiste l'elemento lo creo
                { document.getElementById("form").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessage" style="height: fit-content; width: fit-content; font-size: 20px;"></div>'); }
                $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else //Se la richiesta va a buon fine
            {
                var errorMessage = "";
                
                if(data == "insert") //Se la risposta è la stringa "insert" allora c'è stato un errore durante l'insert
                { 
                    errorMessage = "Attenzione la password attuale inserita non è corretta"; 
                    if(document.getElementById("errorMessage") == null) //Se non esiste l'elemento lo creo
                    { document.getElementById("form").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessage" style="height: fit-content; width: fit-content; font-size: 20px;"></div>'); }
                    $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
                }
                else //Altrimenti creo il popup di successo
                { 
                    document.getElementById("form").insertAdjacentHTML("afterbegin",'<div class="alert alert-success d-flex align-items-end alert-dismissible" id="successMessage" style="height: fit-content"><strong class=\'mx-2\'>La segnalazione è stata inviata con successo!</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"successMessage\")\'></button></div>'); 
                    $("#email").val("");
                    $("#subject").val("");   
                    $("#description").val(""); //Azzero i valori dei campi                 
                }
            }
        });
}

function setInvisible(id)
{ $("#" + id).remove(); } //Elimino la div contenente il messaggio di errore