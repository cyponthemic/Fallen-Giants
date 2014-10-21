if(typeof(GMP_DATA) == 'undefined')
	var GMP_DATA = {};
if(isNumber(GMP_DATA.animationSpeed)) 
    GMP_DATA.animationSpeed = parseInt(GMP_DATA.animationSpeed);
else if(jQuery.inArray(GMP_DATA.animationSpeed, ['fast', 'slow']) == -1)
    GMP_DATA.animationSpeed = 'fast';
GMP_DATA.showSubscreenOnCenter = parseInt(GMP_DATA.showSubscreenOnCenter);
var sdLoaderImgGmp = '<img src="'+ GMP_DATA.loader+ '" />';
var gmpGeocoder;
var gmpEvents = {};
jQuery.fn.showLoaderGmp = function() {
    jQuery(this).html( sdLoaderImgGmp );
}
jQuery.fn.appendLoaderGmp = function() {
    jQuery(this).append( sdLoaderImgGmp );
}

jQuery.sendFormGmp = function(params) {
	// Any html element can be used here
	return jQuery('<br />').sendFormGmp(params);
}
/**
 * Send form or just data to server by ajax and route response
 * @param string params.fid form element ID, if empty - current element will be used
 * @param string params.msgElID element ID to store result messages, if empty - element with ID "msg" will be used. Can be "noMessages" to not use this feature
 * @param function params.onSuccess funstion to do after success receive response. Be advised - "success" means that ajax response will be success
 * @param array params.data data to send if You don't want to send Your form data, will be set instead of all form data
 * @param array params.appendData data to append to sending request. In contrast to params.data will not erase form data
 * @param string params.inputsWraper element ID for inputs wraper, will be used if it is not a form
 * @param string params.clearMsg clear msg element after receive data, if is number - will use it to set time for clearing, else - if true - will clear msg element after 5 seconds
 */
jQuery.fn.sendFormGmp = function(params) {
    var form = null;
    if(!params)
        params = {fid: false, msgElID: false, onSuccess: false};

    if(params.fid)
        form = jQuery('#'+ params.fid);
    else
        form = jQuery(this);
    
    /* This method can be used not only from form data sending, it can be used just to send some data and fill in response msg or errors*/
    var sentFromForm = (jQuery(form).tagName() == 'FORM');
    var data = new Array();
    if(params.data)
        data = params.data;
    else if(sentFromForm)
        data = jQuery(form).serialize();
    
    if(params.appendData) {
		var dataIsString = typeof(data) == 'string';
		var addStrData = [];
        for(var i in params.appendData) {
			if(dataIsString) {
				if(toeInArrayGmp(typeof(params.appendData[i]), ['array', 'object'])) {
					for(var k in params.appendData[i]) {
						addStrData.push(i+ '[]='+ params.appendData[i][k]);
					}
				} else
					addStrData.push(i+ '='+ params.appendData[i]);
			} else
				data[i] = params.appendData[i];
        }
		if(dataIsString)
			data += '&'+ addStrData.join('&');
    }
    var msgEl = null;
    if(params.msgElID) {
        if(params.msgElID == 'noMessages')
            msgEl = false;
        else if(typeof(params.msgElID) == 'object')
           msgEl = params.msgElID;
       else
            msgEl = jQuery('#'+ params.msgElID);
    } else
        msgEl = jQuery('#msg');
	if(typeof(params.inputsWraper) == 'string') {
		form = jQuery('#'+ params.inputsWraper);
		sentFromForm = true;
	}
	if(sentFromForm && form) {
        jQuery(form).find('*').removeClass('gmpInputError');
    }
	if(msgEl) {
		jQuery(msgEl).removeClass('gmpSuccessMsg')
			.removeClass('gmpErrorMsg')
			.showLoaderGmp();
	}
    var url = '';
	if(typeof(params.url) != 'undefined')
		url = params.url;
    else if(typeof(ajaxurl) == 'undefined')
        url = GMP_DATA.ajaxurl;
    else
        url = ajaxurl;
    
    jQuery('.gmpErrorForField').hide(GMP_DATA.animationSpeed);
	var dataType = params.dataType ? params.dataType : 'json';
	// Set plugin orientation
	if(typeof(data) == 'string')
		data += '&pl='+ GMP_DATA.GMP_CODE;
	else
		data['pl'] = GMP_DATA.GMP_CODE;
	
    jQuery.ajax({
        url: url,
        data: data,
        type: 'POST',
        dataType: dataType,
        success: function(res) {
            toeProcessAjaxResponseGmp(res, msgEl, form, sentFromForm, params);
			if(params.clearMsg) {
				setTimeout(function(){
					jQuery(msgEl).animateClear();
				}, typeof(params.clearMsg) == 'boolean' ? 5000 : params.clearMsg);
			}
        }
    });
}

