$(document).ready(function () {
  function initializeMenu(buttonId, menuClass, iconId) {
    const $button = $(`#${buttonId}`);
    const $menuItems = $(`.${menuClass}`);
    const $iconToRotate = $(`#${iconId}`);

    // Esconder en pantallas pequeñas
    if ($(window).width() <= 1280) {
      $menuItems.addClass("hidden");
      $iconToRotate.attr("transform", "rotate(180)"); // Rotar icono
    }

    $button.click(function () {
      $menuItems.toggleClass("hidden");

      const isMenuHidden = $menuItems.first().hasClass("hidden");
      $iconToRotate.attr(
        "transform",
        isMenuHidden ? "rotate(180)" : "rotate(0)"
      ); // Toggle rotacion
    });
  }

  initializeMenu("miniMenu", "menu-item", "iconToRotate");
  initializeMenu("miniMenu2", "menu-item2", "iconToRotate2");
});
