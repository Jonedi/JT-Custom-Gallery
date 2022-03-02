if( typeof jQuery == 'undefined' ) {
    throw new Error( 'JT Custom Gallery requiere de la librería jQuery' );
}

(function( $ ) {
    
    'use strict';
    
    /* Declarando Objeto y constructor */
    var JTCustomGallery = function( element, options, callback ) {
        this.$element = null;
        this.options  = null;
        this.zoomfull = '<div id="jtcg-zoom">\
                              <div>\
                                  <img src="" alt="">\
                              </div>\
                          </div>';
        this.overdark = '<div class="jtcg-overdark"></div>';
        
        this.init( element, options, callback );
        
    }
    
    JTCustomGallery.Defatuls = {
        filter      : '.jtcg-ul li',
        item        : '.jtcg-item',
        animation   : 'scale',
        callback    : null
    }
    
    JTCustomGallery.prototype.init = function( element, options, callback ) {
        
        this.$element   = $(element);
        this.options    = this.getOptions(options);
        
        this.$element.children().addClass( this.options.item.replace( '.', '' ) );
        this.filtro(this.options);
        
        $('body').prepend(this.zoomfull + this.overdark);        
        this.zoom();
        
        if( typeof callback == 'function' ) {
            callback.call(this);
        }
        
        if( typeof this.options.callback == 'function' ) {
            this.options.callback.call(this);
        }
        
    }
    
    JTCustomGallery.prototype.getDefatuls = function() {
        return JTCustomGallery.Defatuls;
    }
    
    JTCustomGallery.prototype.getOptions = function(options) {
        return $.extend({}, this.getDefatuls(), options);
    }
    
    JTCustomGallery.prototype.filtro = function(options) {
        
        $(document).on('click', options.filter, function(){
            
            var $this   = $(this),
                filtro  = $this.attr('data-filter'),
                $item   = $( options.item );
            
            if( filtro == '*' ) {
                $this.addClass('activo')
                    .siblings().removeClass('activo');
                
                /* Validando animaciones */
                
                if( options.animation == 'scale' ) {
                    $item.show('slow')                    
                } else if( options.animation == 'desvaneciendo' ) {
                    // ...
                }
                
            } else {
                
                if( ! $this.hasClass('activo') ) {
                    $this.addClass('activo')
                            .siblings().removeClass('activo');
                }
                
                /* Validando animaciones */
                
                if( options.animation == 'scale' ) {                    
                    $item.hide('slow');
                    setTimeout(function(){
                        $( '[data-f*="' + filtro + '"]' ).show('slow');
                    }, 700);
                } else if( options.animation == 'desvaneciendo' ) {
                    // ...
                }
                
            }
            
        });
        
    }
    
    JTCustomGallery.prototype.zoom = function() {
        
        var $overdark   = $('.jtcg-overdark'),
            $zoom       = $('#jtcg-zoom'),
            $zoomImg    = $('#jtcg-zoom img'),
            $jtcg_img   = $('.jtcg_img');
        
        $overdark.on('click', function(){
            $(this).fadeOut();
            $zoom.fadeOut();
        });
        
        $(document).on('click', '.jtcg_img', function(){
            
            var $this   = $(this),
                src     = $this.parent().parent().find('img').attr('src');
            
            $zoomImg.attr('src', src);
            
            $zoom.fadeIn();
            $overdark.fadeIn();
            
        });
        
    }
    
    /* $(element).jtcg( {}, function(){
        // ...
    }) */
    var Plugin = function( options, callback ) {
        return this.each(function(){
            var option  = typeof options == 'object' && options,
                data    = new JTCustomGallery( this, option, callback );
        });
    }
    
    var old = $.fn.jtcg;
    $.fn.jtcg = Plugin;
    $.fn.jtcg.Constructor = JTCustomGallery;
    
    $.fn.jtcg.noConflict = function() {
        $.fn.jtcg = old;
    }
    
})( jQuery );








