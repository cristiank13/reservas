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
    }

    $(document).on("click", "#btnReporte", function (e) {
        $("#mainScreen").attr("data-src", '');
        $("#mainScreen").attr("src", `reporteReserva.html`);
        
    });

    $(document).on("click", "#btnCrearReserva", function (e) {
        $("#mainScreen").attr("data-src", '');
        $("#mainScreen").attr("src", `reserva.html`);
    });

    $(document).on("click", "#btnSalir", function (e) {
        $.ajax({
            url: `Controllers/ControlController.php`,
            async: false,
            type: 'POST',
            dataType: 'json',
            data: {
                metodo: 'cerrarSesion',
                clase: "LoginController"
            },
            success: response => {
                if (response.success) {
                    toastr.error(response.message);
                    top.window.location.href = "login.html";
                }
            }
        });
    });



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
                console.log(response.success);
                if (response.success == 0) {
                    console.log("salir");
                    toastr.error(response.message);
                    top.window.location.href = "login.html";
                }
            }
        });
    }


});
