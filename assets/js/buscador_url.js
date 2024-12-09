document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("search-form");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(form);
    const params = [];

    for (const pair of formData.entries()) {
      if (pair[1] !== "") {
        params.push(
          `${encodeURIComponent(pair[0])}=${encodeURIComponent(pair[1])}`
        );
      }
    }

    const url = baseUrl + "?dir=bazar";

    if (params.length > 0) {
      const newUrl = url + "&" + params.join("&");
      window.location.href = newUrl;
    } else {
      window.location.href = url;
    }
  });
});
