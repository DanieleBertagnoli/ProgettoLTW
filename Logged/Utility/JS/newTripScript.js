function addPeriod()
{
    var newPeriodID = "period-" + (periodsLen+1);
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

    var newPeriod = '<div class="period" id="' + newPeriodID + '"><div class="top-elements"><div class="date-elements"><div><label class="period-label-start" for="start">Da: </label><input type="date" name="start-' + periodsLen + '" id="start-' + periodsLen + '"></div><div><label class="period-label-end" for="end">A: </label><input type="date" name="end-' + periodsLen + '" id="end-' + periodsLen + '"></div></div><input type="file" name="images-' + periodsLen + '[]" id="images-' + periodsLen + '" multiple="true" accept="image/png, image/jpg, image/jpeg"><label class="file-picker" for="images-' + periodsLen + '" id="images-label-' + periodsLen + '"><i class="bi bi-plus pe-1"></i><i class="bi bi-images"></i></label></div> <textarea class="period-description" placeholder="Descrivi le attività svolte durante questo periodo" name="description-' + periodsLen +'" id="description' + periodsLen + '"></textarea><button class="btn-trash" type="button" id="btn-trash-' + newPeriodID + '"><i class="bi bi-trash"></i></button></div>';

    lastPeriod.insertAdjacentHTML("afterend", newPeriod);
    document.getElementById("btn-trash-" + newPeriodID).addEventListener("click", () => removePeriod(newPeriodID));
    document.getElementById("period-num").value = periodsLen;
    document.getElementById("images-" + periodsLen).addEventListener("change", () => changeLabel("images-" + periodsLen, "images-label-" + periodsLen));
}

function removePeriod(id)
{ document.getElementById(id).remove(); }

function changeLabel(id, idLabel)
{ 
    var numFiles = document.getElementById(id).files.length;
    document.getElementById(idLabel).innerHTML = numFiles + ' <i class="bi bi-images"></i>'; 
}

function init()
{ 
    periods = ["period-1"]; 
    document.getElementById("images-1").addEventListener("change", () => changeLabel("images-1", "images-label-1"));
}

function checkForm()
{
    if(!document.getElementById("title").value.match(/^[a-zA-Z0-9 ]{5,45}$/)) //Se il titolo non rispetta la regex
    { 
        var errorMessage = "Il titolo deve essere lungo dai 5 ai 45 caratteri, può contenere lettere e numeri"; 
        document.getElementById("title").style.borderColor = "rgba(200, 37, 37, 0.9)";
        document.getElementById("title").style.borderWidth = "2px";
        setError(errorMessage);
        return false;
    }

    if(document.getElementById("place").value == "") //Se non è stato inserito un luogo
    { 
        var errorMessage = "Inserire almeno un luogo"; 
        document.getElementById("place").style.borderColor = "rgba(200, 37, 37, 0.9)";
        document.getElementById("place").style.borderWidth = "2px";
        setError(errorMessage);
        return false;
    }

    if(document.getElementById("thumbnail").files.length == 0) //Se non è stata selezionata un'immagine di copertina
    { 
        var errorMessage = "Inserire almeno l'immagine di copertina"; 
        document.getElementById("place").style.borderColor = "rgba(200, 37, 37, 0.9)";
        document.getElementById("place").style.borderWidth = "2px";
        setError(errorMessage);
        return false;
    }

    for(var i=0; i<periodsLen; i++)
    {
        var period = document.getElementById(periods[i]);
        if(period == null)
        { continue; }

        if(document.getElementById("start-" + (i+1)).value == "") //Se il campo data start-i è vuoto
        { 
            var errorMessage = "Aggiungere la data di inizio"; 
            document.getElementById("start-" + (i+1)).style.borderColor = "rgba(200, 37, 37, 0.9)";
            document.getElementById("start-" + (i+1)).style.borderWidth = "2px";
            setError(errorMessage);
            return false;
        }

        if(document.getElementById("end-" + (i+1)).value == "") //Se il campo data end-i è vuoto
        { 
            var errorMessage = "Aggiungere la data di fine"; 
            document.getElementById("end-" + (i+1)).style.borderColor = "rgba(200, 37, 37, 0.9)";
            document.getElementById("end-" + (i+1)).style.borderWidth = "2px";
            setError(errorMessage);
            return false;
        }

        if(document.getElementById("end-" + (i+1)).value < document.getElementById("start-" + (i+1)).value) //Se la data di fine è minore della data di inizio
        { 
            var errorMessage = "Le data di fine periodo deve essere successiva alla data di inizio periodo"; 
            document.getElementById("end-" + (i+1)).style.borderColor = "rgba(200, 37, 37, 0.9)";
            document.getElementById("end-" + (i+1)).style.borderWidth = "2px";
            document.getElementById("start-" + (i+1)).style.borderColor = "rgba(200, 37, 37, 0.9)";
            document.getElementById("start-" + (i+1)).style.borderWidth = "2px";
            setError(errorMessage);
            return false;
        }

        if(document.getElementById("description-" + (i+1)).value == "") //Se il campo descrizione description-i è vuoto
        { 
            var errorMessage = "Aggiungere la descrizione"; 
            document.getElementById("description-" + (i+1)).style.borderColor = "rgba(200, 37, 37, 0.9)";
            document.getElementById("description-" + (i+1)).style.borderWidth = "2px";
            setError(errorMessage);
            return false;
        }
    }

    return true;
}

function setError(errorMessage)
{
    document.getElementById("errorMessage").style.visibility = "visible"; //Rendo visibile la div che contiene l'errore
    document.getElementById("errorMessage").innerHTML = "<strong class=\'mx-2\'>Errore! <br>" + errorMessage + "</strong><button type=\'button\' class=\'btn-close\' onclick=\'setInvisible()\'></button>"; //Aggiungo l'HTML interno alla div
}

function setInvisible()
{ document.getElementById("errorMessage").style.visibility = "hidden"; } //Rendo invisibile la div dell'errore

var periods = [];
var periodsLen = 1;
window.addEventListener("load", init);