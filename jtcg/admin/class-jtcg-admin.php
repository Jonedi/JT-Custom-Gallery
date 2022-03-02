<?php

/**
 * La funcionalidad específica de administración del plugin.
 *
 * @link       https://appetz.com
 * @since      1.0.0
 *
 * @package    Appetz_blank
 * @subpackage Appetz_blank/admin
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
 * @property string $plugin_name
 * @property string $version
 */
class JTCG_Admin {
    
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
	 * Objeto registrador de menús
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $build_menupage  Instancia del objeto JTCG_Build_Menupage
	 */
    private $build_menupage;
    
    /**
	 * Objeto wpdb
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $db @global $wpdb
	 */
    private $db;
    
    /**
	 * Objeto JTCG_CRUD_JSON
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $crud_json Instancia del objeto JTCG_CRUD_JSON
	 */
    private $crud_json;
    
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
        $this->build_menupage = new JTCG_Build_Menupage();
        $this->normalize = new JTCG_Normalize;
        $this->helpers = new JTCG_Helpers;
        
        global $wpdb;
        $this->db = $wpdb;
    }
    
    /**
	 * Registra los archivos de hojas de estilos del área de administración
	 *
	 * @since    1.0.0
     * @access   public
     *
     * @param    string   $hook    Devuelve el texto del slug del menú con el texto toplevel_page
	 */
    public function enqueue_styles( $hook ) {
        
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
         * jtcg-admin.css
         * Archivo de hojas de estilos principales
         * de la administración
         */
		wp_enqueue_style( 'jtcg_wordpress_icons_css', JTCG_PLUGIN_DIR_URL . 'admin/css/jtcg-wordpress.css', array(), $this->version, 'all' );
        
        /**
         * Condicional para controlar la carga de los archivos
         * solamente en la página del plugin
         */
        if( $hook != 'toplevel_page_jtcg' ) {
            return;
        }
        
        /**
         * Framework Materializecss
         * http://materializecss.com/getting-started.html
         * Material Icons Google
         * https://material.io/icons/
         */
		wp_enqueue_style( 'jtcg_material_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), $this->version, 'all' );
		wp_enqueue_style( 'jtcg_materialize_admin_css', JTCG_PLUGIN_DIR_URL . 'helpers/materialize/css/materialize.min.css', array(), '0.100.1', 'all' );
        
        /**
         * Sweet Alert
         * http://t4t5.github.io/sweetalert/
         */
		wp_enqueue_style( 'jtcg_sweet_alert_css', JTCG_PLUGIN_DIR_URL . 'helpers/sweetalert-master/dist/sweetalert.css', array(), $this->version, 'all' );
        
        /**
         * jtcg.min.css
         * Archivo de hojas de estilos para la funcionalidad
         * del filtrado del plugin
         */
		wp_enqueue_style( 'jquery_jtcg_css', JTCG_PLUGIN_DIR_URL . 'helpers/jquery-jtcg/css/jtcg.min.css', array(), $this->version, 'all' );
        
        /**
         * jtcg-admin.css
         * Archivo de hojas de estilos principales
         * de la administración
         */
		wp_enqueue_style( $this->plugin_name, JTCG_PLUGIN_DIR_URL . 'admin/css/jtcg-admin.css', array(), $this->version, 'all' );
        
    }
    
    /**
	 * Registra los archivos Javascript del área de administración
	 *
	 * @since    1.0.0
     * @access   public
     *
     * @param    string   $hook    Devuelve el texto del slug del menú con el texto toplevel_page
	 */
    public function enqueue_scripts( $hook ) {
        
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
         * Condicional para controlar la carga de los archivos
         * solamente en la página del plugin
         */
        if( $hook != 'toplevel_page_jtcg' ) {
            return;
        }
        
        wp_enqueue_media();
        
        /**
         * Framework Materializecss
         * http://materializecss.com/getting-started.html
         * Material Icons Google
         */
		wp_enqueue_script( 'jtcg_materialize_admin_js', JTCG_PLUGIN_DIR_URL . 'helpers/materialize/js/materialize.min.js', ['jquery'], '0.100.1', true );
        
        /**
         * Sweet Alert
         * http://t4t5.github.io/sweetalert/
         */
		wp_enqueue_script( 'jtcg_sweet_alert_js', JTCG_PLUGIN_DIR_URL . 'helpers/sweetalert-master/dist/sweetalert.min.js', ['jquery'], $this->version, true );
        
        /**
         * jtcg.min.js
         * Archivo Javascript que contiene el core
         * y funcionalidad principal del plugin
         */
        wp_enqueue_script( 'jquery_jtcg_js', JTCG_PLUGIN_DIR_URL . 'helpers/jquery-jtcg/js/jtcg.min.js', ['jquery'], $this->version, true );
        
        /**
         * jtcg-admin.js
         * Archivo Javascript principal
         * de la administración
         */
        wp_enqueue_script( 'jtcustomg_global', JTCG_PLUGIN_DIR_URL . 'admin/js/jtcustomg-global.min.js', ['jquery'], $this->version, true );
        
        /**
         * jtcg-admin.js
         * Archivo Javascript principal
         * de la administración
         */
        wp_enqueue_script( $this->plugin_name, JTCG_PLUGIN_DIR_URL . 'admin/js/jtcg-admin.js', ['jquery'], $this->version, true );
        
        /**
         * Lozalizando el archivo Javascript
         * principal del área de administración
         * para pasarle el objeto "jtcg" con los parámetros:
         * 
         * @param jtcg.url        Url del archivo admin-ajax.php
         * @param jtcg.seguridad  Nonce de seguridad para el envío seguro de datos
         */
        wp_localize_script(
            $this->plugin_name,
            'jtcg',
            [
                'url'       => admin_url( 'admin-ajax.php' ),
                'seguridad' => wp_create_nonce( 'jtcg_seg' )
            ]
        );
        
    }
    
    /**
	 * Registra los menús del plugin en el
     * área de administración
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function add_menu() {
        
        $this->build_menupage->add_menu_page(
            __( 'JT Custom Gallery', 'jtcg-textdomain' ),
            __( 'JT Custom Gallery', 'jtcg-textdomain' ),
            'manage_options',
            'jtcg',
            [ $this, 'controlador_display_menu' ],
            'dashicons-jtcg',
            22
        );
        
        $this->build_menupage->run();
        
    }
    
    /**
	 * Controla las visualizaciones del menú
     * en el área de administración
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function controlador_display_menu() {
        
        if(
            $_GET['page'] == 'jtcg' &&
            isset( $_GET['action'] ) &&
            isset( $_GET['id'] )
        ) {
            
            if( $_GET['action'] == 'edit' ) {
                require_once JTCG_PLUGIN_DIR_PATH . 'admin/partials/jtcg-admin-display-edit.php';
            }
            
        } else {
            require_once JTCG_PLUGIN_DIR_PATH . 'admin/partials/jtcg-admin-display.php';
        }
        
        
    }
    
    /**
	 * Método que controla el envío
     * de datos con POST, desde el lado público
     * hacia el lado del servidor
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function ajax_crud_gallery() {
        
        check_ajax_referer( 'jtcg_seg', 'nonce' );
        
        if( current_user_can( 'manage_options' ) ) {
            
            extract( $_POST, EXTR_OVERWRITE );
            
            if( $tipo == 'add' ) {
                
                $columns = [
                    'nombre'    => $nombre,
                    'tipo'      => $type_val,
                    'data'      => '',
                ];
                
                $format = [
                    "%s",
                    "%s",
                    "%s"
                ];

                $result = $this->db->insert( JTCG_TABLE, $columns, $format );

                $json = json_encode( [
                    'result'        => $result,
                    'nombre'        => $nombre,
                    'insert_id'     => $this->db->insert_id
                ] );
                
            } elseif( $tipo == 'delete' ) {
                
                $where = [
                    'id' => $idgal
                ];
                
                $where_format = [
                    "%s"
                ];
                
                $result = $this->db->delete( JTCG_TABLE, $where, $where_format );
                $json = json_encode( [
                    'result' => $result
                ] );
            }
            echo $json;
            wp_die();
            
        }
    }
    
    /**
	 * Método que controla el envío
     * de datos con POST, desde el lado público
     * hacia el lado del servidor
     * para guardar la información en
     * formato JSON
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function ajax_data() {
        
        check_ajax_referer( 'jtcg_seg', 'nonce' );
        
        if( current_user_can( 'manage_options' ) ) {
            
            extract( $_POST, EXTR_OVERWRITE );
            
            $data = stripslashes( $data );
            
            $columns = [
                'nombre'    => $nombregaljtcg,
                'tipo'      => $type,
                'data'      => $data
            ];

            $where = [
                "id" => $idgaljtcg
            ];

            $format = [
                "%s",
                "%s",
                "%s"
            ];

            $where_format = [
                "%d"
            ];

            $result_update = $this->db->update( JTCG_TABLE, $columns, $where, $format, $where_format );

            $json = json_encode( [
                'result'        => $result_update
            ] );
            
            echo $json;
            wp_die();
            
        }
    }
    
    /**
	 * Método que controla el envío
     * de datos con POST, desde el lado público
     * hacia el lado del servidor
     * para obtener la configuración
     * de que tipo de categoría mostrar
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function ajax_categorias() {
        
        check_ajax_referer( 'jtcg_seg', 'nonce' );
        
        if( current_user_can( 'manage_options' ) ) {
            
            extract( $_POST, EXTR_OVERWRITE );
            
            $args_query = [
                'cat'               => $cat_ID,
                'posts_per_page'    => $postPerPage,
                'order'             => $orden,
                'orderby'           => $orderby
            ];
            
            $query = new WP_Query( $args_query );
            $posts = [];
            $c = 0;
            
            if( $query->have_posts() ) {
                
                while( $query->have_posts() ) {
                    
                    $query->the_post();
                    
                    $posts[$c]['title']     = get_the_title();
                    $posts[$c]['content']   = get_the_content();
                    $posts[$c]['excerpt']   = get_the_excerpt();
                    
                    if( has_post_thumbnail() ) {
                        
                        $posts[$c]['imgtag'] = get_the_post_thumbnail(
                            get_the_ID(),
                            'medium',
                            [
                                'class' => 'jtcg-img-tag'
                            ]
                        );
                        
                        $posts[$c]['imgurl'] = get_the_post_thumbnail_url(
                            get_the_ID(),
                            'medium'
                        );
                        
                    } else {
                        $posts[$c]['imgtag'] = null;
                        $posts[$c]['imgurl'] = null;
                    }
                    
                    $posts[$c]['link'] = get_the_permalink();
                    $posts[$c]['time'] = get_the_time( 'F j Y' );
                    
                    $c++;
                    
                }
                
            }
            wp_reset_postdata();

            $json = json_encode( [
                'posts' => $posts
            ] );
            
            echo $json;
            wp_die();
            
        }
        
    }
    
}