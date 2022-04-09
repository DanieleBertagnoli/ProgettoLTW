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

    var newPeriod = '<div class="period" id="' + newPeriodID + '"><div class="top-elements"><div class="date-elements"><div><label class="period-label-start" for="start">Da: </label><input type="date" name="start-' + periodsLen + '" id="start-' + periodsLen + '"></div><div><label class="period-label-end" for="end">A: </label><input type="date" name="end-' + periodsLen + '" id="end-' + periodsLen + '"></div></div><input type="file" name="images-' + periodsLen + '" id="images-' + periodsLen + '" multiple="true" accept="image/png, image/jpg, image/jpeg"><label class="file-picker" for="images-' + periodsLen + '">Scegli immagini</label></div> <textarea class="period-description" maxlength="250" placeholder="Descrivi le attività svolte durante questo periodo" name="description-' + periodsLen +'"></textarea><button class="btn-trash" type="button" id="btn-trash-' + newPeriodID + '"><i class="bi bi-trash"></i></button></div>';

    lastPeriod.insertAdjacentHTML("afterend", newPeriod);
    document.getElementById("btn-trash-" + newPeriodID).addEventListener("click", () => removePeriod(newPeriodID));
    document.getElementById("period-num").value = periodsLen;
}

function removePeriod(id)
{
    document.getElementById(id).remove();
}

function initPeriod()
{ periods = ["period-1"]; }

var periods = [];
var periodsLen = 1;
window.addEventListener("load", initPeriod);