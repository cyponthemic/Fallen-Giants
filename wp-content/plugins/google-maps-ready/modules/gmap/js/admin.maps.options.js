var currentMap;
var gmpTempMap;
var gmpMapsArr = [];
var gmpCurrentIcon = 1;
var markerArr = {};
var infoWindows = [];
var gmpNewMapOpts = {};
var disableFormCheck = false;
var geocoder;
var markersToSend = {};
var gmpDropDownObj = {};
var gmpMapEditing = false;
var gmpMapForEdit;
var gmpCurrentMarkerForm = jQuery("#gmpAddMarkerToEditMap");
var gmpAddMapForm;
var gmpEditMapForm;
var gmpIsMapEditing = {
		mapData: ''
	,	markerData: ''
	,	state: ''
};
var datatables = {
	tables:{
		"GmpTableGroups"	:   "#GmpTableGroups",
		//"gmpMapsListTable"		 :   "#gmpMapsListTable",
		//"GmpTableMarkers"   :   "#GmpTableMarkers"
	},
	createDatatables:function(){
		for(var i in this.tables){
			this.datatables[i] = jQuery(this.tables[i]).dataTable(this.default_options)
		}
	},
	reCreateTable:function(tableSelector){
		this.datatables[tableSelector] = jQuery(this.tables[tableSelector]).dataTable(this.default_options);
	},
	datatables:{},
	default_options:{	
					// "bJQueryUI": true,
			"iDisplayLength": 10,
			"oLanguage": {
							"sLengthMenu": "Display _MENU_",
							"sSearch": "Search:",
							"sZeroRecords": "Not found",
							"sInfo": "Show  _START_ to _END_ from _TOTAL_ records",
							"sInfoEmpty": "show 0 to 0 from 0 records",
							"sInfoFiltered": "(filtered from _MAX_ total records)",
						},
				"bProcessing": true ,
				"bPaginate": true,
				"sPaginationType": "full_numbers"
	 }
};
var gmpAdminOpts ={
	forms:{
		
	},
	getFormData :function(formId){
		if(typeof(this.forms[formId])=="undefined"){
			return false;
		}
		var res = {};
		for(var s in gmpAdminOpts.forms[''+formId].params){
			res[s]= gmpAdminOpts.forms[''+formId].params[s].getVal();
		}
		return res;
	},
	/*getMarkerFormData:function(formId){
		
		var data = this.getFormData(formId);
		
	
		var markerParams = {
			title	 	: data.gmpMarkerTitleOpt
		,	desc	  	: data.description
		,	group_id 	: data.gmpMarkerGroupOpt
		,	animation	: data.marker_optsanimation
		,	address		: data.gmpMarkerAddressOpt
 	
		};
		markerParams.titleLink={
			linkEnabled:false
		}
		if(data.gmpMarkerTitleIsLinkOpt){
			 markerParams.titleLink.linkEnabled = true;
			 markerParams.titleLink.link = data.gmpMarkerTitleIsLinkOpt;
		}
		
		markerParams.icon=data.gmpMarkerSelectedIconOpt;
		var lat = data.gmpMarkerCoordYOpt;
		var lng = data.gmpMarkerCoordXOpt;
		if(lat!="" && lng!=""){
			   markerParams.position={
					coord_x:parseFloat(lng),
					coord_y:parseFloat(lat)
				 }
		}
		return markerParams;
	},*/
	prepareMarkerDataToSet:function(data,type){
		if(type=="map_form"){
		}else{
			var params = {
				"gmpMarkerGroupOpt" :data.groupId,
				"gmpMarkerTitleOpt"	:data.title,
				"gmpMarkerAddressOpt":data.address,
				"gmpMarkerCoordXOpt":data.coord_x,	
				"gmpMarkerCoordYOpt":data.coord_y,	
				"gmpMarkerSelectedIconOpt":data.icon,
				"description":data.description,
			};
			if(typeof(data.titleLink)=="object"){
				params.gmpMarkerTitleIsLinkOpt = data.titleLink.link;
			}else{
				params.gmpMarkerTitleIsLinkOpt = "";			
			}	
			params.marker_optsanimation="";
			if(data.animation>1){
				params.marker_optsanimation=data.animation
			}
		}	
		return params;
	},
	getFormType:function(formId){
		if( formId=="gmpAddMarkerToNewForm" || formId=="gmpAddMarkerToEditMap" || 
				formId=="gmpEditMarkerForm" ){
					return "marker_form";
		}
		return "map_form";
	},
	convertKeys:function(data, revers){
		var exists_keys = {
			"gmpMarkerGroupOpt":"groupId",
			"marker_group_id":"gmpMarkerGroupOpt",
			"gmpMarkerTitleOpt":"title",
			"gmpMarkerAddressOpt":"address",
			"gmpMarkerCoordXOpt":"coord_x",
			"gmpMarkerCoordYOpt":"coord_y",
			"gmpMarkerSelectedIconOpt":"icon",
			"description":"description",
			"gmpMapTitleOpts":"title",
			"gmpMapDescOpts":"desc",
			"gmpMapWidthOpt":"width",
			"gmpMapHeightOpt":"height",
			"gmpMapAlignOpt":"align",
			"gmpMapZoomOpts":"zoom",
			"gmpMapTypeOpt":"type",
			"gmpMapLngOpt":"language",		
			"gmpMapMarginOpt":"margin",
			"gmpMapBorderColorOpt":"border_color",		
			"gmpMapBorderWidthOpt":"border_width",
			"map_optsenable_zoom":"enable_zoom",		
			"map_optsenable_mouse_zoom":"enable_mouse_zoom",
			"gmpMapEnableMouseZoomOpts":"enable_mouse_zoom",
			"gmpMapEnableZoomOpts":"enable_zoom",
			"gmpMapInfoWindowHeightOpt":"infowindow_height",					
			"gmpMapInfoWindowWidthOpt":"infowindow_width"
		}
		,	res = data;
		if(typeof(revers) === 'undefined') {
			for(var key in res){
				if(key in exists_keys){
					res[exists_keys[key]] = res[key];
					delete res[key];
				}
			}
			return res;				
		}
		for(var key in res) {
			for(var ex_key in exists_keys){
				if(key==exists_keys[ex_key]){
					res[ex_key] = res[key];
					delete res[key];
				}
			}
		}
		return res;
	},
	construct:function(){
		var forms_list = {
			  "formElems" :{
					"map_form" :{
					"params":['gmpMapTitleOpts','gmpMapDescOpts','gmpMapWidthOpt','gmpMapHeightOpt',
								{"type"	  :   'hiddencheckbox',	"selector"  :   "gmpMapEnableZoomOpts"},
								{"type"	  :   "hiddencheckbox",	"selector"  :   "gmpMapEnableMouseZoomOpts"	},
						"gmpMapTypeOpt","gmpMapLngOpt","gmpMapAlignOpt","gmpMapZoomOpts","gmpMapMarginOpt",
						"gmpMapBorderColorOpt","gmpMapBorderWidthOpt","gmpMapInfoWindowHeightOpt",
						"gmpMapInfoWindowWidthOpt"
					  ]
				 },
				 "marker_form":{ 
						"params":["gmpMarkerGroupOpt","gmpMarkerTitleOpt","gmpMarkerAddressOpt",
									"gmpMarkerCoordXOpt","gmpMarkerCoordYOpt","gmpMarkerSelectedIconOpt",
									{"type":"is_link",	"selector":"gmpMarkerTitleIsLinkOpt"},
									{"type":"description"},
									{"type" : "hiddencheckbox",	"selector":"marker_optsanimation"},
					
								]
				 }
			   },
			 "forms":{
					  "marker_form":["gmpAddMarkerToNewForm","gmpAddMarkerToEditMap","gmpEditMarkerForm"],  
					  "map_form":["gmpAddNewMapForm","gmpEditMapForm"],  
					}
		} 
		for(var formType in forms_list.forms){
			 for(var j=0;j<forms_list.forms[formType].length;j++){
				var currentFormSelector = forms_list.forms[formType][j];
				var currentForm =jQuery("#"+currentFormSelector);
					gmpAdminOpts.forms[currentFormSelector] = {
						formObj:currentForm,
						params:{}
					}					 
				var currentFormElems = forms_list.formElems[formType].params;
					var elem_params ={
					} ;
				for(var k =0;k<currentFormElems.length;k++){
					var formElem = currentFormElems[k];
	  
				switch(typeof(formElem)){
					case "string":
						elem_params[formElem]={
							obj:currentForm.find("."+formElem),
							getVal:function(){
									return this.obj.val()
							},
							setVal:function(val){
									if(this.obj.hasClass("gmpMapBorderColorOpt")){
											this.obj.css("background-color",val);
									}
									if(this.obj.hasClass("gmpMapZoomOpts")){
											if(typeof(currentMap)!="undefined"){
													currentMap.setZoom(parseInt(val));
											}
									}
									return this.obj.val(val).trigger("change");
							}
						}
						break;
						case "object":
							if(formElem.type=="hiddencheckbox"){
								elem_params[formElem.selector]={
										obj:{
												'checkbox':currentForm.find("."+formElem.selector+"[type='checkbox']"),
												'input':currentForm.find("."+formElem.selector+"[type='hidden']"),
											
										},
										getVal:function(){
											
												return this.obj.input.val();
										},
										setVal:function(value){
										   this.obj.checkbox.prop("checked",Boolean(parseInt(value)));
										  
										   
										   this.obj.input.val(Number(value));
										  
										}
								}
							}else if(formElem.type=='is_link'){
									var sel = formElem.selector;
									elem_params[sel]={
											obj:{
													"checkbox":currentForm.find("input[type='checkbox']."+sel),
													"input":currentForm.find("input[type='text']."+sel),
											},
											setVal:function(val){
													if(val==""){
															this.obj.checkbox.prop("checked",false);
															this.obj.input.parents(".markerTitleLink_Container").hide();
													}else{
															this.obj.checkbox.prop("checked",true);
															this.obj.input.parents(".markerTitleLink_Container").show();
													}
													this.obj.input.val(val)
											},
											getVal:function(){
													if(this.obj.checkbox.prop("checked")){
															return this.obj.input.val();
													}
													return false;
											}
									}
							}else if(formElem.type=='description'){
									elem_params["description"]={
											setVal:function(value){
													gmpSetEditorContent(value);
											},
											getVal:function(){
													return gmpGetEditorContent();										
											}
									}
							}

						break;
						default:
						break;
				}
					gmpAdminOpts.forms[currentFormSelector].params=elem_params; 	
					gmpAdminOpts.forms[currentFormSelector].setFormData = function(formData){
						
						var data = gmpAdminOpts.prepareMarkerDataToSet(formData);
						for(var key in data ){
							this.params[""+key].setVal(data[key]);
						}
					}
				}
			}
		} 
	}  
		
};
var gmpMapsTable = null;
var gmpMapsTblColumns = [];
var gmpMapEditMarkersTable = null;

