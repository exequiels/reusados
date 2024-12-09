document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector("#search-form")
    .addEventListener("click", function (event) {
      if (event.target && event.target.id === "resetearbtn") {
        event.preventDefault();
        window.location.href = baseUrl + "?dir=bazar";
      }
    });
});
