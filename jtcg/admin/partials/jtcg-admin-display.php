<?php

/**
  * Proporcionar una vista de área de administración para el plugin
  *
  * Este archivo se utiliza para marcar los aspectos de administración del plugin.
  *
  * @link https://appetz.com
  * @since desde 1.0.0
  *
  * @package Appetz_blank
  * @subpackage Appetz_blank/admin/parcials
  */

/* Este archivo debe consistir principalmente en HTML con un poco de PHP. */

$sql        = "SELECT id, nombre, tipo FROM " . JTCG_TABLE;
$result     = $this->db->get_results( $sql );

?>

<!-- Modal Structure -->
<div id="addjtcg" class="modal">
    <div class="modal-content">
        
        <!-- Precargador -->
        
        <div class="precargador">
            
            <div class="preloader-wrapper big active">
              <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                  <div class="circle"></div>
                </div><div class="gap-patch">
                  <div class="circle"></div>
                </div><div class="circle-clipper right">
                  <div class="circle"></div>
                </div>
              </div>

              <div class="spinner-layer spinner-red">
                <div class="circle-clipper left">
                  <div class="circle"></div>
                </div><div class="gap-patch">
                  <div class="circle"></div>
                </div><div class="circle-clipper right">
                  <div class="circle"></div>
                </div>
              </div>

              <div class="spinner-layer spinner-yellow">
                <div class="circle-clipper left">
                  <div class="circle"></div>
                </div><div class="gap-patch">
                  <div class="circle"></div>
                </div><div class="circle-clipper right">
                  <div class="circle"></div>
                </div>
              </div>

              <div class="spinner-layer spinner-green">
                <div class="circle-clipper left">
                  <div class="circle"></div>
                </div><div class="gap-patch">
                  <div class="circle"></div>
                </div><div class="circle-clipper right">
                  <div class="circle"></div>
                </div>
              </div>
            </div>
            
            
        </div>
        
        <form method="post" class="col s12">
            <div class="row">
               
                <div class="input-field col s4">
                    <input id="nombre-jtcg" type="text" class="validate">
                    <label for="nombre-jtcg">Nombre de la Galería</label>
                </div>
                <div class="input-field col s6">
                    <select id="type">
                        <option value="" disabled selected>Selecciona el tipo</option>
                        <option value="custom">Personalizada</option>
                        <option value="category">Categoría</option>
                    </select>
                </div>
           
            </div>
            
            <div class="row">
                <div class="col s4">
                    <button id="crear-jtcg" class="btn waves-effect waves-light" type="button" name="action">
                        Crear <i class="material-icons right">add</i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="had-container">
      
      <div class="row">
          <div class="col s12">
              <div class="logo-jtcg">
                  <img src="<?php echo JTCG_PLUGIN_DIR_URL; ?>admin/img/horse-small.webp" alt="">
                  <span class="border-v v-31"></span>
                  <span><?php esc_html_e('JT Custom Gallery', 'jtcg_textdomain'); ?></span>
              </div>
          </div>
          <div class="col s12">
              <div class="divider"></div>
          </div>
      </div>      
      
      <div class="section">
          
          <!-- Botón agregar nueva galería -->
          <div class="row">
              <div class="col s4">
                  <button type="button" class="addjtcg btn-jtcg jtcg-bg-azul">Nuevo <i class="material-icons">insert_photo</i></button>
              </div>
          </div>
          
          <!-- Tabla de información de la galería con los Shortcodes -->
          <div class="row">
              <div class="col s12">
                  <div class="jtcg-table">
                      <table class="responsive-table">
                          <thead>
                              <tr>
                                  <th>Nombre</th>
                                  <th>Tipo</th>
                                  <th>Shortcode</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                             
                             <?php
                                foreach( $result as $k => $v ){
                                    
                                    $id     = $v->id;
                                    $nombre = $v->nombre;
                                    $tipo   = $v->tipo;
                                    
                                    echo "<tr data-jtcg='$id'>
                                              <td>$nombre</td>
                                              <td>$tipo</td>
                                              <td>
                                                  <input type='text' class='jtcg-input-shortcode' value='[jtcg id=\"$id\"]'>
                                              </td>
                                              <td>
                                                  <span idjtcgedit='$id'>
                                                      <i class='tiny material-icons'>mode_edit</i>
                                                  </span>
                                                  <span idjtcgremove='$id'>
                                                      <i class='tiny material-icons'>close</i>
                                                  </span>
                                              </td>
                                          </tr>";
                                }
                             ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
</div>