/*function gmpIsMapFormIsEditing(){
	if(disableFormCheck){
		return true;
	}
	var items={
		title : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_title").val(),		
		desc  : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_description").val(),
		margin : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_margin").val(),
		bwidth : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_border_width").val(),
		mtitle : jQuery("#gmpAddMarkerToNewForm").find("#gmpNewMap_marker_title").val(),
		maddress : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_address").val(),
		mcoord_x : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_coord_x").val(),
		mcoord_y : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_coord_y").val(),
	}
		try{
			items.mdesc = gmpGetEditorContent();
		}catch(e){
			
		}
  
	for(var i in items){
	  if(items[i]!="" || items[i].length>0){
			return true;
	  }
	}
  return false;
}*/
/*function gmpClearMap(mapObj){
	if(typeof(mapObj)=="undefined"){
		mapObj = currentMap;
	}
   for(var i in markerArr){
		markersToSend[i]= markerArr[i];
		markersToSend[i].markerObj.setMap(null);
		markersToSend[i].markerObj=[];
		delete markerArr[i].markerObj;
		delete markerArr[i]["__e3_"];
	}
}*/
function getInfoWindow(title, content, markerItem) {
	if(markerItem && parseInt(markerItem.params.title_is_link) && markerItem.params.marker_title_link) {
		title = '<a href="'+ markerItem.params.marker_title_link+ '" target="_blank" class="gmpInfoWIndowTitleLink">'+ title+ '</a>'
	}
	var text = '<div class="gmpMarkerInfoWindow">';
	text += '<div class="gmpInfoWindowtitle">'+ title;
	text += '</div>';
	text += '<div class="gmpInfoWindowContent">'+ content;
	text += '</div></div>';
	return text;
}
/*function clearAddNewMapData(mapForm) {
	if(mapForm==undefined){
		mapForm = jQuery("#gmpAddNewMapForm");
	}

	mapForm.clearForm();
	mapForm.find("#gmpNewMap_title").val("");
	mapForm.find("#gmpNewMap_description").val("");
	mapForm.find("#gmpNewMap_width").val("600");
	mapForm.find("#gmpNewMap_height").val("250");
	mapForm.find("#map_optsenable_zoom_check").trigger('click');
	
	mapForm.find("#map_optsenable_zoom_check").attr('checked',true);
	mapForm.find("#map_optsenable_zoom_text").val(1);
	mapForm.find("#map_optsenable_mouse_zoom_check").attr('checked',true);
	mapForm.find("#map_optsenable_mouse_zoom_text").val(1);
	 try{
		mapForm.find("#map_optsenable_zoom_em_check").attr('checked',true);
		mapForm.find("#map_optsenable_zoom_em_text").val(1);
		mapForm.find("#map_optsenable_mouse_zoom_em_check").attr('checked',true);
		mapForm.find("#map_optsenable_mouse_zoom_em_text").val(1);
	}catch(e){
		
	}
	mapForm.find("#gmpMap_zoom").val(8);	
	mapForm.find("#gmpMap_type").val('roadmap');	
	mapForm.find("#gmpMap_language").val('en');	
	mapForm.find("#gmpMap_align").val('top');	
	mapForm.find("input[name='map_opts[display_mode]']").each(function(){
		if(jQuery(this).val()=='map'){
			jQuery(this).attr('checked','checked');
		}
	})	
	 

	jQuery("#gmpAddMarkerToNewForm").clearForm();
	mapForm.find("#gmpNewMap_marker_group").val(1);
	markerArr	   =   new Object; 
	infoWindows	 =   new Array();
	gmpNewMapOpts   =   new Object;
	jQuery("#gmpNewMap_title").css('border','');
}*/

