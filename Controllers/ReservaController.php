<?php

use Models\Reserva;
use Controllers\GeneralController;

class ReservaController extends GeneralController
{
    public function guardar($parametros)
    {
        $respuesta = [
            "message" => "No fue posible guardar",
            "success" => "0",
            "data"    => ""
        ];

        if (!empty($parametros['idreserva'])) {
            $respuesta["message"] = "Este registro ya existe no se puede guardar";
            return json_encode($parametros);
        }

        if (!empty($_FILES["anexo"]['tmp_name'])) {
            $nuevaUbicacion = '../assets/img/reservas/' . $_FILES["anexo"]['name'];
            move_uploaded_file($_FILES["anexo"]['tmp_name'], $nuevaUbicacion);
            $parametros["anexo"] = $nuevaUbicacion;
        }

        if (!empty($_FILES["anexo_hab"]['tmp_name'])) {
            $nuevaUbicacion = '../assets/img/reservas/' . $_FILES["anexo_hab"]['name'];
            move_uploaded_file($_FILES["anexo_hab"]['tmp_name'], $nuevaUbicacion);
            $parametros["anexo_hab"] = $nuevaUbicacion;
        }

        $Reserva = new Reserva();
        $Reserva->setatributos(
            $parametros
        );

        if ($id = $Reserva->save()) {
            $respuesta["message"] = "Proceso exitos";
            $respuesta["success"] = 1;
            $respuesta["data"] = $id;

            $Reserva2 = new Reserva($id);

            $cadena = '';
            if (strlen($id) == 1) {
                $cadena = "000$id";
            } else if (strlen($id) == 2) {
                $cadena = "00$id";
            } else if (strlen($id) == 3) {
                $cadena = "0$id";
            } else if (strlen($id) == 4) {
                $cadena = $id;
            }

            $Reserva2->setatributos([
                'cod_reserva' => "STY$cadena"
            ]);
            $Reserva2->update();

            $respuesta["cod"] = "STY$cadena";
        } else {
            $respuesta["message"] = "Se presento un error al momento de guardar";
        }

        return json_encode($respuesta);
    }

    public function actualizar($parametros)
    {
        if (!empty($_FILES["anexo"]['tmp_name'])) {
            $nuevaUbicacion = '../assets/img/reservas/' . $_FILES["anexo"]['name'];
            move_uploaded_file($_FILES["anexo"]['tmp_name'], $nuevaUbicacion);
            $parametros["anexo"] = $nuevaUbicacion;
        }

        if (!empty($_FILES["anexo_hab"]['tmp_name'])) {
            $nuevaUbicacion = '../assets/img/reservas/' . $_FILES["anexo_hab"]['name'];
            move_uploaded_file($_FILES["anexo_hab"]['tmp_name'], $nuevaUbicacion);
            $parametros["anexo_hab"] = $nuevaUbicacion;
        }

        $respuesta = [
            "message" => "No fue posible guardar",
            "success" => "0",
            "data"    => ""
        ];

        if (empty($parametros['idreserva'])) {
            $respuesta["message"] = "No se encuentra el registro para actualizar";
            return json_encode($parametros);
        }

        $Reserva = new Reserva($parametros['idreserva']);
        $Reserva->setatributos(
            $parametros
        );
        
        if ($id = $Reserva->update()) {
            $respuesta["message"] = "Proceso exitoso";
            $respuesta["success"] = 1;
            $respuesta["data"] = $id;
            $respuesta["cod"] = $Reserva->atributos['cod_reserva'];
        } else {
            $respuesta["message"] = "Se presento un error al momento de actualizar";
        }

        return json_encode($respuesta);
    }

    public function cargar($parametros)
    {
        $respuesta = [
            "message" => "No fue posible cargar",
            "success" => "0",
            "data"    => []
        ];

        $Reserva = new Reserva($parametros['idreserva']);
        if ($Reserva->atributos['idreserva']) {
            $respuesta["message"] = 'Carga exitosa';
            $respuesta["success"] = 1;
            $respuesta["data"] = $Reserva->atributos;
        }

        return json_encode($respuesta);
    }

