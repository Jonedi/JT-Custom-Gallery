<?php

/**
 * Se activa en la desactivación del plugin
 *
 * @link       https://appetz.com
 * @since      1.0.0
 *
 * @package    JTCustomGallery
 * @subpackage JTCustomGallery/includes
 */

/**
 * Ésta clase define todo lo necesario durante la desactivación del plugin
 *
 * @since      1.0.0
 * @package    JTCustomGallery
 * @subpackage JTCustomGallery/includes
 * @author     Jon Tmarz <jontmarz@appetz.com>
 */

class JTCG_Deactivator {

	/**
	 * Método estático
	 *
	 * Método que se ejecuta al desactivar el plugin
	 *
	 * @since 1.0.0
     * @access public static
	 */
	public static function deactivate() {
        
        flush_rewrite_rules();
        
	}

}