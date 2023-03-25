// /!\ ARCHIVE 1.0 INUTILISÉE POUR LE MOMENT 

function _(elmt) {
    return document.getElementById(elmt);
}

function uploadPost() {
    var file = _('file').files[0];
    var data = new FormData();
    data.append('file', file);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", "/controller/uploadpost.php");
    ajax.send(data);
}

function progressHandler(event) {
    var pourcentage = (event.loaded / event.total) * 100;
    _('progressBar').value = Math.round(pourcentage);
    _('status').innerHTML = Math.round(pourcentage) + '% Terminé... Patientez';
}

function completeHandler(event) {
    _('status').innerHTML = event.target.responseText;
    _('progressBar').value = 0;
}

function errorHandler() {
    _('status').innerHTML = "L'envoi a échoué !";
}

function abortHandler() {
    _('status').innerHTML = "L'envoi a été annulé !";
}