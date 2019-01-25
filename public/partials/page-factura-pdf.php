<?php
/*
 * INVOICR : THE PHP INVOICE GENERATOR (HTML, DOCX, PDF)
 * Visit https://code-boxx.com/invoicr-php-invoice-generator for more
 * 
 * ! YOU CAN DELETE THE ENTIRE EXAMPLE FOLDER IF YOU DON'T NEED IT... !
 */

/* [STEP 1 - CREATE NEW INVOICR OBJECT] */
require WP_PLUGIN_DIR . DIRECTORY_SEPARATOR .'br-ait-facturacion/includes/'. "invoicr.php";
$invoice = new Invoicr();

if(isset($_GET["user_id"]) && isset($_GET["clave"])) {
	$user_id = $_GET["user_id"];
	$clave = $_GET["clave"];
	$historialPagos = get_user_meta( $user_id , 'historial_pagos', false);
	$factura = $historialPagos[$clave];

	/*
	$empresa = array();
	$empresa['nombre'] = $factura['name_proveedor'];
	$empresa['direccion'] = $factura['direccion_proveedor'].','.$factura['state_proveedor'].','.$factura['country_proveedor'].','.$factura['zipcode_proveedor'];
	$empresa['nit'] = $factura['nit_proveedor'];
	$empresa['url'] = home_url();
	*/
	$themeOptions = aitOptions()->getOptionsByType('theme');
	$facturaOptions = $themeOptions['facturas'];

	$empresa['nombre'] = $facturaOptions['nombreEmpresa'];
	$empresa['direccion'] = $facturaOptions['domicilioFiscalEmpresa'].','.$facturaOptions['provinciaEmpresa'].','.$facturaOptions['paisEmpresa'].','.$facturaOptions['codigoPostalEmpresa'];
	$empresa['nit'] = $facturaOptions['nitEmpresa'];
	$empresa['url'] = home_url();


	$item = array();
	$item['nombre'] = 'Paquete '.$factura['package'];
	$item['descripcion'] = 'Pago por período de '.$factura['periodo'].' días.';
	$item['cantidad'] = 1;
	$item['precio_unitario'] =	number_format(($factura['amount']/1.21),2).' '.$factura['currency_code'];

	$item['precio_total'] = number_format(($factura['amount']/1.21),2).' '.$factura['currency_code'];

	$total['sub_total'] = $item['precio_total'];
	$total['iva'] = number_format(($item['precio_total'] * 0.21),2).' '.$factura['currency_code'];
	$total['monto'] = number_format(($factura['amount']),2).' '.$factura['currency_code'];

	$cliente['razonsocial'] = get_the_author_meta( 'razonsocial', get_current_user_id());
	$cliente['cifnit'] = get_the_author_meta( 'cifnit', get_current_user_id() );
	$cliente['direccion'] = get_the_author_meta( 'direccion', get_current_user_id()  );
	$cliente['codigopostal'] = get_the_author_meta( 'codigopostal', get_current_user_id() );
	$cliente['provincia'] = get_the_author_meta( 'provincia', get_current_user_id()  );
	$cliente['pais'] = get_the_author_meta( 'pais', get_current_user_id()  );
	$cliente['country_zip'] = $cliente['provincia'].','.$cliente['pais'].','.$cliente['codigopostal'];

	
}


/* [STEP 2 - FEED ALL THE INFORMATION] */
// 2A - COMPANY INFORMATION
// OR YOU CAN PERMANENTLY CODE THIS INTO THE LIBRARY ITSELF
$invoice->set("company", [
	plugins_url( 'br-ait-facturacion'). "/public/img/logo-portalsolar-2.png",
	plugins_url( 'br-ait-facturacion'). "/public/img/logo-portalsolar-2.png",
	$empresa['nombre'], 
	$empresa['direccion'],
	$empresa['nit'],
	$empresa['url']
]);

// 2B - INVOICE INFO
$invoice->set("invoice", [
	["Factura #", $factura['factura']],
	["Fecha", date("j/m Y",strtotime($factura['payment_date']))]
]);

// 2C - BILL TO
$invoice->set("billto", [
	$cliente['razonsocial'],
	$cliente['cifnit'],
	$cliente['direccion'], 
	$cliente['country_zip']
]);

// 2D - SHIP TO
$invoice->set("shipto", [
	$factura['payment_type']
]);

// 2E - ITEMS
// YOU CAN JUST DUMP THE ENTIRE ARRAY IN USING SET, BUT HERE IS HOW TO ADD ONE AT A TIME... 
$items = [
	[$item['nombre'], $item['descripcion'], $item['cantidad'], $item['precio_unitario'], $item['precio_total']]
];
foreach ($items as $i) { $invoice->add("items", $i); }

// 2F - TOTALS
$invoice->set("totals", [
	["SUB-TOTAL", $total['sub_total']],
	["I.V.A 21%", $total['iva']],
	["TOTAL", $total['monto']]
]);

// 2G - NOTES, IF ANY
$invoice->set("notes", [
	"Factura Pagada"
]);


/* [STEP 3 - OUTPUT] */
// 3A - CHOOSE TEMPLATE, DEFAULTS TO SIMPLE IF NOT SPECIFIED
$invoice->template("portalsolar");

/*****************************************************************************/
// 3B - OUTPUT IN HTML
// DEFAULT DISPLAY IN BROWSER | 1 DISPLAY IN BROWSER | 2 FORCE DOWNLOAD | 3 SAVE ON SERVER
// $invoice->outputHTML();
// $invoice->outputHTML(1);
// $invoice->outputHTML(2, "invoice.html");
// $invoice->outputHTML(3, __DIR__ . DIRECTORY_SEPARATOR . "invoice.html");
/*****************************************************************************/
// 3C - PDF OUTPUT
// DEFAULT DISPLAY IN BROWSER | 1 DISPLAY IN BROWSER | 2 FORCE DOWNLOAD | 3 SAVE ON SERVER
 $invoice->outputPDF();
// $invoice->outputPDF(1);
// $invoice->outputPDF(2, "invoice.pdf");
// $invoice->outputPDF(3, __DIR__ . DIRECTORY_SEPARATOR . "invoice.pdf");
/*****************************************************************************/
// 3D - DOCX OUTPUT
// DEFAULT FORCE DOWNLOAD| 1 FORCE DOWNLOAD | 2 SAVE ON SERVER
// $invoice->outputDOCX();
// $invoice->outputDOCX(1, "invoice.docx");
// $invoice->outputDOCX(2, __DIR__ . DIRECTORY_SEPARATOR . "invoice.docx");
/*****************************************************************************/
?>