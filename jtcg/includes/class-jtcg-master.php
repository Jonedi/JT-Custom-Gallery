<?php

/**
 * El archivo que define la clase del cerebro principal del plugin
 *
 * Una definición de clase que incluye atributos y funciones que se 
 * utilizan tanto del lado del público como del área de administración.
 * 
 * @link       https://appetz.com
 * @since      1.0.0
 *
 * @package    JTCustomGallery
 * @subpackage JTCustomGallery/includes
 */

/**
 * También mantiene el identificador único de este complemento,
 * así como la versión actual del plugin.
 *
 * @since      1.0.0
 * @package    JTCustomGallery
 * @subpackage JTCustomGallery/includes
 * @author     Jon Tmarz <jontmarz@appetz.com>
 * 
 * @property object $cargador
 * @property string $plugin_name
 * @property string $version
 */
class JTCG_Master {
    
    /**
	 * El cargador que es responsable de mantener y registrar
     * todos los ganchos (hooks) que alimentan el plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      JTCG_Cargador    $cargador  Mantiene y registra todos los ganchos ( Hooks ) del plugin
	 */
    protected $cargador;
    
    /**
	 * El identificador único de éste plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name  El nombre o identificador único de éste plugin
	 */
    protected $plugin_name;
    
    /**
     * Versión actual del plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version  La versión actual del plugin
	 */
    protected $version;
    
    /**
     * Constructor
     * 
	 * Defina la funcionalidad principal del plugin.
	 *
	 * Establece el nombre y la versión del plugin que se puede utilizar en todo el plugin.
     * Cargar las dependencias, carga de instancias, definir la configuración regional (idioma)
     * Establecer los ganchos para el área de administración y
     * el lado público del sitio.
	 *
	 * @since    1.0.0
	 */
    public function __construct() {
        
        $this->plugin_name = 'JTCustomGallery';
        $this->version = '1.0.0';
        
        $this->cargar_dependencias();
        $this->cargar_instancias();
        $this->set_idiomas();
        $this->definir_admin_hooks();
        $this->definir_public_hooks();
        
    }
    
    /**
	 * Cargue las dependencias necesarias para este plugin.
	 *
	 * Incluya los siguientes archivos que componen el plugin:
	 *
	 * - JTCG_Cargador. Itera los ganchos del plugin.
	 * - JTCG_i18n. Define la funcionalidad de la internacionalización
	 * - JTCG_Admin. Define todos los ganchos del área de administración.
	 * - JTCG_Public. Define todos los ganchos del del cliente/público.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function cargar_dependencias() {
        
        /**
		 * La clase responsable de iterar las acciones y filtros del núcleo del plugin.
		 */
        require_once JTCG_PLUGIN_DIR_PATH . 'includes/class-jtcg-cargador.php';
        
        /**
		 * La clase responsable de definir la funcionalidad de la
         * internacionalización del plugin
		 */
        require_once JTCG_PLUGIN_DIR_PATH . 'includes/class-jtcg-i18n.php';        
        
        /**
		 * La clase responsable de registrar menús y submenús
         * en el área de administración
		 */
        require_once JTCG_PLUGIN_DIR_PATH . 'includes/class-jtcg-build-menupage.php';
        
        /**
		 * La clase responsable de normalizar acentos, eñes,
         * y caracteres especales
		 */
        require_once JTCG_PLUGIN_DIR_PATH . 'includes/class-jtcg-normalize.php';
        
        /**
		 * La clase responsable de aportar ayuda con
         * algunas tareas tediosas
		 */
        require_once JTCG_PLUGIN_DIR_PATH . 'includes/class-jtcg-helpers.php';
        
        /**
		 * La clase responsable de definir todas las acciones en el
         * área de administración
		 */
        require_once JTCG_PLUGIN_DIR_PATH . 'admin/class-jtcg-admin.php';
        
        /**
		 * La clase responsable de definir todas las acciones en el
         * área del lado del cliente/público
		 */
        require_once JTCG_PLUGIN_DIR_PATH . 'public/class-jtcg-public.php';        
        
    }
    
    /**
	 * Defina la configuración regional de este plugin para la internacionalización.
     *
     * Utiliza la clase JTCG_i18n para establecer el dominio y registrar el gancho
     * con WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function set_idiomas() {
        
        $jtcg_i18n = new JTCG_i18n();
        $this->cargador->add_action( 'plugins_loaded', $jtcg_i18n, 'load_plugin_textdomain' );        
        
    }
    
    /**
	 * Cargar todas las instancias necesarias para el uso de los 
     * archivos de las clases agregadas
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function cargar_instancias() {
        
        // Cree una instancia del cargador que se utilizará para registrar los ganchos con WordPress.
        $this->cargador     = new JTCG_cargador;
        $this->jtcg_admin   = new JTCG_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->jtcg_public  = new JTCG_Public( $this->get_plugin_name(), $this->get_version() );
        
    }
    
    /**
	 * Registrar todos los ganchos relacionados con la funcionalidad del área de administración
     * del plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function definir_admin_hooks() {
        
        $this->cargador->add_action( 'admin_enqueue_scripts', $this->jtcg_admin, 'enqueue_styles' );
        $this->cargador->add_action( 'admin_enqueue_scripts', $this->jtcg_admin, 'enqueue_scripts' );
        
        $this->cargador->add_action( 'admin_menu', $this->jtcg_admin, 'add_menu' );
        
        $this->cargador->add_action( 'wp_ajax_jtcg_data', $this->jtcg_admin, 'ajax_data' );
        $this->cargador->add_action( 'wp_ajax_jtcg_crud_gallery', $this->jtcg_admin, 'ajax_crud_gallery' );
        $this->cargador->add_action( 'wp_ajax_jtcg_categorias', $this->jtcg_admin, 'ajax_categorias' );
        
    }
    
    /**
	 * Registrar todos los ganchos relacionados con la funcionalidad del área de administración
     * Del plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function definir_public_hooks() {
        
        $this->cargador->add_action( 'wp_enqueue_scripts', $this->jtcg_public, 'enqueue_styles' );
        $this->cargador->add_action( 'wp_enqueue_scripts', $this->jtcg_public, 'enqueue_scripts' );
        $this->cargador->add_shortcode( 'jtcg', $this->jtcg_public, 'shortcode_id' );
                
    }
    
    /**
	 * Ejecuta el cargador para ejecutar todos los ganchos con WordPress.
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function run() {
        $this->cargador->run();
    }
    
	/**
	 * El nombre del plugin utilizado para identificarlo de forma exclusiva en el contexto de
     * WordPress y para definir la funcionalidad de internacionalización.
	 *
	 * @since     1.0.0
     * @access    public
	 * @return    string    El nombre del plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * La referencia a la clase que itera los ganchos con el plugin.
	 *
	 * @since     1.0.0
     * @access    public
	 * @return    JTCG_Cargador    Itera los ganchos del plugin.
	 */
	public function get_cargador() {
		return $this->cargador;
	}

	/**
	 * Retorna el número de la versión del plugin
	 *
	 * @since     1.0.0
     * @access    public
	 * @return    string    El número de la versión del plugin.
	 */
	public function get_version() {
		return $this->version;
	}
    
}
