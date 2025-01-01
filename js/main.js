document.addEventListener("DOMContentLoaded", () => {
  // Cargar tipos de productos en c[0][0]
  loadContent("php/view/tiposproductos.php", "#c00");

  // Manejar clics en los botones de tipos de productos
  document.querySelector("#c00").addEventListener("click", (event) => {
    if (event.target.tagName === "BUTTON") {
      const tipoProductoId = event.target.dataset.id;
      // Cargar productos según el tipo de producto seleccionado
      loadContent(`php/view/productos.php?tipoProductoId=${tipoProductoId}`, "#c01");
    }
  });

  // Función para cargar contenido dinámico en una posición específica
  function loadContent(url, target) {
    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Error al cargar contenido desde ${url}`);
        }
        return response.text();
      })
      .then((html) => {
        document.querySelector(target).innerHTML = html;
      })
      .catch((error) => console.error("Error cargando contenido:", error));
  }
});
