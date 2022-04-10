function checkForm()
{
    if(document.getElementById("search-box").value == "") //Se la ricerca è vuota
    { 
        document.getElementById("search-form").style.borderColor = "rgba(200, 37, 37, 0.9)";
        document.getElementById("search-form").style.borderWidth = "2px";
        return false;
    }

    return true;
}