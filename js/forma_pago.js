document.addEventListener("DOMContentLoaded", () => {
    const formaPagoForm = document.querySelector("#formaPagoForm");
    const pagoInputs = document.querySelectorAll(".pago-input");
    const pendienteCobrar = document.querySelector("#pendienteCobrar");
    const valeEmpleados = document.querySelector("#valeEmpleados");
    const organismos = document.querySelector("#organismos");
    const pagoRecibidoBtn = document.querySelector("#pagoRecibido");

    const totalCompraCOP = document.querySelector("#totalCompraCOP");
    const totalCompraUSD = document.querySelector("#totalCompraUSD");
    const totalCompraVES = document.querySelector("#totalCompraVES");

    const pagadoCOP = document.querySelector("#pagadoCOP");
    const pagadoUSD = document.querySelector("#pagadoUSD");
    const pagadoVES = document.querySelector("#pagadoVES");

    const porPagarCOP = document.querySelector("#porPagarCOP");
    const porPagarUSD = document.querySelector("#porPagarUSD");
    const porPagarVES = document.querySelector("#porPagarVES");

    // Carga las tasas desde el servidor
    let tasas = {};
    fetch("tasas.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(tasa => {
                tasas[tasa.nombre] = parseFloat(tasa.monto);
            });
            console.log("Tasas cargadas:", tasas);
        })
        .catch(error => console.error("Error cargando tasas:", error));

    // Recalcula totales
    function recalcularTotales() {
        let totalPagadoCOP = 0;
        let totalPagadoUSD = 0;
        let totalPagadoVES = 0;

        // Sumar los valores ingresados en cada columna
        pagoInputs.forEach(input => {
            const moneda = input.dataset.moneda;
            const monto = parseFloat(input.value) || 0;

            if (moneda === "COP") totalPagadoCOP += monto;
            if (moneda === "USD") totalPagadoUSD += monto;
            if (moneda === "BSS") totalPagadoVES += monto;
        });

        // Convertir a las otras monedas y actualizar "PAGADO"
        const pagadoCOPValue = totalPagadoCOP + (totalPagadoUSD * tasas["USD_COP"] || 0) + (totalPagadoVES / tasas["COP_BSS"] || 0);
        const pagadoUSDValue = totalPagadoUSD + (totalPagadoCOP / (tasas["USD_COP"] || 1)) + (totalPagadoVES / (tasas["USD_BSS"] || 1));
        const pagadoVESValue = totalPagadoVES + (totalPagadoCOP * (tasas["COP_BSS"] || 1)) + (totalPagadoUSD * (tasas["USD_BSS"] || 1));

        pagadoCOP.textContent = pagadoCOPValue.toFixed(2);
        pagadoUSD.textContent = pagadoUSDValue.toFixed(2);
        pagadoVES.textContent = pagadoVESValue.toFixed(2);

        // Actualizar "POR PAGAR"
        const compraCOP = parseFloat(totalCompraCOP.textContent);
        const compraUSD = parseFloat(totalCompraUSD.textContent);
        const compraVES = parseFloat(totalCompraVES.textContent);

        porPagarCOP.textContent = (compraCOP - pagadoCOPValue).toFixed(2);
        porPagarUSD.textContent = (compraUSD - pagadoUSDValue).toFixed(2);
        porPagarVES.textContent = (compraVES - pagadoVESValue).toFixed(2);

        // Habilitar botón si no hay saldo pendiente
        const saldoPendiente =
            parseFloat(porPagarCOP.textContent) > 0 ||
            parseFloat(porPagarUSD.textContent) > 0 ||
            parseFloat(porPagarVES.textContent) > 0;

        pagoRecibidoBtn.disabled = saldoPendiente && !pendienteCobrar.checked && !valeEmpleados.checked && !organismos.checked;
    }

    // Eventos para recalcular
    pagoInputs.forEach(input => input.addEventListener("input", recalcularTotales));
    pendienteCobrar.addEventListener("change", recalcularTotales);
    valeEmpleados.addEventListener("change", recalcularTotales);
    organismos.addEventListener("change", recalcularTotales);

    // Registro del pago
    pagoRecibidoBtn.addEventListener("click", () => {
        const data = {
            pedido_id: pedidoId,
            pagos: Array.from(pagoInputs).map(input => ({
                forma_pago_id: parseInt(input.dataset.formaId),
                moneda: input.dataset.moneda,
                monto: parseFloat(input.value) || 0,
            })),
            pendienteCobrar: pendienteCobrar.checked,
            valeEmpleados: valeEmpleados.checked,
            organismos: organismos.checked,
        };
    
        console.log("Datos enviados:", data); // Verificar los datos que se están enviando
    
        fetch("registrar_pago.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        })
            .then(response => response.json())
            .then(result => {
                // console.log(result);
                if (result.success) {
                    alert("Pago registrado correctamente");
                    window.location.href = "../../index.php";

                } else {
                    alert("Error al registrar el pago: " + result.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Hubo un error al procesar el pago.");
            });
    });
});
