<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BR_AIT_Facturacion
 * @subpackage BR_AIT_Facturacion/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    BR_AIT_Facturacion
 * @subpackage BR_AIT_Facturacion/includes
 * @author     Your Name <email@example.com>
 */
class BR_AIT_Facturacion_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::checkCompatibility();
		AitCache::clean();
	}

	public static function checkCompatibility($die = false){
		if ( !defined('AIT_THEME_TYPE') ){	// directory themes
			require_once(ABSPATH . 'wp-admin/includes/plugin.php' );
			deactivate_plugins(plugin_basename( __FILE__ ));
			if($die){
				wp_die('Current theme is not compatible with BR Facturacion plugin :(', '',  array('back_link'=>true));
			} else {
				add_action( 'admin_notices', function(){
					echo "<div class='error'><p>" . __('Current theme is not compatible with BR Facturacion plugin!', 'br-ait-facturacion') . "</p></div>";
				} );
			}
		}
	}

}
