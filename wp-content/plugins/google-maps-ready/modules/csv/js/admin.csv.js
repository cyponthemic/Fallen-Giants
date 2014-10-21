var gmpCsvImportData = {};
jQuery(document).ready(function(){
	jQuery('#gmpCsvExportMapsBtn').click(function(){
		toeRedirect(createAjaxLinkGmp({
			page: 'csv'
		,	action: 'exportMaps'
		,	withMarkers: jQuery('#gmpCsvWithMarkersCheck').attr('checked') ? 1 : 0
		}));
		return false;
	});
	jQuery('#gmpCsvExportMarkersBtn').click(function(){
		toeRedirect(createAjaxLinkGmp({
			page: 'csv'
		,	action: 'exportMarkers'
		}));
		return false;
	});
});
function gmpCsvImportOnSubmit() {
    jQuery('#gmpCsvImportMsg').showLoaderGmp();
    jQuery('#gmpCsvImportMsg').removeClass('toeErrorMsg');
    jQuery('#gmpCsvImportMsg').removeClass('toeSuccessMsg');
	gmpCsvImportData['overwrite_same_names'] = jQuery('#gmpCsvOverwriteSameNames').attr('checked') ? 1 : 0;
}
function gmpCsvImportOnComplete(file, res) {
	toeProcessAjaxResponseGmp(res, 'gmpCsvImportMsg');
	if(!res.error) {
		getMapsList();
		gmpRefreshMarkerList();
	}
}