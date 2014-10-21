<div class="gmpMapsContainer">
	<script type='text/javascript'>
		var existsMapsArr = JSON.parse('<?php  echo utilsGmp::listToJson($this->mapsArr);?>');
		var defaultOpenTab = "<?php echo $this->currentTab;?>";
	</script>
	<div id="gmpAllMapsListShell">
		<?php echo $this->allMaps?>
	</div>
	<div id="gmpEditMapShell" style="display: none;">
		<?php echo $this->editMap?>
	</div>
</div>


