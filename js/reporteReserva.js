$(function () {

    $.ajax({
        url: `Controllers/ControlController.php`,
        async: false,
        type: 'GET',
        dataType: 'json',
        data: {
            metodo: 'cargarTodos',
            clase: "ReservaController"
        },
        success: response => {
            if (response.success == 1) {
                let atributos = response.data;

                atributos.forEach(fila => {
                    $('#dataTable tbody').append(`
                        <tr>
                            <td>${fila['cod_reserva']}</td>
                            <td>${fila['fecha_ingreso']}</td>
                            <td>${fila['primer_nombre']}</td>
                            <td>${fila['email']}</td>   
                            <td>${fila['ciudad']}</td>
                            <td>${fila['nombre_hotel']}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm mb-2 accion" data-id="${fila['idreserva']}" data-tipo="editar">Editar</button><br>
                                <a href="Utilities/crearPDF.php?idreserva=${fila['idreserva']}" target="_blank" class="btn btn-primary btn-sm mb-2" data-id="${fila['idreserva']}" data-tipo="generar">Generar</a><br>
                                <button href="#" class="btn btn-danger btn-sm mb-2 accion" data-id="${fila['idreserva']}" data-tipo="correo">Correo</button>
                            </td>
                        </tr>
                    `);
                });

                $('#dataTable').DataTable({
                    language: {
                        url: 'Utilities/es-ES.json'
                    },
                });


            }
        }
    });

    $(document).on("click", ".accion", function (e) {
        let id = $(this).data('id');
        let tipo = $(this).data('tipo');

        if (tipo == 'editar') {
            top.$("#mainScreen").attr('data-src', `reserva.html?idreserva=${id}`);
            top.$("#mainScreen").attr("src", `reserva.html?idreserva=${id}`);
        } else if (tipo == 'correo') {
            enviarCorreo(id);
        }

    });

    function enviarCorreo(id) {
        toastr.warning("Espere mientras se envia el correo...");

        $.ajax({
            url: `Controllers/ControlController.php`,
            type: 'GET',
            dataType: 'json',
            data: {
                idreserva: id,
                metodo: 'enviarCorreoReserva',
                clase: "ReservaController"
            },
            success: response => {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error("No fue posible enviar el correo");
                }
            }
        });
    }

});
