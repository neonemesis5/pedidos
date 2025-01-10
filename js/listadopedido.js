document.addEventListener("DOMContentLoaded", function () {
    // Seleccionar todos los botones de detalle
    const detalleButtons = document.querySelectorAll(".btnDetalle");

    detalleButtons.forEach(button => {
        button.addEventListener("click", function () {
            const pedidoId = this.dataset.id; // Obtener el ID del pedido
            const baseUrl = "/pedidos"; // Ajusta esto según tu proyecto

            // Realizar la solicitud AJAX
            fetch(`${baseUrl}/php/view/detallepedido.php?pedido_id=${pedidoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error al obtener los detalles del pedido");
                    }
                    return response.json();
                })
                .then(data => {
                    const detalleTable = document.querySelector("#detallePedido tbody");
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
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Hubo un error al cargar el detalle del pedido.");
                });
        });
    });
});
