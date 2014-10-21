jQuery(document).ready(function(){
	jQuery('.gmpMLTypeTpls').find('li,button').click(function(e){
		e.stopPropagation();
		gmpSetMLTemplate(jQuery(this).attr('data_code'));
		return false;
	});
	jQuery('.gmpCMCTplsList').find('li,button').click(function(e){
		e.stopPropagation();
		gmpSetCMCTemplate(jQuery(this).attr('data_code'));
		return false;
	});
	jQuery('.gmpGSTplsList').find('li,button').click(function(e){
		e.stopPropagation();
		gmpSetGSTemplate(jQuery(this).attr('data_code'));
		return false;
	});
});
// ML - Markers List
function gmpShowMarkersListTplPopup() {
	toeShowDialogCustomized(jQuery('#gmpMarkersListTypeTplsShell'), {
		width: 780
	,	height: jQuery(window).height()
	,	modal: true
	,	closeOnBg: true
	,	title: toeLangGmp('Select Your Markers list style')
	});
}
function gmpSetMLTemplate(code) {
	window.open(gmpProSiteLink);
	return false;
}
function gmpShowCustomControlsTplPopup() {
	toeShowDialogCustomized(jQuery('#gmpCustomMapControlsTplsShell'), {
		width: 740
	,	height: 380
	,	modal: true
	,	closeOnBg: true
	,	title: toeLangGmp('Select Your Custom map controls')
	});
}
function gmpSetCMCTemplate(code) {
	window.open(gmpProSiteLink+ '?custom_map_controls='+ code);
	return false;
}
function gmpShowGSTplPopup() {
	toeShowDialogCustomized(jQuery('#gmpGSControlsTplsShell'), {
		width: 780
	,	height: jQuery(window).height()
	,	modal: true
	,	closeOnBg: true
	,	title: toeLangGmp('Select Your map style')
	});
}
function gmpSetGSTemplate(code) {
	window.open(gmpProSiteLink+ '?stylization='+ code);
	return false;
}

