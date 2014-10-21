function getInfoWindow(title, content, markerItem, mapParams) {
	if(markerItem && parseInt(markerItem.params.title_is_link) && markerItem.params.marker_title_link) {
		title = '<a href="'+ markerItem.params.marker_title_link+ '" target="_blank" class="gmpInfoWIndowTitleLink">'+ title+ '</a>'
	}

	if(parseInt(markerItem.params.more_info_link) && content && content != '') {
		content = gmpContentPrevFull(content);
	}
	var text = '<div class="gmpMarkerInfoWindow">';
	text += '<div class="gmpInfoWindowtitle">'+ title;
	text += '</div>';
	text += '<div class="gmpInfoWindowContent">'+ content;
	text += '</div></div>';
	if(typeof(gmpAddMarkerAdditionalLinks) === 'function') {
		text = gmpAddMarkerAdditionalLinks(text, title, content, markerItem, mapParams);
	}
	var infoWindow = new google.maps.InfoWindow({
		content: text
	});
	/*google.maps.event.addListener(infoWindow, 'domready', function(){
		gmpAdjustCloseBtnInfoPos(this);
	});*/
	return infoWindow;
}
function gmpContentPrevFull(content, params) {
	params = params || {};
	var previewContent = gmpGetPreviewContent(content)
	,	contentShell = jQuery('<div class="gmpMarkerDescShell"/>')
	,	previewHtmlObj = jQuery('<div />').addClass('gmpPreviewContent').html( previewContent )
	,	fullHtmlObj = jQuery('<div />').addClass('gmpFullContent').html( content )
	,	moreInfoButt = jQuery('<a href="#" onclick="gmpToggleInfowndMoreInfoClickButt(this); return false;"/>').html(toeLangGmp('Read more')).addClass('gmpMarkerMoreInfoButt');

	contentShell.append(previewHtmlObj).append(fullHtmlObj);
	if(!params.withoutMoreLink)
		contentShell.append(moreInfoButt);
	content = contentShell.get(0).outerHTML;
	return content;
}
function gmpGetPreviewContent(content) {
	var tmpDiv = jQuery('<div />').html(content);
	if(tmpDiv.find('img').size()) {
		return tmpDiv.find('img:first').get(0).outerHTML;
	} else if(content && content != '') {
		return jQuery('<span />').html(content.substring(0, 30)+ ' ...').get(0).outerHTML;
	}
	return content;
}
function gmpToggleInfowndMoreInfoClickButt(link) {
	var contentShell = jQuery(link).parents('.gmpMarkerDescShell:first');
	if(contentShell.find('.gmpPreviewContent').is(':visible')) {
		contentShell.find('.gmpPreviewContent').hide(100);
		contentShell.find('.gmpFullContent').show(100, function(){
			var infoWnd = contentShell.parents('.gmpMarkerInfoWindow:first');
			// If infoWnd have scroll - move more link to the left
			if(infoWnd.get(0).scrollHeight > infoWnd.height()) {
				jQuery(link).css({
					'right': '30px'
				});
			}
		});
		jQuery(link).html(toeLangGmp('Hide'));
	} else {
		contentShell.find('.gmpFullContent').hide(100);
		contentShell.find('.gmpPreviewContent').show(100);
		jQuery(link).css({
			'right': '0px'
		}).html(toeLangGmp('Read more'));
	}
}
var gmapPreview = {
	maps: []
,	mapObjects: []
,	mapItemsContainer: {}
,	prepareToDraw: function(mapId) {
	   if(typeof(mapId) == 'undefined'){
		   return false;
	   }
	   var currentMap = gmapPreview.maps[mapId].mapParams;
	   if(currentMap.params.map_display_mode == 'popup') {
			var imgSrc = gmpGetMapImgSrc(gmapPreview.maps[mapId]);
			gmapPreview.maps[mapId].onAfterDraw.push(function(map){
				google.maps.event.addListenerOnce(map.mapObject, 'tilesloaded', function(){
					var bodyWidth = jQuery('#mapConElem_'+ mapId+ '.display_as_popup').attr('data-bodywidth')
					,	mapWidth = jQuery('#mapConElem_'+ mapId+ '.display_as_popup').width()
					,	newLeftPosition = Math.round((bodyWidth - mapWidth) / 2);
					if(newLeftPosition < 0)
						newLeftPosition = 0;
					jQuery('#mapConElem_'+ mapId+ '.display_as_popup').css({
						'left': newLeftPosition+ 'px'
					//,	'width': mapWidth - 10
					});
					if(bodyWidth - mapWidth <= 17) {
						jQuery('#mapConElem_'+ mapId+ '.display_as_popup').css({
							'width': mapWidth - 10
						});
					}
				});
			});
			jQuery('.show_map_icon.map_num_'+ mapId).attr('src', imgSrc).click(function(){
				var map_id = jQuery(this).attr('data_val')
				,	bodyWidth = jQuery('body').width();
				jQuery('#mapConElem_'+ mapId+ '.display_as_popup').attr('data-bodywidth', bodyWidth);
				jQuery('#mapConElem_'+ mapId+ '.display_as_popup').bPopup({
					positionStyle: 'fixed'
				,	position: [0, 100]
				});
				gmapPreview.drawMap(currentMap);
			});
		} else if(currentMap.params.map_display_mode == 'map') {
			this.drawMap(currentMap);
		}
	}
,	getMapById: function(id) {
		for(var i in gmapPreview.maps) {
			if(gmapPreview.maps[i].mapParams.id == id)
				return gmapPreview.maps[i];
		}
		console.log('CAN NOT FIND MAP BY ID', id, '!!!');
		return false;
	}
,	drawMap: function(mapForPreview) {
		if(typeof(mapForPreview) == 'undefined') {
			return false;
		}
		var mapElemId = 'ready_google_map_'+ mapForPreview.id
		,	lat = 40.7127837		// default coords - NY
		,	lng = -74.00594130000002;	// default coords - NY
		if(typeof(mapForPreview.params.map_center) != 'undefined') {
			lat = mapForPreview.params.map_center.coord_y;
			lng = mapForPreview.params.map_center.coord_x;
		} else if(mapForPreview.markers && mapForPreview.markers.length > 0) {
			lat = mapForPreview.markers[0].coord_y;
			lng = mapForPreview.markers[0].coord_x;
		}

		var mapCenter = new google.maps.LatLng(lat, lng);

		var mapOptions = {
			center: mapCenter
		,	zoom: parseInt(mapForPreview.params.zoom)
		,	scrollwheel: false	//mouse disable
		,	draggable: true	//drag map
		,	zoomControl: Boolean(parseInt(mapForPreview.params.enable_zoom))
		,	disableDoubleClickZoom: true
		};
		if(mapForPreview.params.enable_mouse_zoom == 1) {
			mapOptions.disableDoubleClickZoom = false;
			mapOptions.scrollwheel = true;
		}
		
		mapOptions.mapTypeId = google.maps.MapTypeId[mapForPreview.params.type];

		// This is for pro, I didn't write dispatcher for JS, maybe in next version of framework....
		if(typeof(gmpCmcPrepareMapOptions) === 'function') {
			mapOptions = gmpCmcPrepareMapOptions(mapOptions, mapForPreview);
		}
		if(typeof(gmpGSPrepareMapOptions) === 'function') {
			mapOptions = gmpGSPrepareMapOptions(mapOptions, mapForPreview);
		}
		var map = new google.maps.Map(document.getElementById(mapElemId), mapOptions);
		this.maps[mapForPreview.id].mapObject = map;

		if(mapForPreview.markers && mapForPreview.markers.length > 0) {
			this.drawMarkers(mapForPreview.markers, mapForPreview.id);	  
		}
		google.maps.event.addListenerOnce(this.maps[mapForPreview.id].mapObject, 'tilesloaded', function(){
			gmpAddLicenzeBlock(mapElemId);
		});
		if(this.maps[mapForPreview.id].onAfterDraw && this.maps[mapForPreview.id].onAfterDraw.length) {
			for(var i in this.maps[mapForPreview.id].onAfterDraw) {
				this.maps[mapForPreview.id].onAfterDraw[i]( this.maps[mapForPreview.id] );
			}
		}
		delete map;
	}
,	drawMarkers: function(markerList, mapId) {
		for(var i in markerList){
			this.createMarker(markerList[i], mapId);
		}
	}
,	createMarker: function(markerItem, mapId) {
		var iconUrl = GMP_DATA.imgPath+ 'markers/marker_1.png'; // default icon
		if(markerItem.icon_data != undefined && markerItem.icon_data.path != undefined) {
			iconUrl = markerItem.icon_data.path;
		}
		var markerIcon;
		if(parseInt(markerItem.params.icon_fit_standard_size)) {
			var standardWidth = 18
			,	markerSize = new google.maps.Size(standardWidth, 30);
			markerIcon = new google.maps.MarkerImage(iconUrl, null, null, null, markerSize);
			// update marker icon height with proportion value, it should be always proportional scaled with width - 18px
			getImgSize(iconUrl, function(size){
				if(size.w && size.h) {
					markerSize.height = standardWidth * size.h / size.w;
				}
			});
		} else {
			markerIcon = {
				url: iconUrl
			,	origin: new google.maps.Point(0,0)
			};
		}

		var markerLatLng = new google.maps.LatLng(markerItem.coord_y, markerItem.coord_x);
		this.maps[mapId].markerArr[markerItem.id] = new google.maps.Marker({
			position: markerLatLng
		,	title: markerItem.title
		,	description: markerItem.description
		,	icon: markerIcon
		,	draggable: false
		,	map: gmapPreview.maps[mapId].mapObject
		,	animation: markerItem.animation
		,	address: markerItem.address
		,	id: markerItem.id
		});

		var infoWindow = getInfoWindow(markerItem.title, markerItem.description, markerItem, this.maps[mapId].mapParams)
		,	showWndEvent = parseInt(this.maps[mapId].mapParams.params.infowindow_on_mouseover) ? 'mouseover' : 'click';
		
		google.maps.event.addListener(this.maps[mapId].markerArr[markerItem.id], showWndEvent, function(){
			for(var i in gmapPreview.maps[mapId].infoWindows) {
				gmapPreview.maps[mapId].infoWindows[i].close();
			}
			infoWindow.open(gmapPreview.maps[mapId].mapObject, gmapPreview.maps[mapId].markerArr[markerItem.id]);
			toggleBounce(gmapPreview.maps[mapId].markerArr[markerItem.id], markerItem.animation);
			if(typeof(gmpGoToMarkerInList) === 'function') {
				gmpGoToMarkerInList(gmapPreview.maps[mapId].mapParams, gmapPreview.maps[mapId].markerArr[markerItem.id]);
			}
		});
		this.maps[mapId].infoWindows[markerItem.id] = infoWindow;
	}
};

