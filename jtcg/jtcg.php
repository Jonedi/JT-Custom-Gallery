<?php
/**
 * Archivo del plugin 
 * Este archivo es leído por WordPress para generar la información del plugin
 * en el área de administración del complemento. Este archivo también incluye 
 * todas las dependencias utilizadas por el complemento, registra las funciones 
 * de activación y desactivación y define una función que inicia el complemento.
 *
 * @link                https://appetz.com
 * @since               1.0.0
 * @package             JTCustomGallery
 *
 * @wordpress-plugin
 * Plugin Name:         JT Custom Gallery
 * Plugin URI:          https://appetz.com.co
 * Description:         Crea una galería tipo portafolio y también toma valores de publicaciones a mostrar
 * Version:             1.0.0
 * Author:              Jon Tmarz
 * Author URI:          https://appetz.com.co
 * License:             GPL2
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         jtcg-textdomain
 * Domain Path:         /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wpdb;
define( 'JTCG_REALPATH_BASENAME_PLUGIN', dirname( plugin_basename( __FILE__ ) ) . '/' );
define( 'JTCG_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'JTCG_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'JTCG_TABLE', "{$wpdb->prefix}jtcg_data" );

/**
 * Código que se ejecuta en la activación del plugin
 */
function activate_jtcustomg_portfolio_galeria() {
    require_once JTCG_PLUGIN_DIR_PATH . 'includes/class-jtcg-activator.php';
	JTCG_Activator::activate();
}

/**
 * Código que se ejecuta en la desactivación del plugin
 */
function deactivate_jtcustomg_portfolio_galeria() {
    require_once JTCG_PLUGIN_DIR_PATH . 'includes/class-jtcg-deactivator.php';
	JTCG_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jtcustomg_portfolio_galeria' );
register_deactivation_hook( __FILE__, 'deactivate_jtcustomg_portfolio_galeria' );

require_once JTCG_PLUGIN_DIR_PATH . 'includes/class-jtcg-master.php';

function run_jtcg_master() {
    $jtcg_master = new JTCG_Master;
    $jtcg_master->run();
}

run_jtcg_master();
