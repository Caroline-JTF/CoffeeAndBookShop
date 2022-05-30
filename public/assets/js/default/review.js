// =========================================
// =============== VARIABLES ===============
// =========================================

const btn = document.querySelector(".btn");
btn.addEventListener("click", active);

// =========================================
// =============== FONCTIONS ===============
// =========================================

function active() {
    var div = document.querySelector(".box");
    if (div.style.display === "none") {
      div.style.display = "block";
    } else {
      div.style.display = "none";
    }
  }