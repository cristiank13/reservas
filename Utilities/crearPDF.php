<?php

use Models\Reserva;

include_once('../Models/Reserva.php');
require_once('TCPDF/examples/tcpdf_include.php');

const PDF_AGENCIA = '../../../../assets/img/pdf/tcpdf_logo.jpg';

const FONT_NAME_HEAD = 'helvetica';

const FONT_SIZE_HEAD = '12';

try {
    if (!empty($_POST["idreserva"])) {
        $Reserva = new Reserva($_POST["idreserva"]);
        $params = $Reserva->atributos;
    } else if (!empty($_REQUEST["idreserva"])) {
        $Reserva = new Reserva($_REQUEST["idreserva"]);
        $params = $Reserva->atributos;
    }

    if (empty($params)) {
        print_r("Acceso no permitido");
        die();
    }

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

    $codReserva = $params["cod_reserva"];
    $titulo = $params["titulo"];
    $ciudad = $params["ciudad"];
    $direccion = $params["direccion"];
    $telefono = $params["telefono"];

    $nombreHotel = $params["nombre_hotel"];
    $nombreHabitacion = $params["nombre_habitacion"];
    $huespedes = $params["huespedes"];
    $tipo = $params["tipo_habitacion"];
    $fecha_ingreso = $params["fecha_ingreso"];
    $fecha_salida = $params["fecha_salida"];
    $precio = $params["precio"];
    $moneda = $params["moneda"];
    $anexoHotel = $params["anexo"];
    $anexoHabitacion = $params["anexo_hab"];

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
} catch (Exception $e) {
    print_r("..No se puede generar el archivo verifique que el formulario contenga todos los campos obligatorios");
    die();
}


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
/*$pdf->setAuthor('Nicola Asuni');
$pdf->setTitle('TCPDF Example 001');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');*/

// set default header data
//$pdf->setHeaderData(PDF_AGENCIA, 100, 'Factura generada', '', array(0,0,0), array(0,64,128));
$espacios = '';

for ($i = 0; $i < 60; $i++) {
    $espacios = $espacios . " ";
}

$espaciosCod = '';

for ($i = 0; $i < 65; $i++) {
    $espaciosCod = $espaciosCod . " ";
}


$pdf->setHeaderData(PDF_AGENCIA, 40, $espacios . "Comprobante de Reserva", $espaciosCod . "Código de confirmación " . $codReserva, array(0, 0, 0), array(0, 64, 128));

