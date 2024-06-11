function obtenerHoraActual() {
  var fecha = new Date();
  var horas = fecha.getHours();
  var minutos = fecha.getMinutes();
  var ampm = horas >= 12 ? "PM" : "AM";
  horas = horas % 12;
  horas = horas ? horas : 12; // a las 00:00, mostrar 12 y no 0
  minutos = minutos < 10 ? "0" + minutos : minutos;
  var horaActual = horas + ":" + minutos + " " + ampm;
  return horaActual;
}

var fecha = new Date();
var clientTime = new Date(fecha.getTime() - fecha.getTimezoneOffset() * 60000);

var entry1Element = document.getElementById("entry1");
var entry2Element = document.getElementById("entry2");

entry1Element.textContent =
  (clientTime.getDate() < 10 ? "0" : "") +
  clientTime.getDate() +
  "/" +
  (clientTime.getMonth() + 1 < 10 ? "0" : "") +
  (clientTime.getMonth() + 1) +
  "/" +
  clientTime.getFullYear() +
  " " +
  obtenerHoraActual() +
  " <DIR>          .";

entry2Element.textContent =
  (clientTime.getDate() < 10 ? "0" : "") +
  clientTime.getDate() +
  "/" +
  (clientTime.getMonth() + 1 < 10 ? "0" : "") +
  (clientTime.getMonth() + 1) +
  "/" +
  clientTime.getFullYear() +
  " " +
  obtenerHoraActual() +
  " <DIR>         ..";
