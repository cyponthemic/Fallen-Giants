jQuery.fn.scrollTo = function(elem) { 
	jQuery(this).scrollTop(jQuery(this).scrollTop() - jQuery(this).offset().top + jQuery(elem).offset().top); 
	return this; 
};
function toggleBounce(marker,animType) {
	if(animType == 0 || !marker){
		return false;   
	}
	if (marker.getAnimation() != null) {
		marker.setAnimation(null);
	} else if(animType==2) {	
		marker.setAnimation(null);
	} else {
		marker.setAnimation(google.maps.Animation.BOUNCE);
	}
}
 function gmpGetLicenseBlock() {
   return '<a class="mapLicenzetext GmpMapLicenseBlock" href="http://readyshoppingcart.com/product/google-maps-plugin/" target="_blank">'+ 'Google Maps WordPress Plugin'+ '</a>';
}
function gmpAddLicenzeBlock(mapId){
	if(parseInt(GMP_DATA.youHaveLicense))
		return;
	var befElem = jQuery('#'+ mapId).find('.gmnoprint').find('.gm-style-cc');
	befElem.css('float', 'right');
	befElem.css('width', '400px');
	befElem.find('a').after(gmpGetLicenseBlock());
}