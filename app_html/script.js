// script.js - SmartCampus frontend JS

// Hamburger: mostra/nasconde la sidebar su mobile
const hamburger = document.getElementById('hamburger');
const sidebar = document.querySelector('.sidebar');

if (hamburger && sidebar) {
    hamburger.addEventListener('click', function () {
        sidebar.classList.toggle('open');
    });
}
