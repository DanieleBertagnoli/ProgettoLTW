function checkForm()
{
    if($("#searchBox").val() == "") //Se la ricerca è vuota
    { 
        $("#searchForm").css("border-color", "rgba(200, 37, 37, 0.9)");
        $("#searchForm").css("border-width", "2px");
        return false;
    }

    addSearchCookie($("#searchBox").val());
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