function closePopup(){
	jQuery('.display_as_popup').bPopup().close();	
}
jQuery(document).ready(function(){
	if(typeof(gmpAllMapsInfo) != 'undefined') {
		for(var i in gmpAllMapsInfo){
			var map_id = gmpAllMapsInfo[i].id;
			gmapPreview.maps[map_id] = {
				mapObject: {}
			,	markerArr: {}
			,	infoWindows: {}
			,	mapParams: gmpAllMapsInfo[i]
			,	onAfterDraw: []
			};
		}
		jQuery(document).trigger('gmpBeforePrepareToDraw');
		for(var i in gmpAllMapsInfo){
			gmapPreview.prepareToDraw(gmpAllMapsInfo[i].id);
		}
	}
});
/**
 * Convert angel - to radians
 * @param {number} a angel to convert
 * @return {number} angel in Rad
 */
function gmpToRad(a) {
	return a * Math.PI / 180;
}
/**
 * Get distance, in meter, betweent two point positions
 * @param {object} p1 google maps API position of first point
 * @param {object} p2 google maps API position of second point
 * @return {number} distance in meter
 */
function gmpGetDistance(p1, p2) {
	var R = 6378137; // Earth’s mean radius in meter
	var dLat = gmpToRad(p2.lat() - p1.lat());
	var dLong = gmpToRad(p2.lng() - p1.lng());
	var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
		Math.cos(gmpToRad(p1.lat())) * Math.cos(gmpToRad(p2.lat())) *
		Math.sin(dLong / 2) * Math.sin(dLong / 2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
	var d = R * c;
	return d; // returns the distance in meter
}
function gmpM2Km(d) {
	return d / 1000;
}
function gmpKm2M(d) {
	return d * 1000;
}
function gmpGetMapImgSrc(map) {
	var imgSize = map.mapParams.params.img_width ? map.mapParams.params.img_width : 175;
	imgSize += 'x';
	imgSize += map.mapParams.params.img_height ? map.mapParams.params.img_height : 175;

	var reqParams = {
		center: map.mapParams.params.map_center.coord_y+ ','+ map.mapParams.params.map_center.coord_x
	,	zoom: map.mapParams.params.zoom
	,	size: imgSize
	,	maptype: map.mapParams.params.type
	,	sensor: 'false'
	,	language: map.mapParams.params.language
	};
	var reqStr = (GMP_DATA.isHttps ? 'https' : 'http')+ '://maps.google.com/maps/api/staticmap?'+ jQuery.param(reqParams);
	
	if(map.mapParams.markers && map.mapParams.markers.length) {
		for(var i in map.mapParams.markers) {
			reqStr += '&markers=color:red|label:none|'+ map.mapParams.markers[i].coord_y+ ','+ map.mapParams.markers[i].coord_x;
		}
	}
	return reqStr;
}
