function update()
{
    var start = $("#start").val();
    var end = $("#end").val();

    if(start == "" || end == "") //Se una delle due date è vuota
    {
        errorMessage = "Inserire il giorno di inizio e di fine periodo";
        if(document.getElementById("periodMessage") == null) //Se l'elemento non esiste lo creo
        { document.getElementById("period").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="periodMessage" style="height: fit-content; width: fit-content; font-size: 20px;"></div>'); }
        $("#periodMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"periodMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
        return false
    }

    if(start > end) //Se la data di inizio è successiva alla data di fine
    {
        errorMessage = "Attenzione il giorno di inizio deve essere successivo al giorno di fine";
        if(document.getElementById("periodMessage") == null) //Se l'elemento non esiste lo creo
        { document.getElementById("period").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="periodMessage" style="height: fit-content; width: fit-content; font-size: 20px;"></div>'); }
        $("#periodMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"periodMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
        return false;
    }

    $.post("../Logged/Utility/PHP/getProblems.php", //Effettuo la richiesta per ottenere le segnalazioni pubblicate nel periodo indicato
        
        {
            start: start,
            end: end
        },
        function(data, status)
        {
            if(status == "success") //Se la richiesta va a buon fine
            { 
                var errorMessage = "";

                if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento delle segnalazioni a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

                if(data == "select") //Se la risposta è la stringa "select" allora c'è stato un errore durante l'ottenimento della media dei voti
                { errorMessage = "Siamo spiacenti, si è verificato un errore durante l'ottenimento delle segnalazioni. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

                if(errorMessage != "")
                {
                    if(document.getElementById("errorMessage") == null)
                    { document.getElementById("period").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="errorMessage" style="width:90%; align-self:center;"></div>'); }
                    $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
                }
                else
                {
                    var problems = data; 
                    problems = problems.split("~(~~)~"); //Suddivido la stringa ottenuta
                    var allProblemsDiv = "";
                    for(var i=0; i<problems.length-4; i+=4) //Costruisco una div personalizzata per ogni segnalazione
                    {
                        var email = problems[i];
                        var subject = problems[i+1];
                        var text = problems[i+2];
                        var date = problems[i+3];

                        allProblemsDiv = allProblemsDiv +   '<div class="problem">' +
                                                                '<h1>Da: ' + email + '</h1>' +
                                                                '<h1>Il: ' + date  + '</h1>' +
                                                                '<h2>Oggetto: ' + subject + '</h2>' +
                                                                '<p>Problema: ' + text + '</p>' +
                                                            '</div>';
                    }
                    if(allProblemsDiv == "")
                    { allProblemsDiv = "<h1 class=\"text-center\">Nessuna segnalazione nel periodo indicato!</h1>"; }
                    $("#problemContainer").html(allProblemsDiv);
                }
            }
            else //Se la richiesta non va a buon fine
            {  
                var errorMessage = "Siamo spiacenti, si è verificato un errore durante delle segnalazioni. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
                if(document.getElementById("errorMessage") == null) //Effettuo la richiesta per ottenere le segnalazioni pubblicate nel periodo indicato
                { document.getElementById("period").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessage" style="height: fit-content; width: fit-content; font-size: 20px;"></div>'); }
                $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
        });
}

function setInvisible(id)
{ $("#" + id).remove(); } //Rimuovo l'elemento