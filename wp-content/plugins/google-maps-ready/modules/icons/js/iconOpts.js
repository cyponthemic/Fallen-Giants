function gmpUploadNewIconStart(param){
    jQuery('.gmpFileUpRes img').attr('src', GMP_DATA.loader);
}
function drawNewIcon(icon){
	console.log(icon);
	if(typeof(icon.data) == undefined){
		return;
	}
	var newElem = '<a class="markerIconItem active" data_name="'+icon.title+'" data_desc="'+icon.description+'" '
		newElem += 'title="'+icon.title+'" data_val="'+icon.id+'">';
		newElem += '<img src="'+icon.url+'" class="gmpMarkerIconFile">';
		newElem += '<span class="gmpMarkerIconRemoveBtn gmpHidden"><img src="'+ GMP_DATA.imgPath+ 'delete.png" /></span>';
		newElem += '</a>';
	jQuery('.markerIconItem').removeClass('active');
	gmpCurrentMarkerForm.find('.gmpIconsList').prepend(newElem);
	jQuery('.gmpIconsList').scrollTop( 0 );
	if(gmpExistsIcons == undefined){
		gmpExistsIcons = [];
	}
	gmpExistsIcons[icon.id] = icon;
	gmpExistsIcons[icon.id].path = icon.url;
	gmpCurrentIcon = icon.id;
}

function setcurrentIconToForm(iconId, markerForm){
    markerForm.find('#gmpSelectedIcon').val(iconId);
    markerForm.find('.markerIconItem.active').removeClass('active');
    var currItm = markerForm.find('.markerIconItem[data_val="'+ iconId+ '"]');
	currItm.addClass('active');
	markerForm.find('.gmpIconsList').scrollTo(currItm);
    gmpCurrentIcon = iconId;
}

var custom_uploader;
jQuery(document).ready(function(){
    gmpCurrentIcon = jQuery("#gmpAddMarkerToNewForm").find("#gmpSelectedIcon").val();
    jQuery('body').on('change', '#gmpSelectedIcon', function(){
        gmpCurrentIcon = jQuery(this).val();
    });
    jQuery('body').on('change', '#gmpSelectedIcon_edit', function(){
        gmpCurrentIcon = jQuery(this).val();
    });
	jQuery('.gmpIconsList').on('click', '.gmpMarkerIconRemoveBtn', function(e){
		var parentLink = jQuery(this).parents('.markerIconItem')
		,	iconId = parentLink.attr('data_val');
		if(iconId && confirm(toeLangGmp('Are you sure want to delete this icon?'))) {
			jQuery(this).removeClass('gmpHidden');
			jQuery.sendFormGmp({
				msgElID: jQuery(this)
			,	data: {page: 'icons', action: 'remove', id: iconId, reqType: 'ajax'}
			,	onSuccess: function(res) {
					if(!res.error) {
						parentLink.animateRemove(500);
					}
				}
			});
		}
		return false;
	});
  /* 
   * wp media upload
   * 
   */
	jQuery('.gmpUploadIcon').click(function(e){
		e.preventDefault();
         //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
         //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image'
		,	button: {
                text: 'Choose Image'
            }
		,	multiple: false
        });
        //When a file is selected, grab the URL and set it as the text field's value
        var currentForm = jQuery(this).parents('form');
        custom_uploader.on('select', function(){
			var attachment = custom_uploader.state().get('selection').first().toJSON()
            ,	respElem = jQuery('.gmpUplRes')
			,	sendData={
					page: 'icons'
				,	action: 'saveNewIcon'
				,	reqType: 'ajax'
				,	icon: {
						url: attachment.url
					}
				};
				if(attachment.title != undefined){
					sendData.icon.title = attachment.title;
				}
                if(attachment.description != undefined){
                    sendData.icon.description = attachment.description;
                }
                jQuery.sendFormGmp({
					msgElID: respElem
				,	data: sendData
				,	onSuccess: function(res){
						if(!res.error) {
                           var newItem = drawNewIcon(res.data);
						} else {
                           respElem.html(data.error.join(','));
						}
					}
                });
        });
        //Open the uploader dialog
        custom_uploader.open();
    });
    jQuery('.gmpIconsList').on('click', '.markerIconItem', function(){
		jQuery('.markerIconItem').removeClass('active');
		jQuery(this).addClass('active');
		var value = jQuery(this).attr("data_val");
		jQuery(this).parents(".gmpFormRow").find("#gmpSelectedIcon").val(value);
		gmpCurrentIcon = value;
    });
	jQuery('.gmpSearchIconField').keyup(function(e){
		var search_word = jQuery(this).val();
		if(search_word == '') {
			jQuery('.markerIconItem').show();
			return;
		}
		if(search_word.length < 2) {
			return false;
		}
		jQuery('.markerIconItem').each(function(){
			var itmDesc=jQuery(this).attr('data_desc');
			var name=jQuery(this).attr('data_name');
			if(itmDesc.indexOf(search_word) == -1){
				jQuery(this).hide();
			}
		});
	});
});
function clearIconSearch(){
    jQuery('.gmpSearchIconField').val('');
    jQuery('.markerIconItem').show();
}