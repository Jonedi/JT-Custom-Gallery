<?php

$id = $_GET['id'];
$sql = $this->db->prepare("SELECT * FROM " . JTCG_TABLE . " WHERE id = %d", $id );
$resultado = $this->db->get_results( $sql );

$data       = json_decode( $resultado[0]->data, true );
$items      = $data['items'];
$settings   = $data['settings'];

?>

<!-- Estructura Modal para la edición de items -->
<div id="bpcg-item-edit" class="modal">
    <div class="modal-content">
        
        <div class="modal-header">
            <div class="row mb0">
                <div class="col s8">
                    <h5><?php _e('Editar item', 'jtcg-textdomain'); ?></h5>
                </div>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <input id="edit-item-id" type="hidden" value="">
        
        <!-- Sección imagen -->
        <h6 class="title-modal">Media:</h6>
        
        <div class="content-item">
            
            <div class="row mb0">
                <div class="col m4">
                    <p><?php _e('Imagen', 'jtcg-textdomain'); ?>:</p>                    
                </div>
                <div class="col m4">
                    <button type="button" id="change-img-item" class="btn-jtcg jtcg-bg-azul">
                        Cambiar imagen <i class="material-icons">cached</i>
                    </button>
                    <br>
                    <br>
                    <img id="edit-item-img" src="" alt="">
                </div>
            </div>
            
        </div>
        
        <!-- Sección campos de datos -->
        <h6 class="title-modal">Datos:</h6>
        
        <div class="content-item">
            
            <!-- Campo título -->
            <div class="row mb0">
               
               <div class="col m4">
                   <h6><?php _e('Título', 'jtcg-textdomain'); ?>:</h6>
               </div>
               <div class="jtcg-input col m7">
                   <input id="edit-item-title" type="text" placeholder="Título">
               </div>
               
            </div>
            
            <!-- Campo filtros -->
            <div class="row mb0">
               
               <div class="col m4">
                   <h6><?php _e('Filtros', 'jtcg-textdomain'); ?>:</h6>
               </div>
               <div class="jtcg-input col m7">
                   <input id="edit-item-filters" type="text" placeholder="Ej: diseño,operación">
               </div>
               
            </div>
            
        </div>
        
        <div class="row mb0">
            <div class="col s12 right-align">
               <br>
                <button type="button" id="update-item" class="modal-action modal-close btn waves-effect waves-light">Actualizar</button>
            </div>
        </div>
        
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
      
      <div class="row">
          
          <form method="post" id="jtcg-edit-formu">
              
              <div class="row">
               
              <!-- Nombre y Tipo de galería  -->
               <input id="idgaljtcg" type="hidden" value="<?php echo $id; ?>">
               
                <div class="jtcg-input col m4">
                     <input id="nombregaljtcg" type="text" class="" value="<?php echo $resultado[0]->nombre; ?>">
                </div>
                 
                 <div class="col m4">
                    <select id="type">
                        <option value="" disabled selected>Selecciona el tipo</option>
                        <option value="custom" <?php selected( $resultado[0]->tipo, 'custom' ) ?>>Personalizada</option>
                        <option value="category" <?php selected( $resultado[0]->tipo, 'category' ) ?>>Categoría</option>
                    </select>
                </div>
                 
                 <div class="col s12">
                     <div class="divider"></div>
                 </div>
                 
                 <div class="col s12">
                     <div class="row">
                         
                         <!-- Zona de edición de los items -->
                         <div class="col m8">
                             
                             <!-- Sección personalizada -->
                             <section id="custom">
                                 
                                 <!-- Botones de filtrado -->
                                 <div class="row mb0">
                                     <div class="col s12">
                                         <ul class="jtcg-ul">
                                             <li data-filter="*" class="activo"><?php _e('Todo', 'jtcg-textdomain'); ?></li>
                                             <?php
                                             
                                             if( $resultado[0]->data != '' ) {
                                                 echo $this->helpers->add_btn_filters( $items );
                                             }
                                             
                                             ?>
                                         </ul>
                                     </div>
                                 </div>
                                 
                                 <!-- Botón agregar items -->
                                 <div class="row">
                                     <div class="col s12">
                                         <button type="button" id="addItems" class="btn-jtcg jtcg-bg-azul"><?php _e('Agregar items', 'jtcg-textdomain'); ?> <i class="material-icons">add</i></button>
                                     </div>
                                 </div>
                                 
                                 <!-- Contenido de los items -->
                                 <div id="content_gallery" class="row">
                                     
                                     <ul class="jtcg-container">
                                    <?php
                                         
                                         if( $resultado[0]->data != '' ) {
                                             
                                             $output        = '';
                                             $val_columns   = $settings['columns'];
                                             
                                             switch( $val_columns ) {
                                                 case 2:
                                                     $column = 'm6';
                                                     break;
                                                 case 3:
                                                     $column = 'm4';
                                                     break;
                                                 case 4:
                                                     $column = 'm3';
                                                     break;
                                                 
                                             }
                                             
                                             foreach( $items as $item ){
                                                 
                                                 $media     = $item['media'];
                                                 $title     = $item['title'];
                                                 $filters   = $item['filters'];
                                                 $filters2  = $this->normalize->init( $item['filters'] );
                                                 $id        = $item['id'];
                                                 
                                                 if( $title != '' ) {
                                                     $title_output = "<div class='title-item'>
                                                                        <h5>$title</h5>
                                                                       </div>";
                                                 } else {
                                                     $title_output = '';
                                                 }
                                                 
                                                 $output .= "<li class='col $column jtcg-item' data-f='$filters2' data-id='$id' data-src='$media' data-value='media=$media;title=$title;filters=$filters;id=$id'>
                                                                <div class='jtcg-box'>
                                                                   <div class='edit-item'>
                                                                       <i class='material-icons'>edit</i>
                                                                   </div>
                                                                   $title_output
                                                                   <div class='remove-item'>
                                                                       <i class='material-icons'>close</i>
                                                                   </div>
                                                                    <div class='jtcg-masc'>
                                                                        <i class='material-icons jtcg_img'>zoom_in</i>
                                                                    </div>
                                                                    <img src='$media' alt='$title'>
                                                                </div>
                                                            </li>";
                                                 
                                             }
                                             
                                             echo $output;
                                             
                                         }
                                     ?>
                                     </ul>
                                 </div>
                             </section>
                             
                             <!-- Sección categría -->
                             <section id="category">
                                
                                <div class="loaderengine">
                                    <img src="<?php echo JTCG_PLUGIN_DIR_URL . '/admin/img/loader.gif'; ?>" alt="">
                                </div>
                                 
                                <div class="row">
                                     <div class="categoryTemplate">
                                         
                                         <?php
                                         
                                         if( $resultado[0]->data != '' ) {
                                             
                                             
                                             $outputItemsCard   = '';
                                             $val_columns       = $settings['columns'];
                                             $category          = $settings['category'];
                                             $postPerPage       = $settings['postPerPage'];
                                             $order             = $settings['order'];
                                             $orderby           = $settings['orderby'];
                                             
                                             switch( $val_columns ) {
                                                 case 2:
                                                     $column = 'm6';
                                                     break;
                                                 case 3:
                                                     $column = 'm4';
                                                     break;
                                                 case 4:
                                                     $column = 'm3';
                                                     break;
                                                 
                                             }
                                             
                                             if( ! is_null( $category ) && $category != '' ) {
                                                 
                                                 $args_query = [
                                                    'cat'               => $category,
                                                    'posts_per_page'    => $postPerPage,
                                                    'order'             => $order,
                                                    'orderby'           => $orderby
                                                ];

                                                $query = new WP_Query( $args_query );
                                                 
                                                 if( $query->have_posts() ) {
                                                     
                                                     while( $query->have_posts() ) {
                                                         
                                                         $query->the_post();
                                                         
                                                         $title     = get_the_title();
                                                         $excerpt   = get_the_excerpt();
                                                         $link      = get_the_permalink();
                                                         $time      = get_the_time( 'F j Y' );
                                                         
                                                         if( has_post_thumbnail() ) {
                                                            
                                                            $src = get_the_post_thumbnail_url(
                                                                get_the_ID(),
                                                                'medium'
                                                            );

                                                        } else {
                                                            $src = '';
                                                        }
                                                         
                                                         $outputItemsCard .= "
                                                            <div class='jtcg-carditem col s12 $column'>
                                                                <div class='card'>
                                                                    <div class='card-image'>
                                                                        <img src='$src' alt='$title'>
                                                                        <span class='card-title'>$title</span>
                                                                        <a target='_blank' href='$link' class='btn-floating halfway-fab waves-effect waves-light red'><i class='material-icons'>link</i></a>
                                                                    </div>
                                                                    <div class='card-content'>$excerpt</p>
                                                                    </div>
                                                                </div>
                                                            </div>";
                                                     }
                                                 }
                                                 
                                                 wp_reset_postdata();
                                                 
                                                 echo $outputItemsCard;
                                             }
                                         }
                                         
                                         ?>
                                         
                                    </div>
                                 </div>
                                 
                             </section>                             
                             
                         </div>
                         
                         <!-- Zona de ajustes -->
                         <div class="col m4">
                             
                             <div class="" style="border-left:1px solid #DDDDDD">
                                 
                                 <div class="row">
                                     
                                     <div class="col s12">
                                         <h5><?php echo _e( 'Ajustes', 'jtcg-textdomain' ); ?></h5>
                                         <div class="divider"></div>
                                     </div>
                                     
                                 </div>
                                 
                                 <?php
                                 
                                if( $resultado[0]->data != '' ) {
                                    $selected = '';
                                } else {
                                    $selected = 'selected';
                                }
                                 
                                 if( ! isset($val_columns) ) {
                                     $val_columns = '';
                                 }
                                 
                                 ?>
                                 
                                 <!-- Columnas -->
                                 <div class="row">
                                     <div class="col s12">
                                         <label for="columnas">Columnas</label>
                                         <select id="columnas">
                                             <option value="" disabled>Seleciona las columnas</option>
                                             <option value="2" <?php selected( $val_columns, 2 ) ?>>2</option>
                                             <option value="3" <?php echo $selected; selected( $val_columns, 3 ) ?>>3</option>
                                             <option value="4" <?php selected( $val_columns, 4 ) ?>>4</option>
                                         </select>
                                     </div>
                                 </div>
                                 
                                 <!-- Ajuste de la categoría -->
                                 <div id="setCategory">
                                     <div class="row">
                                         
                                         <!-- Categoría -->
                                         <div class="col s12">
                                            
                                             <label for="categorias">Categorías</label>
                                             <select id="categorias">
                                                 <option value="" selected disabled>Seleciona la categoría</option>
                                                 <?php

        $args = [
            'orderby'       => 'name',
            'taxonomy'      => 'category'
        ];
                                                     
         $categories = get_categories( $args );

         foreach( $categories as $category ){

             echo "<option value='{$category->cat_ID}'" . selected( $settings['category'], $category->cat_ID ) . " > ". ucfirst( $category->name ) ." </option>";

         }
    
                                                    ?>
                                                 
                                             </select>
                                             
                                         </div>
                                         
                                         <!-- Límite -->
                                         <div class="col s12">
                                             
                                             <div class="jtcg-input col s12">
                                                 <label for="limite"></label>
                                                 <input type="text" id="limite" value="<?php echo isset( $postPerPage ) && $postPerPage != '' ? $postPerPage : '-1'; ?>" disabled>
                                             </div>
                                             
                                         </div>
                                         
                                         <!-- Orden -->
                                         <div class="col s12">
                                            
                                             <label for="orden">Orden</label>
                                             <select id="orden" disabled>
                                                 <option value="" disabled>Selecciona orden</option>
                                                 <option value="desc" <?php echo $selected; selected( $order, 'desc' ) ?>>Descendente</option>
                                                 <option value="asc" <?php selected( $order, 'asc' ) ?>>Ascendente</option>
                                             </select>
                                             
                                         </div>
                                         
                                         <!-- Ordenar Por -->
                                         <div class="col s12">
                                            
                                             <label for="orderby">Ordenar por</label>
                                             <select id="orderby" disabled>
                                                 <option value="" disabled>Selecciona el orden por</option>
                                                 <option value="date" <?php echo $selected; selected( $orderby, 'date' ); ?>>Fecha</option>
                                                 <option value="author" <?php selected( $orderby, 'author' ); ?>>Autor</option>
                                                 <option value="title" <?php selected( $orderby, 'title' ); ?>>Título</option>
                                                 <option value="name" <?php selected( $orderby, 'name' ); ?>>Nombre (slug)</option>
                                                 <option value="rand" <?php selected( $orderby, 'rand' ); ?>>Aleatorio</option>
                                             </select>
                                             
                                         </div>
                                         
                                     </div>
                                 </div>                                 
                                 
                             </div>
                             
                         </div>
                         
                     </div>
                 </div>
                 
                 
                 
                  
              </div>
              
          </form>
          
      </div>
      
      <button type="button" id="guardar-items" class="btn-jtcg jtcg-bg-verde"><?php _e( 'Guardar', 'jtcg-textdomain' ); ?> <i class="material-icons">save</i> </button>
      <a href="?page=jtcg" id="cancelar" class="btn-jtcg jtcg-bg-azulC"><?php _e( 'Cancelar', 'jtcg-textdomain' ); ?> <i class="material-icons">close</i> </a>
      
</div>