    public function cargarTodos()
    {
        $respuesta = [
            "message" => "No fue posible cargar",
            "success" => "0",
            "data"    => []
        ];

        $Reserva = new Reserva();
        $datos = $Reserva->getTodos();

        if (!empty($datos)) {
            $respuesta["message"] = 'Carga exitosa';
            $respuesta["success"] = 1;
            $respuesta["data"] = $datos;
        }


        return json_encode($respuesta);
    }


    public function enviarCorreoReserva($atributos)
    {
        $respuesta = [
            "message" => "No fue enviar",
            "success" => "0",
            "data"    => []
        ];

        $datos = json_decode($this->cargar($atributos), true);
        $reserva = $datos['data'];

        if (empty($reserva["email"])) {
            $respuesta["message"] = "No se han definido los destinos para enviar el correo";
        }

        $imagenes = [
            [$reserva["anexo"], 'reserva1'],
            [$reserva["anexo_hab"], 'reserva2'],
            ['../assets/img/pdf/air.png', 'logoReserva']
        ];

        $asunto = $this->obtenerAsunto($reserva);
        $contenido = $this->obtenerContenido($reserva);

        $Correo =  new CorreoController();
        $Correo->setDestinos([$reserva["email"]]);
        $Correo->setAsunto($asunto);
        $Correo->setContenido($contenido);
        $Correo->setImagenesCorreo($imagenes);



        $enviado = json_decode($Correo->enviar(), true);

        if ($enviado["success"]) {
            $respuesta["message"] = $enviado["message"];
            $respuesta["success"] = $enviado["success"];
        }

        return json_encode($respuesta);
    }

    function obtenerNombreCompleto($params)
    {
        $nombreCompleto = '';

        if ($params["primer_nombre"]) {
            $nombreCompleto = $params["primer_nombre"];
        }

        if ($params["segundo_nombre"]) {
            $nombreCompleto = $nombreCompleto . " " . $params["segundo_nombre"];
        }

        if ($params["primer_apellido"]) {
            $nombreCompleto = $nombreCompleto . " " . $params["primer_apellido"];
        }

        if ($params["segundo_apellido"]) {
            $nombreCompleto = $nombreCompleto . " " . $params["segundo_apellido"];
        }

        return $nombreCompleto;
    }

    function obtenerAsunto($reserva)
    {
        return "Gracias, " . $this->obtenerNombreCompleto($reserva) . "! Tu reserva en " . $reserva['nombre_hotel'] . " ha sido confirmada.";
    }

