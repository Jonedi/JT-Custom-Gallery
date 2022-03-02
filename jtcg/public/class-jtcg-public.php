<?php

/**
 * La funcionalidad específica de administración del plugin.
 *
 * @link       https://appetz.com
 * @since      1.0.0
 *
 * @package    JTCustomGallery
 * @subpackage JTCustomGallery/admin
 */

/**
 * Define el nombre del plugin, la versión y dos métodos para
 * Encolar la hoja de estilos específica de administración y JavaScript.
 * 
 * @since      1.0.0
 * @package    JTCustomGallery
 * @subpackage JTCustomGallery/admin
 * @author     Jon Tmarz <jontmarz@appetz.com>
 * 
 * @property string $JTCustomGallery
 * @property string $version
 */
class JTCG_Public {
    
    /**
	 * El identificador único de éste plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name  El nombre o identificador único de éste plugin
	 */
    private $plugin_name;
    
    /**
	 * Versión actual del plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version  La versión actual del plugin
	 */
    private $version;
    
    /**
	 * Objeto wpdb
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $db @global $wpdb
	 */
    private $db;
    
    /**
	 * Objeto JTCG_Normalize
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $normalize Instancia del objeto JTCG_Normalize
	 */
    private $normalize;
    
    /**
	 * Objeto JTCG_Helpers
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $helpers Instancia del objeto JTCG_Helpers
	 */
    private $helpers;
    
    /**
     * @param string $plugin_name nombre o identificador único de éste plugin.
     * @param string $version La versión actual del plugin.
     */
    public function __construct( $plugin_name, $version ) {
        
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->helpers = new JTCG_Helpers;
        $this->normalize = new JTCG_Normalize;
        
        global $wpdb;
        $this->db = $wpdb;
        
    }
    
    /**
	 * Registra los archivos de hojas de estilos del área de administración
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function enqueue_styles() {
        
        /**
         * Una instancia de esta clase debe pasar a la función run()
         * definido en JTCG_Cargador como todos los ganchos se definen
         * en esa clase particular.
         *
         * El JTCG_Cargador creará la relación
         * entre los ganchos definidos y las funciones definidas en este
         * clase.
		 */
        
        
        /**
         * Framework Materializecss
         * http://materializecss.com/getting-started.html
         * Material Icons Google
         * https://material.io/icons/
         */
		wp_enqueue_style( 'jtcg_material_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), $this->version, 'all' );
        
        /**
         * jtcg.min.css
         * Archivo de hojas de estilos para la funcionalidad
         * del filtrado del plugin
         */
		wp_enqueue_style( 'jquery_jtcg_css', JTCG_PLUGIN_DIR_URL . 'helpers/jquery-jtcg/css/jtcg.min.css', array(), $this->version, 'all' );
        
        
		wp_enqueue_style( $this->plugin_name, JTCG_PLUGIN_DIR_URL . 'public/css/jtcg-public.css', array(), $this->version, 'all' );
        
    }
    
    /**
	 * Registra los archivos Javascript del área de administración
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function enqueue_scripts() {
        
        /**
         * Una instancia de esta clase debe pasar a la función run()
         * definido en JTCG_Cargador como todos los ganchos se definen
         * en esa clase particular.
         *
         * El JTCG_Cargador creará la relación
         * entre los ganchos definidos y las funciones definidas en este
         * clase.
		 */        
        
        /**
         * jtcg.min.js
         * Archivo Javascript que contiene el core
         * y funcionalidad principal del plugin
         */
        wp_enqueue_script( 'jquery_jtcg_js', JTCG_PLUGIN_DIR_URL . 'helpers/jquery-jtcg/js/jtcg.min.js', ['jquery'], $this->version, true );
        
        wp_enqueue_script( $this->plugin_name, JTCG_PLUGIN_DIR_URL . 'public/js/jtcg-public.js', array( 'jquery' ), $this->version, true );
        
    }
    
    public function shortcode_id( $atts, $content = '' ) {
        
        $args = shortcode_atts([
            'id'    => ''
        ], $atts);
        
        extract( $args, EXTR_OVERWRITE );
        
        if( $id != '' ) {
            
            require_once JTCG_PLUGIN_DIR_PATH . 'public/partials/jtcg-public-display.php';
            
            return $output;
            
        }
        
    }
    
}
