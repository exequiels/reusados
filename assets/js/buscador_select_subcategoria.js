// Filtro subcategorias
$(document).ready(function () {
  $("#categoria").change(function () {
    const categoria = $(this).val();
    const pais = $("#pais").val();

    if (categoria) {
      $.ajax({
        url: "utils/subcategorias.php",
        type: "POST",
        data: { categoria: categoria, pais: pais },
        beforeSend: function (xhr, settings) {},
        success: function (data) {
          $("#subcategoriaSelect").html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {},
      });
    } else {
      //$('#subcategoriaSelect').attr('disabled', true);
    }
  });
});