/**
 * Hide content in element and then clear it
 */
jQuery.fn.animateClear = function() {
	var newContent = jQuery('<span>'+ jQuery(this).html()+ '</span>');
	jQuery(this).html( newContent );
	jQuery(newContent).hide(GMP_DATA.animationSpeed, function(){
		jQuery(newContent).remove();
	});
}
/**
 * Hide content in element and then remove it
 */
jQuery.fn.animateRemove = function(animationSpeed, callback) {
	animationSpeed = animationSpeed == undefined ? GMP_DATA.animationSpeed : animationSpeed;
	jQuery(this).hide(animationSpeed, function(){
		if(callback && typeof(callback) === 'function')
			callback(this);
		jQuery(this).remove();
	});
}

function toeProcessAjaxResponseGmp(res, msgEl, form, sentFromForm, params) {
    if(typeof(params) == 'undefined')
        params = {};
    if(typeof(msgEl) == 'string')
        msgEl = jQuery('#'+ msgEl);
    if(msgEl)
        jQuery(msgEl).html('');
    /*if(sentFromForm) {
        jQuery(form).find('*').removeClass('gmpInputError');
    }*/
    if(typeof(res) == 'object') {
        if(res.error) {
            if(msgEl) {
                jQuery(msgEl).removeClass('gmpSuccessMsg')
					.addClass('gmpErrorMsg');
            }
            for(var name in res.errors) {
                if(sentFromForm) {
                    jQuery(form).find('[name*="'+ name+ '"]').addClass('gmpInputError');
                }
                if(jQuery('.gmpErrorForField.toe_'+ nameToClassId(name)+ '').exists())
                    jQuery('.gmpErrorForField.toe_'+ nameToClassId(name)+ '').show().html(res.errors[name]);
                else if(msgEl)
                    jQuery(msgEl).append(res.errors[name]).append('<br />');
            }
        } else if(res.messages.length) {
            if(msgEl) {
                jQuery(msgEl).removeClass('gmpErrorMsg')
					.addClass('gmpSuccessMsg');
                for(var i in res.messages) {
                    jQuery(msgEl).append(res.messages[i]).append('<br />');
                }
            }
        }
    }
    if(params.onSuccess && typeof(params.onSuccess) == 'function') {
        params.onSuccess(res);
    }
}

function getDialogElementGmp() {
	return jQuery('<div/>').appendTo(jQuery('body'));
}

function toeOptionGmp(key) {
	if(GMP_DATA.options && GMP_DATA.options[ key ] && GMP_DATA.options[ key ].value)
		return GMP_DATA.options[ key ].value;
	return false;
}
function toeLangGmp(key) {
	if(GMP_DATA.siteLang && GMP_DATA.siteLang[key])
		return GMP_DATA.siteLang[key];
	return key;
}
function toePagesGmp(key) {
	if(typeof(GMP_DATA) != 'undefined' && GMP_DATA[key])
		return GMP_DATA[key];
	return false;;
}
/**
 * This function will help us not to hide desc right now, but wait - maybe user will want to select some text or click on some link in it.
 */
function toeOptTimeoutHideDescriptionGmp() {
	jQuery('#gmpOptDescription').removeAttr('toeFixTip');
	setTimeout(function(){
		if(!jQuery('#gmpOptDescription').attr('toeFixTip'))
			toeOptHideDescriptionGmp();
	}, 500);
}
/**
 * Show description for options
 */