function gmpGetRandomid(){
	var num = Math.random();
	num = ''+ num;
	var rand = num.substr(10);
	return 'id'+ rand;
}

function gmpDrawMap(params) {
	var lat, lng, mapZoom;
	if(params.options != undefined){
		lat = params.options.center.lat;
		lng = params.options.center.lng;
		mapZoom = parseInt(params.options.zoom);
	} else {
		lat = 40.1879714881;
		lng = 44.5234475708;
		mapZoom = 1;
	}
	var mapOptions = {
		center: new google.maps.LatLng(lat, lng)
	,	zoom: mapZoom
	};
	if(typeof(params.mapTypeId) != 'undefined'){
		mapOptions.mapTypeId = google.maps.MapTypeId[params.mapTypeId];
	}
	var map = new google.maps.Map(document.getElementById(params.mapContainerId), mapOptions);
	gmpTempMap = currentMap;
	currentMap = map;	
	google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
		gmpAddLicenzeBlock(params.mapContainerId);
		google.maps.event.addListener(map, 'zoom_changed', function() {
			jQuery('#gmpEditMapForm').find('[name="map_opts[zoom]"]').val( map.getZoom() );
		});
		if(jQuery('#gmpEditMapForm').is(':visible')) {
			// It trigger after form data fill, but at that moment - map can be not loaded, 
			// so let's trigger it here again - to make sure that map will have correct zoom
			jQuery('#gmpEditMapForm').find('[name="map_opts[zoom]"]').trigger('change');
		}
	});
	gmpMapsArr[params.mapContainerId] = map;
	if(geocoder == undefined){
		geocoder = new google.maps.Geocoder();
	}
	return map;
}
var bbg = {};
function gmpAddMarkerToMapMarkersTable(marker, update, opts) {
	if(gmpMapEditMarkersTable) {
		opts = opts || {};
		marker = gmpMarkerPrepareToMapMarkersTable(marker);
		if(update)
			updateDataTableRow(gmpMapEditMarkersTable, marker.id, [marker.id, marker.edit_title, marker.coord_x_y]);
		else
			addDataTableRow(gmpMapEditMarkersTable, [marker.id, marker.edit_title, marker.coord_x_y]);
		drawMarker( marker, opts );
	}
}
function gmpMarkerPrepareToMapMarkersTable(marker) {
	marker.edit_title = '<a href="#" onclick="editMarker('+ marker.id+ '); return false;">'+ marker.title+ '</a>';
	marker.coord_x_y = marker.coord_x+ ' / '+ marker.coord_y;
	return marker;
}
function gmpRemoveMarkerFromMapMarkersTable(markerId) {
	if(gmpMapEditMarkersTable) {
		removeDataTableRow(gmpMapEditMarkersTable, markerId);
		if(markerArr && markerArr[markerId] && markerArr[markerId].markerObj)
			markerArr[markerId].markerObj.setMap(null);
	}
}
function gmpCreateMapMarkersTable() {
	if(gmpMapEditMarkersTable) {
		gmpMapEditMarkersTable.fnDestroy();
	}
	gmpMapEditMarkersTable = jQuery('#gmpMapMarkersTable').dataTable({
		aoColumns: {
			mData: 'id', sClass: 'id'
		,	mData: 'title', sClass: 'title'
		,	mData: 'lat_lon', sClass: 'lat_lon'
		}
	,	sPaginationType: 'full_numbers'
	,	fnDrawCallback: function(dataTbl) {
			gmpSwitchDataTablePagination(dataTbl);
		}
	});
}
function gmpEditMap(mapId) {
	if(gmpMapsTable) {
		var mapData = getDataTableRow(gmpMapsTable, mapId);
		if(mapData) {
			gmpDrawMap({
				mapContainerId: 'gmpEditMapsContainer'
			,	options: {
					zoom: parseInt(mapData.params.zoom)
				,	center: {
						lat: mapData.params.map_center ? mapData.params.map_center.coord_y : 0
					,	lng: mapData.params.map_center ? mapData.params.map_center.coord_x : 0
					}
				,	zoom: parseInt(mapData.params.zoom)
				}
			,	mapTypeId: mapData.params.type
			});
			fillFormData({
				form: '#gmpEditMapForm'
			,	data: objToOneDimension(mapData, {exclude: ['markers']})
			,	arrayInset: 'map_opts'
			});
			// For prev. versions - this parameter is just undefined, and should be px as we didn't used % before, so set it to px manualy here
			if(typeof(mapData.params.width_units) === 'undefined') {
				jQuery('#gmpEditMapForm').find('[name="map_opts[width_units]"]').val('px');
			}
			if(mapData && mapData.markers) {
				for(var i in mapData.markers) {
					gmpAddMarkerToMapMarkersTable(mapData.markers[i], false, {
						ignoreFitMap: true
					});
				}
			}
			gmpMapIdShow(mapId);
			gmpDoAction('afterMapOpen', mapId, mapData);
		}
	}
	return;
}

