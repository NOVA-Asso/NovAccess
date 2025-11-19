// Novaccess - Modern Dashboard
// Author: GitHub Copilot
// Features: Drag & Drop, Live Search, Favicon Loading

document.addEventListener("DOMContentLoaded", function () {
  // Initialize features
  initFavicons();
  initSearch();
  initDragAndDrop();
  loadOrder();
});

// ===== FAVICON LOADING =====
function initFavicons() {
  const iconContainers = document.querySelectorAll(".card-icon");

  iconContainers.forEach((container) => {
    const url = container.dataset.url;
    const img = container.querySelector("img");

    if (!url) return;

    // Extract domain from URL
    try {
      const urlObj = new URL(url);
      const domain = urlObj.hostname;

      // Try multiple favicon sources
      const faviconSources = [
        `https://www.google.com/s2/favicons?domain=${domain}&sz=128`,
        `https://${domain}/favicon.ico`,
        `https://icons.duckduckgo.com/ip3/${domain}.ico`,
      ];

      loadFaviconWithFallback(img, container, faviconSources, 0);
    } catch (e) {
      // If URL parsing fails, show fallback
      showFaviconFallback(container, url);
    }
  });
}

function loadFaviconWithFallback(img, container, sources, index) {
  if (index >= sources.length) {
    // All sources failed, show fallback
    const url = container.dataset.url;
    showFaviconFallback(container, url);
    return;
  }

  img.onload = function () {
    // Favicon loaded successfully
    img.style.display = "block";
    container.classList.remove("loading");
  };

  img.onerror = function () {
    // Try next source
    loadFaviconWithFallback(img, container, sources, index + 1);
  };

  img.src = sources[index];
}

function showFaviconFallback(container, url) {
  container.classList.remove("loading");

  // Extract first letter of domain for fallback
  let letter = "?";
  try {
    const urlObj = new URL(url);
    letter = urlObj.hostname.charAt(0).toUpperCase();
  } catch (e) {
    letter = url.charAt(0).toUpperCase();
  }

  // Create a colored fallback with letter
  const colors = [
    "#6366f1",
    "#8b5cf6",
    "#ec4899",
    "#f59e0b",
    "#10b981",
    "#06b6d4",
    "#3b82f6",
    "#a855f7",
  ];
  const color = colors[letter.charCodeAt(0) % colors.length];

  container.style.backgroundColor = color;
  container.innerHTML = `<span style="font-size: 24px; font-weight: 700; color: white;">${letter}</span>`;
}

// ===== SEARCH FUNCTIONALITY =====
function initSearch() {
  const searchInput = document.getElementById("searchInput");
  const searchClear = document.getElementById("searchClear");
  const resultsCount = document.getElementById("resultsCount");
  const cards = document.querySelectorAll(".link-card");

  if (!searchInput) return;

  // Search input handler
  searchInput.addEventListener("input", function (e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    filterCards(searchTerm, cards, resultsCount);
  });

  // Clear button handler
  if (searchClear) {
    searchClear.addEventListener("click", function () {
      searchInput.value = "";
      searchInput.focus();
      filterCards("", cards, resultsCount);
    });
  }

  // Initial count
  updateResultsCount(cards.length, cards.length, resultsCount);
}

function filterCards(searchTerm, cards, resultsCount) {
  let visibleCount = 0;

  cards.forEach((card) => {
    const name = card.dataset.name.toLowerCase();
    const description =
      card.querySelector(".card-description")?.textContent.toLowerCase() || "";

    if (
      searchTerm === "" ||
      name.includes(searchTerm) ||
      description.includes(searchTerm)
    ) {
      card.classList.remove("hidden");
      visibleCount++;
    } else {
      card.classList.add("hidden");
    }
  });

  updateResultsCount(visibleCount, cards.length, resultsCount);
}

