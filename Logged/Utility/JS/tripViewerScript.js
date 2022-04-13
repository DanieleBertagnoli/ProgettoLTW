function changeStarLevel(lvl)
{
    for(var i=1; i<=5; i++)
    { 
        if(i <= lvl)
        { document.getElementById("star-" + i).innerHTML = "&#9733"; }
        else
        { document.getElementById("star-" + i).innerHTML = "&#9734"; }
    }
}

function sendVote(vote, tripID)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/insertVote.php?tripID=" + tripID + "&vote=" + vote, true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () => 
    { 
        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo voto. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            document.getElementById("vote-error").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"vote-error\")\'></button>"; //Aggiungo l'HTML interno alla div
            document.getElementById("vote-error").style.visibility = "visible";
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        { changeStarLevel(vote); }
    }
    refreshVoteAvg(tripID);
}

function refreshVoteAvg(tripID)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/getVoteAvg.php?tripID=" + tripID, true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () => 
    { 
        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        {
            var newAvg = httpRequest.responseText; 
            document.getElementById("vote-avg").innerHTML = "Gli altri utenti hanno votato: " + newAvg + "/5"; 
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento dei voti. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            document.getElementById("vote-error").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"vote-error\")\'></button>"; //Aggiungo l'HTML interno alla div
            document.getElementById("vote-error").style.visibility = "visible";
        }
    }
}

function checkButton()
{
    var text = document.getElementById("comment-text").value;
    if(text.replace(/\s/g,"") == "")
    { document.getElementById("comment-button").classList.add("btn-disabled"); }
    else
    { document.getElementById("comment-button").classList.remove("btn-disabled"); }
}

function sendComment(tripID)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/insertNewComment.php?tripID=" + tripID + "&comment-text=" + document.getElementById("comment-text").value.trim(), true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        { reloadComments(tripID); }

        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo commento. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            document.getElementById("comment-error").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"comment-error\")\'></button>"; //Aggiungo l'HTML interno alla div
            document.getElementById("comment-error").style.visibility = "visible";
        }
    }

    //Remove content to avoid spamming the same comment.
    document.getElementById("comment-text").value = "";
    document.getElementById("comment-button").classList.add("btn-disabled");

    return true;
}

function reloadComments(tripID)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/getComments.php?tripID=" + tripID, true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        { 
            var comments = httpRequest.responseText; 
            comments = comments.split("~(~~)~");
            var allCommentsDiv = "";
            for(var i=comments.length-4; i>=0; i-=3)
            {
                var email = comments[i];
                var date = comments[i+1];
                var text = comments[i+2];

                allCommentsDiv = allCommentsDiv +   "<div class=\"comment\">" +  
                                                    "<h2>" + email + "</h2>" +
                                                    "<p class=\"text\">" + text + "</p>" +
                                                    "<p class=\"date\">" + date + "</p>" +
                                                    "</div>";
            }
            document.getElementById("old-comments").innerHTML = allCommentsDiv;
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento dei commenti. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            document.getElementById("comment-error").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"comment-error\")\'></button>"; //Aggiungo l'HTML interno alla div
            document.getElementById("comment-error").style.visibility = "visible";
        }
    }
}

function setInvisible(element)
{ document.getElementById(element).style.visibility = "hidden"; }