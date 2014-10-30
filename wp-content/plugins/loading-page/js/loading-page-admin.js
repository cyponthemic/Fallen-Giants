(function($){
    // Main application
    window['loading_page_selected_image'] = function(fieldName){
        var img_field = $('input[name="'+fieldName+'"]');
        var media = wp.media({
				title: 'Select Media File',
				library:{
					type: 'image'
				},
				button: {
				text: 'Select Item'
				},
				multiple: false
		}).on('select', 
			(function( field ){
				return function() {
					var attachment = media.state().get('selection').first().toJSON();
					var url = attachment.url;
					field.val( url );
				};
			})( img_field )	
		).open();
		return false;
    };
    
    window['loading_page_display_screen_tips'] = function(e){
        t = $(e.options[e.selectedIndex]).attr('title');
        if(t && t.length){
            alert(t);
        }
    }
    
    function setPicker(field, colorPicker){
        $(colorPicker).hide();
        $(colorPicker).farbtastic(field);
        $(field).click(function(){$(colorPicker).slideToggle()});
    };
    
    $(function(){
        setPicker("#lp_backgroundColor", "#lp_backgroundColor_picker");
        setPicker("#lp_foregroundColor", "#lp_foregroundColor_picker");
    });
})(jQuery);

