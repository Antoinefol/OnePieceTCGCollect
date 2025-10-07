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

document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.flip-card');

  cards.forEach(card => {
    card.addEventListener('click', () => {
      card.classList.toggle('flipped');
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const toggleButton = document.getElementById('toggleFilters');
  const moreFilters = document.querySelector('.moreFilters');

  toggleButton.addEventListener('click', () => {
    moreFilters.classList.toggle('more');
    toggleButton.textContent = moreFilters.classList.contains('more')
      ? '− Moins de filtres'
      : '+ Plus de filtres';
  });
});