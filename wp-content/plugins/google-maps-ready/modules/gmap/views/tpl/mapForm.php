<?php echo htmlGmp::formStart('editMapForm', array('attrs'=> 'id="gmpEditMapForm" class="gmpMapFormItm"')); ?>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
			<?php echo htmlGmp::text('map_opts[title]', array(
				'attrs' => " class='gmpInputLarge gmpMapTitleOpts gmpHintElem' required='required' id='gmpNewMap_title' ", 
				'hint' => langGmp::_('Title For Map')));?>
		</div>
		<label for="gmpNewMap_title" class="gmpFormLabel">
			<?php langGmp::_e('Map Name')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::text('map_opts[width]', array(
				'value' => '100',
				'attrs' => 'class="gmpInputSmall gmpMapWidthOpt gmpHintElem" required="required" id="gmpNewMap_width" style="float: left;"',
				'hint' => langGmp::_('Width for map in pixels or percent')));?>
		&nbsp;
		<?php 
			echo htmlGmp::selectbox('map_opts[width_units]', array(
				'value' => '%',
				'options' => array('%' => '%', 'px' => 'px'),
				'attrs' => 'class="gmpInputSmall gmpHintElem" style="width: 45px !important;"',
				'hint' => langGmp::_('Width measure units, height will always be in px'),
			));?>
		</div>
		<label for="gmpNewMap_width" class="gmpFormLabel">
			<?php langGmp::_e('Map Width')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::text('map_opts[height]', array(
				'value' => '250', 
				'attrs' => 'class="gmpInputSmall gmpMapHeightOpt gmpHintElem" required="required" id="gmpNewMap_height" style="float: left;"',
				'hint' => langGmp::_('Height For Map In Pixels')));
		?>&nbsp;<span style="margin-right: 30px;">px</span>
		</div>
		<label for="gmpNewMap_height" class="gmpFormLabel">
			<?php langGmp::_e('Map Height')?>
		</label>
	</div>
	<?php dispatcherGmp::doAction('editMapFormProButtons')?>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::checkboxHiddenVal('map_opts[enable_zoom]', array(
				'checked' => '1',
				'attrs' => " class='gmpHintElem gmpMapEnableZoomOpts' ",
				'hint' => langGmp::_('Enable Zoom Control In Map')));
		?>
		</div>
		<label for="map_optsenable_zoom_check" class="gmpFormLabel">
			  <?php langGmp::_e('Enable Zoom/Control  Panel')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
			<?php
			  echo htmlGmp::checkboxHiddenVal('map_opts[enable_mouse_zoom]', array(
					'checked' => '1',
					'attrs' => 'class="gmpHintElem gmpMapEnableMouseZoomOpts"',
					'hint' => langGmp::_('Enable Mouse Zoom In Map'),
				));
			?>
		</div>
		<label for="map_optsenable_mouse_zoom_check" class="gmpFormLabel">
			<?php langGmp::_e('Enable Mouse Zoom/Control Panel')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
			<?php
				echo htmlGmp::checkboxHiddenVal('map_opts[infowindow_on_mouseover]', array(
					'checked' => '0',
					'attrs' => 'class="gmpHintElem"',
					'hint' => langGmp::_('If disabled - it will show infowindow on click event')));
			?>
		</div>
		<label for="map_optsinfowindow_on_mouseover_check" class="gmpFormLabel">
			<?php langGmp::_e('Show marker infowindow on mouse over (no click)')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			 echo htmlGmp::selectbox('map_opts[zoom]', array(
				 'attrs' => 'class="gmpMap_zoom gmpMapZoomOpts gmpInputSmall gmpHintElem" id="gmpMap_zoom"',
				 'options' => $this->map_opts['zoom'],
				 'value' => 1,
				 'hint' => langGmp::_('Default Zoom For Map')))
		?>
		</div>
		<label for="gmpMap_zoom" class="gmpFormLabel">
			<?php langGmp::_e('Map Zoom Level')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::selectbox('map_opts[type]', array(
				'attrs' => 'class="gmpMap_type gmpInputSmall gmpMapTypeOpt gmpHintElem" id="gmpMap_type"',
				'options' => $this->map_opts['type'],
				'hint' => langGmp::_('Select Map Display Mode')));
		?>
		</div>
		<label for="gmpMap_type" class="gmpFormLabel">
			<?php langGmp::_e('Map Type')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::selectbox('map_opts[language]', array(
				'attrs' => 'class="gmpMap_language gmpInputSmall gmpMapLngOpt gmpHintElem" id="gmpMap_language"',
				'options' => $this->map_opts['language'],
				'value' => 'en',
				'hint' => langGmp::_('Select Map Display Language')));
		?>
		</div>
		<label for="gmpMap_language" class="gmpFormLabel">
			<?php langGmp::_e('Map Language')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::selectbox('map_opts[align]', array(
				'attrs' => 'class="gmpInputSmall gmpMapAlignOpt gmpHintElem" id="gmpMap_align"',
				'options' => $this->map_opts['align'],
				'hint' => langGmp::_('Map Align')));
		?>
		</div>
		<label for="gmpMap_align" class="gmpFormLabel">
			<?php langGmp::_e('Map Align')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::hidden('map_opts[display_mode]', array(
				'value' => 'map',
				'attrs' => 'id="map_display_mode" class="map_display_preview_mode gmpMapDisplayModeOpt"'));
		?>
		</div>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::text('map_opts[margin]', array(
				'attrs' => 'class="gmpInputSmall gmpMapMarginOpt gmpHintElem" id="gmpNewMap_margin"',
				'hint' => langGmp::_('Select Map Display Mode')));
		?>
		</div>
		<label for="gmpNewMap_margin" class="gmpFormLabel">
			<?php langGmp::_e('Map Margin')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::colorpicker('map_opts[border_color]', array(
				'attrs' => 'class="gmpInputSmall map_border_color gmpMapBorderColorOpt gmpHintElem"',
				'hint' => langGmp::_('Select Map Display Mode')));
		?>
		</div>
		<label for="gmpNewMap_border_color" class="gmpFormLabel">
			<?php langGmp::_e('Border Color')?>
		</label>
	</div>
	<div class="gmpFormRow">
		<div class="gmpFormElemCon">
		<?php
			echo htmlGmp::text('map_opts[border_width]', array(
				'attrs' => 'class="gmpInputSmall gmpMapBorderWidthOpt gmpHintElem"  id="gmpNewMap_border_width"',
				'hint' => langGmp::_('Select Map Display Mode')));
		?>
		</div>
		<label for="gmpNewMap_border_width" class="gmpFormLabel">
			<?php langGmp::_e('Border Width')?>
		</label>
	</div>
	<div class="gmpFormRowsCon">
		<h3><?php langGmp::_e('Markers Infowindows Size');?></h3>
		<p><small><i><?php langGmp::_e('In Pixels');?></i></small></p>
		<div class="gmpFormRow">
			<div class="gmpFormElemCon">
			<?php
				echo htmlGmp::text('map_opts[infowindow_width]', array(
					'attrs' => 'class="gmpInputSmall gmpMapInfoWindowWidthOpt gmpHintElem" id="gmpNewMap_Infowindow_width"',
					'hint' => langGmp::_('InfoWindow Width'),
					'value' => '200'));
			?>
			</div>
			<label for="gmpNewMap_Infowindow_width" class="gmpFormLabel">
				<?php langGmp::_e('InfoWindow Width')?>
			</label>
		</div>  

		<div class="gmpFormRow">
			<div class="gmpFormElemCon">
			<?php
				echo htmlGmp::text('map_opts[infowindow_height]', array(
					'attrs' => 'class="gmpInputSmall gmpMapInfoWindowHeightOpt gmpHintElem"  id="gmpNewMap_Infowindow_height"',
					'hint' => langGmp::_('InfoWindow Height'),
					'value' => '100'));
			?>
			</div>
			<label for="gmpNewMap_Infowindow_height" class="gmpFormLabel">
				<?php langGmp::_e('InfoWindow Height');?>
			</label>
		</div>
	</div>
	<?php dispatcherGmp::doAction('editMapFormEnd')?>
	
	<?php echo htmlGmp::hidden('map_opts[id]')?>
	<?php echo htmlGmp::hidden('map_opts[map_center][coord_x]')?>
	<?php echo htmlGmp::hidden('map_opts[map_center][coord_y]')?>
	<?php echo htmlGmp::hidden('page', array('value' => 'gmap'))?>
	<?php echo htmlGmp::hidden('action', array('value' => 'save'))?>
	<?php echo htmlGmp::hidden('reqType', array('value' => 'ajax'))?>
<?php echo htmlGmp::formEnd();?>