var gmpGroupForm ;
jQuery(document).ready(function(){
    gmpGroupForm = jQuery("#gmpGroupForm");
});
function gmpRefreshGroupList(){
    var sendData = {
        mod: 'marker_groups'
	,	action: 'refreshGroupsList'
	,	reqType: 'ajax'
    };
    jQuery('#GmpTableGroups').remove();
    jQuery('.gmpGTablecon').addClass('gmpMapsTableListLoading');
    jQuery.sendFormGmp({
        msgElID: ''
	,	data: sendData
	,	onSuccess:function(res){
            if(!res.error){
                jQuery('.gmpGTablecon').removeClass('gmpMapsTableListLoading');
                jQuery('.gmpGTablecon').html(res.html);
                datatables.reCreateTable("GmpTableGroups");
            }
        }
    });
}
function gmpEditGroupItem(groupId){
    var currentGroup;
    for(var i in gmpExistsGroups){
        if(gmpExistsGroups[i].id == groupId){
            currentGroup=gmpExistsGroups[i];
        }
    }
    gmpGroupForm.find("#group_title").val(currentGroup.title);
    gmpGroupForm.find("#group_description").val(currentGroup.description);
    gmpGroupForm.find("#gmpSave_group_button").attr("onclick","gmpSaveGroup("+groupId+")");
    jQuery(".groupListConOpts").toggleClass("active");
}
function gmpSaveGroup(groupId){
    var params = {
        title: gmpGroupForm.find('#group_title').val()
	,	description: gmpGroupForm.find('#group_description').val()
    };
    if(typeof(groupId) == 'undefined'){
        params.mode = 'new';
    } else {
        params.mode = 'update';
        params.id = groupId;
    }
    var sendData = {
        mod: 'marker_groups'
	,	action: 'saveGoup'
	,	reqType: 'ajax'
	,	goupInfo: params
    };
    var respElem = jQuery('#gmpGroupOptsMsg');
    jQuery.sendFormGmp({
        msgElID: 'gmpGroupOptsMsg'
	,	data: sendData
	,	onSuccess: function(res){
            if(res.error) {
                respElem.html(res.errors.join(","));
            } else {
                setTimeout(function(){
                    gmpResetGroupForm();
                    gmpSetNewGruopToForms(res.data.group);
				},500);
			}
		}
    });
    return false;
}
function gmpSetNewGruopToForms(params){
    jQuery('.gmpMarkerGroupOpt').each(function(){
		var founded = false;
		var opt = jQuery(this).find("option[value='"+params.id+"']");
		if(opt.length == 0){
			jQuery(this).append("<option value='"+params.id+"'>"+params.title+"</option>");
		} else {

		}
		opt.attr("value",params.id);
		opt.text(params.title);
    });
    gmpRefreshMarkerList();
    getMapsList();
}
function gmpResetGroupForm(){
    gmpGroupForm.find("#group_title").val("");
    gmpGroupForm.find("#group_description").val("");
    gmpGroupForm.find("#gmpSave_group_button").attr("onclick","gmpSaveGroup()");    
    jQuery(".groupListConOpts").toggleClass("active");
    gmpRefreshGroupList();
}
function gmpAddNewGroup(){
   gmpResetGroupForm();
}
function gmpRemoveGroupItem(groupId){
     var sendData ={
        mod     :   'marker_groups',
        action  :   'removeGroup',
        reqType :   'ajax',
        group_id:   groupId
    }
    var respElem = jQuery("#gmpGroupListTableLoader_"+groupId);
    jQuery.sendFormGmp({
        msgElID:"gmpGroupListTableLoader_"+groupId,
        data:sendData,
        onSuccess:function(res){
            if(!res.error){
                setTimeout(function(){
                    jQuery("tr#groupRow_"+groupId).hide('500');
                },800);
            }else{
                respElem.html(res.errors.join(","));                
            }
        }
    })   
}