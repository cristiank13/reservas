<?php
// Include the main TCPDF library (search for installation path).

//var_dump($_POST);
require_once('TCPDF/examples/tcpdf_include.php');

const PDF_AGENCIA = '../../../../assets/img/pdf/tcpdf_logo.jpg';

const FONT_NAME_HEAD = 'helvetica';

const FONT_SIZE_HEAD = '12';

$nombreCompleto = '';

if ($_POST["primer_nombre"]) {
    $nombreCompleto = $_POST["primer_nombre"];
}

if ($_POST["segundo_nombre"]) {
    $nombreCompleto = $nombreCompleto . " " . $_POST["segundo_nombre"];
}

if ($_POST["primer_apellido"]) {
    $nombreCompleto = $nombreCompleto . " " . $_POST["primer_apellido"];
}

if ($_POST["segundo_apellido"]) {
    $nombreCompleto = $nombreCompleto . " " . $_POST["segundo_apellido"];
}

$titulo = $_POST["titulo"];
$ciudad = $_POST["ciudad"];
$nombreHotel = $_POST["nombre_hotel"];
$nombreHabitacion = $_POST["nombre_habitacion"];
$huespedes = $_POST["huespedes"];


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
/*$pdf->setAuthor('Nicola Asuni');
$pdf->setTitle('TCPDF Example 001');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');*/

// set default header data
//$pdf->setHeaderData(PDF_AGENCIA, 100, 'Factura generada', '', array(0,0,0), array(0,64,128));
$pdf->setHeaderData(PDF_AGENCIA, 40, '                Comprobante de Reserva', null, array(0,0,0), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(FONT_NAME_HEAD, '', FONT_SIZE_HEAD));
$pdf->setFooterFont(Array(FONT_NAME_HEAD, '', FONT_SIZE_HEAD));

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
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->setFont('dejavusans', '', 11, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<HTML
<h3>Código de Reserva: #ABC123</h3>

<p>$titulo $nombreCompleto, Su reserva está confirmada.</p>

<p><span style="font-weight:bold">País / Ciudad: </span>$ciudad</p>
<p><span style="font-weight:bold">Nombre del Hotel: </span>$nombreHotel</p>
<p><span style="font-weight:bold">Número de Huespedes: </span>$huespedes</p>
</p>
<table style="border-collapse: collapse; width: 100%; border: 1px solid #b4b7ae;">
    <tr>
        <td colspan="2" style="border: 1px solid #b4b7ae; text-align: center; height: 10mm; font-weight:bold; background-color: #dbdfd3;" >Resumen de la Reserva</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 10mm;" width="40%">Entrada</td>
        <td style="border: 1px solid #b4b7ae; height: 10mm;" width="60%">Miércoles, 1 de febrero de 2023<br>(14:00)</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 10mm;">Salida</td>
        <td style="border: 1px solid #b4b7ae">Jueves, 2 de febrero de 2023<br>(12:00)</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 10mm;">Tu reserva</td>
        <td style="border: 1px solid #b4b7ae">1 noche, 1 habitación</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 10mm;">Nombre de la habitación</td>
        <td style="border: 1px solid #b4b7ae">$nombreHabitacion</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 10mm;">Tipo</td>
        <td style="border: 1px solid #b4b7ae">Doble</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 10mm;">Huespedes</td>
        <td style="border: 1px solid #b4b7ae">$huespedes</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 10mm;">Ubicación</td>
        <td style="border: 1px solid #b4b7ae">calle 34 #1-2</td>
    </tr>
    <tr>
        <td style="border: 1px solid #b4b7ae; height: 10mm;">Teléfono</td>
        <td style="border: 1px solid #b4b7ae">+57 4445556789</td>
    </tr>
</table>
<p><span style="font-weight:bold">Información del pago</span></p>
<table style="border-collapse: collapse; width: 100%; border: 1px solid #b4b7ae; background-color: #dbdfd3">
    <tr>
        <td colspan="2" style="border: 1px solid #b4b7ae; height: 10mm;" >
        <p>Se ha realizado esta reserva por pago con <span style="font-weight:bold">Tarjeta de Credito</span></p>
        </td>
    </tr>
    <tr>
        <td style="height: 10mm;" width="40%">
            <p style="font-weight:bold; text-align: left;">Tarjeta de Credito</p>
        </td>
        <td width="60%">
            <p style="font-weight:bold; text-align: right;">$1.200.000</p>
        </td>
    </tr>
</table>

HTML;

$pdf->Image(PDF_AGENCIA, '', '', 40, 40, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
$pdf->Image(PDF_AGENCIA, '', '', 40, 40, '', '', '', false, 300, '', false, false, 1, false, false, false);


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
