var gmpActiveTab = {}
,	gmpMapConstructParams = {
		mapContainerId: 'mapPreviewToNewMap'
	}
,	nochange = false;
function gmpGetEditorContent(editorId){
	if(typeof(editorId) == 'undefined') {
		return tinyMCE.activeEditor.getContent();            
	}
	return tinyMCE.editors[editorId].getContent();
}
function gmpSetEditorContent(content,editorId){
	if(content == '') {
		content = ' ';
	}
	if(typeof(editorId) == 'undefined') {
		try {
			tinyMCE.activeEditor.setContent(content);            
		} catch(e) {
			console.log(e);
		}
	} else {
		try {
		   tinyMCE.editors[editorId].setContent(content)
		} catch(e) {
			console.log(e);
		}          
	}
}
jQuery(document).ready(function(){
	jQuery('.gmpMapOptionsTab a').click(function(e){
		e.preventDefault();
	});
	jQuery('.gmpNewMapOptsTab a').click(function(e){
		jQuery('.gmpNewMapOptsTab a').removeClass('btn-primary');
		jQuery(this).addClass('btn-primary');
	});
	jQuery('.gmpShowNewMapFormBtn').click(function(){
		if(checkAdminFormSaved()) {
			gmpShowAddMap();
		}
		return false;
	});
	jQuery('#gmpEditMapContent').tabs({
		activate: function(event, ui) {
			ui.newTab.find('#gmpTabForNewMapOpts').size() 
			 ? gmpDoAction('afterMapTabOpen')
			 : gmpDoAction('afterMarkerTabOpen');
		}
	});
});

