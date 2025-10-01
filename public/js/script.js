document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger');
    const menu = document.getElementById('menu');

    burger.addEventListener('click', () => {
        menu.classList.toggle('show');

        // Changer le symbole burger ↔ croix
        if (menu.classList.contains('show')) {
            burger.textContent = '✖';
        } else {
            burger.textContent = '☰';
        }
    });
});
