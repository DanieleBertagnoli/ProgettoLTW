function addPeriod()
{
    var newPeriodID = "period" + (periodsLen+1);
    periods[periodsLen] = newPeriodID;
    periodsLen++;
    
    var lastPeriod = null;
    var count = periodsLen-1;
    while(lastPeriod == null)
    { 
        var lastPeriodID = periods[count];
        lastPeriod = document.getElementById(lastPeriodID); 
        count--;
    }

    var newPeriod = '<div class="period" id="' + newPeriodID + '"><div class="top-elements"><div class="date-elements"><div><label class="period-label-start" for="start' + periodsLen + '">Da: </label><input type="date" name="start' + periodsLen + '" id="start' + periodsLen + '"></div><div><label class="period-label-end" for="end' + periodsLen + '">A: </label><input type="date" name="end' + periodsLen + '" id="end' + periodsLen + '"></div></div><input type="file" name="images' + periodsLen + '[]" id="images' + periodsLen + '" multiple="true" accept="image/png, image/jpg, image/jpeg"><label class="file-picker" for="images' + periodsLen + '" id="imagesLabel' + periodsLen + '"><i class="bi bi-plus pe-1"></i><i class="bi bi-images"></i></label></div> <textarea class="period-description" placeholder="Descrivi le attività svolte durante questo periodo" name="description' + periodsLen +'" id="description' + periodsLen + '"></textarea><button class="btn-trash" type="button" id="btnTrash' + periodsLen + '"><i class="bi bi-trash"></i></button></div>';

    lastPeriod.insertAdjacentHTML("afterend", newPeriod);
    $("#periodNum").val(periodsLen);
    $("#btnTrash" + periodsLen).click(() => removePeriod(newPeriodID));
    $("#images" + periodsLen).change(() => changeLabel("images" + periodsLen, "imagesLabel" + periodsLen));
}

function removePeriod(id)
{ document.getElementById(id).remove(); }

function changeLabel(id, idLabel)
{ 
    var numFiles = document.getElementById(id).files.length;
    $("#" + idLabel).html(numFiles + ' <i class="bi bi-images"></i>'); 
}

function init()
{ 
    periods = ["period1"]; 
    $("#images1").change(() => changeLabel("images1", "imagesLabel1"));
}

function checkForm()
{
    if(!$("#title").val().match(/^[a-zA-Z0-9 ]{5,45}$/)) //Se il titolo non rispetta la regex
    { 
        var errorMessage = "Il titolo deve essere lungo dai 5 ai 45 caratteri, può contenere lettere e numeri"; 
        $("#title").css("border-color", "rgba(200, 37, 37, 0.9)");
        $("#title").css("border-width", "2px");
        setError(errorMessage);
        return false;
    }

    if($("#place").val() == "") //Se non è stato inserito un luogo
    { 
        var errorMessage = "Inserire almeno un luogo"; 
        $("#place").css("border-color", "rgba(200, 37, 37, 0.9)");
        $("#place").css("border-width", "2px");
        setError(errorMessage);
        return false;
    }

    if(document.getElementById("thumbnail").files.length == 0) //Se non è stata selezionata un'immagine di copertina
    { 
        var errorMessage = "Inserire almeno l'immagine di copertina"; 
        setError(errorMessage);
        return false;
    }

    for(var i=0; i<periodsLen; i++)
    {
        var period = document.getElementById(periods[i]);
        if(period == null)
        { continue; }

        if($("#start" + (i+1)).val() == "") //Se il campo data start-i è vuoto
        { 
            var errorMessage = "Aggiungere la data di inizio"; 
            $("#start" + (i+1)).css("border-color", "rgba(200, 37, 37, 0.9)");
            $("#start" + (i+1)).css("border-width", "2px");
            setError(errorMessage);
            return false;
        }

        if($("#end" + (i+1)).val() == "") //Se il campo data end-i è vuoto
        { 
            var errorMessage = "Aggiungere la data di fine"; 
            $("#end" + (i+1)).css("border-color", "rgba(200, 37, 37, 0.9)");
            $("#end" + (i+1)).css("border-width", "2px");
            setError(errorMessage);
            return false;
        }

        if($("#end" + (i+1)).val() < $("#start" + (i+1)).val()) //Se la data di fine è minore della data di inizio
        { 
            var errorMessage = "Le data di fine periodo deve essere successiva alla data di inizio periodo"; 
            $("#end" + (i+1)).css("border-color", "rgba(200, 37, 37, 0.9)");
            $("#end" + (i+1)).css("border-width", "2px");
            $("#start" + (i+1)).css("border-color", "rgba(200, 37, 37, 0.9)");
            $("#start" + (i+1)).css("border-width", "2px");
            setError(errorMessage);
            return false;
        }

        if($("#description" + (i+1)).val() == "") //Se il campo descrizione description-i è vuoto
        { 
            var errorMessage = "Aggiungere la descrizione"; 
            $("#description" + (i+1)).css("border-color", "rgba(200, 37, 37, 0.9)");
            $("#description" + (i+1)).css("border-width", "2px");
            setError(errorMessage);
            return false;
        }
    }

    return true;
}

function setError(errorMessage)
{
    if(document.getElementById("errorMessage") == null)
    { document.getElementById("period1").insertAdjacentHTML("beforebegin",'<div class="alert alert-danger d-flex align-items-end alert-dismissible mt-5" id="errorMessage" style="width: fit-content; align-self: center;"></div>'); }
    $("#errorMessage").html("<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible()\'></button>"); //Aggiungo l'HTML interno alla div
}

function setInvisible()
{ $("#errorMessage").remove(); } //Rendo invisibile la div dell'errore

var periods = [];
var periodsLen = 1;
window.addEventListener("load", init);