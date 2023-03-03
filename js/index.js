$(function () {

    var queryString = window.location.search;
    var params = {};

    // Obtener la URL de la página actual
    var url = window.location.href;
    console.log(url);


    console.log(queryString);
    queryString = queryString.substring(1);

    
    queryString.split("&").forEach(function(part) {
        let item = part.split("=");
        params[item[0]] = decodeURIComponent(item[1]);
    });

    //console.log(params);
    
    llenarReserva(8);


    $(document).on("click", "#btnGuardar", function (e) {
        e.preventDefault();
        envioFormulario();
    });

    $(document).on("click", "#btnReporte", function (e) {
        $("#mainScreen").load(`reporteReserva.html`);
    });

    //Se activa con el boton btnGenerar
    $("#formReserva").validate({
        ignore: [],
        submitHandler: function (form) {
            envioFormulario();
            return true;

        },
        invalidHandler: function () {
            return false;
        }
    });

    function envioFormulario() {
        let data = $("#formReserva").serialize();
        let metodo = 'guardar';

        if ($("#idreserva").val()) {
            metodo = "actualizar";
        }

        data =
            data +
            '&' +
            $.param({
                metodo: metodo,
                clase: "ReservaController"
            });

        $.ajax({
            url: `Controllers/ControlController.php`,
            async: false,
            type: 'POST',
            dataType: 'json',
            data,
            success: response => {
                if (response.success == 1) {
                    toastr.success(response.message);
                    $("#idreserva").val(response.data);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    }


    function llenarReserva(idreserva) {
        $.ajax({
            url: `Controllers/ControlController.php`,
            async: false,
            type: 'GET',
            dataType: 'json',
            data: {
                metodo: 'cargar',
                clase: "ReservaController",
                idreserva
            },
            success: response => {
                if (response.success == 1) {
                    let atributos = response.data;

                    for (let llave in atributos) {
                        let elemento = $(`#${llave}`);
                        if (elemento.hasClass('form-control')) {
                            elemento.val(atributos[llave]);
                        }

                        elemento = $(`[name=${llave}]`);

                        if (elemento.hasClass('form-check-input')) {
                            $(`input[type="radio"][name="${llave}"]`).filter(function () {
                                return $(this).next().text().trim() === atributos[llave];
                            }).prop('checked', true);
                        }
                    }
                }
            }
        });
    }


});
