document.addEventListener("DOMContentLoaded", function () {
    // Seleccionar todos los botones de detalle 
    const detalleButtons = document.querySelectorAll(".btnDetalle");

    detalleButtons.forEach(button => {
        button.addEventListener("click", function () {
            const factcompra_id = this.dataset.id; // Obtener el ID de la factura de compra
            const baseUrl = "/pedidos"; // Ajusta esto según tu proyecto

            // Obtener el estado directamente de la tabla de pedidos
            const status = this.closest("tr").querySelector("td:nth-child(7)").textContent.trim();

            // Realizar la solicitud AJAX para obtener los detalles del pedido
            fetch(`${baseUrl}/php/view/detallecompra.php?factcompra_id=${factcompra_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    const detalleTable = document.querySelector("#detalleCompra tbody");
                    detalleTable.innerHTML = ""; // Limpiar contenido anterior

                    if (!data || data.length === 0) {
                        detalleTable.innerHTML = "<tr><td colspan='5'>No hay detalles disponibles.</td></tr>";
                        document.querySelector("#detalleCompra").style.visibility = "visible";
                        return;
                    }

                    // Renderizar los detalles en la tabla
                    data.forEach(detalle => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${detalle.id}</td>
                            <td>${detalle.producto_nombre}</td>
                            <td>${detalle.qty}</td>
                            <td>${detalle.precioc}</td>
                            <td>${(detalle.qty * detalle.precioc).toFixed(2)}</td>
                        `;
                        detalleTable.appendChild(row);
                    });

                    document.querySelector("#detalleCompra").style.visibility = "visible";
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Hubo un error al cargar el detalle del pedido.");
                });
        });
    });
});
function compras() {
    // Redirige al logout.php para cerrar sesión
    window.location.href = "compras.php";
  }
function logout() {
    // Redirige al logout.php para cerrar sesión
    window.location.href = "logout.php";
  }