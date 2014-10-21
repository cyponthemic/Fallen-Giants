<!-- Add New Map -->
<div class='gmpNewMapContent'>
	<div class='gmpMapOptionsTab'>
		<ul class='gmpNewMapOptsTab nav nav-tabs'>
			<li>
				<a class="" id="gmpTabForNewMapMarkerOpts" href="#gmpAddMarkerToNewMap">
					<button class="btn btn-success gmpAddNewMarkerBtn" onclick="gmpAddNewMarker(this)">
						<span class="gmpIcon gmpIconMarker"></span>
						<?php langGmp::_e('Add Marker')?>
					</button>
					<span class="gmpTabElemSimpTxt" disabled="disabled">
						<span class="gmpIconSimpMarker"></span>
						<b><?php langGmp::_e("Markers");?></b>
					</span>
					<span class="gmp-tabs-btns">
						<?php
						 echo htmlGmp::button(array('attrs' => 'id="AddMаrkerToMap" class="btn btn-success gmpAddSaveMarkerBtn" type="submit"  disabled="disabled"', 'value' => '<span class="gmpIcon gmpIconAdd"></span>'. langGmp::_('Save')));
						?>
						<button class="btn btn-danger removeMarkerFromForm" disabled="disabled">
							<span class="gmpIcon gmpIconReset"></span>
							<?php langGmp::_e('Remove');?>											
						</button>
					</span>
				</a>
			</li>
			<li class="active">
				<a id="gmpTabForNewMapOpts" class="btn btn-primary gmpTabForNewMapOpts" href="#gmpMapProperties">
					<span class="gmpTabElemSimpTxt" disabled="disabled">
						<span class="gmpIconSimpMarker"></span>
						<b><?php langGmp::_e('Map Properties');?></b>
					</span>
					<button class="btn btn-success" id="gmpSaveNewMap"  disabled="disabled">
						<span class="gmpIcon gmpIconSuccess"></span>
						<?php langGmp::_e('Save Map'); ?>
					</button>	
				</a>
			</li>
		</ul>
	</div>
	<div class="gmpNewMapForms">
		<div class="gmpNewMapTabs tab-content">
			<div class="" id="newMapSubmitBtn">
				<div class="gmpNewMapOperations">
					<?php  htmlGmp::button(array('attrs' => ' type="submit" class="btn btn-success" id="gmpSaveNewMap" disabled="disabled" ', 
						'value' => '<span class="gmpIcon gmpIconSuccess"></span>Save Map'));?>
					<div id="gmpNewMapMsg"></div>
				</div>
			</div>
			<div class="tab-pane active" id="gmpMapProperties">
				<?php echo $this->mapForm; ?>
			</div>
			<div class="tab-pane" id="gmpAddMarkerToNewMap">
				<?php echo $this->markerForm;?>
			</div>
		</div>  
		 <!-- Map Start -->
		<div class="gmpMapContainer">
			<div class="clearfix"></div>
			<div class="gmpDrawedNewMapOpts"></div>
			<div class="gmpNewMapPreview" id="mapPreviewToNewMap"></div>
			<div style="clear:both"></div>
		</div>
		<!-- Map End-->
	</div>
</div>	
	