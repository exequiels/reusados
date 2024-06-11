const button = document.getElementById("miniMenu");
const menuItems = document.querySelectorAll(".menu-item");
const iconToRotate = document.getElementById("iconToRotate"); // Id del icono

// Esconder en pantallas pequeñas
if (window.innerWidth <= 768) {
  menuItems.forEach((item) => {
    item.classList.add("hidden");
    iconToRotate.setAttribute("transform", "rotate(180)"); // Rotar icono
  });
}

button.addEventListener("click", () => {
  menuItems.forEach((item) => {
    item.classList.toggle("hidden");
  });

  const isMenuHidden = menuItems[0].classList.contains("hidden");
  iconToRotate.setAttribute("transform", isMenuHidden ? "rotate(180)" : ""); // Toggle rotacion
});
