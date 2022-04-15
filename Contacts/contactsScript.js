function check()
{
	var errorMessage = "";

    if($("#description").val() == "")
	{
        $("#description").css("border-color", "rgba(200, 37, 37, 0.9)");
        $("#description").css("border-width", "2px");
		errorMessage = "Inserire la descrizione del problema";
	}

	if($("#subject").val() == "")
	{
        $("#subject").css("border-color", "rgba(200, 37, 37, 0.9)");
        $("#subject").css("border-width", "2px");
		errorMessage = "Inserire l'oggetto del problema";
	}

    if($("#email").val() == "")
	{
        $("#email").css("border-color", "rgba(200, 37, 37, 0.9)");
        $("#email").css("border-width", "2px");
		errorMessage = "Inserire l'email";
	}

	if(errorMessage != "") //Se c'è almeno un errore
    { 
        if(document.getElementById("errorMessage") == null)
        { document.getElementById("form").insertAdjacentHTML("afterbegin", '<div class="alert alert-danger d-flex align-items-end alert-dismissible" id="errorMessage" style="height: fit-content"></div>'); }
        $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible()\'></button>"); //Aggiungo l'HTML interno alla div
        return false;
    }

    prova($("#email").val(), $("#subject").val(), $("#description").val());

    return true;
}

function prova(email, subject, description)
{
    $.post("../Logged/Utility/PHP/insertNewMessage.php",
        
        {
            email: email,
            subject: subject,
            description: description,
        },
        
        function(data, status)
        {
            alert("Dati ricevuti: " + data + "\nStatus: " + status);
        });
}

function setInvisible()
{ $("#errorMessage").remove(); }