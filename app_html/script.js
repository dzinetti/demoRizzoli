// script.js - SmartCampus frontend JS

// Hamburger: mostra/nasconde la sidebar su mobile
const hamburger = document.getElementById('hamburger');
const sidebar = document.querySelector('.sidebar');

if (hamburger && sidebar) {
    hamburger.addEventListener('click', function () {
        sidebar.classList.toggle('open');
    });
}

// Mappa dei colori in base all'hostname / IP della VM
const vmColorMap = {
    "10.0.0.225": "#e8f5e9",       // VM Database / Backend specifico (Verde)
    "localhost": "#e3f2fd",        // Ambiente locale / dev (Azzurro)
    "vm-sviluppo.local": "#ede7f6",// Viola
    "vm-prod.scuola.it": "#ffebee" // Produzione (Rosso)
};

function applyVmTheme() {
    const currentHost = window.location.hostname.toLowerCase();
    
    // Trova il colore esatto o verifica se l'hostname contiene una parola chiave
    let selectedColor = vmColorMap[currentHost];
    
    if (!selectedColor) {
        if (currentHost.includes("1")) selectedColor = "#e3f2fd";
        else if (currentHost.includes("2")) selectedColor = "#fff9c4";
        else if (currentHost.includes("3")) selectedColor = "#ffebee";
        else selectedColor = "#f4f6f9"; // Default
    }

    document.documentElement.style.setProperty('--page-bg-color', selectedColor);
    document.body.style.backgroundColor = selectedColor;
}

// Applica appena il DOM è pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyVmTheme);
} else {
    applyVmTheme();
}
