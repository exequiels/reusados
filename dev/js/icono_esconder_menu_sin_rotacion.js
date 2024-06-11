const button = document.getElementById("miniMenu");
const menuItems = document.querySelectorAll(".menu-item");

// Esconder en pantallas pequeñas
if (window.innerWidth <= 768) {
  menuItems.forEach((item) => {
    item.classList.add("hidden");
  });
}

button.addEventListener("click", () => {
  menuItems.forEach((item) => {
    item.classList.toggle("hidden");
  });
});
