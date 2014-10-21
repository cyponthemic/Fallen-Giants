jQuery(document).ready(function(){
	jQuery('.gmpInfoWindowSize input').keyup(function(e){
		if(e.keyCode == 0) {
			return;
		}
		var val = jQuery(this).val();
		if(val == ''){
			return false;
		}
		var res= parseInt(val);
		if(isNaN(res)) {
			res = 100;
		}
		jQuery(this).val(res);
	});
	jQuery('#gmpPluginSettingsForm').submit(function(){
		jQuery(this).sendFormGmp({
			msgElID: 'gmpPluginOptsMsg'
		,	onSuccess: function(res){
				
			}
		});
		return false;
	});
});