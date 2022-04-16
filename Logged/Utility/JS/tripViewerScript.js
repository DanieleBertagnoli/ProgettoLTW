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
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/insertVote.php?tripID=" + tripID + "&vote=" + vote, true); //Invio il voto
    httpRequest.send(null);
    httpRequest.onreadystatechange = () => 
    { 
        if(httpRequest.readyState == 4 && httpRequest.status == 500) //Se la richiesta non va a buon fine
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo voto. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            $("#voteError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"voteError\")\'></button>"); //Aggiungo l'HTML interno alla div
            $("#voteError").css("visibilty", "visible");
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 200) //Altrimenti cambio il colore delle stelle
        { changeStarLevel(vote); }
    }
    refreshVoteAvg(tripID);
}

function refreshVoteAvg(tripID)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/getVoteAvg.php?tripID=" + tripID, true); //Richiedo la media dei voti del trip
    httpRequest.send(null);
    httpRequest.onreadystatechange = () => 
    { 
        if(httpRequest.readyState == 4 && httpRequest.status == 200) //Se la richeista va a buon fine 
        {
            var newAvg = httpRequest.responseText; 
            $("#voteAvg").html("Gli altri utenti hanno votato: " + newAvg + "/5"); //Aggiorno l'etichetta della media dei voti
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 500) //Se la richiesta non va a buon fine 
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento dei voti. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            $("#voteError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"voteError\")\'></button>"); //Aggiungo l'HTML interno alla div
            $("#voteError").css("visibility", "visible");
        }
    }
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
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/insertNewComment.php?tripID=" + tripID + "&commentText=" + $("#commentText").val().trim(), true); //Invio il nuovo commento
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 200) //Se la richiesta va a buon fine
        { reloadComments(tripID); } //Aggiorno i commenti

        if(httpRequest.readyState == 4 && httpRequest.status == 500) //Se la richiesta non va a buon fine
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo commento. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            $("#commentError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"commentError\")\'></button>"); //Aggiungo l'HTML interno alla div
            $("#commentError").css("visibility", "visible");
        }
    }

    $("#commentText").val(""); //Rimuovo il testo dalla casella del commento
    $("#commentButton").addClass("btn-disabled"); //Disabilito il bottone di invio

    return true;
}

function reloadComments(tripID)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/getComments.php?tripID=" + tripID, true); //Richiedo i commenti dell'itinerario
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 200) //Se la richiesta va a buon fine
        { 
            var comments = httpRequest.responseText; 
            comments = comments.split("~(~~)~"); //Suddivido la stringa ottenuta
            var allCommentsDiv = "";
            for(var i=comments.length-5; i>=0; i-=4) //Per ogni commento creo una div personalizzata
            {
                var email = comments[i];
                var date = comments[i+1];
                var text = comments[i+2];
                var username = comments[i+3];

                allCommentsDiv = allCommentsDiv +   '<div class=\"comment\">' +  
                                                    '<h2><a href="externalProfilePage.php?user=' + email + '">' + username + '</a></h2>' +
                                                    '<p class=\"text\">' + text + '</p>' +
                                                    '<p class=\"date\">' + date + '</p>' +
                                                    '</div>';
            }
            $("#oldComments").html(allCommentsDiv); //Imposto i nuovi commenti
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 500) //Se la richiesta non va a buon fine
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento dei commenti. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            $("#commentError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"commentError\")\'></button>"); //Aggiungo l'HTML interno alla div
            $("#comment-error").css("visibility", "visible");
        }
    }
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
{ $("#" + element).css("visibility", "hidden"); }