<h2><?php langGmp::_e('Export / Import');?></h2>
<div class="gmpFormRow">
	<?php echo htmlGmp::button(array(
		'value' => langGmp::_('Export Maps'),
		'attrs' => 'id="gmpCsvExportMapsBtn" class="btn btn-success"'
	))?>
	<?php echo htmlGmp::button(array(
		'value' => langGmp::_('Export Markers'),
		'attrs' => 'id="gmpCsvExportMarkersBtn" class="btn btn-success"'
	))?>
	<br />
	<?php echo htmlGmp::checkbox('csv_export_with_markers', array(
		'attrs' => 'id="gmpCsvWithMarkersCheck"'
	))?>
	<label for="gmpCsvWithMarkersCheck"><?php langGmp::_e('Export maps with markers')?></label>
</div>
<hr />
<div class="gmpFormRow">
	<?php echo htmlGmp::ajaxfile('csv_import_file', array(
		'url' => uriGmp::_(array('baseUrl' => admin_url('admin-ajax.php'), 'page' => 'csv', 'action' => 'import', 'reqType' => 'ajax')), 
		'data' => 'gmpCsvImportData', 
		'buttonName' => 'Import Maps / Markers', 
		'responseType' => 'json',
		'onSubmit' => 'gmpCsvImportOnSubmit',
		'onComplete' => 'gmpCsvImportOnComplete',
		'btn_class' => 'btn btn-success',
	))?>
	<br />
	<span id="gmpCsvImportMsg"></span>
	<br />
	<?php echo htmlGmp::checkbox('csv_import_overwrite_same_names', array(
		'attrs' => 'id="gmpCsvOverwriteSameNames"'
	))?>
	<label for="gmpCsvOverwriteSameNames"><?php langGmp::_e('Overwrite data with same names')?></label>
</div>