document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector("#tendencias-form")
    .addEventListener("click", function (event) {
      if (event.target && event.target.id === "resetearbtn") {
        event.preventDefault();
<<<<<<< HEAD
        window.location.href = "https://test.reusados.net/?dir=tendencias";
=======
        window.location.href = "http://localhost/reusados/?dir=tendencias";
>>>>>>> b407152db69c8f4399b50513af2586a4c323d626
      }
    });
});
