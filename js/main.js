document.addEventListener("DOMContentLoaded", () => {
  const carrito = []; // Array para almacenar los productos seleccionados
  let tasas = {}; // Objeto para almacenar las tasas dinámicas
  let productoAsadoSeleccionado = null; // Producto actual de tipo Asados

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
        data.forEach((tasa) => {
          tasas[tasa.nombre] = parseFloat(tasa.monto);
        });
        console.log("Tasas cargadas:", tasas);
      })
      .catch((error) => console.error("Error al cargar tasas:", error));
  }

  // Cargar tasas al inicio
  cargarTasas();

  // Cargar tipos de productos en c[0][0]
  loadContent("php/view/tiposproductos.php", "#c00");

  // Manejar clics en los botones de tipos de productos
  document.querySelector("#c00").addEventListener("click", (event) => {
    if (event.target.tagName === "BUTTON") {
      const tipoProductoId = event.target.dataset.id; // Aseguramos que `data-id` está presente
      loadContent(`php/view/productos.php?tipoProductoId=${tipoProductoId}`, "#c01");

      // Mostrar/ocultar el div de asados según el tipo de producto
      const asadoInputs = document.querySelector("#asadoInputs");
      if (asadoInputs) {
        if (tipoProductoId === "200") {
          asadoInputs.style.display = "block";
          productoAsadoSeleccionado = null; // Limpiar selección previa
        } else {
          asadoInputs.style.display = "none";
        }
      }
    }
  });

  // Manejar clics en los productos
  document.querySelector("#c01").addEventListener("click", (event) => {
    if (event.target.tagName === "BUTTON") {
      const tipoProductoId = event.target.dataset.tipo; // Este atributo debe estar configurado en los botones
      const productoId = event.target.dataset.id;
      const productoNombre = event.target.innerText.split(" - ")[0];
      const precio = parseFloat(event.target.dataset.precio);

      if (tipoProductoId === "200") {
        // Producto tipo Asados
        productoAsadoSeleccionado = { id: productoId, nombre: productoNombre, precio };
        const gramosInput = document.querySelector("#gramosInput");
        if (gramosInput) {
          gramosInput.value = ""; // Limpiar input de gramos
        }
      } else {
        // Producto normal
        agregarProductoAlCarrito({ id: productoId, nombre: productoNombre, precio, cantidad: 1 });
      }
    }
  });

  // Agregar producto tipo Asados al carrito
  const addAsadoButton = document.querySelector("#addAsadoButton");
  if (addAsadoButton) {
    addAsadoButton.addEventListener("click", () => {
      const gramosInput = document.querySelector("#gramosInput");
      if (!gramosInput) {
        console.error("No se encontró el input de gramos.");
        return;
      }

      const gramos = parseInt(gramosInput.value, 10);

      if (!productoAsadoSeleccionado || isNaN(gramos) || gramos <= 0) {
        alert("Por favor, seleccione un producto y un valor válido en gramos.");
        return;
      }

      agregarProductoAlCarrito({
        id: productoAsadoSeleccionado.id,
        nombre: `${productoAsadoSeleccionado.nombre} (${gramos}g)`,
        precio: (productoAsadoSeleccionado.precio * gramos) / 1000,
        cantidad: gramos,
      });

      // Ocultar inputs después de agregar
      const asadoInputs = document.querySelector("#asadoInputs");
      if (asadoInputs) {
        asadoInputs.style.display = "none";
      }
      productoAsadoSeleccionado = null;
    });
  }

  // Agregar un producto al carrito
  function agregarProductoAlCarrito(producto) {
    const existente = carrito.find((item) => item.id === producto.id);

    if (existente) {
      existente.cantidad += producto.cantidad; // Incrementar cantidad si ya existe
    } else {
      carrito.push(producto); // Agregar nuevo producto
    }

    renderizarCarrito();
  }

  // Renderizar carrito en c[1][0]
  function renderizarCarrito() {
    const tabla = document.querySelector("#c10");
    if (!tabla) {
      console.error("No se encontró la tabla del carrito.");
      return;
    }

    tabla.innerHTML = ""; // Limpiar contenido

    if (carrito.length === 0) {
      tabla.innerText = "El carrito está vacío.";
      return;
    }

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
        <td>${producto.cantidad}</td>
        <td>${producto.precio.toFixed(2)} COP</td>
        <td>${total.toFixed(2)} COP</td>
      `;

      table.appendChild(row);
    });

    tabla.appendChild(table);

    const totalDiv = document.createElement("div");
    totalDiv.innerHTML = `
      <p>Total COP: ${totalCOP.toFixed(2)}</p>
      <p>Total USD: ${tasas["USD_COP"] ? (totalCOP / tasas["USD_COP"]).toFixed(2) : "N/A"}</p>
      <p>Total VES: ${tasas["COP_BSS"] ? (totalCOP * tasas["COP_BSS"]).toFixed(2) : "N/A"}</p>
    `;
    tabla.appendChild(totalDiv);
  }

  // Función para cargar contenido dinámico
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
