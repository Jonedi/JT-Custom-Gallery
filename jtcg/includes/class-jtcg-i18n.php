<?php

/**
 * Define la funcionalidad de internacionalización
 *
 * Carga y define los archivos de internacionalización de este plugin para que esté listo para su traducción.
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
class JTCG_i18n {
    
    /**
	 * Carga el dominio de texto (textdomain) del plugin para la traducción.
	 *
     * @since    1.0.0
     * @access public static
	 */    
    public function load_plugin_textdomain() {
        
        load_plugin_textdomain(
            'jtcg-textdomain',
            false,
            JTCG_PLUGIN_DIR_PATH . 'languages'
        );
        
    }
    
}