document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("dark-mode-toggle");
  if (!toggle) return;

  function updateToggleIcon() {
    toggle.textContent = document.body.classList.contains("dark-mode") ? "☀️" : "🌙";
  }

  function applyTableMode(isDark) {
    document.querySelectorAll("table.table").forEach(table => {
      if (isDark) {
        table.classList.remove("table-light");
        table.classList.add("table-dark");
      } else {
        table.classList.remove("table-dark");
        table.classList.add("table-light");
      }
    });

    document.querySelectorAll("thead.table-light, thead.table-dark").forEach(thead => {
      if (isDark) {
        thead.classList.remove("table-light");
        thead.classList.add("table-dark");
      } else {
        thead.classList.remove("table-dark");
        thead.classList.add("table-light");
      }
    });
  }

  toggle.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    document.querySelector("nav").classList.toggle("dark-mode");
    document.querySelector(".lang-btn").classList.toggle("dark-mode");

    const isDark = document.body.classList.contains("dark-mode");
    applyTableMode(isDark);
    updateToggleIcon();

    localStorage.setItem("darkMode", isDark ? "enabled" : "disabled");
  });

  const saved = localStorage.getItem("darkMode");
  if (saved === "enabled") {
    if (!document.body.classList.contains("dark-mode")) {
      document.body.classList.add("dark-mode");
    }
    if (!document.querySelector("nav").classList.contains("dark-mode")) {
      document.querySelector("nav").classList.add("dark-mode");
    }
    if (!document.querySelector(".lang-btn").classList.contains("dark-mode")) {
      document.querySelector(".lang-btn").classList.add("dark-mode");
    }
    applyTableMode(true);
  } else {
    applyTableMode(false);
  }

  updateToggleIcon();
});
