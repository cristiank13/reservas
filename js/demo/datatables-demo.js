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
                                <button type="button" class="btn btn-primary btn-sm mb-2 accion" data-id="${fila['idreserva']}" data-tipo="editar">Editar</button><br>
                                <a href="Utilities/crearPDF.php?idreserva=${fila['idreserva']}" target="_blank" class="btn btn-primary btn-sm mb-2" data-id="${fila['idreserva']}" data-tipo="generar">Generar</a><br>
                            </td>
                        </tr>
                    `);
                });

                $('#dataTable').DataTable();

            }
        }
    });

    $(document).on("click", ".accion", function (e) {
        let id = $(this).data('id');
        let tipo = $(this).data('tipo');

        console.log(id, tipo);

        if (tipo == 'editar') {
            console.log("entra");
            top.$("#mainScreen").attr('data-src', `reserva.html?idreserva=${id}`);
            top.$("#mainScreen").attr("src", `reserva.html?idreserva=${id}`);
        }

    });

});
