document.addEventListener("DOMContentLoaded", function () {
    // Seleccionar todos los botones de detalle 
    const detalleButtons = document.querySelectorAll(".btnDetalle");

    detalleButtons.forEach(button => {
        button.addEventListener("click", function () {
            const pedidoId = this.dataset.id; // Obtener el ID del pedido
            const baseUrl = "/pedidos"; // Ajusta esto según tu proyecto

            // Obtener el estado directamente de la tabla de pedidos
            const status = this.closest("tr").querySelector("td:nth-child(4)").textContent.trim();

            // Realizar la solicitud AJAX para obtener los detalles del pedido
            fetch(`${baseUrl}/php/view/detallepedido.php?pedido_id=${pedidoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error al obtener los detalles del pedido");
                    }
                    return response.json();
                })
                .then(data => {
                    const detalleTable = document.querySelector("#detallePedido tbody");
                    const realizarPagoButton = document.querySelector("#realizarPagoButton"); // Seleccionar el botón
                    detalleTable.innerHTML = ""; // Limpiar contenido anterior

                    // Verificar si la respuesta es un objeto o un array
                    if (data && !Array.isArray(data)) {
                        data = [data]; // Convierte un objeto en array para procesarlo
                    }

                    if (data.length > 0) {
                        data.forEach(detalle => {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${detalle.nombre}</td>
                                <td>${detalle.preciov}</td>
                                <td>${detalle.qty}</td>
                                <td>${detalle.status}</td>
                            `;
                            detalleTable.appendChild(row);
                        });
                        document.querySelector("#detallePedido").style.visibility = "visible";
                    } else {
                        detalleTable.innerHTML = "<tr><td colspan='4'>No hay detalles disponibles.</td></tr>";
                        document.querySelector("#detallePedido").style.visibility = "visible";
                    }

                    // Mostrar el botón "Realizar Pago" si el estado del pedido es 'P'
                    if (status === "P") {
                        realizarPagoButton.style.display = "block"; // Mostrar el botón
                        realizarPagoButton.href = `${baseUrl}/php/view/forma_pago.php?pedido_id=${pedidoId}`; // Establecer el enlace dinámicamente
                    } else {
                        realizarPagoButton.style.display = "none"; // Ocultar el botón si el estado no es 'P'
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Hubo un error al cargar el detalle del pedido.");
                });
        });
    });
});
