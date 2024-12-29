$(document).ready(function () {
    $('#leer_mas').click(function () {
        var moreText = $('#more')
        var btnText = $(this);

        if (moreText.hasClass("d-none")) {
            moreText.removeClass("d-none");
            btnText.text("... leer menos");
        } else {
            moreText.addClass("d-none");
            btnText.text("... leer más");
        }
    });
});