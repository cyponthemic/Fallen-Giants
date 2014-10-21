<!--Markers lists-->
<style type="text/css">
	#gmpMarkersListTypeTplsShell .gmpAdminTemplateImgPrev {
		display: block;
		margin-left: auto;
		margin-right: auto;
		max-height: none;
		max-width: none;
	}
	#gmpMarkersListTypeTplsShell .gmpTemplatesList .button {
		position: static;
		margin-left: auto;
		margin-right: auto;
		display: block;
	}
	#gmpMarkersListTypeTplsShell .gmpTemplatesList .gmpTemplatePrevShell {
		width: 355px;
		height: 650px;
	}
</style>
<div class="gmpFormRow">
	<div class="gmpFormElemCon">
		<?php echo htmlGmp::button(array(
			'value' => langGmp::_('Select markers list type'),
			'attrs' => 'class="btn btn-primary gmpHintElem" type="button" onclick="gmpShowMarkersListTplPopup(); return false;"',
			'hint' => langGmp::_('Select view of markers list on frontend from available list')
		))?>
		<?php echo htmlGmp::hidden('map_opts[markers_list_type]', array('value' => 0))?>
	</div>
	<label for="" class="gmpFormLabel">
		<?php langGmp::_e('Markers list type')?>
	</label>
</div>
<div id="gmpMarkersListTypeTplsShell" style="display: none;">
	<ul class="gmpTemplatesList gmpMLTypeTpls">
	<?php foreach($this->listAvailableDirectionsViews as $id => $dView) { ?>
	<?php 
		$liStyle = '';
		$btnStyle = '';
		$isSlider = $id == 6; // slider id
		if($isSlider) {
			$liStyles = array('height' => '405px', 'width' => '730px');
			$btnStyles = array('margin-left' => '0', 'margin-right' => '0', 'display' => 'inline');
			//$imgStyles = array('max-width' => 'auto');
			$liStyle = $btnStyle = 'style="';
			foreach($liStyles as $k => $v) {
				$liStyle .= $k. ':'. $v. ';';
			}
			$liStyle .= '"';
			foreach($btnStyles as $k => $v) {
				$btnStyles .= $k. ':'. $v. ';';
			}
			$btnStyle .= '"';
		}
	?>
	<li class="gmpTemplatePrevShell gmpTemplatePrevShell-existing gmpTemplatePrevShell-<?php echo $id?>" data_code="<?php echo $id?>" <?php if(!empty($liStyle)) { echo $liStyle; }?>>
		<h2 style="text-align: center; color: #454545; <?php if($isSlider) {?> padding-left: 138px;<?php }?>"><?php echo $dView['label']?>
			<?php if($isSlider) { ?>
				<input type="submit" class="button button-primary button-large" value="<?php langGmp::_e('Available in PRO')?>" style="margin-left: 0; margin-right: 0; display: inline;" data_code="<?php echo $id?>">
			<?php }?>
		</h2><hr />
		<?php echo htmlGmp::img( $dView['prev_img'], false, array('attrs' => 'class="gmpAdminTemplateImgPrev"'));?><hr />
		<?php if(!$isSlider) { ?>
			<input type="submit" class="button button-primary button-large" value="<?php langGmp::_e('Available in PRO')?>"  data_code="<?php echo $id?>">
		<?php }?>
	</li>
	<?php } ?>
	</ul>
</div>
<!--Custom controls-->
<div class="gmpFormRowsCon">
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
			<?php echo htmlGmp::button(array(
				'value' => langGmp::_('Select custom controls view'),
				'attrs' => 'class="btn btn-primary gmpHintElem" type="button" onclick="gmpShowCustomControlsTplPopup(); return false;"',
				'hint' => langGmp::_('You can select custom view of map controls')
			))?>
			<?php echo htmlGmp::hidden('map_opts[custom_map_controls]')?>
		</div>
		<label for="" class="gmpFormLabel">
			<?php langGmp::_e('Custom controls')?>
		</label>
	</div>
</div>
<div id="gmpCustomMapControlsTplsShell" style="display: none;">
	<ul class="gmpTemplatesList gmpCMCTplsList">
	<?php foreach($this->listAvailableCustomControls as $cmc) { ?>
	<li class="gmpTemplatePrevShell gmpTemplatePrevShell-existing gmpTemplatePrevShell-<?php echo $cmc['code']?>" data_code="<?php echo $cmc['code']?>">
		<h2 style="text-align: center; color: #454545"><?php echo $cmc['label']?></h2><hr />
		<?php echo htmlGmp::img( $cmc['prev_img'], false, array('attrs' => 'class="gmpAdminTemplateImgPrev"'));?><hr />
		<input type="submit" class="button button-primary button-large" value="<?php langGmp::_e('Available in PRO')?>"  data_code="<?php echo $cmc['code']?>">
	</li>
	<?php } ?>
	</ul>
</div>
<!--Maps stulizations-->
<div class="gmpFormRowsCon">
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
			<?php echo htmlGmp::button(array(
				'value' => langGmp::_('Select map stylization'),
				'attrs' => 'class="btn btn-primary gmpHintElem" type="button" onclick="gmpShowGSTplPopup(); return false;"',
				'hint' => langGmp::_('You can select style for your map to make it view unbelievable')
			))?>
			<?php echo htmlGmp::hidden('map_opts[stylization]')?>
		</div>
		<label for="" class="gmpFormLabel">
			<?php langGmp::_e('Map Stylization')?>
		</label>
	</div>
</div>
<div id="gmpGSControlsTplsShell" style="display: none;">
	<ul class="gmpTemplatesList gmpGSTplsList">
	<?php foreach($this->stylesList as $s) { ?>
	<li class="gmpTemplatePrevShell gmpTemplatePrevShell-existing gmpTemplatePrevShell-<?php echo $s['code']?>" data_code="<?php echo $s['code']?>">
		<h2 style="text-align: center; color: #454545"><?php echo $s['label']?></h2><hr />
		<?php echo htmlGmp::img( $s['prev_img'], false, array('attrs' => 'class="gmpAdminTemplateImgPrev"'));?><hr />
		<input type="submit" class="button button-primary button-large" value="<?php langGmp::_e('Available in PRO')?>"  data_code="<?php echo $s['code']?>">
	</li>
	<?php } ?>
	</ul>
</div>
<!--Additional pro controls-->
<div class="gmpFormRow">
	<a href="<?php echo $this->proLink?>" target="_blank"><img src="<?php echo $this->modPath?>img/pro_controls_1.png" title="PRO Additional Settings" /></a>
</div>
<script type="text/javascript">
// <!--
var gmpProSiteLink = '<?php echo $this->proLink?>';
// -->
</script>