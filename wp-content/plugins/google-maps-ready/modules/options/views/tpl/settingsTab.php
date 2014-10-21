<div class="clearfix"></div>
<div class="gmpPluginSettingsFormContainer">
	<h2><?php langGmp::_e('Plugin Settings');?></h2>
	<form id="gmpPluginSettingsForm">
		<div class="gmpFormRow">
			<?php echo htmlGmp::checkboxHiddenVal('opt_values[save_statistic]', array(
				'attrs' => 'class="statistic"',
				'checked' => (bool)$this->optModel->get('save_statistic')))	
			?>
			<label for="gmpNewMap_title" class="gmpFormLabel">
				<?php langGmp::_e('Send anonym statistic?')?>
			</label>
		</div>
		<hr />
		<div class="gmp-control-group">
			<label><?php langGmp::_e('Marker Description window size')?></label>
			<div class="controls">
				<div class="gmpInfoWindowSize gmpInfoWindowSize-width">
					<label for="gmpInfoWindowSize_width"><?php langGmp::_e('Width');?></label>
					<div class="gmpSizePoint">Px</div>
					<input type="text" name="opt_values[infowindow_size][width]" class="input-mini" id="gmpInfoWindowSize_width" required="required" value="<?php echo $this->indoWindowSize['width'];?>">
				</div>
				<div class="gmpInfoWindowSize gmpInfoWindowSize-height">
					<label for="gmpInfoWindowSize_height"><?php langGmp::_e('Height');?></label>
					<div class="gmpSizePoint">Px</div>
					<input type="text" name="opt_values[infowindow_size][height]" class="input-mini" id="gmpInfoWindowSize_height" required="required" value="<?php echo $this->indoWindowSize['height'];?>">
				</div>
			</div>
		</div>
		<hr />
		<?php if(!empty($this->additionalOptions)) { ?>
			<?php foreach($this->additionalOptions as $addOpt) { ?>
				<div class="gmp-control-group"><?php echo $addOpt?></div>
				<hr />
			<?php }?>
		<?php }?>
		<div class="controls">
			<?php
				echo htmlGmp::hidden('mod', array('value' => 'options'));
				echo htmlGmp::hidden('action', array('value' => 'saveGroup'));
				echo htmlGmp::hidden('reqType', array('value' => 'ajax'));
			?>
			<div id="gmpPluginOptsMsg"></div>
			<input type="submit" class="btn btn-success" value="<?php langGmp::_e('Save')?>" />
		</div>
	</form>
</div>
<?php if(!empty($this->additionalGlobalSettings)) {
	foreach($this->additionalGlobalSettings as $setData) { ?>
		<div class="gmpPluginSettingsFormContainer"><?php echo $setData?></div>
	<?php }
}?>	