function gmpGetMapObj(mapId) {
	if(!existsMapsArr) 
		return false;
	for(var i in existsMapsArr){
		if(existsMapsArr[i].id == mapId) {
			return existsMapsArr[i];
		}
	}
	return false;
}
function gmpSaveEditedMap() {
	var mapId = parseInt(jQuery('#gmpEditMapForm').find('[name="map_opts[id]"]'))
	,	markersData = gmpMapEditMarkersTable ? gmpMapEditMarkersTable.fnGetData() : false
	,	addDataToReq = mapId ? false : {};
	// Get all markers, that was saved, for new map, to update them on server side
	if(!mapId && markersData) {
		addDataToReq.add_marker_ids = [];
		for(var i in markersData) {
			addDataToReq.add_marker_ids.push( markersData[i][0] );
		}
	}
	jQuery('#gmpEditMapForm').find('[name="map_opts[map_center][coord_x]"]').val( currentMap.getCenter().lng() );
	jQuery('#gmpEditMapForm').find('[name="map_opts[map_center][coord_y]"]').val( currentMap.getCenter().lat() );
	
	jQuery('#gmpEditMapForm').sendFormGmp({
		msgElID: 'gmpSaveEditedMapMsg'
	,	appendData: addDataToReq
	,	onSuccess: function(res) {
			if(!res.error) {
				gmpRefreshMarkerList();
				getMapsList();
				if(res.data && res.data.map_id) {
					jQuery('#gmpEditMapForm').find('[name="map_opts[id]"]').val( res.data.map_id );
					gmpMapIdShow( res.data.map_id );
				}
				gmpDoAction('afterMapSave', jQuery('#gmpEditMapForm').find('[name="map_opts[id]"]').val());
			}
		}
	});
	adminFormSavedGmp('gmpEditMapForm');
	return;
}
var paramObj;
function arrayUnique(param) {
	if(typeof(param.concat) === 'undefined') {
	   return '';
	}
	var a = param.concat();
	for(var i = 0; i < a.length; ++i) {
		for(var j = i+1; j < a.length; ++j) {
			if(a[i] === a[j])
				a.splice(j--, 1);
		}
	}
	if(a != '' || a != ' ' || a != ','){
		return a;
	}
};


