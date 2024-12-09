$(document).ready(function () {
  $("#consola").change(function () {
    const consolas = $(this).val();
    const juegos = $("#juego").val();

    if (consolas) {
      $.ajax({
        url: "utils/archivo_juegos.php",
        type: "POST",
        data: { consolas: consolas, juegos: juegos },
        beforeSend: function (xhr, settings) {},
        success: function (data) {
          $("#juego").html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {},
      });
    } else {
      // debug
    }
  });
});
