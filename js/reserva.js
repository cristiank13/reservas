$(function () {
    var getUrlParameter = function getUrlParameter(sParam) {
        var main = top.$("#mainScreen").attr('data-src');
        if (main === undefined || main == '') {
            return false;
        } else {
            var param = main.split('?');
            var sURLVariables = param[1].split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        }
    };

    init ();


    function init () {
        validarSesion();
        $('#fecha_inicio').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "YYYY-MM-DD HH:mm",
        });

        $('#fecha_salida').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "YYYY-MM-DD HH:mm",
        });

        let idreserva = getUrlParameter('idreserva');

        if (idreserva) {
            llenarReserva(idreserva);
        }
    }
    
    $(document).on("click", "#btnGuardar", function (e) {
        e.preventDefault();
        envioFormulario(false);
        
    });


    //Se activa con el boton btnGenerar
    $("#formReserva").validate({
        ignore: [],
        submitHandler: function (form) {
            /*if ($("[name=fecha_ingreso]").val() == $("[name=fecha_salida]").val()) {
                toastr.error('La fecha de ingreso y salida no pueden ser iguales');
                return false;
            }*/
            envioFormulario(true);
            return true;

        },
        invalidHandler: function () {
            return false;
        }
    });

    function envioFormulario(continuar) {
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
                    return continuar;
                } else {
                    toastr.error(response.message);
                    return continuar;
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

    function validarSesion() {
        $.ajax({
            url: `Controllers/ControlController.php`,
            async: false,
            type: 'POST',
            dataType: 'json',
            data: {
                metodo: 'validarSesionActiva',
                clase: "LoginController"
            },
            success: response => {
                if (response.success == 0) {
                    toastr.error(response.message);
                    top.window.location.href = "login.html";
                }
            }
        });
    }


});
