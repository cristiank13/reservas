$(function () {

    $("#formLogin").validate({
        ignore: [],
        submitHandler: function (form) {

            let data = $(form).serialize();
            let continua = false;

            data =
                data +
                '&' +
                $.param({
                    metodo: 'validarAcceso',
                    clase: "LoginController"
                });

            $.ajax({
                url: `Controllers/ControlController.php`,
                async: false,
                type: 'POST',
                dataType: 'json',
                data,
                success: response => {
                    console.log(response);
                    if (response.success == 1) {
                        toastr.success(response.message);
                        $("#idreserva").val(response.data); 
                        continua = true;
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
            return continua;

        },
        invalidHandler: function () {
            return false;
        }
    });

});