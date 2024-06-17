document.addEventListener("DOMContentLoaded", function () {
  document
    const urlbase = "<?php echo $desvioUrl; ?>"
    .querySelector("#search-form")
    .addEventListener("click", function (event) {
      if (event.target && event.target.id === "resetearbtn") {
        event.preventDefault();
        window.location.href = urlbase;
      }
    });
});
