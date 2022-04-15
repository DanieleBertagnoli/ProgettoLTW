function update()
{
    var start = $("#start").val();
    var end = $("#end").val();

    if(start == "" || end == "")
    {
        errorMessage = "Inserire il giorno di inizio e di fine periodo";
        if(document.getElementById("periodMessage") == null)
        { document.getElementById("period").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="periodMessage" style="height: fit-content; width: fit-content; font-size: 20px;"></div>'); }
        $("#periodMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"periodMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
        return false
    }

    if(start > end)
    {
        errorMessage = "Attenzione il giorno di inizio deve essere successivo al giorno di fine";
        if(document.getElementById("periodMessage") == null)
        { document.getElementById("period").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="periodMessage" style="height: fit-content; width: fit-content; font-size: 20px;"></div>'); }
        $("#periodMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"periodMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
        return false;
    }

    $.post("../Logged/Utility/PHP/getProblems.php",
        
        {
            start: start,
            end: end
        },
        
        function(data, status)
        {
            if(status == "success")
            { 
                var problems = data; 
                problems = problems.split("~(~~)~");
                var allProblemsDiv = "";
                for(var i=0; i<problems.length-4; i+=4)
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
            else
            {  
                var errorMessage = "Siamo spiacenti, si è verificato un errore durante delle segnalazioni. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
                if(document.getElementById("errorMessage") == null)
                { document.getElementById("form").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessage" style="height: fit-content; width: fit-content; font-size: 20px;"></div>'); }
                $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"errorMessage\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
        });
}

function setInvisible(id)
{ $("#" + id).remove(); }