    function obtenerContenido($atributos)
    {
        $colorTema = '#14275e';
        $asunto = $this->obtenerAsunto($atributos);

        /*****Variables de la tabla ******/
        $codReserva = $atributos["cod_reserva"];
        $titulo = $atributos["titulo"];
        $ciudad = $atributos["ciudad"];
        $direccion = $atributos["direccion"];
        $telefono = $atributos["telefono"];

        $nombreHotel = $atributos["nombre_hotel"];
        $nombreHabitacion = $atributos["nombre_habitacion"];
        $huespedes = $atributos["huespedes"];
        $tipo = $atributos["tipo_habitacion"];
        $fecha_ingreso = $atributos["fecha_ingreso"];
        $fecha_salida = $atributos["fecha_salida"];
        $precio = $atributos["precio"];
        $moneda = $atributos["moneda"];
        $anexoHotel = $atributos["anexo"];
        $anexoHabitacion = $atributos["anexo_hab"];

        $fi = explode(' ', $fecha_ingreso);
        $fs = explode(' ', $fecha_salida);

        $date1 = new DateTime($fi[0]);
        $date2 = new DateTime($fs[0]);
        $diff = $date1->diff($date2);
        $dias = $diff->days;

        if ($dias <= 0) {
            $dias = 1;
        }

        if (empty($precio) || empty($dias)) {
            print_r(".No se puede generar el archivo verifique que el formulario contenga todos los campos obligatorios");
            die();
        }

        $total = ($precio * $dias);
        /*****Fin Variables de la tabla ******/

        $body = <<<HTML
        <br>
        <table style="width: 100%; border: 0;">
            <tr>
                <td colspan="2" style="color: black;">
                    <ul>
                        <li>Tu código de reserva es: <span style="font-weight:bold; color: black;">$codReserva</span></li>
                        <li>Recuerda presentar tu reserva o el código de la misma al momento de que llegues a tu alojamiento.</li>
                        <li>$nombreHabitacion te espera</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <img style="width: 100%; min-height: 10rem; max-height: 10rem; display:block; margin: 1.5% 3%" src="cid:reserva1">
                </td>
                <td style="width: 50%;">
                    <img style="width: 100%; min-height: 10rem; max-height: 10rem; display:block; margin: 1.5% 3%" src="cid:reserva2">
                </td>
            </tr>
        </table>
        <br>
        <table style="border-collapse: collapse; width: 100%; border: 1px solid #b4b7ae;">
            <tr>
                <td colspan="2" style="border: 1px solid #b4b7ae; text-align: left; height: 11mm; font-weight:bold; background-color: $colorTema; color: white;" >Resumen de la Reserva</td>
            </tr>
            <tr>
                <td style="color: black; border: 1px solid #b4b7ae; height: 11mm;">Nombre de la habitación</td>
                <td style="border: 1px solid #b4b7ae">$nombreHabitacion</td>
            </tr>
            <tr>
                <td style="color: black; border: 1px solid #b4b7ae; height: 11mm;">Check-in</td>
                <td style="border: 1px solid #b4b7ae">$fi[0]<br>($fi[1])</td>
            </tr>
            <tr>
                <td style="color: black; border: 1px solid #b4b7ae; height: 11mm;">Check-out</td>
                <td style="border: 1px solid #b4b7ae">$fs[0]<br>($fs[1])</td>
            </tr>
            <tr>
                <td style="color: black; border: 1px solid #b4b7ae; height: 11mm;">Tipo</td>
                <td style="border: 1px solid #b4b7ae">$tipo</td>
            </tr>
            <tr>
                <td style="color: black; border: 1px solid #b4b7ae; height: 11mm;">Huésped(es)</td>
                <td style="border: 1px solid #b4b7ae">$huespedes</td>
            </tr>
            <tr>
                <td style="color: black; border: 1px solid #b4b7ae; height: 11mm;">Ubicación</td>
                <td style="border: 1px solid #b4b7ae">$direccion</td>
            </tr>
            <tr>
                <td style="color: black; border: 1px solid #b4b7ae; height: 11mm;">Teléfono</td>
                <td style="border: 1px solid #b4b7ae">$telefono</td>
            </tr>
            <tr>
                <td style="color: black; border: 1px solid #b4b7ae; height: 11mm;">Condiciones de Cancelación</td>
                <td style="color: red; border: 1px solid #b4b7ae">Ten en cuenta que si cancelas o no te presentas, se te cobrará el precio total de la reserva.</td>
            </tr>
        </table>
        <p></p>
        <table style="border-collapse: collapse; width: 100%; border: 1px solid #b4b7ae;">
            <tr>
                <td colspan="3" style="border: 1px solid #b4b7ae; text-align: left; height: 11mm; font-weight:bold; background-color: $colorTema; color: white;" >Información del pago</td>
            </tr>
            <tr>
                <td style="border: 1px solid #b4b7ae; height: 11mm;" width="30%">Habitación $tipo</td>
                <td style="border: 1px solid #b4b7ae; text-align: center;" width="30%">1</td>
                <td style="border: 1px solid #b4b7ae; text-align: right; font-size:11px" width="40%">
                    $moneda$precio
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #b4b7ae; height: 11mm;">Días</td>
                <td style="border: 1px solid #b4b7ae; text-align: center;">$dias</td>
                <td style="border: 1px solid #b4b7ae; text-align: right;">*$dias</td>
            </tr>
            <tr>
                <td style="border: 1px solid #b4b7ae; height: 11mm;">Huésped(es)</td>
                <td style="border: 1px solid #b4b7ae; text-align: center;">$huespedes</td>
                <td style="border: 1px solid #b4b7ae; text-align: right;">-</td>
            </tr>
            <tr>
                <td style="border: 1px solid #b4b7ae; height: 11mm;">Total</td>
                <td style="border: 1px solid #b4b7ae"></td>
                <td style="border: 1px solid #b4b7ae; text-align: right; font-size:12px">$moneda$total</td>
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid #b4b7ae; height: 2mm;">Se ha realizado esta reserva por pago con <span style="font-weight:bold">Tarjeta de Credito</span></td>
            </tr>
        </table>
        <br>
        <table style="width: 100%; border: 0;">
            <tr>
                <td colspan="2" style="font-weight:bold; color: black;" >Información importante</td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    Cuando haces una Reserva, estás aceptando las condiciones aplicables que se muestran durante el proceso de reserva. Encontrarás las condiciones de cancelación de los Proveedores de servicios, así como cualesquiera otras condiciones (requisitos de edad, depósitos por daños/seguridad, suplementos adicionales para Reservas de grupo, camas extra, desayuno, mascotas, tarjetas aceptadas, etc.), en nuestra Plataforma: en la página de información del Proveedor de servicios, durante el proceso de reserva, en la sección "A tener en cuenta" y/o en el e-mail de confirmación o billete (si procede).
                </td>
            </tr>
        </table>
        <br>
        <table style="width: 100%; border: 0;">
            <tr>
                <td colspan="2" style="font-weight:bold; color: black;" >Hacer cambios en la reserva</td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    Es muy sencillo cambiar o cancelar tu reserva. Recuerda que según el tipo de reserva que hagas, pueden existir cargos de cancelación.
                </td>
            </tr>
        </table>
        <br>
        <table style="width: 100%; border: 0;">
            <tr>
                <td colspan="2" style="font-weight:bold; color: black;" >Ponerse en contacto con el alojamiento</td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    ¿Tienes alguna duda? Comunicate con tu asesor especializado para resolver cualquier inquietud.
                </td>
            </tr>
        </table>
        <br>
        HTML;




        $footer = <<<HTML
            <H3>AirStay</H3>
            <p>Al ponerte en contacto con el alojamiento que has reservado, estás aceptando cómo procesamos las comunicaciones según nuestra politica de privacidad.</P>
        HTML;

        $contenido = <<<HTML
        <table style="font-family:Helvetica,serif;font-size:12px;font-style:normal;font-variant-caps:normal;font-weight:normal;letter-spacing:normal;text-align:start;text-indent:0;text-transform:none;white-space:normal;word-spacing:0;background-color:white;text-decoration:none;border:0;border-collapse:collapse;border-spacing:0;width: 100%">

        <tbody>
            <tr>
                <td style="display:block;text-align: center"> 
                <table style="width:100%;font-family:Helvetica,serif;font-style:normal;font-variant-caps:normal;font-weight:normal;letter-spacing:normal;text-align:start;text-indent:0;text-transform:none;white-space:normal;word-spacing:0;text-decoration:none;border:0;border-collapse:collapse;border-spacing:0">
                        <tr>
                            <td style="color:white; background-color:$colorTema; padding-left:30px; padding-right:30px; padding-top:10px; padding-bottom:10px">
                                <h2>$asunto</h2>
                            </td>
                        </tr>
                    </table> 
                    <table style="width:100%;font-family:Helvetica,serif;font-style:normal;color:#555;font-variant-caps:normal;font-weight:normal;letter-spacing:normal;text-align:start;text-indent:0;text-transform:none;white-space:normal;word-spacing:0;text-decoration:none;border:0;border-collapse:collapse;border-spacing:0">
                        <tr>
                            <td style="width:10%;"></td>
                            <td style="width:80%; font-size:14px; background:white;">
                                $body
                            </td>
                            <td style="width:10%;"></td>
                        </tr>
                    </table>
                            
                    <table style="width:100%;font-family:Helvetica,serif;font-style:normal;font-variant-caps:normal;font-weight:normal;letter-spacing:normal;text-align:start;text-indent:0;text-transform:none;white-space:normal;word-spacing:0;text-decoration:none;border:0;border-collapse:collapse;border-spacing:0">
                        <tr>
                        <td style="width:20%; background-color:$colorTema; text-align:center">
                                <div style="position:relative;padding-bottom:2px;padding-right:15px;">
                                    <img style="min-height:10rem; max-height:10rem; display:block; margin: 1.5% 3%" src="cid:logoReserva">
                                </div>    
                            </td>
                            <td style="width:80%; padding-left:32px; color:white; background-color:$colorTema; padding-top:12px; padding-bottom:12px">
                                <p style="font-size:11px">$footer</p>
                            </td>                    
                        </tr>
                    </table> 
                </td>
            </tr>
        </tbody>
        </table>

        HTML;

        return $contenido;
    }
}