function gmpRemoveMarkerObj(marker,formObj){
   	if(confirm('Remove Marker?')) {
	   var sendData = {
			id: marker.id
		,	mod: 'marker'
		,	action: 'removeMarker'
		,	reqType: 'ajax'
		};
		jQuery.sendFormGmp({
			data:sendData
		,	onSuccess:function(res){
				if(res.error){
					alert(res.errors.join(','));
				} else {
					marker.markerObj.setMap(null);
					delete markerArr[marker.id];
					if(typeof(formObj) != 'undefined') {
						clearMarkerForm(formObj);
					}
				}
			}
		});
	}
}
function drawMarker(params, opts) {
	opts = opts || {};
	var iconId;
	var mIcon;
	var iconObj = typeof(params.icon) == 'object' ? params.icon : typeof(params.icon_data) == 'object' ? params.icon_data : null;
	if(iconObj && iconObj.path && iconObj.path != '') {
		mIcon = iconObj.path;
		iconId = iconObj.id;
	} else {
		var defIcon = {};	// Default icon
		if(gmpCurrentIcon && gmpExistsIcons[gmpCurrentIcon]) {
			defIcon = gmpExistsIcons[gmpCurrentIcon];
		} else {
			for(var i in gmpExistsIcons) {
				defIcon = gmpExistsIcons[i];
				break;
			}
		}
		mIcon = defIcon.path;   
		iconId = defIcon.id;
	}
	var markerIcon = {
		url: mIcon
	,	origin: new google.maps.Point(0,0)
	};
	var markerTitle = 'New Marker'
	,	markerDesc = ''
	,	markerLatLng;
	if(params.position == undefined) {
		markerLatLng = currentMap.getCenter();			 
	} else {
		markerLatLng = new google.maps.LatLng(params.position.coord_y, params.position.coord_x);
	}
	if(params.title != undefined) {
		markerTitle = params.title;
	} else {
		markerTitle = 'New Marker';
	}
	if(params.desc != undefined) {
		markerDesc = params.desc;
	} else if(params.description != 'undefined') {
		markerDesc = params.description;		
	}
	if(params.id == undefined || params.id == '') {
	   var randId = gmpGetRandomid()+ '';
	} else {
	   var randId = params.id;
	}
	params.animation = parseInt(params.animation);
	//var animType = parseInt(params.animation);
	/*if(params.animation == 1){
		 animType = 1;
	}*/
	markerItem = jQuery.extend(params, {
		title: markerTitle
	,	description: markerDesc
	,	id: randId
	,	coord_y: markerLatLng.lat()
	,	coord_x: markerLatLng.lng()
	,	icon: iconId
	,	groupId: params.group_id
	,	animation: params.animation
	});
	if(params.address != 'undefined') {
		markerItem.address = params.address; 
	}
	// If such marker already exist on map - let's remove it and re-insert
	if(markerArr[randId]) {
		markerArr[randId].markerObj.setMap(null);
	}
	markerArr[randId] = markerItem;
	markerArr[randId].markerObj = new google.maps.Marker({
		position: markerLatLng
	,	icon: markerIcon
	,	draggable: true
	,	map: currentMap
	,	title: markerTitle
	,	zIndex: 99999999
	,	animation: params.animation
	,	id: randId
	});
	if(typeof(params.address) == 'undefined' ||  params.address == '') {
		getGmapMarkerAddress(markerItem,randId);
	}
	google.maps.event.addListener( markerArr[randId].markerObj, 'rightclick', function() {
		gmpRemoveMarkerObj(markerArr[randId]); 
	});
	google.maps.event.addListener(markerArr[randId].markerObj, 'dragend', function(e) {
		markerArr[this.id].coord_x = this.position.lng();
		markerArr[this.id].coord_y = this.position.lat();
		changeFormParams(this);
		editMarker(markerArr[randId]);
	});
	infoWindows[randId] = new google.maps.InfoWindow({
		content: getInfoWindow(markerTitle, markerDesc, markerItem)
	,	markerId: randId
	});
	/*google.maps.event.addListener(infoWindows[randId], 'domready', function(){
		gmpAdjustCloseBtnInfoPos(this, {
			checkScrollContent: true
		});
	});*/
	google.maps.event.addListener(markerArr[randId].markerObj, 'click', function(){
		for(var i in infoWindows) {
			if(typeof(infoWindows[i].close) != 'undefined') {
				infoWindows[i].close();			
			}
		}
		if(typeof(infoWindows[randId].open) != 'undefined') {
			infoWindows[randId].open(currentMap, markerArr[randId].markerObj);			
		}
		editMarker(markerArr[randId]);
		toggleBounce(markerArr[randId].markerObj, markerArr[randId].animation);
	});
	if(!opts.ignoreFitMap) {
		var bounds = new google.maps.LatLngBounds();
		for(var i in markerArr){
			var mLatLng = new google.maps.LatLng(markerArr[i].coord_y, markerArr[i].coord_x);
			bounds.extend (mLatLng);
		}
		currentMap.fitBounds (bounds);
	}
	if(opts.setMapCenter) {
		currentMap.setCenter( markerArr[randId].markerObj.position );
	}
	if(currentMap.getZoom() > 19){
		currentMap.setZoom(18);
	}
	if(gmpAddMapForm.is(':visible')){
	   gmpAddMapForm.find('.gmpMap_zoom').val(currentMap.getZoom());			
	}
	if(gmpEditMapForm.is(':visible')){
	   gmpEditMapForm.find('.gmpMap_zoom').val(currentMap.getZoom());				
	}
	if(typeof(params.address) == 'undefined' || params.address == '') {
		getGmapMarkerAddress({
			coord_x: markerItem.coord_x
		,	coord_y: markerItem.coord_y
		}
		,	markerItem.id);
	}
	return randId;
}
function changeFormParams(markerObj){
	var newAddress= getGmapMarkerAddress({
		coord_y:markerObj.position.lat(),
		coord_x:markerObj.position.lng()
	},"",true,{
		func:function(params){
			if(typeof(params.address)!="undefined"){
			   gmpCurrentMarkerForm.find("#gmp_marker_address").val(params.address);
			}
			if(typeof(params.coord_x)!="undefined"){
			   gmpCurrentMarkerForm.find("#gmp_marker_coord_x").val(params.coord_x);
			}
			if(typeof(params.coord_y)!="undefined"){
			   gmpCurrentMarkerForm.find("#gmp_marker_coord_y").val(params.coord_y);
			}
		}
	});
 }
 
