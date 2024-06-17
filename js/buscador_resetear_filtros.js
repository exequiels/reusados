document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector("#search-form")
    .addEventListener("click", function (event) {
      if (event.target && event.target.id === "resetearbtn") {
        event.preventDefault();
<<<<<<< HEAD
        window.location.href = "https://test.reusados.net/?dir=buscador";
=======
        window.location.href = "http://localhost/reusados/?dir=buscador";
>>>>>>> b407152db69c8f4399b50513af2586a4c323d626
      }
    });
});
