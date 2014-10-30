(function ($) {
    /*Browser detection patch*/
    var browser = {};
    browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
    browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
    browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
    browser.msie = /msie/.test(navigator.userAgent.toLowerCase());
	
    if (!Array.prototype.indexOf){
	   Array.prototype.indexOf = function(elt /*, from*/){
         var len  = this.length >>> 0;
         var from = Number(arguments[1]) || 0;
             from = (from < 0)
                  ? Math.ceil(from)
                  : Math.floor(from);
         if (from < 0)
             from += len;

             for (; from < len; from++)
                 {
                 if (from in this &&
                 this[from] === elt)
                 return from;
                 }
         return -1;
       };
    }
    
    // Defining namespace
    $.loadingpage = $.loadingpage || {};

    var lp      = $.loadingpage, // Namespace shortcut
        // Global variables
        
        // Lazy load variables
        $window = $(window),
        
        // Loading page variables
        images = new Array,
        done = 0,
        destroyed = false,
        imageContainer = "",
        imageCounter = 0,
        start = 0,
            
        // Default options
        
        default_options = {
            // Options for lazy load
            threshold: 100,
            effect: "show",
            effectspeed: 0,
            
            // Options for loading page
            loadingScreen: true,
            graphic : 'bar',
            onComplete: function () {}, // callback for loading page complete
            backgroundColor: "#000",
            foregroundColor: "#fff",
            text: 1,
            deepSearch: true,
            pageEffect: 'none'
        },
        
        options; // Default options extended with values passed as parameters
        
    
    // Methods used in loading page
    lp.graphicAction = function( action, params ){
        if( typeof lp.graphics != 'undefined' && typeof lp.graphics[options.graphic] != 'undefined' && lp.graphics[options.graphic].created )
        {
            lp.graphics[ options.graphic ][ action ]( params );
        }
    };

    lp.ApplyAnimationToElement = function(animName) {
        $('body').addClass('lp-'+animName);
    };
    
    lp.onLoadComplete = function () {
        lp.graphicAction( 'complete', function(){ lp.ApplyAnimationToElement(options.pageEffect); options.onComplete; } );
    };
    
    lp.afterEach = function () {
        //start timer
        var currentTime = new Date();
        start = currentTime.getTime();

        lp.createPreloadContainer();
        lp.createOverlayLoader();
    };

    lp.createPreloadContainer = function() {
        imageContainer = $("<div></div>").appendTo("body").css({
            display: "none",
            width: 0,
            height: 0,
            overflow: "hidden"
        });
        var d = document.domain;
        imageCounter = images.length;
        for (var i = 0; imageCounter > i; i++) {
            if( images[i].indexOf( d ) == -1 )
            {
                lp.completeImageLoading();
                continue;
            }            
            $.ajax({
                url: images[i],
                type: 'HEAD',
				timeout: 3000,
                complete: function(data) {
                    if(!destroyed){
						if( data.status==200 )
						{
							lp.addImageForPreload(this['url']);
						}
						else
						{
							lp.completeImageLoading();
						}
                    }
                }
            });
        }        	

    };
    
    lp.addImageForPreload = function(url) {
		var image = $("<img />").attr("src", url).bind("load", function () {
            lp.completeImageLoading();
        }).appendTo(imageContainer);
    };

    lp.completeImageLoading = function () {
        done++;
        var percentage = (done / imageCounter) * 100;
        lp.graphicAction( 'set', percentage );
        
        if (done == imageCounter) {
            lp.destroyLoader();
        }
    };

    lp.destroyLoader = function () {
        $(imageContainer).remove();
        lp.onLoadComplete();
        destroyed = true;
    };

    lp.createOverlayLoader = function () {
        if ( !images.length) {
        	lp.destroyLoader()
        }
    };
    
    lp.findImageInElement = function (element) {
        var url = "";

        if ($(element).css("background-image") != "none") {
            var backImg = $(element).css("background-image");
            if( /\.(png|gif|jpg|jpeg|bmp)/i.test(backImg)) url = backImg;
        } else if (typeof($(element).attr("src")) != "undefined" && element.nodeName.toLowerCase() == "img") {
            var url = $(element).attr("src");
        }

        if (url.indexOf("gradient") == -1) {
            url = url.replace(/url\(\"/g, "");
            url = url.replace(/url\(/g, "");
            url = url.replace(/\"\)/g, "");
            url = url.replace(/\)/g, "");

            var urls = url.split(", ");

            for (var i = 0; i < urls.length; i++) {
                if (urls[i].length > 0 && images.indexOf(urls[i]) == -1) {
                    var extra = "";
                    if (browser.msie && browser.version < 9) {
                        extra = "?" + Math.floor(Math.random() * 3000);
                    }
                    images.push(urls[i] + extra);
                }
            }
        }
    };
    
    
    
    $.fn.loadingpage = function(o){
        options = $.extend(
            default_options, o || {}
        );
        
        // loading page
        if(options['loadingScreen']*1){
            this.each(function() {
                lp.findImageInElement(this);
                if (options.deepSearch == true) {
                    $(this).find("*:not(script)").each(function() {
                        lp.findImageInElement(this);
                    });
                }
            });

            lp.afterEach();
        }    
        return this;
    };
	
	if( typeof loading_page_settings != 'undefined' ){
		// Check for body existence and insert the loading screen if enabled
		window[ 'loading_page_available_body' ] = function(){
			var b = jQuery("body");
			if( b.length )
			{
				var options = $.extend(
					default_options, loading_page_settings || {}
				);
                
                options[ 'text' ] *= 1;
                
				if( options['loadingScreen']*1 )
				{
					if( ( typeof lp.graphics != 'undefined' ) && ( typeof lp.graphics[options.graphic] != 'undefined' ) )
					{
						lp.graphics[options.graphic].create(options);
                        b.css( 'visibility', 'visible' );
					}
					else
					{
						setTimeout( function(){ loading_page_available_body(); }, 30 );
					}	
				}	
			}
			else
			{
				setTimeout( function(){ loading_page_available_body(); }, 30 );
			}	
		}	
		
		loading_page_available_body( loading_page_settings );
		
		// Define the on-load event handle
		jQuery(document).ready(function () {
			jQuery("body").loadingpage( loading_page_settings );
		});
	}
})(jQuery);