function editMarker(marker){
	if(typeof(marker) != 'object')
		marker = markerArr[marker];
	selectTab('gmpEditMapMarkers', 'gmpEditMapContent');
	fillFormData({
		form: '#gmpAddMarkerToEditMap'
	,	data: marker
	,	arrayInset: 'marker_opts'
	});
	fillFormData({
		form: '#gmpAddMarkerToEditMap'
	,	data: marker.params
	,	arrayInset: 'marker_opts[params]'
	});
	setcurrentIconToForm(marker.icon, jQuery('#gmpAddMarkerToEditMap'));
	// Clear description in any case
	gmpMarkerDescSetContent('');
	if(marker.description)
		gmpMarkerDescSetContent(marker.description);
	
	jQuery('#gmpAddMarkerToEditMap').find('[name="marker_opts[params][title_is_link]"]').trigger('change');
	// For prev. versions - this parameter is just undefined, and will not be in fillFormData(), so uncheck this manualy here
	if(typeof(marker.params.more_info_link) === 'undefined') {
		jQuery('#marker_optsparamsmore_info_link_check').removeAttr('checked').trigger('change');
	}
	jQuery('.removeMarkerFromForm').removeAttr('disabled');
	gmpDoAction('afterMarkerOpen', marker.id);
	return;
}
var newShortcodePreview;
jQuery("#gmpSaveNewMap").click(function(){
	jQuery("#gmpAddNewMapForm").trigger("submit");	
});
jQuery("#gmpSaveEditedMap.gmpSaveEditedMapBtn").click(function(){
	gmpSaveEditedMap();
});
function clearMarkerForm(markerForm){
	if(markerForm === undefined) {
		markerForm = jQuery('#gmpAddMarkerToNewForm');
	} else if(typeof(markerForm) === 'string') {
		markerForm = jQuery(markerForm);
	}
	markerForm.find('#gmpEditedMarkerLocalId').val('');
	markerForm.find('#gmpNewMap_marker_group').val(1);
	markerForm.find('#gmpNewMap_marker_title').val('');
	markerForm.find('#gmp_marker_address').val('');
	
	markerForm.find('[name="marker_opts[id]"]').val(0);
	markerForm.find('[name="marker_opts[description]"]').val('');
	
	markerForm.find('.title_is_link').removeAttr('checked');
	markerForm.find('.markerTitleLink_Container').hide();
	markerForm.find('.marker_title_link').val('');

	gmpMarkerDescSetContent('');
	markerForm.find('#gmp_marker_coord_x').val('');
	markerForm.find('#gmp_marker_coord_y').val('');
	markerForm.find('#gmpIconUrlToDown').val('');
	markerForm.find('#marker_optsanimation_text').val('0');
	markerForm.find('#marker_optsanimation_check').removeAttr('checked');
	markerForm.find('#marker_optsanimation_check').removeAttr('checked');
	//markerForm.find('.gmpAddressAutocomplete ul').empty();
	jQuery('.removeMarkerFromForm').attr('disabled', 'disabled');
	adminFormSavedGmp( markerForm.attr('id') );
}
/*function afterMarkerFormSubmit(formId) {
	var markerParams = gmpAdminOpts.getMarkerFormData(formId);
	if(markerParams.title.length < 3){
		alert('Marker Title must be at 3 character at least');
		jQuery('.gmpMarkerTitleOpt').focus();
		return false;
	}
	clearMarkerForm(jQuery("form#"+formId));
	var markerId = drawMarker(markerParams);
	return markerId;
}*/
jQuery(document).ready(function() {
	getMapsList();
	//gmpAdminOpts.construct();
	gmpAddMapForm = jQuery('#gmpAddNewMapForm');
	gmpEditMapForm = jQuery('#gmpEditMapForm');
	datatables.createDatatables();
	jQuery('#gmpHideNewMapPreview').click(function(){
		jQuery('#mapPreviewToNewMap').toggle();
		if(jQuery('#mapPreviewToNewMap').is(':visible')){
			jQuery('#gmpHideNewMapPreview').html('Hide Map Preview');
		} else {
			jQuery('#gmpHideNewMapPreview').html('Show Map Preview');
		}
	});
	jQuery('#gmpAddMarkerToEditMap').submit(function(){
		// For new markers, that are created from add/edit map form, we should set this value to current editable map ID
		if(!parseInt(jQuery(this).find('[name="marker_opts[map_id]"]').val())) {
			jQuery(this).find('[name="marker_opts[map_id]"]').val( jQuery('#gmpEditMapForm').find('[name="map_opts[id]"]').val() );
		}
		jQuery(this).find('[name="marker_opts[description]"]').val( gmpMarkerDescGetContent() );
		
		var lat = jQuery(this).find('[name="marker_opts[coord_y]"]').val()
		,	lng = jQuery(this).find('[name="marker_opts[coord_x]"]').val();
		// Put marker in map center if it has empty coords
		if(currentMap && currentMap.getCenter() && (!lat || !lng)) {
			if(!lat)
				jQuery(this).find('[name="marker_opts[coord_y]"]').val( currentMap.getCenter().lat() );
			if(!lng)
				jQuery(this).find('[name="marker_opts[coord_x]"]').val( currentMap.getCenter().lng() );
		}
		jQuery(this).sendFormGmp({
			msgElID: jQuery('#gmpEditMapShell').is(':visible') ? 'gmpSaveEditedMapMsg' : 'gmpUpdateMarkerItemMsg'	// Different msg elements for edit marker from map and from marker forms
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.marker) {
						gmpRefreshMarkerList();
						gmpAddMarkerToMapMarkersTable(res.data.marker, res.data.update, {
							ignoreFitMap: true
						,	setMapCenter: true
						});
						jQuery('#gmpAddMarkerToEditMap').find('[name="marker_opts[id]"]').val( res.data.marker.id );
						jQuery('.removeMarkerFromForm').removeAttr('disabled');
						gmpDoAction('afterMarkerSave', res.data.marker.id, res.data.marker);
					}
				}
			}
		});
		adminFormSavedGmp('gmpAddMarkerToEditMap');
		return false;
	});
	jQuery('#AddMаrkerToMap').click(function() {
		jQuery('#gmpAddMarkerToEditMap').submit();
	});
	jQuery('.gmpCancelMarkerEditing').click(function(){
		var parentForm = gmpAdminOpts.forms.gmpEditMarkerForm ? gmpAdminOpts.forms.gmpEditMarkerForm.formObj : false;
		if(parentForm) {
			clearMarkerForm(parentForm);
			parentForm.find('#gmpEditedMarkerLocalId').val('');
		}
		if(jQuery('.gmpMarkerEditForm.markerListConOpts').hasClass('active'))
			jQuery('.markerListConOpts').toggleClass('active');
	});
	jQuery('.map_border_color').click(function(){
		jQuery(this).val() == '' ? jQuery(this).val(' ') : '';
	});
	jQuery('.title_is_link').change(function(){
		if(this.checked) {
			jQuery(this).parents('.gmpFormRow').find('.markerTitleLink_Container').show(100);
		} else {
			jQuery(this).parents('.gmpFormRow').find('.markerTitleLink_Container').hide(100);		   
		}
	});
	jQuery('.gmpMarkerSelectedIconOpt').on('change', function(){
		setcurrentIconToForm(jQuery(this).val(), jQuery(this).parents('form'));
	});
	jQuery('.gmpMapTitleOpts').focusout(function(){
		if(jQuery(this).val().length >= 3){
			jQuery('#gmpSaveNewMap').removeAttr('disabled');
		} else {
			jQuery('#gmpSaveNewMap').attr('disabled', 'disabled');		
		}
	});
});
// Update map name in additional title view
function gmpMapNameTitleShow(newName) {
	jQuery('.gmpEditingMapName').html( newName );
}
function gmpMapIdShow(newId) {
	jQuery('.gmpEditingMapId').html( newId );
	var shortcode = jQuery('.gmpShortCodePreviewForEditMap').html();
	jQuery('.gmpShortCodePreviewForEditMap').html( shortcode.replace(/='\d*'/, "='"+ newId+ "'") );
}

