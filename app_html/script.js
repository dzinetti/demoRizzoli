// script.js - SmartCampus frontend JS

// Hamburger: mostra/nasconde la sidebar su mobile
const hamburger = document.getElementById('hamburger');
const sidebar = document.querySelector('.sidebar');

if (hamburger && sidebar) {
    hamburger.addEventListener('click', function () {
        sidebar.classList.toggle('open');
    });
}

function applyVmTheme() {
    const currentHost = window.location.hostname.toLowerCase();
    
    // Trova il colore esatto o verifica se l'hostname contiene una parola chiave
    let selectedColor = "#d0e3ff"; // Default
    if (currentHost.includes("1")) selectedColor = "#c8ffc8";
    else if (currentHost.includes("2")) selectedColor = "#fff9c4";
    else if (currentHost.includes("3")) selectedColor = "#ffc9d1";
    
    document.documentElement.style.setProperty('--page-bg-color', selectedColor);
    document.body.style.backgroundColor = selectedColor;
}

// Applica appena il DOM è pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyVmTheme);
} else {
    applyVmTheme();
}
