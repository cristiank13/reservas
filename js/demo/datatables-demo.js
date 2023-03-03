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
                            <td>${fila['idreserva']}</td>
                            <td>${fila['fecha']}</td>
                            <td>${fila['primer_nombre']}</td>
                            <td>${fila['email']}</td>   
                            <td>${fila['ciudad']}</td>
                            <td>${fila['nombre_hotel']}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm mb-2 accion" data-id="${fila['idreserva']}" data-tipo="editar">Editar</button><br>
                                <button type="button" class="btn btn-primary btn-sm mb-2 accion" data-id="${fila['idreserva']}" data-tipo="generar">Generar</button><br>
                                <button type="button" class="btn btn-primary btn-sm mb-2 accion" data-id="${fila['idreserva']}" data-tipo="correo">Correo</button>
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

        if (tipo == 'editar') {
            $("#mainScreen").load(`reserva.html?idreserva=${id}`);
            return true;
        }
        
    });

});
