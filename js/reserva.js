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
        $('#fecha_inicio_div').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "YYYY-MM-DD HH:mm",
        });

        $('#fecha_salida_div').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "YYYY-MM-DD HH:mm",
        });

        /*document.querySelector('.custom-file-input').addEventListener('change',function(e){
            var fileName = document.getElementById("myInput").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = fileName
          })*/

        let idreserva = getUrlParameter('idreserva');

        if (idreserva) {
            llenarReserva(idreserva);
        }
    }

    $(document).on("change", "#anexo", function (e) {
        let rutaArchivo = $(this).val();
        $(".anexo-hotel").text(rutaArchivo);     
    });

    $(document).on("change", "#anexo_hab", function (e) {
        let rutaArchivo = $(this).val();
        $(".anexo-habitacion").text(rutaArchivo);     
    });
    
    $(document).on("click", "#btnGuardar", function (e) {
        e.preventDefault();
        envioFormulario(false);
        
    });


    //Se activa con el boton btnGenerar
    $("#formReserva").validate({
        ignore: [],
        submitHandler: function (form) {

            envioFormulario(true);
            return true;

        },
        invalidHandler: function () {
            return false;
        }
    });

    function envioFormulario(continuar) {

        let metodo = 'guardar';
        let formulario = $("#formReserva")[0];
        let datos = new FormData(formulario);

        if ($("#idreserva").val()) {
            metodo = "actualizar";
        }

        datos.append('metodo', metodo);
        datos.append('clase', 'ReservaController');

        $.ajax({
            url: `Controllers/ControlController.php`,
            async: false,
            type: 'POST',
            dataType: 'json',
            data: datos,
            contentType: false,
            processData: false,
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
                        if (elemento.hasClass('form-control') || elemento.hasClass('form-control2')) {
                            elemento.val(atributos[llave]);
                        }

                        if (elemento.hasClass('custom-file-input') && llave == 'anexo') {
                            $(".anexo-hotel").text(atributos[llave]);
                        }

                        if (elemento.hasClass('custom-file-input') && llave == 'anexo_hab') {
                            $(".anexo-habitacion").text(atributos[llave]);
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
