// document.addEventListener("DOMContentLoaded", function () {
//   const form = document.getElementById("archivo-form");

//   form.addEventListener("submit", function (event) {
//     event.preventDefault();

//     const formData = new FormData(form);
//     const params = [];

//     for (const pair of formData.entries()) {
//       if (pair[1] !== "") {
//         params.push(
//           `${encodeURIComponent(pair[0])}=${encodeURIComponent(pair[1])}`
//         );
//       }
//     }

//     const url = baseUrl + "?dir=archivo";

//     if (params.length > 0) {
//       const newUrl = url + "&" + params.join("&");
//       window.location.href = newUrl;
//     } else {
//       window.location.href = url;
//     }
//   });
// });
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("archivo-form");
  const buscarBtns = document.querySelectorAll(".buscar-btn");

  buscarBtns.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      const buscarPor = this.getAttribute("data-buscar");
      const formData = new FormData(form);
      const params = new URLSearchParams();

      // Añadir los parámetros del formulario
      for (const [key, value] of formData.entries()) {
        if (value !== "" && key !== "dir") {
          params.append(key, value);
        }
      }

      // Añadir el parámetro buscar_por
      params.append("buscar_por", buscarPor);

      // Construir la URL base
      const url = new URL(baseUrl);

      // Asegurarse de que 'dir=archivo' esté presente una sola vez
      url.searchParams.set("dir", "archivo");

      // Añadir los demás parámetros
      for (const [key, value] of params.entries()) {
        url.searchParams.append(key, value);
      }

      // Redirigir a la nueva URL
      window.location.href = url.toString();
    });
  });
});
