document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector("#tendencias-form")
    .addEventListener("click", function (event) {
      if (event.target && event.target.id === "resetearbtn") {
        event.preventDefault();
        window.location.href = "https://www.reusados.net/?dir=tendencias";
      }
    });
});
