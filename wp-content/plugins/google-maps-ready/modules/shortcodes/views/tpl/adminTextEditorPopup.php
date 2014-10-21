<script type="text/javascript">
// <!--
var toeTextEditorInst = null;   //Here will be stored current text editor instance, see shortcodes/js/textEditroPlugin.js - ed var
var toeShortCodesData = <?php echo utilsGmp::jsonEncode($this->shortcodes)?>;
var toeShortCodeDataSelected = '';
var toeShortCodeCurrentMenuSelectedHtml = null;
(function(){
    jQuery('#toeInsertShortcodeSelectBox').accordion({
        change: function(event, ui) {
            toeShortCodeCurrentMenuSelectedHtml = ui.newContent;
            var code = jQuery(ui.newHeader).find('a:first').attr('title');
            jQuery('#toeInsertShortcodeSelectBox > div').css('height', '');
            if(code) {
                toeShortcodeSelect(code, toeShortCodeCurrentMenuSelectedHtml);
            } else {
                toeShortcodeSelect(jQuery('#toeInsertShortcodeForm select[name=shortcodeType]').val(), toeShortCodeCurrentMenuSelectedHtml);
            }
        },
        create: function(event, ui) {
            toeShortCodeCurrentMenuSelectedHtml = jQuery('#toeInsertShortcodeContentDefault');
        }
    });
    jQuery('#toeInsertShortcodeSelectBox > div').css('height', '');
    jQuery('#toeInsertShortcodeForm').submit(function(){
        if(jQuery(toeShortCodeCurrentMenuSelectedHtml).find('.toeInsertShortcodeAtts').find('input, select, textarea').size()) {
            var attsStr = ' ';
            var insertedAtts = 0;
            jQuery(toeShortCodeCurrentMenuSelectedHtml).find('.toeInsertShortcodeAtts').find('input, select, textarea').each(function(){
                var value = jQuery(this).val();
                if(value != null && value != '') {
                    var name = jQuery(this).attr('name');
                    name = str_replace(name, '[]', '');
                    if(jQuery(this).attr('type') == 'checkbox' && !jQuery(this).attr('checked'))
                        value = '';
                    attsStr += name+ '="'+ value+ '" ';
                    insertedAtts++;
                }
            });
            if(insertedAtts)
                attsStr = attsStr.substr(0, attsStr.length-1);
            else
                attsStr = '';
            jQuery('#toeInsertShortcodeForm input[name=newContent]').val(
                str_replace(jQuery('#toeInsertShortcodeForm input[name=newContent]').val(), '%atts%', attsStr)
            );
        }
        toeTextEditorInst.selection.setContent( jQuery('#toeInsertShortcodeForm input[name=newContent]').val() );
        subScreen.hide();
        return false;
    });
    jQuery('#toeInsertShortcodeForm select[name=shortcodeType]').change(function() {
        toeShortcodeSelect(jQuery(this).val(), toeShortCodeCurrentMenuSelectedHtml);
    });
})();
function toeShortcodeSelect(code, attsHtmlBox) {
    var codeData = toeShortCodesData[ code ];
    if(typeof(codeData) != 'undefined') {
        var selectedContent = toeTextEditorInst.selection.getContent();
        var newContent = str_replace(codeData.tpl, '%code%', code);
        newContent = str_replace(newContent, '%content%', selectedContent);
        jQuery(attsHtmlBox).find('.toeInsertShortcodeAtts:first').html('');
        if(jQuery(codeData.atts).size()) {
            var attsStr = ' ';
            for(var attId in codeData.atts) {
                attsStr += attId+ '="" ';
                jQuery(attsHtmlBox).find('.toeInsertShortcodeAtts:first').append('<div>'+ codeData.atts[ attId ].label+ ': '+ codeData.atts[ attId ].html+ '</div>');
            }
        }
        jQuery('#toeInsertShortcodeForm input[name=newContent]').val( newContent );
    }
}
// -->
</script>
<?php echo htmlGmp::formStart('toeInsertShortcodeForm', array('attrs' => 'id="toeInsertShortcodeForm"'))?>
    <div id="toeInsertShortcodeSelectBox">
        <h3><a href="#"><?php langGmp::_e('Shortcode')?></a></h3>
        <div id="toeInsertShortcodeContentDefault">
            <div><?php langGmp::_e('Type')?>: <?php echo htmlGmp::selectbox('shortcodeType', array('optionsGmp' => $this->shortcodesSelectOptions))?></div>
            <div class="toeInsertShortcodeAtts"></div>
        </div>
        <h3><a href="#" title="category"><?php langGmp::_e('Category')?></a></h3>
        <div>
            <div class="toeInsertShortcodeAtts"></div>
        </div>
        <h3><a href="#" title="product"><?php langGmp::_e('Product')?></a></h3>
        <div>
            <div class="toeInsertShortcodeAtts"></div>
        </div>
    </div>
    <div>
        <?php echo htmlGmp::hidden('newContent')?>
        <?php echo htmlGmp::submit('insert', array('value' => langGmp::_('Insert')))?>
    </div>
<?php echo htmlGmp::formEnd()?>