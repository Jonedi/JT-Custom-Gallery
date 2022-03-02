<?php

/**
  * Proporcionar una vista del lado del cliente/público para el plugin.
  *
  * Este archivo se utiliza para marcar los aspectos del lado del cliente/público para el plugin
  *
  * @link https://appetz.com
  * @since desde 1.0.0
  *
  * @package Appetz_blank
  * @subpackage Appetz_blank/public/parcials
  */

$sql = $this->db->prepare("SELECT nombre, tipo, data FROM " . JTCG_TABLE . " WHERE id = %d", $id );
$resultado = $this->db->get_results( $sql );

if( $resultado[0]->data != '' ) {

    $data       = json_decode( $resultado[0]->data, true );
    $nombre     = $resultado[0]->nombre;
    
    $items      = $data['items'];
    $settings   = $data['settings'];

    $output = "";
    
    if( $resultado[0]->tipo == 'custom' ) {
        require_once JTCG_PLUGIN_DIR_PATH . 'public/partials/view/jtcg-public-custom-display.php';
    } elseif( $resultado[0]->tipo == 'category' ) {
        require_once JTCG_PLUGIN_DIR_PATH . 'public/partials/view/jtcg-public-category-display.php';        
    }

} else {

    $output = "<h5>[No hay información con el ID #$id]";

}
