<?php

class JTCG_Helpers {
    
    
    /**
	 * Objeto JTCG_Normalize
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $normalize Instancia del objeto JTCG_Normalize
	 */
    private $normalize;
    
    public function __construct() {
        $this->normalize = new JTCG_Normalize;
    }
    
    /**
	 * Itera los items para crear una template
	 *
	 * @since    1.0.0
     * @access   public
     *
     * @param    array   $items    Valores de los items a itera
     * @return   array             Array de items con la estructura lista
	 */
    public function add_btn_filters( $items ) {
        
        $output         = '';
        $arrConAcentos  = [];
        $arrSinAcentos  = [];
        
        foreach( $items as $item ){
            
            $arrConAcentos =  array_merge( $arrConAcentos, explode( ',', $item['filters'] ) );
            $arrSinAcentos =  array_merge( $arrSinAcentos, explode( ',', $this->normalize->init( $item['filters'] ) ) );
            
        }
        
        $arrConAcentosUnique = array_filter( array_unique( $arrConAcentos ) );
        $arrSinAcentosUnique = array_filter( array_unique( $arrSinAcentos ) );
        
        foreach( $arrConAcentosUnique as $k => $v ){
            
            $output .= "<li data-filter='{$arrSinAcentosUnique[$k]}'> $v </li>";
            
        }
        
        return $output;
        
    }
    
}