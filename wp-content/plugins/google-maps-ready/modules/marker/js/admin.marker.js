var gmpMarkersTblColumns = []
,	gmpMarkersTbl = null;
jQuery(document).ready(function(){
	jQuery('.gmp_marker_address').mapSearchAutocompleateGmp({
		msgEl: '.gmpAddrErrors'
	,	onSelect: function(item) {
			jQuery('#gmp_marker_coord_y').val(item.lat);
			jQuery('#gmp_marker_coord_x').val(item.lng);
		}
	});
	gmpRefreshMarkerList();
	jQuery('#gmpTableMarkers').on('change', 'input[type=checkbox]', function(e){
		var name = jQuery(this).attr('name')
		,	checked = jQuery(this).attr('checked');
		if(name == 'check_all_markers') {
			var allCheckboxes = jQuery('#gmpTableMarkers').find('input[type=checkbox]:not([name="check_all_markers"])');
			checked ? allCheckboxes.attr('checked', 'checked') : allCheckboxes.removeAttr('checked');
		} else {
			var markerId = name.split('[');
			markerId = markerId[1].split(']');
			markerId = parseInt(markerId[0]);
		}
		var totalSelectedCheckboxes = jQuery('#gmpTableMarkers').find('input[type=checkbox]:not([name="check_all_markers"]):checked').size()
		,	selectAllCheckbox = jQuery('#gmpTableMarkers').find('input[type=checkbox][name="check_all_markers"]');
		if(totalSelectedCheckboxes) {
			var totalCheckboxes = jQuery('#gmpTableMarkers').find('input[type=checkbox]:not([name="check_all_markers"])').size();
			jQuery('#gmpTableMarkers_wrapper .gmpRemoveListShell').show();
			if(totalCheckboxes == totalSelectedCheckboxes) {
				selectAllCheckbox.get(0).indeterminate = false;
				selectAllCheckbox.attr('checked', 'checked');
			} else {
				selectAllCheckbox.get(0).indeterminate = true;
				selectAllCheckbox.removeAttr('checked');
			}
		} else {
			jQuery('#gmpTableMarkers_wrapper .gmpRemoveListShell').hide();
			selectAllCheckbox.get(0).indeterminate  = false;
			selectAllCheckbox.removeAttr('checked');
		}
	});
});
function gmpRemoveSelectedMarkers() {
	var totalSelectedCheckboxes = jQuery('#gmpTableMarkers').find('input[type=checkbox]:not([name="check_all_markers"]):checked');
	if(confirm('Are you sure want to remove '+ totalSelectedCheckboxes.size()+ ' markers?')) {
		//gmpRemoveListMsg
		var removeIds = [];
		totalSelectedCheckboxes.each(function(){
			var markerId = jQuery(this).attr('name').split('[');
			markerId = markerId[1].split(']');
			markerId = parseInt(markerId[0]);
			if(markerId)
				removeIds.push(markerId);
		});
		jQuery.sendFormGmp({
			msgElID: jQuery('#gmpTableMarkers_wrapper .gmpRemoveListShell .gmpRemoveListMsg')
		,	data: {page: 'marker', action: 'removeList', remove_ids: removeIds, reqType: 'ajax'}
		,	onSuccess: function(res) {
				if(!res.error) {
					gmpRefreshMarkerList();
				}
			}
		});
	}
}
function gmpRemoveMarkerItemFromMarkerForm() {
	var markerId = parseInt(jQuery('#gmpAddMarkerToEditMap').find('[name="marker_opts[id]"]').val());
	gmpRemoveMarkerItem(markerId, {
		msgEl: '#gmpUpdateMarkerItemMsg'
	,	returnToMarkersList: true
	,	clearForm: true
	});
}
function gmpRemoveMarkerItemFromMapForm() {
	var markerId = parseInt(jQuery('#gmpAddMarkerToEditMap').find('[name="marker_opts[id]"]').val());
	gmpRemoveMarkerItem(markerId, {
		msgEl: '#gmpSaveEditedMapMsg'
	,	clearForm: true
	,	onSuccess: function() {
			gmpRemoveMarkerFromMapMarkersTable(markerId);
			jQuery('.removeMarkerFromForm').attr('disabled', 'disabled');
		}
	});
}
function gmpRemoveMarkerItem(markerId, params) {
	if(!markerId)
		return false;
	if(confirm(toeLangGmp('Are you sure want to remove marker?'))) { 
		params = params || {};
		var sendData = {
			mod: 'marker'
		,	action: 'removeMarker'
		,	reqType: 'ajax'
		,	id:  markerId
		}
		,	msgEl = params.msgEl ? params.msgEl : jQuery('#gmpMarkerListTableLoader_'+ markerId);
		if(typeof(msgEl) === 'string')
			msgEl = jQuery(msgEl);
		jQuery.sendFormGmp({
			msgElID: msgEl
		,	data: sendData
		,	onSuccess: function(res) {
				if(!res.error) {
					gmpRefreshMarkerList();
					if(msgEl.parents('#gmpTableMarkers').size()) {	// For case when we remove it from table
						setTimeout(function(){
							msgEl.parents('tr:first').hide('500', function(){
								jQuery(this).remove();
							});
						}, 500);
					}
					if(params.returnToMarkersList) {
						setTimeout(function(){
							jQuery('.gmpCancelMarkerEditing').trigger('click');
						}, 500);
						msgEl.html('');
					}
					if(params.clearForm) {
						gmpClearMarkerForm();
					}
					if(params.onSuccess && typeof(params.onSuccess) === 'function') {
						params.onSuccess();
					}
				}
			}
		});
	}
}
function gmpMarkerDescSetContent(content) {
	if(tinyMCE.get('marker_opts_description'))
		tinyMCE.get('marker_opts_description').setContent(content);
	else
		jQuery('#marker_opts_description').val( content );
}
function gmpMarkerDescGetContent() {
	if(tinyMCE.get('marker_opts_description'))
		return tinyMCE.get('marker_opts_description').getContent();
	else
		return jQuery('#marker_opts_description').val();
	return '';
}