function gmpRemoveMap(mapId){
	if(!confirm(toeLangGmp('Remove Map?'))) {
		return false;
	}
	if(mapId == ''){
		return false;
	}
	var sendData = {
		map_id: mapId
	,	mod: 'gmap'
	,	action: 'removeMap'
	,	reqType: 'ajax'
	}
	,	msgEl = jQuery('#gmpRemoveElemLoader__'+ mapId);
	
	jQuery.sendFormGmp({
		msgElID: msgEl
	,	data: sendData
	,	onSuccess: function(res) {
			if(!res.error){
				getMapsList();
				setTimeout(function(){
					msgEl.hide('500', function(){
						jQuery(this).parents('tr:first').remove();
					});
				}, 500);
			}
		}
	});
}
var resp=""
function getMapsList(showEdit) {
		this.page;	// Let's save page ID here, in static variable
	if(typeof(this.page) == 'undefined')
		this.page = 0;
	if(typeof(page) != 'undefined')
		this.page = page;
	if(gmpMapsTable) {
		gmpMapsTable.fnDestroy();
	}
	var page = this.page;

	var columns = [];
	if(gmpMapsTblColumns) {
		for(var key in gmpMapsTblColumns) {
			columns.push({
				mData: key, sClass: key
			});
		}
	}
	var iDisplayLength = gmpGetDataTableDefDisplayLen('gmpMapsListTable');
	gmpMapsTable = jQuery('#gmpMapsListTable').dataTable({
		bProcessing: true
	,	bServerSide: true
	,	sAjaxSource: createAjaxLinkGmp({page: 'gmap', action: 'getListForTable', reqType: 'ajax'})
	,	aoColumns: columns
	,	sPaginationType: 'full_numbers'
	,	iDisplayLength: iDisplayLength
	,	oLanguage: {
			sLengthMenu: 'Show _MENU_'
 		}
	,	fnDrawCallback: function(dataTbl) {
			gmpSetDataTableDefDisplayLen(jQuery(dataTbl.nTable).attr('id'), dataTbl._iDisplayLength);
			gmpSwitchDataTablePagination(dataTbl);
		}
	});
	
	/*jQuery('#gmpMapsListTable').remove();
	jQuery('#gmpMapsListTable_wrapper').remove();
	jQuery('.gmpMapsContainer').addClass('gmpMapsTableListLoading');
	var sendData = {
		mod	:'gmap',
		action :'getMapsList',
		reqType:'ajax'
	}
	jQuery.sendFormGmp({
		data: sendData
	,	onSuccess: function(res) {
			if(!res.error){
			   jQuery(".gmpMapsContainer").removeClass("gmpMapsTableListLoading");
			   jQuery(".gmpMapsContainer").append(res.html);
				datatables.reCreateTable("gmpMapsListTable");
			   if(showEdit != undefined) {
				   gmpEditMap(showEdit.id);
			   }
			}
		}
	});*/
}
jQuery('.gmpMap_zoom').change(function(){
	var opts = {
		opt: 'zoom'
	,	val: jQuery(this).val()
	};
	changeMap(opts);
});
jQuery('.gmpMap_type').change(function(){
	var opts = {
		opt: 'type'
	,	val: jQuery(this).val()
	};
	changeMap(opts);
});
jQuery('.gmpMap_language').change(function(){
	var opts = {
		opt: 'language'
	,	val: jQuery(this).val()
	};
	changeMap(opts);
});
jQuery('#map_optsenable_mouse_zoom_check').change(function(){
	var value = 0;
	if(jQuery(this).is(':checked')){
		value = 1;
	}
	var opts = {
		opt: 'mouse_zoom_enable'
	,	val: value
	};
	changeMap(opts);
});
jQuery('#map_optsenable_zoom_check').change(function(){
	var value=0;
	if(jQuery(this).is(':checked')){
		value=1;
	}
	var opts = {
		opt: 'zoom_enable'
	,	val: value
	};
	changeMap(opts);
});

function changeMap(params) {
	var val =(params.val) ? true : false;
	switch(params.opt) {
		case 'mouse_zoom_enable':
			currentMap.setOptions({
				disableDoubleClickZoom: !val
			,	scrollwheel: val
			});
			break;	
		case 'zoom_enable':
			currentMap.setOptions({
				zoomControl: val
			});
			break;
		case 'type':
			currentMap.setMapTypeId(google.maps.MapTypeId[params.val]);
			break;
		case 'zoom':
			if(typeof(currentMap) !== 'undefined') {
				currentMap.setZoom(parseInt(params.val));			
			}
			break;
	}
}
function drawAutocompleteResult(params, form){
	if(typeof(form) === 'undefined'){
		form = gmpCurrentMarkerForm;
	}
	form.find('.gmpAddressAutocomplete ul').empty();
	form.find('.gmpAddressAutocomplete').slideDown();
	for(var i in params){
		var item = '<li class="gmpAutoCompRes"><a class="autoCompRes" id="{position}">{address}</a></li>';
		item = item.replace('{position}', params[i].position.lat+ '__'+ params[i].position.lng).replace('{address}', params[i].address);
		form.find('.gmpAddressAutocomplete ul').append(item);
	}
}