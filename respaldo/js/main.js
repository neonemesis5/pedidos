document.addEventListener("DOMContentLoaded", () => {
    // Cargar tipos de productos en c[0][0]
    loadContent("php/tiposproductos.php", "#c00");
  
    // Función para cargar contenido dinámico en una posición específica
    function loadContent(url, target) {
      fetch(url)
        .then((response) => response.text())
        .then((html) => {
          document.querySelector(target).innerHTML = html;
        })
        .catch((error) => console.error("Error cargando contenido:", error));
    }
  });
  