function gmpOpenMapForm() {
	// Clear messages from prev. usage
	jQuery('#gmpSaveEditedMapMsg').html('');
	// Hide list
	jQuery('#gmpAllMapsListShell').hide();
	// Show form
	jQuery('#gmpEditMapShell').show();
	selectTabMainGmp('gmpAllMaps');
	selectTab('gmpEditMapProperties', 'gmpEditMapContent');
	gmpClearMapForm();
	gmpCreateMapMarkersTable();
	gmpAddMarkerFormToMap();
	gmpClearMarkerForm();
	// Make All Maps tab - deactivated, as we now in edit map form
	jQuery('#gmpAdminOptionsTabs li a[href="#gmpAllMaps"]').parents('li:first').removeClass('ui-tabs-active');
	jQuery('.removeMarkerFromForm').attr('disabled', 'disabled');
	// Scroll to top in case we were on the bottom of the page
	window.scrollTo(0, 0);
	// Clear markers list from prev. map
	markerArr = {};
}
function gmpOpenMapLists() {
	jQuery('#gmpAllMapsListShell').show();
	jQuery('#gmpEditMapShell').hide();
}
function gmpShowAddMap() {
	gmpOpenMapForm();
	gmpDrawMap({
		mapContainerId: 'gmpEditMapsContainer'
	});
}
function gmpShowEditMap(id) {
	gmpOpenMapForm();
	gmpEditMap(id);
}
var gmpChangeEventBindedToMarkerDesc = false;
function gmpBindChangeEventToMarkerDesc() {
	if(!gmpChangeEventBindedToMarkerDesc && tinymce && tinymce.get('marker_opts_description')) {
		tinymce.get('marker_opts_description').onChange.add(function (ed, e) {
			changeAdminFormGmp('gmpAddMarkerToEditMap');
		});
		gmpChangeEventBindedToMarkerDesc = true;
	}
}
function gmpUnbindChangeEventToMarkerDesc() {
	gmpChangeEventBindedToMarkerDesc = false;
}
function gmpAddMarkerFormToMap() {
	if(!jQuery('#gmpMarkerMapFormShell').find('form').size()) {
		gmpUnbindChangeEventToMarkerDesc();
		gmpMarkerDescSetContent('');	// Clear editor content
		tinyMCE.execCommand('mceRemoveEditor', false, 'marker_opts_description');	// Deatach all events from editor
		jQuery('#gmpMarkerMapFormShell').append( jQuery('#gmpAddMarkerToEditMap') );// Move full form. with editor
		tinyMCE.execCommand('mceAddEditor', false, 'marker_opts_description');	// Attach events to editor - re-activa it
	}
	// Try to bind it each time we swith between markers forms
	gmpBindChangeEventToMarkerDesc();
}
function gmpAddMarkerFormToMarker() {
	if(!jQuery('#gmpMarkerSingleFormShell').find('form').size()) {
		gmpUnbindChangeEventToMarkerDesc();
		gmpMarkerDescSetContent('');	// Clear editor content
		tinyMCE.execCommand('mceRemoveEditor', false, 'marker_opts_description');	// Deatach all events from editor
		jQuery('#gmpMarkerSingleFormShell').append( jQuery('#gmpAddMarkerToEditMap') );	// Move full form. with editor
		tinyMCE.execCommand('mceAddEditor', false, 'marker_opts_description');	// Attach events to editor - re-activa it
	}
	// Try to bind it each time we swith between markers forms
	gmpBindChangeEventToMarkerDesc();
}
function gmpClearMapForm() {
	jQuery('#gmpEditMapForm')[0].reset();
	jQuery('#gmpEditMapForm').find('[name="map_opts[border_color]"]').css('background-color', '#fff');
	jQuery('#gmpEditMapForm').find('[name="map_opts[id]"]').val(0);
	jQuery('#gmpEditMapForm').find('[name="map_opts[map_center][coord_y]"]').val(0);
	jQuery('#gmpEditMapForm').find('[name="map_opts[map_center][coord_y]"]').val(0);
	jQuery('#gmpEditMapForm').find('[name="map_opts[custom_map_controls]"]').val('');
	jQuery('#gmpEditMapForm').find('[name="map_opts[stylization]"]').val('');
	// For PRO version
	if(jQuery('#gmpNewMap_Infowindow_markers_list_type').size()) {
		jQuery('#gmpNewMap_Infowindow_markers_list_type').trigger('change');
	}
	if(gmpMapEditMarkersTable) {
		gmpMapEditMarkersTable.fnClearTable();
	}
	//gmpMapNameTitleShow('');
	gmpMapIdShow('');
}
function gmpFormatAddress(addressObj){
	var finishAddr = [];
	var count = 0;
	var codes = ['street_address', 'route', 'administrative_area_level_1', 'country'];
	for(var i in addressObj){
		cur_addr = addressObj[i];
		switch(cur_addr.types[0]){
			case 'neighborhood':
				if(cur_addr.types[1] == 'political') {
					finishAddr.push(cur_addr.address_components[0].long_name);
				}
				break;
			case 'route':
			case 'street_address':
					finishAddr.push(cur_addr.address_components[0].long_name);
					finishAddr.push(cur_addr.address_components[1].long_name);
				break;
			case 'sublocalit':
				if(cur_addr.types[1] == 'political'){
					finishAddr.push(cur_addr.address_components[0].long_name);
				}
				break;
			case 'administrative_area_level_1':
				if(cur_addr.types[1] == 'political'){
					finishAddr.push(cur_addr.address_components[0].long_name);
				} 
				break;
			case 'locality':
				if(cur_addr.types[1] == 'political'){

				}
				break;
			case 'country':
				if(cur_addr.types[1] == 'political'){
					finishAddr.push(cur_addr.address_components[0].long_name);
				}
				break;
		}
	}
	finishAddr = arrayUnique(finishAddr);
	return finishAddr.join(', ');
}
function getGmapMarkerAddress(params, markerId, ret, callback){
	var latlng = new google.maps.LatLng(params.coord_y, params.coord_x);
	geocoder.geocode({'latLng': latlng}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if(results.length > 1){
				if(typeof(callback) != 'undefined') {
					var fAddress = gmpFormatAddress(results) ;
					callback.func({
						address: fAddress
					,	coord_x: params.coord_x
					,	coord_y: params.coord_y
					});
					return;
				} else if(ret != undefined) {
					return results[1].formatted_address;
				}
				markerArr[markerId].address = results[1].formatted_address;
			}
		} else {
			if(markerArr[markerId])
				markerArr[markerId].address = '';			
		}
	});
}