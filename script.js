// Script voor het tonen van de informatiepop-up
const infoButton = document.getElementById('info-button');
const popup = document.getElementById('info-popup');
const closeButton = document.querySelector('.close-button');

// Toon de pop-up wanneer de knop wordt geklikt
infoButton.addEventListener('click', () => {
    popup.style.display = 'block';
});

// Sluit de pop-up wanneer de sluitknop wordt geklikt
closeButton.addEventListener('click', () => {
    popup.style.display = 'none';
});

// Sluit de pop-up wanneer er buiten de pop-up wordt geklikt
window.addEventListener('click', (event) => {
    if (event.target === popup) {
        popup.style.display = 'none';
    }
});
