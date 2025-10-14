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

let activeClone = null;
let activeCard = null;
let activeInfo = null;
let animating = false;

document.querySelectorAll(".collectionFlipCard").forEach((card) => {
  card.addEventListener("click", () => {
    if (animating) return;
    animating = true;

    // Si une autre carte est déjà ouverte
    if (activeClone && activeCard && activeCard !== card) {
      const sleeve = activeCard.parentElement.querySelector(".sleeve");
      const sleeveRect = sleeve.getBoundingClientRect();

      if (activeInfo) activeInfo.remove();
      activeInfo = null;

      Object.assign(activeClone.style, {
        transform: "scale(1)",
        top: sleeveRect.top + "px",
        left: sleeveRect.left + "px",
        width: sleeveRect.width + "px",
        height: sleeveRect.height + "px"
      });

      activeClone.addEventListener("transitionend", function handleCloseOld(e) {
        if (e.propertyName !== "transform") return;
        activeClone.removeEventListener("transitionend", handleCloseOld);
        activeCard.style.visibility = "";
        activeClone.remove();
        activeClone = activeCard = null;
        animating = false;
      });

      return;
    }

    if (activeCard === card) {
      animating = false;
      return;
    }

    const rect = card.getBoundingClientRect();
    const clone = card.cloneNode(true);
    clone.querySelectorAll(".sleeve").forEach(s => s.remove());

    Object.assign(clone.style, {
      boxSizing: "border-box",
      width: rect.width + "px",
      height: rect.height + "px",
      position: "fixed",
      top: rect.top + "px",
      left: rect.left + "px",
      margin: "0",
      transform: "none",
      zIndex: "1000",
      transition: "transform 800ms ease, top 700ms ease, left 700ms ease, width 500ms ease, height 500ms ease"
    });

    document.body.appendChild(clone);
    card.style.visibility = "hidden";

    activeClone = clone;
    activeCard = card;

    // Phase 1 : montée
    requestAnimationFrame(() => {
      clone.style.transform = "translate(0, -300px)";
    });

    clone.addEventListener("transitionend", function handleFirstTransition(e) {
      if (e.propertyName !== "transform") return;
      clone.removeEventListener("transitionend", handleFirstTransition);

      // Phase 2 : centrage + zoom
      clone.style.top = "40%";
      clone.style.left = "40%";
      const img = clone.querySelector("img");
      if (img) img.style.transform = "scale(2)";

      // Création du bloc d'informations
      const { id, name, type, color, life, rarity, quantity, price } = card.dataset;
      const info = document.createElement("div");
      info.classList.add("card-info");
      info.innerHTML = `
        <strong>${name}</strong><br>
        Type : ${type}<br>
        Couleur : ${color}<br>
        Vie : ${life}<br>
        Rareté : ${rarity}<br>
        Quantité : ${quantity}<br>
        Prix : ${price}<br><br>
        <form method="POST" action="index.php?controller=card&action=removeFromCollection" style="display:block;">
          <input type="hidden" name="card_id" value="${id}">
          <input type="number" name="quantity" min="1" max="${quantity}" value="1" style="width:60px;">
          <button type="submit">🗑️ Retirer</button>
        </form>
      `;

      clone.appendChild(info);
      info.addEventListener("click", (e) => e.stopPropagation());   
      requestAnimationFrame(() => info.classList.add("visible"));
      activeInfo = info;

      animating = false;

      // Clic sur clone pour refermer
      clone.addEventListener("click", () => {
        if (activeInfo) activeInfo.remove();

        const sleeve = card.parentElement.querySelector(".sleeve");
        const sleeveRect = sleeve.getBoundingClientRect();

        clone.style.transform = "scale(1)";
        clone.style.top = sleeveRect.top + "px";
        clone.style.left = sleeveRect.left + "px";
        clone.style.width = sleeveRect.width + "px";
        clone.style.height = sleeveRect.height + "px";

        clone.addEventListener("transitionend", function handleClose(e) {
          if (e.propertyName !== "transform") return;
          clone.removeEventListener("transitionend", handleClose);
          card.style.visibility = "";
          clone.remove();
          activeClone = activeCard = activeInfo = null;
        });
      });
    });
  });
});

window.addEventListener('DOMContentLoaded', () => {
  const btnWrap = document.querySelector('.btnWrap');
  const activeBtn = document.querySelector('.profilebtn.active');

  if (btnWrap && activeBtn) {
    // Position exacte du bouton dans le parent
    const left = activeBtn.offsetLeft;
    const width = activeBtn.offsetWidth;

    // On injecte les valeurs dans les variables CSS
    btnWrap.style.setProperty('--indicator-left', `${left}px`);
    btnWrap.style.setProperty('--indicator-width', `${width}px`);
  }
});
document.querySelectorAll('.leader-card').forEach(card => {
  card.addEventListener('click', () => {
    document.querySelectorAll('.leader-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById('leader_id').value = card.dataset.leaderId;
  });
});

