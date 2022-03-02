(function( global, jQuery, nucleo ) {
    
    nucleo( global, jQuery );
    
})( typeof window !== 'undefined' ? window : this, jQuery, function( window, $ ) {
        
    var Jtcustomg = (function(){
        
        var core = {
            
            /* Limpiador de enlaces para las imagenes */
            limpiarEnlace           : function( url ) {
        
                var local = /localhost/;

                if( local.test( url ) ) {

                    var url_pathname    = location.pathname,
                        indexPos        = url_pathname.indexOf( 'wp-admin' ),
                        url_pos         = url_pathname.substr( 0, indexPos ),
                        url_delete      = location.protocol + "//" + location.host + url_pos;

                    return url_pos + url.replace( url_delete, '' );

                } else {

                    var url_real = location.protocol + '//' + location.hostname;
                    return url.replace( url_real, '' );

                }

            },
            /* Validando que los campos no estén vacíos */
            validarCamposVacios     : function( selector ) {
        
                var $inputs = $( selector ),
                    result  = false;

                $.each( $inputs, function(k,v){

                    var $input      = $(v),
                        inputVal    = $input.val();

                    if( inputVal == '' && $input.attr('type') != 'file' ) {

                        if( ! $input.hasClass( 'invalid' ) ) {

                            $input.addClass( 'invalid' );

                        }

                        result = true;

                    }

                });

                if( result ) {
                    return true;
                } else {
                    return false;
                }

            },
            /* Método para validar los correos electrónicos */
            validarEmail            : function ( email ) {
        
                var er  = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;        

                return er.test( email );

            },
            /**
             * Permite quitar las clases invalid
             * de los formularios del framework
             * Materializecss
             */
            quitarInvalid           : function( selector ) {
        
                var $inputs = $( selector );

                $.each( $inputs, function(k,v){

                    var $input = $(v);

                    if( $input.hasClass( 'invalid' ) ) {
                        $input.removeClass( 'invalid' );
                    } else if( $input.hasClass( 'active' ) ) {
                        $input.removeClass( 'active' );
                    }

                });

            },
            /**
             * Itera las imagenes seleccionadas
             * para utilizar una template o estructura
             * y agregarlas dentro del contenedor
             * establecido
             */
            templateItems           : function ( arrItems ) {
        
                var template = '',
                    col      = parseInt($('#columnas').val()),
                    classCol = '';
                
                switch( col ) {
                    case 2:
                        classCol = 'm6';
                        break;
                    case 3:
                        classCol = 'm4';
                        break;
                    case 4:
                        classCol = 'm3';
                        break;
                }

                for( var i in arrItems ) {
                    
                    if( typeof arrItems[i] != 'function' ) {
                        
                        var url = this.limpiarEnlace( arrItems[i].url ),
                        id  = arrItems[i].id;

                        template += '<li class="col '+classCol+' jtcg-item" data-f="" data-id="'+id+'" data-src="'+url+'" data-value="media='+url+';id='+id+'">\
                                <div class="jtcg-box">\
                                   <div class="edit-item">\
                                       <i class="material-icons">edit</i>\
                                   </div>\
                                   <div class="remove-item">\
                                       <i class="material-icons">close</i>\
                                   </div>\
                                    <div class="jtcg-masc">\
                                        <i class="material-icons jtcg_img">zoom_in</i>\
                                    </div>\
                                    <img src="'+url+'" alt="">\
                                </div>\
                            </li>';
                        
                    }
                    

                }

                return template;

            },
            normalize : (function() {
              var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç", 
                  to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
                  mapping = {};

              for(var i = 0, j = from.length; i < j; i++ )
                  mapping[ from.charAt( i ) ] = to.charAt( i );

              return function( str ) {
                  var ret = [];
                  for( var i = 0, j = str.length; i < j; i++ ) {
                      var c = str.charAt( i );
                      if( mapping.hasOwnProperty( str.charAt( i ) ) )
                          ret.push( mapping[ c ] );
                      else
                          ret.push( c );
                  }      
                  ret = ret.join( '' );

                  var specialChars = "!@#$^&%*()+=-[]\/{}|:<>?.ç";

                  for( var i = 0, j = specialChars.length; i < j; i++ ) {
                      ret = ret.replace( new RegExp( "\\" + specialChars[i], 'gi' ), '' );
                  }

                  return ret;

              }

            })(),
            /* Analisador de filtros */
            analisadorFiltros       : function(selector) {
                
                var obj = [];
                
                $(selector).each(function(i){
                    
                    var $this       = $(this),
                        dataValue   = $this.attr('data-value'),
                        filters     = dataValue.split(';');
                    
                    var er  = /filters/,
                        r   = null,
                        arF = null;

                    for( var t in filters ) {
                        if( typeof filters[t] != 'function' ) {
                            r = filters[t].match(er);

                            if( r != null ) {
                                arF = r['input'].split('=');
                            }                            
                        }
                    }
                    
                    if( arF != null ) {
                                    
                        var arrAcentos      = arF[1].split(','),
                            arrSinAcentos   = Jtcustomg.normalize( arF[1] ).split(',');

                        obj[i] = [];

                        for( var e=0, j = arrAcentos.length; e < j; e++  ) {

                            obj[i][e] = {
                                title   : arrAcentos[e],
                                filtro  : arrSinAcentos[e].toLowerCase()
                            }                                    
                        }
                        
                    }
                    
                });
                
                var filtroFinal     = [],
                    arrAcentos      = [],
                    arrSinAcentos   = [],
                    c=0;
                
                for( var i in obj ) {
                    for( var e in obj[i] ) {
                        
                        if( typeof obj[i][e].title != 'undefined' ) {
                            arrAcentos[c]    = obj[i][e].title;
                            arrSinAcentos[c] = obj[i][e].filtro;
                            c++;
                        }
                        
                    }
                }
                
                var arrAcentosUnique        = arrAcentos.unique(),
                    arrSinAcentosUnique     = arrSinAcentos.unique();
                
                for( var i in arrAcentosUnique ) {
                    
                    if( i != 'unique' ) {
                        filtroFinal[i] = {
                            title   : arrAcentosUnique[i],
                            filtro  : arrSinAcentosUnique[i]
                        }
                    }
                    
                }
                
                return filtroFinal;
                
                
            },
            /* Crea una template de los botones de filtrado */
            templateBtnFilter               : function( arrFilter ) {
                
                var output = '<li data-filter="*" class="activo">Todo</li>';
                
                if( arrFilter != '' ) {
                    for( var i in arrFilter ) {
                        
                        if( 
                            arrFilter[i].filtro != '' &&
                            typeof arrFilter[i] != 'undefined' &&
                            typeof arrFilter[i] != 'function'
                        ) {
                            output += '<li data-filter="'+arrFilter[i].filtro+'">'+arrFilter[i].title.capitalize()+'</li>';
                        }
                        
                    }
                }
                
                return output;
                
            },
            /* Template para el título del item */
            addTitleItem                    : function( titulo ) {
                
                var template = '<div class="title-item">';
                                template+='<h5>'+titulo+'</h5>';
                                template+='</div>';
                
                return template;
                
            },
            /**
             * Convierte el array de items
             * en un objeto ordenado
             */
            toObject                        : function( arrItems ) {
                
                var obj = {
                    items : []
                };
                
                for( var i in arrItems ) {
                    
                    if(
                        typeof arrItems[i] != 'undefined' &&
                        typeof arrItems[i] != 'function'
                    ) {
                        
                        var arrItem = arrItems[i].split(';')
//                        console.log( arrItem );
                        obj.items[i] = {
                            media       : '',
                            title       : '',
                            filters     : '',
                            id          : ''
                        };
                        
                        for( var e in arrItem ) {
                            
                            if(
                                typeof arrItem[e] != 'undefined' &&
                                typeof arrItem[e] != 'function'
                            ) {
                                
                                var item = arrItem[e].split('=');
                                
                                switch( item[0] ) {
                                    case 'media':
                                        obj.items[i].media = item[1];
                                        break;
                                    case 'title':
                                        obj.items[i].title = item[1];
                                        break;
                                    case 'filters':
                                        obj.items[i].filters = item[1];
                                        break;
                                    case 'id':
                                        obj.items[i].id = item[1];
                                        break;
                                }
                                
                            }
                            
                        }
                        
                        
                    }
                    
                }
                
                return obj;
                
            },
            templateCardCategory                : function( posts ) {
                
                var template = '',
                    col      = parseInt($('#columnas').val()),
                    classCol = '';
                
                switch( col ) {
                    case 2:
                        classCol = 'm6';
                        break;
                    case 3:
                        classCol = 'm4';
                        break;
                    case 4:
                        classCol = 'm3';
                        break;
                }
                
                for( var i=0, p=posts.length; i<p; i++) {
                    
                    template += '<div class="jtcg-carditem col s12 '+classCol+'">';
                            template += '<div class="card">';
                                template += '<div class="card-image">';
                                    template += '<img src="'+posts[i].imgurl+'">';
                                    template += '<span class="card-title">'+posts[i].title+'</span>';
                                    template += '<a target="_blank" href="'+posts[i].link+'" class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">link</i></a>';
                                template += '</div>';
                                template += '<div class="card-content">'+posts[i].excerpt+'</p>';
                                template += '</div>';
                            template += '</div>';
                        template += '</div>';
                    
                }
                
                return template;
                
            }
            
        }
        
        return core;
        
    })();
    
    window.Jtcustomg = window.$bc = Jtcustomg;
    
});

Array.prototype.unique = function(a) {
    return function() {
        return this.filter(a);
    }
}(function(a,b,c){
    return c.indexOf(a,b+1)<0
});

String.prototype.capitalize = function(a) {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
