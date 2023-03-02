$(function () {

    /*$(document).on('click', '#btnGenerar', function (e) {
        //e.preventDefault();

        let data = $("#formReserva").serialize();

        data =
            data +
            '&' +
            $.param({
                metodo: "guardar",
                clase: "ReservaController"
            });

        $.ajax({
            url: `Controllers/ControlController.php`,
            async: false,
            type: 'GET',
            dataType: 'json',
            data,
            success: response => {
                console.log(response);
                
            }
        });



        //$("#formReserva").submit();

        //return false;

    });*/

    $("#formReserva").validate({
        ignore: [],
        submitHandler: function (form) {
            let data = $("#formReserva").serialize();

            data =
                data +
                '&' +
                $.param({
                    metodo: "guardar",
                    clase: "ReservaController"
                });
    
            $.ajax({
                url: `Controllers/ControlController.php`,
                async: false,
                type: 'GET',
                dataType: 'json',
                data,
                success: response => {
                    console.log(response);
                    
                }
            });
        },
        invalidHandler: function () {
            $("#save_document").show();
            $("#boton_enviando").remove();
        }
    });


});