function gmpEditMarkerItem(markerId) {
	selectTabMainGmp('gmpMarkerList');
	gmpAddMarkerFormToMarker();
	gmpClearMarkerForm();
	jQuery('#gmpAddMarkerToEditMap').find('.gmapMarkerFormControlButtons').show();
    gmpCurrentMarkerForm = jQuery('#gmpAddMarkerToEditMap');

	jQuery('.markerListConOpts').removeClass('active');
    jQuery('.gmpMarkerEditForm').addClass('active');
    
    var currentMarker = getDataTableRow(gmpMarkersTbl, markerId);

	var showMarkerForm = function(currentMarker){
		if(currentMarker) {
			fillFormData({
				form: '#gmpAddMarkerToEditMap'
			,	data: currentMarker
			,	arrayInset: 'marker_opts'
			});
			fillFormData({
				form: '#gmpAddMarkerToEditMap'
			,	data: currentMarker.params
			,	arrayInset: 'marker_opts[params]'
			});
			setcurrentIconToForm(currentMarker.icon, jQuery('#gmpAddMarkerToEditMap'));
			// Clear description in any case
			gmpMarkerDescSetContent('');
			if(currentMarker.description)
				gmpMarkerDescSetContent(currentMarker.description);
			jQuery('#gmpAddMarkerToEditMap').find('[name="marker_opts[params][title_is_link]"]').trigger('change');
			// For prev. versions - those parameters is just undefined, and will not be in fillFormData(), so uncheck this manualy here
			if(typeof(currentMarker.params.more_info_link) === 'undefined') {
				jQuery('#marker_optsparamsmore_info_link_check').removeAttr('checked').trigger('change');
			}
			if(typeof(currentMarker.params.more_info_link) === 'undefined') {
				jQuery('#marker_optsparamsmore_info_link_check').removeAttr('checked').trigger('change');
			}
			gmpDrawMap({
				mapContainerId: 'gmpMapForMarkerEdit'
			,	options: {
					center: {
						lat: currentMarker.coord_y
					,	lng: currentMarker.coord_x
					}
				,	zoom: 15
				}
			});
			drawMarker(jQuery.extend(currentMarker, {
				icon: currentMarker.icon
			,	position: {
					coord_x: currentMarker.coord_x
				,	coord_y: currentMarker.coord_y
				}
			,	title: currentMarker.title
			,	desc: currentMarker.description
			,	id: currentMarker.id
			,	group_id: currentMarker.marker_group_id
			,	animation: currentMarker.animation
			//,	titleLink: currentMarker.titleLink
			}));
			gmpIsMapEditing.state = false;
			jQuery('#gmpEditMarkerForm').find('input,select,textarea').change(function(){
				gmpIsMapEditing.state = true;
			});
			// Scroll to top in case we were on the bottom of the page
			window.scrollTo(0, 0);
			gmpDoAction('afterMarkerOpen', currentMarker.id);
		} else
			window.location.reload();
	};
	if(!currentMarker) {
		jQuery.sendFormGmp({
			msgElID: jQuery('<div class="gmpFullScreenLoader" id="gmpGetMarkerLoader" />').appendTo('body')
		,	data: {page: 'marker', action: 'getMarker', reqType: 'ajax', id: markerId}
		,	onSuccess: function(res) {
				if(!res.error) {
					showMarkerForm(res.data.marker);
				}
				jQuery('#gmpGetMarkerLoader').hide(500, function(){
					jQuery('#gmpGetMarkerLoader').remove();
				});
			}
		});
	} else {
		showMarkerForm(currentMarker);
	}
}
function gmpRefreshMarkerList() {
	if(gmpMarkersTbl) {
		gmpMarkersTbl.fnDestroy();
	}

	var columns = [];
	if(gmpMarkersTblColumns) {
		for(var key in gmpMarkersTblColumns) {
			columns.push({
				mData: key, sClass: key/*, sWidth: (gmpMarkersTblColumns[key].width ? gmpMarkersTblColumns[key].width : false)*/
			});
		}
	}
	var iDisplayLength = gmpGetDataTableDefDisplayLen('gmpTableMarkers');
	gmpMarkersTbl = jQuery('#gmpTableMarkers').dataTable({
		bProcessing: true
	,	bServerSide: true
	,	sAjaxSource: createAjaxLinkGmp({page: 'marker', action: 'getListForTable', reqType: 'ajax'})
	,	aoColumns: columns
	,	sPaginationType: 'full_numbers'
	,	iDisplayLength: iDisplayLength
	,	fnDrawCallback: function(dataTbl){
			gmpSetDataTableDefDisplayLen(jQuery(dataTbl.nTable).attr('id'), dataTbl._iDisplayLength);
			gmpSwitchDataTablePagination(dataTbl);
		}
	});
	jQuery('#gmpTableMarkers').find('input[type=checkbox][name="check_all_markers"]')
		.removeAttr('checked')
		.get(0).indeterminate = false;
	gmpAppendListRemoveBtn();
}
function gmpAppendListRemoveBtn() {
	var removeShell = jQuery('.gmpRemoveListShell.gmpExample').clone().removeClass('gmpExample').hide();
	removeShell.insertAfter( jQuery('#gmpTableMarkers_length') );
}
function cancelEditMarkerItem(params) {
    clearMarkerForm(jQuery('#gmpEditMarkerForm'));
    gmpRefreshMarkerList();
	if(typeof(params) != 'undefined' && params.changeTab) {
    
    } else {
		jQuery('.markerListConOpts').toggleClass('active');
    }
    return false;
}
var gmpTypeInterval;                //timer identifier
var gmpDoneTypingInterval = 5000;  //time in ms, 5 second for example

function gmpAddNewMarker(param){
	var canClearMarkerForm = !isAdminFormChanged('gmpAddMarkerToEditMap') || confirm(toeLangGmp('Cancel Editind And Add New Marker'));
	if(canClearMarkerForm) {
		var markerForm = param.markerForm ? param.markerForm : gmpCurrentMarkerForm;
		clearMarkerForm(markerForm);
		// In any case - changes will be erased
		adminFormSavedGmp(jQuery(markerForm).attr('id'));
	}
}
function gmpClearMarkerForm() {
	jQuery('#gmpAddMarkerToEditMap')[0].reset();
	jQuery('#gmpAddMarkerToEditMap').find('[name="marker_opts[id]"]').val(0);
	jQuery('#gmpAddMarkerToEditMap').find('[name="marker_opts[map_id]"]').val(0);
	jQuery('#gmpAddMarkerToEditMap').find('[name="marker_opts[description]"]').val('');
	jQuery('#gmpAddMarkerToEditMap').find('.gmapMarkerFormControlButtons').hide();
	jQuery('.removeMarkerFromForm').attr('disabled', 'disabled');
}