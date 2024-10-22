// Vind het modale venster
var modal = document.getElementById("infoModal");

// Vind de knop die het modale venster opent
var btn = document.getElementById("infoButton");

// Vind de <span> element dat het modale venster sluit
var span = document.getElementsByClassName("close")[0];

// Wanneer de gebruiker op de knop klikt, open het modale venster
btn.onclick = function() {
    modal.style.display = "block";
}

// Wanneer de gebruiker op de <span> (sluit knop) klikt, sluit het modale venster
span.onclick = function() {
    modal.style.display = "none";
}

// Wanneer de gebruiker ergens buiten het modale venster klikt, sluit het
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
