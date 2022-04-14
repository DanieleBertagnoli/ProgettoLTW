function changeStarLevel(lvl)
{
    for(var i=1; i<=5; i++)
    { 
        if(i <= lvl)
        { $("#star" + i).html("&#9733"); }
        else
        { $("#star" + i).html("&#9734"); }
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
            $("#voteError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"voteError\")\'></button>"); //Aggiungo l'HTML interno alla div
            $("#voteError").css("visibilty", "visible");
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
            $("#voteAvg").html("Gli altri utenti hanno votato: " + newAvg + "/5"); 
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 500)
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
    if(text.replace(/\s/g,"") == "")
    { $("#commentButton").addClass("btn-disabled"); }
    else
    { $("#commentButton").removeClass("btn-disabled"); }
}

function sendComment(tripID)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/insertNewComment.php?tripID=" + tripID + "&commentText=" + $("#commentText").val().trim(), true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        { reloadComments(tripID); }

        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento del nuovo commento. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            $("#commentError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"commentError\")\'></button>"); //Aggiungo l'HTML interno alla div
            $("#commentError").css("visibility", "visible");
        }
    }

    //Remove content to avoid spamming the same comment.
    $("#commentText").val("");
    $("#commentButton").addClass("btn-disabled");

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
            for(var i=comments.length-5; i>=0; i-=4)
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
            $("#oldComments").html(allCommentsDiv);
        }

        if(httpRequest.readyState == 4 && httpRequest.status == 500)
        {  
            var errorMessage = "Siamo spiacenti, si è verificato un errore durante il caricamento dei commenti. Se l'errore persiste contattare gli sviluppatore tramite la sezione contatti.";
            $("#commentError").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible(\"commentError\")\'></button>"); //Aggiungo l'HTML interno alla div
            $("#comment-error").css("visibility", "visible");
        }
    }
}

function openPopup(index)
{
    var popup = document.getElementById('myPopup');
    var popupImage = document.getElementById('bigImage');
    var cell = document.getElementById('carouselCell-' + index);

    var parts = cell.style.background.split(" ");
    var sourceImage = parts[4].replace('url("', '').replace('")', '');
    
    popupImage.src = sourceImage;
    popup.style.display = "block";
}

function closePopup()
{
    document.getElementById('myPopup').style.display = 'none';
}

function setInvisible(element)
{ $("#" + element).css("visibility", "hidden"); }