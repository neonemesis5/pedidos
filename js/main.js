document.addEventListener("DOMContentLoaded", () => {
  const carrito = []; // Array para almacenar los productos seleccionados
  let tasas = {}; // Objeto para almacenar las tasas dinámicas

  // Función para cargar las tasas desde la base de datos
  function cargarTasas() {
    fetch("php/view/tasas.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error al cargar las tasas de cambio.");
        }
        return response.json();
      })
      .then((data) => {
        // Convertir las tasas en un objeto para fácil acceso
        data.forEach((tasa) => {
          tasas[tasa.nombre] = parseFloat(tasa.monto);
        });
        console.log("Tasas cargadas:", tasas);
      })
      .catch((error) => console.error("Error al cargar tasas:", error));
  }

  // Cargar las tasas al inicio
  cargarTasas();

  // Cargar tipos de productos en c[0][0]
  loadContent("php/view/tiposproductos.php", "#c00");

  // Manejar clics en los botones de tipos de productos
  document.querySelector("#c00").addEventListener("click", (event) => {
    if (event.target.tagName === "BUTTON") {
      const tipoProductoId = event.target.dataset.id;
      loadContent(`php/view/productos.php?tipoProductoId=${tipoProductoId}`, "#c01");
    }
  });

  // Manejar clics en los productos
  document.querySelector("#c01").addEventListener("click", (event) => {
    if (event.target.tagName === "BUTTON") {
      const productoId = event.target.dataset.id;
      const productoNombre = event.target.innerText.split(" - ")[0];
      const precio = parseFloat(event.target.innerText.match(/\d+\.?\d*/)[0]);

      agregarProductoAlCarrito({ id: productoId, nombre: productoNombre, precio, cantidad: 1 });
    }
  });

  // Agregar un producto al carrito
  function agregarProductoAlCarrito(producto) {
    const existente = carrito.find((item) => item.id === producto.id);

    if (existente) {
      existente.cantidad += 1; // Incrementar cantidad si ya existe
    } else {
      carrito.push(producto); // Agregar nuevo producto
    }

    renderizarCarrito();
  }

  // Renderizar carrito en c[1][0]
  function renderizarCarrito() {
    const tabla = document.querySelector("#c10");
    tabla.innerHTML = ""; // Limpiar contenido

    if (carrito.length === 0) {
      tabla.innerText = "El carrito está vacío.";
      return;
    }

    // Crear tabla
    const table = document.createElement("table");
    table.innerHTML = `
      <tr>
        <th>Eliminar</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Total</th>
      </tr>
    `;

    let totalCOP = 0;

    carrito.forEach((producto) => {
      const total = producto.cantidad * producto.precio;
      totalCOP += total;

      const row = document.createElement("tr");
      row.innerHTML = `
        <td><input type="checkbox" data-id="${producto.id}"></td>
        <td>${producto.nombre}</td>
        <td><input type="number" value="${producto.cantidad}" data-id="${producto.id}" min="1"></td>
        <td>${producto.precio.toFixed(2)} COP</td>
        <td>${total.toFixed(2)} COP</td>
      `;

      table.appendChild(row);
    });

    tabla.appendChild(table);

    // Calcular totales en otras monedas
    const totalDiv = document.createElement("div");
    const totalUSD = tasas["USD_COP"] ? (totalCOP / tasas["USD_COP"]).toFixed(2) : "N/A";
    const totalVES = tasas["COP_BSS"] ? (totalCOP * tasas["COP_BSS"]).toFixed(2) : "N/A";

    totalDiv.innerHTML = `
      <p>Total COP: ${totalCOP.toFixed(2)}</p>
      <p>Total USD: ${totalUSD}</p>
      <p>Total VES: ${totalVES}</p>
    `;
    tabla.appendChild(totalDiv);
  }

  // Eliminar productos seleccionados
  document.querySelector("#c10").addEventListener("change", (event) => {
    if (event.target.type === "checkbox") {
      const productoId = event.target.dataset.id;
      const index = carrito.findIndex((item) => item.id === productoId);

      if (index > -1) {
        carrito.splice(index, 1); // Eliminar producto
        renderizarCarrito();
      }
    }

    if (event.target.type === "number") {
      const productoId = event.target.dataset.id;
      const producto = carrito.find((item) => item.id === productoId);

      if (producto) {
        producto.cantidad = parseInt(event.target.value, 10);
        renderizarCarrito();
      }
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