function toeOptShowDescriptionGmp(description, x, y, moveToLeft) {
    if(typeof(description) != 'undefined' && description != '') {
        if(!jQuery('#gmpOptDescription').size()) {
            jQuery('body').append('<div id="gmpOptDescription"></div>');
        }
		if(moveToLeft)
			jQuery('#gmpOptDescription').css('right', jQuery(window).width() - (x - 10));	// Show it on left side of target
		else
			jQuery('#gmpOptDescription').css('left', x + 10);
        jQuery('#gmpOptDescription').css('top', y);
        jQuery('#gmpOptDescription').show(200);
        jQuery('#gmpOptDescription').html(description);
    }
}
/**
 * Hide description for options
 */
function toeOptHideDescriptionGmp() {
	jQuery('#gmpOptDescription').removeAttr('toeFixTip');
    jQuery('#gmpOptDescription').hide(200);
}
function toeInArrayGmp(needle, haystack) {
	if(haystack) {
		for(var i in haystack) {
			if(haystack[i] == needle)
				return true;
		}
	}
	return false;
}
function createAjaxLinkGmp(param) {
	return GMP_DATA.ajaxurl+ '?'+ paramGmp(param);
}
function paramGmp(param) {
	var param = jQuery.extend({}, param);
	param['pl'] = GMP_DATA.GMP_CODE;
	return jQuery.param( param );
}
function addDataTableRow(datatable, rowData) {
	datatable.fnAddData(rowData);
}
function updateDataTableRow(datatable, rowId, rowData) {
	var tblRowId = getDataTableRowId(datatable, rowId);
	if(tblRowId !== false) {
		datatable.fnUpdate(rowData, tblRowId);
	}
}
function removeDataTableRow(datatable, rowId) {
	var tblRowId = getDataTableRowId(datatable, rowId);
	if(tblRowId !== false) {
		datatable.fnDeleteRow(tblRowId);
	}
}
function getDataTableRow(datatable, rowId) {
	var tblRowId = getDataTableRowId(datatable, rowId);
	if(tblRowId !== false) {
		return datatable.fnGetData(tblRowId);
	}
	return false;
}
function getDataTableRowId(datatable, rowId) {
	if(!datatable)
		return false;
	var cells = []
	,	rows = datatable.fnGetNodes()
	,	tblRowId = false;
	for(var i = 0; i < rows.length; i++){
		// Get HTML of 3rd column (for example)
		cells.push(jQuery(rows[i]).find('td:eq(0)').html());
	}
	if(cells.length) {
		for(var i = 0; i < cells.length; i++) {
			if(cells[i] == rowId) {
				tblRowId = i;
				break;
			}
		}
	}
	return tblRowId;
}
function buildAjaxSelect(select, sendData, params) {
	var contMsg = jQuery('<span />').insertAfter( select );
	sendData.reqType = 'ajax';
	jQuery.sendFormGmp({
		msgElID: contMsg
	,	data: sendData
	,	onSuccess: function(res) {
			if(!res.error) {
				select.html('');
				if(params.selectTxt)
					select.append('<option value="0">'+ params.selectTxt+ '</option>');
				if(res.data[ params.itemsKey ]) {
					for(var i in res.data[ params.itemsKey ]) {
						var title = res.data[ params.itemsKey ][i][ params.idNameKeys.name ];//.post_title;
						if(params.titlePrepareCallback && typeof(params.titlePrepareCallback) === 'function') {
							title = params.titlePrepareCallback(title, res.data[ params.itemsKey ][i]);
						}
						select.append('<option value="'+ res.data[ params.itemsKey ][i][ params.idNameKeys.id ]+ '">'+ title+ '</option>');
					}
					if(typeof(params.selectedValue) !== 'undefined' && params.selectedValue !== null) {
						select.val( params.selectedValue );
					}
				}
			}
		}
	});
}
function detectInputType(input) {
	// TODO: add type detection for other inputs
	if(input.hasClass('colorpicker_input'))
		return 'colorpicker'
	if(input.hasClass('hidden_val_input') || input.data() == '')
		return 'hidden_check'
	var attrType = input.attr('type');
	return attrType;
}
function fillFormData(params) {
	params = params || {};
	var form = typeof(params.form) === 'string' ? jQuery(params.form) : params.form
	//,	keys = params.keys || {}
	,	data = params.data || {}
	,	arrayInset = params.arrayInset ? params.arrayInset : false;
	for(var key in data) {
		var formElementName = arrayInset ? arrayInset+ '['+ key+ ']' : key
		,	formElement = form.find('[name="'+ formElementName+ '"]');
		if(formElement.size()) {
			var type = detectInputType(formElement);
			switch(type) {
				case 'colorpicker':
					formElement.css('background-color', data[key]).val(data[key]);
					break;
				case 'checkbox':
					parseInt(data[key]) ? formElement.attr('checked', 'checked') : formElement.removeAttr('checked');
					break;
				case 'hidden_check':
					var checkboxId = formElement.attr('id').substr(0, toeStrlen(formElement.attr('id')) - toeStrlen('_text'))+ '_check'
					,	checkboxElement = form.find('#'+ checkboxId);
					parseInt(data[key]) ? checkboxElement.attr('checked', 'checked') : checkboxElement.removeAttr('checked');
					checkboxElement.trigger('change');
					break;
				default:
					formElement.val( data[key] );
					break;
			}
			if(toeInArray(type, ['colorpicker', 'hidden_check', 'hidden']) === -1) {
				formElement.trigger('change');
			}
		}
	}
	/*for(var key in keys) {
		var formElementName = arrayInset ? arrayInset+ '['+ key+ ']' : key
		,	formElement = form.find('[name="'+ formElementName+ '"]');
		switch(keys[key].type) {
			case 'checkbox':
				parseInt(data[key]) ? formElement.attr('checked', 'checked') : formElement.removeAttr('checked');
				break;
			default:
				formElement.val( data[key] );
				break;
		}
	}*/
}
function objToOneDimension(obj, params) {
	params = params || {};
	var newObj = {};
	for(var key in obj) {
		if(toeInArrayGmp(typeof(obj[ key ]), ['array', 'object']) && (!params.exclude || !toeInArrayGmp(key, params.exclude))) {
			var dimensioned = objToOneDimension(obj[ key ]);
			for(var i in dimensioned) {
				newObj[ i ] = dimensioned[ i ];
			}
		} else {
			newObj[ key ] = obj[ key ];
		}
	}
	return newObj;
}
function toeStrlen(str) {
	return str.length;
}
function toeLengthOfNum(n) {
    var count=0;
    do {n /= 10; count++} while (n >= 1);
    return count;
}

