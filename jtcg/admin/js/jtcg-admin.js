(function( $ ) {
	'use strict';

	/**
	 * Todo el código Javascript orientado a la administración
	 * debe estar escrito aquí
	 */

    $('.jtcg-container').jtcg();

    var $precargador    = $('.precargador'),
        urledit         = "?page=jtcg&action=edit&id=",
        idTable        = $('#idTable').val(),
        marco;

    $('.modal').modal();
    $('select').material_select();

    /* Modal con formulario para crear tabla */
    $('.addjtcg').on( 'click', function(e){
        e.preventDefault();
        $('#addjtcg').modal('open');
    });

    var $type               = $('#type'),
        type_val            = null,
        $select             = $('.select-dropdown'),
        $sectionCustom      = $('#custom'),
        $sectionCategory    = $('#category'),
        $setCategory        = $('#setCategory');

    /**
     * Analisando el tipo de galería
     * para mostrar y ocultar ajustes y secciones
     */

    if( $type.val() == 'custom' ) {
        $sectionCategory.css('display','none');
        $setCategory.css('display','none');
        $sectionCustom.css('display','block');
    } else if( $type.val() == 'category' ) {
        $sectionCustom.css('display','none');
        $sectionCategory.css('display','block');
        $setCategory.css('display','block');
    }

    $type.on('change', function(){

        type_val = $(this).val();

        if( $select.hasClass('invalid') ) {
            $select.removeClass('invalid');
            $select.addClass('valid');
        }

        if( type_val == 'custom' ) {
            $sectionCategory.css('display','none');
            $setCategory.css('display','none');
            $sectionCustom.css('display','block');
        } else if( type_val == 'category' ) {
            $sectionCustom.css('display','none');
            $sectionCategory.css('display','block');
            $setCategory.css('display','block');
        }

    });

    /**
     * Evento click para guardar
     * el registro en la base de datos
     * utilizando AJAX
     */
    $('#crear-jtcg').on( 'click', function(e){
        e.preventDefault();

        var $nombre = $('#nombre-jtcg'),
            nv =  $nombre.val();

        $precargador.css( 'display', 'flex' );

        if( nv == '' ) {

            $precargador.css( 'display', 'none' );

            if( ! $nombre.hasClass('invalid') ) {
                $nombre.addClass('invalid');
            }

        } else if ( type_val == null || type_val == '' ) {

            $precargador.css( 'display', 'none' );

            if( ! $select.hasClass('invalid') ) {
                $select.addClass('invalid');
                $select.removeClass('valid');
            }

        } else {

            // Envío de AJAX

            $.ajax({
                url         : jtcg.url,
                type        : 'POST',
                dataType    : 'json',
                data : {
                    action  : 'jtcg_crud_gallery',
                    nonce   : jtcg.seguridad,
                    nombre  : nv,
                    type_val: type_val,
                    tipo    : 'add'
                }, success  : function( data ) {

                    if( data.result ) {

                        urledit += data.insert_id;

                        setTimeout(function(){
                            location.href = urledit;
                        }, 1300 );

                    }

                }, error: function( d,x,v ) {

                    console.log(d);
                    console.log(x);
                    console.log(v);

                }
            });

        }

    });

    /**
     * Interacción del botón editar galería
     */

    $(document).on('click', '[idjtcgedit]', function(){
        var id = $(this).attr('idjtcgedit');
        location.href = urledit+id;
    });

    /**
     * Interacción del botón eliminar galería
     */

    $(document).on('click', '[idjtcgremove]', function(){

        var id      = $(this).attr('idjtcgremove'),
            $tr     = $('tr[data-jtcg="'+id+'"]'),
            nombre  = $tr.find( $('td:nth-child(1)') ).text();

        swal({
            title               : "¿Estás seguro que quieres eliminar la galería '" + nombre + "'?",
            text                : "No podrás deshacer esto!",
            type                : "warning",
            showCancelButton    : true,
            confirmButtonColor  : "#DD6B55",
            confirmButtonText   : "Si, borrarlo",
            closeOnConfirm      : false,
            showLoaderOnConfirm : true,
            html                : true
        }, function(isConfirm){

            if( isConfirm ) {

                $.ajax({
                    url         : jtcg.url,
                    type        : 'POST',
                    dataType    : 'json',
                    data : {
                        action      : 'jtcg_crud_gallery',
                        nonce       : jtcg.seguridad,
                        tipo        : 'delete',
                        idgal       : id
                    }, success : function( data ) {

                        if( data.result ) {

                            setTimeout(function(){

                                swal({
                                    title       : "Borrado",
                                    text        : "La galería " + nombre + " ha sido eliminado",
                                    type        : "success",
                                    timer       : 1500
                                });
                                $tr.css({
                                    "background" : "red",
                                    "color"      : "white"
                                }).fadeOut(600)
                                setTimeout(function(){
                                    $tr.remove();
                                }, 1000);

                            }, 1500);

                        } else {

                            swal({
                                title   : 'Error',
                                text    : 'Hubo un error al eliminar la galería, por favor intenta más tarde',
                                type    : 'error',
                                timer   : 2000
                            });

                        }

                    }, error: function( d,x,v ) {

                        console.log(d);
                        console.log(x);
                        console.log(v);

                    }
                });

            } else {



            }

        });

    });

    var $addItems = $('#addItems'),
        marco;

    $addItems.on('click', function(){

        if( marco ) {
            marco.open();
            return;
        }

        marco = wp.media({
            title    : 'Seleccionar esta imagen',
            button   : {
                text : 'Usar estas imágenes'
            },
            multiple : true,
            library  : {
                type  : 'image'
            }
        });

        marco.on('select', function(){

            var adjuntos = marco.state().get('selection').map(function(adj){
                return adj.toJSON();
            });

            var output = Jtcustomg.templateItems( adjuntos );

            $('.jtcg-container').append( output );

        });

        marco.open();

    });

    $('#columnas').on('change', function(){

        var $this       = $(this),
            valor       = parseInt( $this.val() ),
            $items      = $('.jtcg-item'),
            $itemsCat   = $('.jtcg-carditem');

        if( $items.length ) {

            var arrClass = $items.attr('class').split(' '),
                er       = /m[346]/,
                r        = null,
                col      = null;

            for( var i in arrClass ) {
                if( typeof arrClass[i] != 'function' ) {

                    r = arrClass[i].match( er );

                    if( r != null ) col = r['input'];

                }

            }

            if( col != null ) {

                if( $items.hasClass(col) ) {

                    $items.removeClass(col);

                    switch( valor ) {
                        case 2:
                            $items.addClass('m6');
                            break;
                        case 3:
                            $items.addClass('m4');
                            break;
                        case 4:
                            $items.addClass('m3');
                            break;
                    }

                }

            }

        }

        if( $itemsCat.length ) {

            var arrClass = $itemsCat.attr('class').split(' '),
                er       = /m[346]/,
                r        = null,
                col      = null;

            for( var i in arrClass ) {

                if( typeof arrClass[i] != 'function' ) {
                    r = arrClass[i].match( er );

                    if( r != null ) col = r['input'];

                }

            }

            if( col != null ) {

                if( $itemsCat.hasClass(col) ) {

                    $itemsCat.removeClass(col);

                    switch( valor ) {
                        case 2:
                            $itemsCat.addClass('m6');
                            break;
                        case 3:
                            $itemsCat.addClass('m4');
                            break;
                        case 4:
                            $itemsCat.addClass('m3');
                            break;
                    }

                }

            }

        }

    });

    /* Acción editar items */
    $(document).on('click', '.edit-item', function(){

        $('#bpcg-item-edit').modal('open');

        var $li         = $(this).parents('li'),
            id          = $li.attr('data-id'),
            title       = $li.attr('data-title'),
            src         = $li.attr('data-src'),
            dataVualue  = $li.attr('data-value'),
            filters     = dataVualue.split(';');

        var er  = /filters/,
            r   = null,
            arF = null;

        for( var i in filters ) {

            if( typeof filters[i] != 'function' ) {

                r = filters[i].match(er);

                if( r != null ) {
                    arF = r['input'].split('=');
                }

            }

        }

        if( arF != null ) {
            $('#edit-item-filters').val( arF[1] );
        } else {
            $('#edit-item-filters').val( '' );
        }

        $('#edit-item-id').val(id);
        $('#edit-item-title').val(title);
        $('#edit-item-img').attr( {
            'src'       : src,
            'data-id'   : id
        });

    });

    var $changeImgItem = $('#change-img-item'),
        mediaSingle;

    $changeImgItem.on('click', function(){

        if( mediaSingle ) {
            mediaSingle.open();
            return;
        }

        mediaSingle = wp.media({
            title    : 'Seleccionar imagen a cambiar',
            button   : {
                text    : 'Usar esta imagen'
            },
            multiple : false
        });

        mediaSingle.on('select', function(){

            var adjunto = mediaSingle.state().get('selection').first().toJSON(),
                url     = Jtcustomg.limpiarEnlace(adjunto.url);

            $('#edit-item-img').attr({
                'src'       : url,
                'data-id'   : adjunto.id
            });

        });

        mediaSingle.open();

    });

    /* Acción actualizar item */
    $('#update-item').on('click', function(){

        var id      = $('#edit-item-id').val(),
            title   = $('#edit-item-title').val(),
            src     = $('#edit-item-img').attr('src'),
            filters = $('#edit-item-filters').val().toLowerCase();

        var $item = $('[data-id="'+id+'"]');
        $item.find('img').attr('src',src);
        var idN = $('#edit-item-img').attr('data-id');

        var valsUpdate = [
            'media='+src,
            'title='+title,
            'filters='+filters,
            'id='+idN
        ];



        $item.attr({
            'data-value'    : valsUpdate.join(';'),
            'data-title'    : title,
            'data-src'      : src,
            'data-f'        : Jtcustomg.normalize(filters),
            'data-id'       : idN
        });

        var $titleItem = $item.find('.title-item h5');

        if( $titleItem.length ) {

            if( title != '' ) {
                $titleItem.text(title);
            } else {
                $('.title-item').remove();
            }

        } else {
            if( title != '' ) {
                var output = Jtcustomg.addTitleItem(title);
                $item.find('.jtcg-masc').before(output);
            }
        }

        if( $('.jtcg-container li').length ) {

            $('ul.jtcg-ul').find('li').remove();

            var filtersArr = Jtcustomg.analisadorFiltros('.jtcg-container li');

            $('ul.jtcg-ul').append( Jtcustomg.templateBtnFilter( filtersArr ) );

        }

    });

    $('.jtcg-container').sortable();

    /**
     * Guardar los datos en la base de datos
     * enviandolo mediante AJAX en
     * formato JSON
     */
    $('#guardar-items').on('click', function(){

        var dataValueItems  = $('.jtcg-container').sortable( 'toArray', {attribute:'data-value'} ),
            objItems        = Jtcustomg.toObject(dataValueItems),
            idgaljtcg       = $('#idgaljtcg').val(),
            nombregaljtcg   = $('#nombregaljtcg').val(),
            type            = $('#type').val(),
            columnas        = $('#columnas').val(),
            /* Valores para las categorías */
            category        = $('#categorias').val(),
            postPerPage     = $('#limite').val(),
            order           = $('#orden').val(),
            orderby         = $('#orderby').val(),
            jtcgMaster      = {
                jtcg  : {
                    name : nombregaljtcg
                }
            },
            conf = {
                settings : {
                    columns     : columnas,
                    category    : category,
                    postPerPage : postPerPage,
                    order       : order,
                    orderby     : orderby
                }
            },
            data = $.extend({}, jtcgMaster, objItems, conf);

        $.ajax({
            url         : jtcg.url,
            type        : 'POST',
            dataType    : 'json',
            data : {
                action          : 'jtcg_data',
                nonce           : jtcg.seguridad,
                idgaljtcg       : idgaljtcg,
                nombregaljtcg   : nombregaljtcg,
                type            : type,
                data            : JSON.stringify(data)
            }, success  : function( data ) {

                if( data.result ) {

                    swal({
                        title   : 'Guardado!',
                        text    : 'La información se ha guardado con éxito',
                        type    : 'success',
                        timer   : 1500
                    });

                }

            }, error: function( d,x,v ) {

                console.log(d);
                console.log(x);
                console.log(v);

            }
        });

    });

    /* Acción remover items */
    $(document).on('click', '.remove-item', function(){

        $(this).parents('li').remove();

        $('ul.jtcg-ul').find('li').remove();

        if( $('.jtcg-container li').length ) {
            var filtersArr = Jtcustomg.analisadorFiltros('.jtcg-container li');
        } else {
            var filterArr = '';
        }

        $('ul.jtcg-ul').append( Jtcustomg.templateBtnFilter( filtersArr ) );

    });

    /**
     * Analisando la selección de una categoría
     * para mostrar la información de los post
     */

    var $categorias         = $('#categorias'),
        $limite             = $('#limite'),
        $orden              = $('#orden'),
        $orderby            = $('#orderby'),
        $categoryTemplate   = $('.categoryTemplate'),
        $loaderengine       = $('.loaderengine');

    if( $categorias.val() != null && ! isNaN( $categorias.val() ) ) {

        $limite.prop( 'disabled', false );
        $orden.prop( 'disabled', false );
        $orderby.prop( 'disabled', false );
        $('select').material_select();

    } else {

        $limite.prop( 'disabled', true );
        $orden.prop( 'disabled', true );
        $orderby.prop( 'disabled', true );
        $('select').material_select();

    }

    $categorias.on('change', function(){

        var catValue        = $(this).val(),
            postPerPage     = $limite.val(),
            orden           = $orden.val(),
            orderby         = $orderby.val();

        if( catValue != null && ! isNaN( catValue ) ) {

            $limite.prop( 'disabled', false );
            $orden.prop( 'disabled', false );
            $orderby.prop( 'disabled', false );
            $('select').material_select();

        } else {

            $limite.prop( 'disabled', true );
            $orden.prop( 'disabled', true );
            $orderby.prop( 'disabled', true );
            $('select').material_select();

        }

        $('.categoryTemplate > *').remove();
        $loaderengine.css('display','block');

        $.ajax({
            url         : jtcg.url,
            type        : 'POST',
            dataType    : 'json',
            data : {
                action          : 'jtcg_categorias',
                nonce           : jtcg.seguridad,
                cat_ID          : catValue,
                postPerPage     : postPerPage,
                orden           : orden,
                orderby         : orderby
            }, success  : function( data ) {

                $loaderengine.css('display','none');
                $categoryTemplate.append( Jtcustomg.templateCardCategory( data.posts ) );


            }, error: function( d,x,v ) {

                console.log(d);
                console.log(x);
                console.log(v);

            }
        });

    });

    /* Analisando el cambio del límite */
    $limite.on('keyup', function(){

        var postPerPage     = $(this).val(),
            catValue        = $categorias.val(),
            orden           = $orden.val(),
            orderby         = $orderby.val();

        if(
            postPerPage != '' &&
            postPerPage != null &&
            ! isNaN(postPerPage)
        ) {

            $('.categoryTemplate > *').remove();
            $loaderengine.css('display','block');

            $.ajax({
                url         : jtcg.url,
                type        : 'POST',
                dataType    : 'json',
                data : {
                    action          : 'jtcg_categorias',
                    nonce           : jtcg.seguridad,
                    cat_ID          : catValue,
                    postPerPage     : postPerPage,
                    orden           : orden,
                    orderby         : orderby
                }, success  : function( data ) {

                    $loaderengine.css('display','none');
                    $categoryTemplate.append( Jtcustomg.templateCardCategory( data.posts ) );


                }, error: function( d,x,v ) {

                    console.log(d);
                    console.log(x);
                    console.log(v);

                }
            });

        }

    });

    /* Analisando el cambio de orden */
    $orden.on('change', function(){

        var orden           = $(this).val(),
            catValue        = $categorias.val(),
            postPerPage     = $limite.val(),
            orderby         = $orderby.val();

        $('.categoryTemplate > *').remove();
        $loaderengine.css('display','block');

        $.ajax({
            url         : jtcg.url,
            type        : 'POST',
            dataType    : 'json',
            data : {
                action          : 'jtcg_categorias',
                nonce           : jtcg.seguridad,
                cat_ID          : catValue,
                postPerPage     : postPerPage,
                orden           : orden,
                orderby         : orderby
            }, success  : function( data ) {

                $loaderengine.css('display','none');
                $categoryTemplate.append( Jtcustomg.templateCardCategory( data.posts ) );


            }, error: function( d,x,v ) {

                console.log(d);
                console.log(x);
                console.log(v);

            }
        });

    });

    /* Analisando el cambio de ordenar por */
    $orderby.on('change', function(){

        var orderby         = $(this).val(),
            catValue        = $categorias.val(),
            postPerPage     = $limite.val(),
            orden           = $orden.val();


        $('.categoryTemplate > *').remove();
        $loaderengine.css('display','block');

        $.ajax({
            url         : jtcg.url,
            type        : 'POST',
            dataType    : 'json',
            data : {
                action          : 'jtcg_categorias',
                nonce           : jtcg.seguridad,
                cat_ID          : catValue,
                postPerPage     : postPerPage,
                orden           : orden,
                orderby         : orderby
            }, success  : function( data ) {

                $loaderengine.css('display','none');
                $categoryTemplate.append( Jtcustomg.templateCardCategory( data.posts ) );


            }, error: function( d,x,v ) {

                console.log(d);
                console.log(x);
                console.log(v);

            }
        });

    });


})( jQuery );
