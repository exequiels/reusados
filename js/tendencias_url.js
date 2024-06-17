document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("tendencias-form");

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

<<<<<<< HEAD
    const url = "https://test.reusados.net/?dir=tendencias";
=======
    const url = "http://localhost/reusados/?dir=tendencias";
>>>>>>> b407152db69c8f4399b50513af2586a4c323d626

    if (params.length > 0) {
      const newUrl = url + "&" + params.join("&");
      window.location.href = newUrl;
    } else {
      window.location.href = url;
    }
  });
});