function gmpGetGeocoder() {
	if(!gmpGeocoder) {
		gmpGeocoder = new google.maps.Geocoder();
	}
	return gmpGeocoder;
}
jQuery.fn.mapSearchAutocompleateGmp = function(params) {
	params = params || {};
    jQuery(this).keyup(function(event){
		// Ignore tab, enter, caps, end, home, arrows
		if(toeInArrayGmp(event.keyCode, [9, 13, 20, 35, 36, 37, 38, 39, 40])) return;
		var address = jQuery.trim(jQuery(this).val());
		if(address && address != '') {
			// We should remember that we have used this functionality - so send ajax request to save usage stat
			/*jQuery.sendFormGmp({
				msgElID: 'noMessages'
			,	data: {mod: 'marker', action: 'saveFindAddressStat', reqType:'ajax'}
			});*/
			if(typeof(params.msgEl) === 'string')
				params.msgEl = jQuery(params.msgEl);
			params.msgEl.showLoaderGmp();
			jQuery(this).autocomplete({
				source: function(request, response) {
					var geocoder = gmpGetGeocoder();
					geocoder.geocode( { 'address': address}, function(results, status) {
						params.msgEl.html('');
						if (status == google.maps.GeocoderStatus.OK && results.length) {
							var autocomleateData = [];
							for(var i in results) {
								autocomleateData.push({
									lat: results[i].geometry.location.lat()
								,	lng: results[i].geometry.location.lng()
								,	label: results[i].formatted_address
								});
							}
							response(autocomleateData);
						} else {
							params.msgEl.html( toeLangGmp('Google can\'t find requested address coordinates, please try to modify search criterias.') );
						}
					});
				}
			,	select: function(event, ui) {
					if(params.onSelect) {
						params.onSelect(ui.item);
					}
				}
			});
			// Force imidiate search right after creation
			jQuery(this).autocomplete('search');
		}
	});
};
function gmpGetDataTableDefDisplayLen(tblId, defLen) {
	defLen = defLen || 10;
	var savedVal = parseInt(getCookieGmp('gmpTblLen_'+ tblId));
	return savedVal ? savedVal : defLen;
}
function gmpSetDataTableDefDisplayLen(tblId, len) {
	setCookieGmp('gmpTblLen_'+ tblId, len);
}
function gmpSwitchDataTablePagination(dataTbl) {
	if((dataTbl._iRecordsTotal
		&& dataTbl._iDisplayLength >= dataTbl._iRecordsTotal
		&& dataTbl._iRecordsDisplay >= dataTbl._iRecordsTotal)
		||
		(!dataTbl._iRecordsTotal
		&& dataTbl.aoData
		&& dataTbl._iDisplayLength >= dataTbl.aoData.length)
	) {
		jQuery(dataTbl.nTableWrapper).find('.dataTables_paginate').hide();
		if(dataTbl._iDisplayLength == 10)
			jQuery(dataTbl.nTableWrapper).find('.dataTables_length').hide();
	} else {
		jQuery(dataTbl.nTableWrapper).find('.dataTables_paginate').show();
		jQuery(dataTbl.nTableWrapper).find('.dataTables_length').show();
	}
}
function gmpSortActionsByPrior(a, b) {
	if(typeof(a.priority) == 'undefined' && typeof(b.priority) == 'undefined')
		return 0;
	if(typeof(a.priority) == 'undefined')
		return -1;
	if(typeof(b.priority) == 'undefined')
		return 1;
	if(a.priority > b.priority)
		return 1;
	if(a.priority < b.priority)
		return -1;
	return 0;
}
function gmpDoAction(tag) {
	if(gmpEvents[ tag ]) {
		gmpEvents[ tag ].sort( gmpSortActionsByPrior );
		var callArgs = [];
		for(var i in arguments) {
			if(parseInt(i)) {
				callArgs.push( arguments[i] );
			}
		}
		for(var i in gmpEvents[ tag ]) {
			callUserFuncArray(gmpEvents[ tag ][ i ].callback, callArgs);
		}
	}
}
function gmpAddAction(tag, callback, priority) {
	if(typeof(gmpEvents[ tag ]) == 'undefined') {
		gmpEvents[ tag ] = [];
	}
	gmpEvents[ tag ].push({
		callback: callback
	,	priority: priority
	});
}
function gmpAdjustCloseBtnInfoPos(infoWnd, params) {
	params = params || {};
	var shell = jQuery('.gm-style-iw')
	,	closeDiv = shell.next()
	,	parent = shell.parent()
	,	contentHtmlObj = shell.find('.gmpMarkerInfoWindow')
	,	hasScrollBar = params.checkScrollContent ? contentHtmlObj.hasScrollBarH() : shell.hasScrollBarH();

	if(!infoWnd.firstOpened) {
		infoWnd.firstOpened = true;
		infoWnd.parentWidth = parent.width();
	}
	contentHtmlObj.css({
		'width': '100%'
	});
	shell.append( closeDiv );
	shell.width(infoWnd.parentWidth - (hasScrollBar ? 15 : 0));	// 15 - for scroll bar
	closeDiv.css({
		'top': parseInt(closeDiv.css('top')) - 10
	,	'right': parseInt(closeDiv.css('right')) + 10
	});
	parent.width(infoWnd.parentWidth);
}