function changeStarLevel(lvl)
{
    for(var i=1; i<=5; i++) //Per ogni stella 
    { 
        if(i <= lvl) //Se il livello inferiore è a quella selezionata
        { $("#star" + i).html("&#9733"); } //La stella diventa piena
        else
        { $("#star" + i).html("&#9734"); } //Altrimenti la stella diventa vuota
    }
}

function sendVote(vote, tripID)
{
    $.post("Utility/PHP/insertVote.php", 
    { 
        tripID: tripID, 
        vote: vote
    }, function(data, status)
    {
        if(status != "success")
        {
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo voto. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("voteError") == null)
            { document.getElementById("starSection").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="voteError" style="width:90%; align-self:center;"></div>'); }
            $("#voteError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"voteError\")\'></button>"); //Aggiungo l'HTML interno alla div
        }
        else
        {
            var errorMessage = "";

            if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo voto a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(data == "select") //Se la risposta è la stringa "select" allora c'è stato un errore durante l'ottenimento della media dei voti
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante la verifica del voto. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(data == "update") //Se la risposta è la stringa "select" allora c'è stato un errore durante l'ottenimento della media dei voti
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante l'aggiornamento del voto dell'itinerario. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(data == "insert") //Se la risposta è la stringa "select" allora c'è stato un errore durante l'ottenimento della media dei voti
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante l'inserimento del voto dell'itinerario. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(errorMessage != "")
            {
                if(document.getElementById("voteError") == null)
                { document.getElementById("starSection").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="voteError" style="width:90%; align-self:center;"></div>'); }
                $("#voteError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"voteError\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else
            { changeStarLevel(vote); }
        }
    });

    refreshVoteAvg(tripID);
}

function refreshVoteAvg(tripID)
{
    $.post("Utility/PHP/getVoteAvg.php", { tripID: tripID }, function(data, status)
    {
        if(status != "success")
        {
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento dei voti. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("voteError") == null)
            { document.getElementById("starSection").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="voteError" style="width:90%; align-self:center;"></div>'); }
            $("#voteError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"voteError\")\'></button>"); //Aggiungo l'HTML interno alla div
        }
        else
        {
            var errorMessage = "";

            if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante l'ottenimento della media dei voti dell'itinerario a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(data == "select") //Se la risposta è la stringa "select" allora c'è stato un errore durante l'ottenimento della media dei voti
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante l'ottenimento della media dei voti dell'itinerario. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(errorMessage != "")
            {
                if(document.getElementById("voteError") == null)
                { document.getElementById("starSection").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="voteError" style="width:90%; align-self:center;"></div>'); }
                $("#voteError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"voteError\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else
            { 
                var newAvg = data;
                $("#voteAvg").html("Gli altri utenti hanno votato: " + (Math.round(newAvg * 100) / 100).toString() + "/5"); //Aggiorno l'etichetta della media dei voti
            }
        }
    });
}

function checkButton()
{
    var text = $("#commentText").val();
    if(text.replace(/\s/g,"") == "") //Se il testo del commento è composto da soli spazi
    { $("#commentButton").addClass("btn-disabled"); } //Lo disabilito
    else
    { $("#commentButton").removeClass("btn-disabled"); } //Altrimenti lo abilito
}

function sendComment(tripID)
{  
    $.post("Utility/PHP/insertNewComment.php", 
    { 
        tripID: tripID, 
        commentText: $("#commentText").val().trim() 
    }, function(data, status)
    {
        if(status != "success")
        {
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo commento. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("commentError") == null)
            { document.getElementById("commentsContainer").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="commentError" style="width:90%; align-self:center;"></div>'); }
            $("#commentError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"commentError\")\'></button>"); //Aggiungo l'HTML interno alla div
        }
        else
        {
            var errorMessage = "";

            if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo commento a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(data == "insert") //Se la risposta è la stringa "insert" allora c'è stato un errore durante l'insertimento del nuovo commento
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo commento. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(errorMessage != "")
            {
                if(document.getElementById("commentError") == null)
                { document.getElementById("commentsContainer").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="commentError" style="width:90%; align-self:center;"></div>'); }
                $("#commentError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"commentError\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else
            { reloadComments(tripID); } //Ricarico i commenti
        }
    });

    $("#commentText").val(""); //Rimuovo il testo dalla casella del commento
    $("#commentButton").addClass("btn-disabled"); //Disabilito il bottone di invio

    return true;
}

function reloadComments(tripID)
{
    $.post("Utility/PHP/getComments.php", { tripID: tripID }, function(data, status)
    {
        if(status != "success")
        {
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento dei commenti. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            if(document.getElementById("commentError") == null)
            { document.getElementById("commentsContainer").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="commentError" style="width:90%; align-self:center;"></div>'); }
            $("#commentError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"commentError\")\'></button>"); //Aggiungo l'HTML interno alla div
        }
        else
        {
            var errorMessage = "";

            if(data == "parametri") //Se la risposta è la stringa "parametri" allora c'è stato un errore con i parametri passati
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante l'aggiornamento dei commenti dell'itinerario a causa di alcuni parametri mancanti o di mancata connessione al database. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(data == "select") //Se la risposta è la stringa "select" allora c'è stato un errore durante l'ottenimento dei commenti
            { errorMessage = "Siamo spiacenti, si è verificato un errore durante l'ottenimento dei commenti dell'itinerario. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti."; }

            if(errorMessage != "")
            {
                if(document.getElementById("commentError") == null)
                { document.getElementById("commentsContainer").insertAdjacentHTML("beforebegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible mb-5" id="commentError" style="width:90%; align-self:center;"></div>'); }
                $("#commentError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"commentError\")\'></button>"); //Aggiungo l'HTML interno alla div
            }
            else
            { $("#oldComments").html(data); } //Imposto i nuovi commenti 
        }
    });
}

function openPopup(index)
{
    //Prendo i vari eleementi che compongono il popup e la cella del carosello in base al suo id.
    var popup = document.getElementById('myPopup');
    var popupImage = document.getElementById('bigImage');
    var cell = document.getElementById('carouselCell-' + index);

    //Prendo il path all'immagine estrapolandola dalla proprieta' background e filtrando gli attributi che non mi servono, lasciando solo il percorso.
    
    var parts = cell.style.background.split(" ");
    var sourceImage = parts[4].replace('url("', '').replace('")', '');
    
    //Metto come src dell'immagine popup lo stesso source dell'immagine nella cella del caroselllo.
    
    popupImage.src = sourceImage;
    popup.style.display = "block";
    document.body.style.overflow = 'hidden';
}

function closePopup()
{
    document.getElementById('myPopup').style.display = 'none';
    document.body.style.overflow = 'scroll';
}

function setInvisible(element)
{ $("#" + element).remove(); }