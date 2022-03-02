<?php

/**
 * Se activa en la activación del plugin
 *
 * @link       https://appetz.com
 * @since      1.0.0
 *
 * @package    JTCustomGallery
 * @subpackage JTCustomGallery/includes
 */

/**
 * Ésta clase define todo lo necesario durante la activación del plugin
 *
 * @since      1.0.0
 * @package    JTCustomGallery
 * @subpackage JTCustomGallery/includes
 * @author     Jon Tmarz <jontmarz@appetz.com>
 */
class JTCG_Activator {

	/**
	 * Método estático que se ejecuta al activar el plugin
	 *
	 * Creación de la tabla {$wpdb->prefix}jtcustomg_data
     * para guardar toda la información necesaria
	 *
	 * @since 1.0.0
     * @access public static
	 */
	public static function activate() {
        
        global $wpdb;
        
        $sql = "CREATE TABLE IF NOT EXISTS " . JTCG_TABLE . "(
            id int(11) NOT NULL AUTO_INCREMENT,
            nombre varchar(50) NOT NULL,
            tipo varchar(50) NOT NULL,
            data longtext NOT NULL,
            PRIMARY KEY (id)
        );";
        
        $wpdb->query( $sql );
        
	}

}
