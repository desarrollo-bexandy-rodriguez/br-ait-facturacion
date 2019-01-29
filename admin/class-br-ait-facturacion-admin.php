<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BR_AIT_Facturacion
 * @subpackage BR_AIT_Facturacion/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    BR_AIT_Facturacion
 * @subpackage BR_AIT_Facturacion/admin
 * @author     Your Name <email@example.com>
 */
class BR_AIT_Facturacion_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/br-ait-facturacion-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/br-ait-facturacion-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Function config().
	 *
	 * @since    1.0.0
	 */
	public function config($config) {
		$prefijo = 'SOLAR';
		$codigo = '00000';
		$sufijo = date('Y');
		$nextFactura = get_option( 'nextFactura', $prefijo.'-'.$codigo.'-'.$sufijo  );
		// titles
		$titles = array();
		for ($i=1; $i <= 4; $i++) {
			$titles[$i] = new NNeonEntity;
			$titles[$i]->value = 'section';
		}
		$titles[2]->attributes = array('title' => __('Datos de la Empresa','br-ait-facturacion'), 'help' => __('Indique los datos de la empresa que apareceran en las facturas', 'br-ait-facturacion'));
		$titles[3]->attributes = array(
			'title' => __('Formato de # de facturación','br-ait-facturacion'),
			'help' => __('# Siguiente Factura = '. $nextFactura, 'br-ait-facturacion'));
		$titles[4]->attributes = array(
			'title' => __('Impuestos','br-ait-facturacion'),
			'help' => __('Porcentaje de Impuesto vigente', 'br-ait-facturacion'));

		// PayPal Single Payments plugin is active
		if (isset($config['facturas'])) {
			// Add help text
			$config['facturas']['options'][2] = $titles[2];
		} else {
			$config['facturas'] = array(
				'title' => 'Facturas',
				'options' => array(

					2 => $titles[2],

					'nombreEmpresa' => array(
						'label' => __('Nombre de la Empresa','br-ait-facturacion'),
						'type' => 'code',
						'default' => ''
					),
					'nitEmpresa' => array(
						'label' => __('NIT / RIF','br-ait-facturacion'),
						'type' => 'code',
						'default' => ''
					),
					'domicilioFiscalEmpresa' => array(
						'label' => __('Domicilio Fiscal','br-ait-facturacion'),
						'type' => 'code',
						'default' => ''
					),
					'codigoPostalEmpresa' => array(
						'label' => __('Código Postal','br-ait-facturacion'),
						'type' => 'code',
						'default' => ''
					),
					'provinciaEmpresa' => array(
						'label' => __('Provincia','br-ait-facturacion'),
						'type' => 'code',
						'default' => ''
					),
					'paisEmpresa' => array(
						'label' => __('País','br-ait-facturacion'),
						'type' => 'code',
						'default' => ''
					),

					3 => $titles[3],
					'restartNumeracion' => array(
						'label' => __('Reiniciar Numeracion','br-ait-facturacion'),
						'type' => 'on-off',
						'default' => false,
						'help' => __('Reiniciar # facturas al numero de abajo','br-ait-facturacion')
					),
					'prefijoFacturacion' => array(
						'label' => __('Prefijo','br-ait-facturacion'),
						'type' => 'code',
						'default' => 'SOLAR'
					),
					'inicioFacturacion' => array(
						'label' => __('Iniciar en','br-ait-facturacion'),
						'type' => 'code',
						'default' => '00000'
					),

					4 => $titles[4],
					'impuesto' => array(
						'label' => __('Impuesto','br-ait-facturacion'),
						'type' => 'number',
						'default' => '21'
					),
				)
			);
		}

		return $config;
	}

	public function add_menu_page() {
		add_menu_page('Facturas','Facturas','read','facturas',array( $this, 'menuPage' ),'dashicons-feedback');
	}

	public function menuPage() {
		//
		require_once plugin_dir_path( __FILE__ ) . 'partials/br-ait-facturacion-admin-display.php';
	}

	public function registrarPago($payment) {
		AitPaypalSubscriptions::log('PHP: Registrar Pago - Factura-Inicio', 'TRACERT');
		$data = $payment->data;
		$user = new Wp_User($data['user']);
		$themeOptions = aitOptions()->getOptionsByType('theme');
		$facturaOptions = $themeOptions['facturas'];
		$prefijo = $facturaOptions['prefijoFacturacion'] ? $facturaOptions['prefijoFacturacion'] :'SOLAR';
		$start = $facturaOptions['inicioFacturacion'] ? $facturaOptions['inicioFacturacion'] :'00000';
		$sufijo = date('Y');
		$codigo = get_option( 'nextFactura', 'XXXX-00000-XXXX'  );
		$codigo = substr($codigo, strlen($prefijo)+1, 5);
		$nextFactura = get_option( 'nextFactura', $prefijo.'-'.$codigo.'-'.$sufijo  );
		$packages = new ThemePackages();
		$package = $packages->getPackageBySlug($data['package']);
		$options = $package->getOptions();
		$impuesto = $options['impuesto'] ? $facturaOptions['impuesto'] : 0;
		$cliente['razonsocial'] = get_the_author_meta( 'razonsocial', $user->ID);
		$cliente['cifnit'] = get_the_author_meta( 'cifnit', $user->ID );
		$cliente['direccion'] = get_the_author_meta( 'direccion', $user->ID  );
		$cliente['codigopostal'] = get_the_author_meta( 'codigopostal', $user->ID );
		$cliente['provincia'] = get_the_author_meta( 'provincia', $user->ID  );
		$cliente['pais'] = get_the_author_meta( 'pais', $user->ID  );

		add_user_meta( $user->ID, 'historial_pagos', array(
			 'payment_type' => 'Paypal Recurring',
			 'payment_date' => $payment->payment_date, 
			 'payment_status' => $payment->payment_status,
			 'product_name' => $payment->product_name,
			 'payer_id' => $payment->payer_id,
			 'recurring_payment_id' => $payment->recurring_payment_id,
			 'amount' => $payment->amount,
			 'currency_code' => $payment->currency_code,
			 'package' => getRoleDisplayName($data['package']),
			 'package_slug' => $data['package'],
			 'periodo' => $data['periodo'],
			 'factura' => $nextFactura,
			 'impuesto' => $impuesto,
			 'name_proveedor' => $facturaOptions['nombreEmpresa'],
			 'nit_proveedor' => $facturaOptions['nitEmpresa'],
			 'direccion_proveedor' => $facturaOptions['domicilioFiscalEmpresa'],
			 'zipcode_proveedor' => $facturaOptions['codigoPostalEmpresa'],
			 'state_proveedor' => $facturaOptions['provinciaEmpresa'],
			 'country_proveedor' => $facturaOptions['paisEmpresa'],
			 'razonsocial_cliente' => $cliente['cifnit'],
			 'cifnit_cliente' => $cliente['cifnit'],
			 'direccion_cliente' => $cliente['direccion'],
			 'codigopostal_cliente' => $cliente['codigopostal'],
			 'provincia_cliente' => $cliente['provincia'],
			 'pais_cliente' => $cliente['pais']
			));
		$codigo++;
		$codigo = str_pad($codigo, 5, '0',STR_PAD_LEFT );
		update_option( 'nextFactura', $prefijo.'-'.$codigo.'-'.$sufijo  );
		AitPaypalSubscriptions::log(
			array(
				'message' =>'PHP: Registrar Pago - Factura-Fin',
				'data' => $data,
			 	'user' => $user,
			 	'user_id' => $user->ID,
			 	'historial_pagos' => get_usermeta( $user->ID, 'historial_pagos' )
			 ), 'TRACERT');
	}

	public function extra_user_profile_fields( $user ) { 
		require_once plugin_dir_path( __FILE__ ) . 'partials/datos-cliente.php';
	}

	public function save_extra_user_profile_fields( $user_id ) {
	    if ( !current_user_can( 'edit_user', $user_id ) ) { 
	        return false; 
	    }
	    update_user_meta( $user_id, 'razonsocial', $_POST['razonsocial'] );
	    update_user_meta( $user_id, 'cifnit', $_POST['cifnit'] );
	    update_user_meta( $user_id, 'direccion', $_POST['direccion'] );
	    update_user_meta( $user_id, 'codigopostal', $_POST['codigopostal'] );
	    update_user_meta( $user_id, 'provincia', $_POST['provincia'] );
	    update_user_meta( $user_id, 'pais', $_POST['pais'] );
	}
}