function updateResultsCount(visible, total, resultsCount) {
  if (!resultsCount) return;

  if (visible === total) {
    resultsCount.textContent = `${total} lien${
      total > 1 ? "s" : ""
    } disponible${total > 1 ? "s" : ""}`;
  } else {
    resultsCount.textContent = `${visible} sur ${total} lien${
      total > 1 ? "s" : ""
    }`;
  }
}

// ===== DRAG AND DROP =====
let draggedElement = null;
let draggedData = null;

function initDragAndDrop() {
  const cards = document.querySelectorAll(".link-card");

  cards.forEach((card) => {
    // Drag start
    card.addEventListener("dragstart", handleDragStart);

    // Drag over
    card.addEventListener("dragover", handleDragOver);

    // Drag enter
    card.addEventListener("dragenter", handleDragEnter);

    // Drag leave
    card.addEventListener("dragleave", handleDragLeave);

    // Drop
    card.addEventListener("drop", handleDrop);

    // Drag end
    card.addEventListener("dragend", handleDragEnd);
  });
}

function handleDragStart(e) {
  draggedElement = this;
  draggedData = {
    id: this.dataset.id,
    html: this.outerHTML,
  };

  this.classList.add("dragging");
  e.dataTransfer.effectAllowed = "move";
  e.dataTransfer.setData("text/html", this.outerHTML);
}

function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault();
  }
  e.dataTransfer.dropEffect = "move";
  return false;
}

function handleDragEnter(e) {
  if (this !== draggedElement) {
    this.classList.add("drag-over");
  }
}

function handleDragLeave(e) {
  this.classList.remove("drag-over");
}

function handleDrop(e) {
  if (e.stopPropagation) {
    e.stopPropagation();
  }

  this.classList.remove("drag-over");

  if (draggedElement !== this) {
    // Get the container
    const container = this.parentNode;

    // Get all cards
    const cards = Array.from(container.children);

    // Find positions
    const draggedIndex = cards.indexOf(draggedElement);
    const targetIndex = cards.indexOf(this);

    // Reorder
    if (draggedIndex < targetIndex) {
      container.insertBefore(draggedElement, this.nextSibling);
    } else {
      container.insertBefore(draggedElement, this);
    }

    // Save order to localStorage
    saveOrder();
  }

  return false;
}

function handleDragEnd(e) {
  this.classList.remove("dragging");

  // Remove drag-over class from all cards
  const cards = document.querySelectorAll(".link-card");
  cards.forEach((card) => {
    card.classList.remove("drag-over");
  });

  draggedElement = null;
  draggedData = null;
}

function saveOrder() {
  const container = document.getElementById("linksGrid");
  const cards = container.querySelectorAll(".link-card");
  const order = Array.from(cards).map((card) => card.dataset.id);

  localStorage.setItem("novaccess-order", JSON.stringify(order));
  console.log("Order saved:", order);
}

function loadOrder() {
  const savedOrder = localStorage.getItem("novaccess-order");
  if (!savedOrder) return;

  try {
    const order = JSON.parse(savedOrder);
    const container = document.getElementById("linksGrid");
    const cards = Array.from(container.querySelectorAll(".link-card"));

    // Sort cards based on saved order
    order.forEach((id) => {
      const card = cards.find((c) => c.dataset.id === id);
      if (card) {
        container.appendChild(card);
      }
    });

    console.log("Order loaded:", order);
  } catch (e) {
    console.error("Failed to load order:", e);
  }
}

// Keyboard shortcuts
document.addEventListener("keydown", function (e) {
  // Focus search with '/' key
  if (
    e.key === "/" &&
    document.activeElement !== document.getElementById("searchInput")
  ) {
    e.preventDefault();
    document.getElementById("searchInput")?.focus();
  }

  // Clear search with 'Escape' key
  if (
    e.key === "Escape" &&
    document.activeElement === document.getElementById("searchInput")
  ) {
    const searchInput = document.getElementById("searchInput");
    if (searchInput.value) {
      searchInput.value = "";
      const event = new Event("input", { bubbles: true });
      searchInput.dispatchEvent(event);
    } else {
      searchInput.blur();
    }
  }
});
