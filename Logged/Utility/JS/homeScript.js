function checkForm()
{
    if(document.getElementById("search-box").value == "") //Se la ricerca è vuota
    { 
        document.getElementById("search-form").style.borderColor = "rgba(200, 37, 37, 0.9)";
        document.getElementById("search-form").style.borderWidth = "2px";
        return false;
    }

    addSearchCookie(document.getElementById("search-box").value);
    return true;
}

function addSearchCookie(search)
{ document.cookie = "search=" + getCookie("search") + search + "~(~~)~;"; }

function getCookie(cookieName) 
{
    cookieName = cookieName+"=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieSplitted = decodedCookie.split(';');
    for(var i=0; i <cookieSplitted.length; i++) 
    {
      var cookie = cookieSplitted[i];
      while (cookie.charAt(0) == ' ')
      { cookie = cookie.substring(1); }
      if (cookie.indexOf(cookieName) == 0)
      { return cookie.substring(cookieName.length, cookie.length); }
    }
    return "";
}