<div class="markerOptsCon">
	<?php echo htmlGmp::formStart($this->params['formName'], array('attrs' => "id='". $this->params['formId']. "' class='gmpMarkerFormItm'"));?>
		<div class="gmapMarkerFormControlButtons">
			<div class="gmpAddMarkerOpts gmpMarkerEditformBtns">
				<?php
					echo htmlGmp::button(array(
						'attrs' => 'id="gmpSaveEditedMarkerItem" type="submit" class="btn btn-success"',
						'value' => '<span class="gmpIcon gmpIconSuccess"></span>'. langGmp::_('Save')));
				?>
				<a class="btn btn-danger gmpDeleteMarker" onclick="gmpRemoveMarkerItemFromMarkerForm(); return false;">
					<span class="gmpIcon gmpIconReset"></span><?php langGmp::_e('Remove');?>
				</a>
				<div id="gmpUpdateMarkerItemMsg"></div>
			</div>
			<div class="gmpEditMarkerOpts">
				<input type="hidden" id="gmpEditedMarkerLocalId" value="" />
			</div>
		</div>
		<div class="gmpFormRow">
			<div class="gmpFormElemCon">
				<?php
					echo htmlGmp::text('marker_opts[title]', array(
						'attrs' => 'class="gmpInputLarge gmpMarkerTitleOpt gmpHintElem" id="gmpNewMap_marker_title" required="required"', 
						'hint' => 'Title For Marker'));
				?>
			</div>
			<label for="gmpNewMap_marker_title" class="gmpFormLabel"><?php langGmp::_e('Marker Name')?></label>
		</div>
		<div class="gmpFormRow">
			<label for="" class="gmpFormLabel"><?php langGmp::_e('Set Title as link');?></label>
			<div class="gmpFormElemCon">
			<?php echo htmlGmp::checkbox('marker_opts[params][title_is_link]', array(
					'attrs' => 'class="title_is_link gmpMarkerTitleIsLinkOpt gmpHintElem"',
					'hint' => langGmp::_('Set Marker Title As Link')));?>
			</div>
			<div class="markerTitleLink_Container">
			<?php echo htmlGmp::text('marker_opts[params][marker_title_link]', array(
					'attrs' => 'id="marker_title_link" class="marker_title_link gmpMarkerTitleIsLinkOpt gmpHintElem" placeholder="http://domain.com"',
					'hint' => langGmp::_('Link For Title'))); ?>
			</div>
		</div>  
		<div class="gmpFormRow">
			<div class="gmpFormElemCon">
			<?php
				$groupArr = array();
				foreach($this->marker_opts['groups'] as $item){
					$groupArr[$item['id']]=$item['title'];
				}
				echo htmlGmp::selectbox('marker_opts[marker_group_id]', array(
					'options' => $groupArr,
					'value' => '1' ,
					'attrs' => 'id="gmpNewMap_marker_group" class="gmpInputLarge gmpMarkerGroupSelect gmpMarkerGroupOpt gmpHintElem"',
					'hint' => langGmp::_('Choose Marker Group')));
			?>
			</div>
			<label for="gmpNewMap_marker_group" class="gmpFormLabel"><?php langGmp::_e('Group')?></label>
		</div>
		<div class="gmpFormRow">
			<label for="gmpNewMap_marker_desc" class="gmpFormLabel"><?php langGmp::_e('Marker Description')?></label>
			<?php wp_editor('', 'marker_opts_description' , array(
				//'quicktags' => false,
				'dfw' => true,
				'drag_drop_upload' => true,
			));?>
		</div>
		<div class="gmpMarkericonOptions">
			<h3><?php langGmp::_e('Marker Icon')?></h3>
			<div class="gmpFormRow">
				<div class="gmpIconsSearchBar">
					<div class="lft">
						<a class="btn btn-default" onclick="clearIconSearch(); return false;"><?php langGmp::_e('All Icons');?></a>
					</div>
					<div class="right gmpSearchFieldContainer">
						<div class="gmpIconSearchZoomIcon"></div>
						<div class="gmpFormElemCon"><?php
							echo htmlGmp::text('gmpSearchIconField', array(
								'attrs' => 'class="gmpSearchIconField gmpHintElem"',
								'hint' => langGmp::_('Search For Icons')));
						?>
						</div>
					</div>
			   </div>
				<div class="gmpIconsList">
				<?php
					 $defIcon = false;
					 $activeClassName = '';
					 foreach(array_reverse($this->marker_opts['icons']) as $icon) {
						   if(!$defIcon){
							   $defIcon = $icon['id'];
							   $activeClassName = ' active';
						   }
				?>
						<a class="markerIconItem <?php echo $activeClassName;?>" data_name="<?php echo $icon['title'];?>" data_desc="<?php echo $icon['description']; ?>" title="<?php echo $icon['title'];?>" data_val="<?php echo $icon['id'];?>">
							<img src="<?php echo $icon['path'];?>" class="gmpMarkerIconFile" />
							<span class="gmpMarkerIconRemoveBtn gmpHidden"><?php echo htmlGmp::img('delete.png')?></span>
						</a>
						<?php
							   $activeClassName = '';
					}
				?>
				</div>  
				<input type="hidden" name="marker_opts[icon]" value="<?php echo $defIcon;?>" id="gmpSelectedIcon" class="right gmpMarkerSelectedIconOpt">
			</div>   
			<div class="gmpFormRow">
				<label for=""><?php langGmp::_e('Or Upload your icon');?></label>
				<label for="upload_image" class='right'><input id="gmpUploadIcon" class="gmpUploadIcon button" type="button" value="Upload Image" /></label>
				<div class="gmpUplRes"></div>  
				<div class="gmpFileUpRes"></div>   
			</div>
		</div> 
		<div class="gmpFormRow">
			<label for="marker_opts[animation]"><?php langGmp::_e('Marker Animation')?></label> 
			<div class="gmpFormElemCon">
				<?php echo htmlGmp::selectbox('marker_opts[animation]', array(
					'attrs' => 'class="gmpHintElem"',
					'options' => $this->animOpts,
					'hint' => langGmp::_('Enable Marker Animation')));?>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="gmpFormRow gmpAddressField">
			<label for="gmp_marker_address" class="gmpFormLabel"><?php langGmp::_e('Marker Address')?></label>
			<span id="gmpAddressLoader"></span><br />
			<?php
				echo htmlGmp::text('marker_opts[address]', array(
					'attrs' => 'class="gmp_marker_address gmpMarkerAddressOpt gmpHintElem" id="gmp_marker_address" placeholder="Type address"'));
			?>
			<div class="gmpAddressAutocomplete">
				<ul></ul>
			</div>	
			<div class="gmpAddrErrors"></div>
		</div>
		<div class="gmpFormRow">
			<label for="gmpNewMap_marker_coords" class="gmpFormLabel"><?php langGmp::_e('Marker Coordinates')?></label><br />
			<small class="gmplft"><?php echo langGmp::_('if your don\'t know coordiates, leave this fields blank');?></small>
			<div class="clearfix"></div>
			<div>
				<?php echo langGmp::_('Lat.'); ?>
				<div class="gmpFormElemCon">
					<?php
						echo htmlGmp::text('marker_opts[coord_y]', array(
							'attrs' => 'class="gmpInputSmall gmpMarkerCoordYOpt gmpHintElem"  id="gmp_marker_coord_y"',
							'hint' => 'Coordinate Y(Latitude)'));
					?>
				</div>
			</div>
			<br />
			<div>
				<?php echo langGmp::_('Lng.'); ?>
				<div class="gmpFormElemCon">
					<?php
						echo htmlGmp::text('marker_opts[coord_x]', array(
							'attrs' => 'class="gmpInputSmall gmpMarkerCoordXOpt gmpHintElem"   id="gmp_marker_coord_x"',
							'hint' => 'Coordinate X (Longitude)'));
					?>
				</div>
			</div>
		</div>
		<div class="gmpFormRow">
			<label for="marker_opts[params][more_info_link]"><?php langGmp::_e('Add "More info" in description window')?></label> 
			<div class="gmpFormElemCon">
				<?php echo htmlGmp::checkboxHiddenVal('marker_opts[params][more_info_link]', array(
					'attrs' => 'class="gmpHintElem"',
					'hint' => langGmp::_('If enabled - in description window by default will be only image or part of description, and added "More Info" link, when click on it - there will be full descrtiption')));?>
			</div>
		</div>
		<div class="gmpFormRow">
			<label for="marker_opts[params][icon_fit_standard_size]"><?php langGmp::_e('Fit icon in standard size')?></label> 
			<div class="gmpFormElemCon">
				<?php echo htmlGmp::checkboxHiddenVal('marker_opts[params][icon_fit_standard_size]', array(
					'attrs' => 'class="gmpHintElem"',
					'hint' => langGmp::_('If enabled - icon width will be always standard - 18, width - from proportion, this parameter is for retina display')));?>
			</div>
		</div>
		<?php echo htmlGmp::hidden('marker_opts[id]')?>
		<?php echo htmlGmp::hidden('marker_opts[map_id]')?>
		<?php echo htmlGmp::hidden('marker_opts[description]')?>
		<?php echo htmlGmp::hidden('page', array('value' => 'marker'))?>
		<?php echo htmlGmp::hidden('action', array('value' => 'save'))?>
		<?php echo htmlGmp::hidden('reqType', array('value' => 'ajax'))?>
	<?php echo htmlGmp::formEnd(); ?>
</div>
