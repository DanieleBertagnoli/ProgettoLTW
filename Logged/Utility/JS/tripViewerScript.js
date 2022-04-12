function addListeners() 
{
    document.getElementById("star-1").addEventListener("click", () => changeStarLevel(1));
    document.getElementById("star-2").addEventListener("click", () => changeStarLevel(2));
    document.getElementById("star-3").addEventListener("click", () => changeStarLevel(3));
    document.getElementById("star-4").addEventListener("click", () => changeStarLevel(4));
    document.getElementById("star-5").addEventListener("click", () => changeStarLevel(5));  

    //document.getElementById("comment-button").addEventListener("click", () => addComment());
}

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

function sendVote(vote, tripID, email)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/insertVote.php?tripID=" + tripID + "&email=" + email + "&vote=" + vote, true);
    httpRequest.send(null);
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

function sendComment(tripID, email)
{
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "./Utility/PHP/insertNewComment.php?tripID=" + tripID + "&email=" + email + "&comment-text=" + document.getElementById("comment-text").value.trim(), true);
    httpRequest.send(null);
    httpRequest.onreadystatechange = () =>
    {
        if(httpRequest.readyState == 4 && httpRequest.status == 200)
        { reloadComments(tripID); }
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
    }
}

window.addEventListener("load", addListeners);