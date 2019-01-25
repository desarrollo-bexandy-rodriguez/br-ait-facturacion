<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BR_AIT_Facturacion
 * @subpackage BR_AIT_Facturacion/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<h2>Historial de Pagos</h2>
	<table class="wp-list-table widefat striped">
		<thead>
			<tr>
				<th width="25%">Factura ID</th>
				<th width="25%">Fecha</th>
				<th width="25%">Monto</th>
				<th width="25%">Factura</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$historialPagos = get_user_meta( get_current_user_id(), 'historial_pagos', false);
			foreach ($historialPagos as $clave => $factura) { ?>
				<tr>
					<td width='25%'><?php echo $factura['factura'] ?></td>
 					<td width='25%'><?php echo date("j/m Y",strtotime($factura['payment_date'])) ?></td>
 					<td width='25%'><?php  echo $factura['amount'].' '.$factura['currency_code'] ?></td>
 					<td width='25%'>
 						<?php $url= home_url( 'factura-pdf').'?user_id='.get_current_user_id().'&clave='.$clave ?>
 						<a href='<?php echo $url ?>' target='_blank'>
 							<button type='button'>VER PDF</button>
 						</a> 
					</td>
				</tr>
			<?php 
 			}
			?>
	 	</tbody>
	</table>
</div>