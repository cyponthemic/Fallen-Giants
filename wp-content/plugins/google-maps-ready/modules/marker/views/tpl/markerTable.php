<script type='text/javascript'>
    var gmpExistsMarkers = JSON.parse('<?php echo utilsGmp::listToJson($this->markerList); ?>');
</script>
<table class="gmpTable" id="gmpTableMarkers">
	<thead>
	<?php foreach($this->displayColumns as $col) { ?>
		<th class="">
			<?php echo $col['label']?>
		</th>
	<?php }?>
	</thead>
	<tbody></tbody>
</table>
<div class="gmpRemoveListShell gmpExample">
	<a class="btn btn-danger gmpRemoveBtn gmpListActBtn" onclick="gmpRemoveSelectedMarkers(); return false;">
		<span class="gmpIcon gmpIconRemove"></span>
		<?php langGmp::_e('Remove selected')?>
	</a>
	<span class="gmpRemoveListMsg"></span>
</div>
<script type="text/javascript">
// <!--
jQuery('#gmpTableMarkers').find('input[name=check_all_markers]').click(function(e){
	e.stopPropagation();
});
jQuery(document).ready(function(){
	gmpMarkersTblColumns = <?php echo utilsGmp::jsonEncode($this->displayColumns)?>;
});
// -->
</script>

    