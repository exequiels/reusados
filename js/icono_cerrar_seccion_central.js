const boton = document.getElementById("seccionCentral"); // Id del icono
const seccionCentrl = document.querySelector(".central-item");

boton.addEventListener("click", () => {
  seccionCentrl.classList.toggle("hidden");
});