$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(FONT_NAME_HEAD, '', 11));
$pdf->setFooterFont(array(FONT_NAME_HEAD, '', FONT_SIZE_HEAD));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->setFont('dejavusans', '', 9, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

// Set some content to print
$html = <<<HTML
<p>$titulo $nombreCompleto, Su reserva está confirmada.</p>

<table boder="0">
    <tr>
        <td style="height: 9mm;" width="35%"></td>
        <td style="height: 9mm;" width="65%"><strong>$nombreHotel</strong></td>
    </tr>
    <tr>
        <td style="height: 6mm;" width="35%"></td>
        <td style="height: 6mm;" width="65%"><strong>Dirección:</strong> $direccion</td>
    </tr>
    <tr>
        <td style="height: 6mm;" width="35%"></td>
        <td style="height: 6mm;" width="65%"><strong>Entrada:</strong> $fecha_ingreso</td>
    </tr>
    <tr>
        <td style="height: 6mm;"></td>
        <td><strong>Salida:</strong>&nbsp;    &nbsp;  $fecha_salida</td>
    </tr>
    <tr>
        <td style="height: 6mm;"></td>
        <td></td>
    </tr>
</table>
<br>
<br>
<br>
<br>
<table style="border-collapse: collapse; width: 100%; border: 1px solid #b4b7ae;">
    <tr>
        <td colspan="3" style="border: 1px solid #b4b7ae; text-align: left; height: 7mm; font-weight:bold; background-color: #dbdfd3;" >Resumen de la Reserva</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;" width="30%">Nombre de la habitación</td>
        <td style="border: 1px solid #b4b7ae" width="30%">$nombreHabitacion</td>
        <td rowspan="5" style="border: 1px solid #b4b7ae" width="40%"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;">Tipo</td>
        <td style="border: 1px solid #b4b7ae">$tipo</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;">Huésped(es)</td>
        <td style="border: 1px solid #b4b7ae">$huespedes</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;">Ubicación</td>
        <td style="border: 1px solid #b4b7ae">$direccion</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;">Teléfono</td>
        <td style="border: 1px solid #b4b7ae">$telefono</td>
    </tr>
</table>
<p></p>
<table style="border-collapse: collapse; width: 100%; border: 1px solid #b4b7ae;">
    <tr>
        <td colspan="3" style="border: 1px solid #b4b7ae; text-align: left; height: 7mm; font-weight:bold; background-color: #dbdfd3;" >Información del pago</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;" width="30%">Habitación $tipo</td>
        <td style="border: 1px solid #b4b7ae; text-align: center;" width="30%">1</td>
        <td style="border: 1px solid #b4b7ae; text-align: right; font-size:11px" width="40%">
            $moneda$precio
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;">Días</td>
        <td style="border: 1px solid #b4b7ae; text-align: center;">$dias</td>
        <td style="border: 1px solid #b4b7ae; text-align: right;">*$dias</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;">Huésped(es)</td>
        <td style="border: 1px solid #b4b7ae; text-align: center;">$huespedes</td>
        <td style="border: 1px solid #b4b7ae; text-align: right;">-</td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid #b4b7ae; height: 2mm;"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 7mm;">Total</td>
        <td style="border: 1px solid #b4b7ae"></td>
        <td style="border: 1px solid #b4b7ae; text-align: right; font-size:12px">$moneda$total</td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid #b4b7ae; height: 2mm;">Se ha realizado esta reserva por pago con <span style="font-weight:bold">Tarjeta de Credito</span></td>
    </tr>
</table>
<p></p>
<table style="border-collapse: collapse; width: 100%; border: 1px solid #b4b7ae; background-color: #dbdfd3">
    <tr>
        <td colspan="2" style="border: 1px solid #b4b7ae; height: 7mm;">
        <p><span style="font-weight:bold">El precio final que se muestra es el importe que pagarás al alojamiento.</span><br>La entidad emisora puede aplicar un cargo por transacción internacional.<br></p>
        <p><span style="font-weight:bold">El alojamiento te cobrará: $moneda$total</span><br/>
        Este alojamiento acepta las siguientes formas de pago: American Express, Visa, Diners Club, Maestro</p>

        </td>
    </tr>
    <tr>
        <td colspan="2" style="height: 7mm;" width="100%">
            <p><span style="font-weight:bold; text-align: left;">Información adicional</span><br>Los suplementos adicionales (como cama supletoria) no están incluidos en el precio total. Si no te presentas o cancelas la reserva, es posible que el alojamiento te cargue los impuestos correspondientes. Recuerda leer la información importante que aparece a continuación, ya que puede contener datos relevantes que no se mencionan aqui</p>
        </td>
    </tr>
</table>

HTML;

$pdf->Image("https://viajes-mafara.com/reservas/assets/img/pdf/tcpdf_logo.jpg", 15, 2, 45, 12, 'jpg', null, '', true, 150, '', false, false, false, false, false, false);


if (!empty($anexoHotel)) {
    $extension = pathinfo($anexoHotel, PATHINFO_EXTENSION);
    $pdf->Image("https://viajes-mafara.com/reservas/assets/" . $anexoHotel, 15, 34, 60, 37, $extension, null, '', true, 150, '', false, false, 1, false, false, false);
}


if (!empty($anexoHabitacion)) {
    $extension = pathinfo($anexoHabitacion, PATHINFO_EXTENSION);
    $pdf->Image("https://viajes-mafara.com/reservas/assets/" .$anexoHabitacion, 128, 88, 60, 34, $extension, null, '', true, 150, '', false, false, 1, false, false, false);
}

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('crearPDF.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
