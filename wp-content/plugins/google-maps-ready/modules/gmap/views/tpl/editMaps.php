<!-- Map Editing -->
<div id="gmpEditMapContent">
	<ul class="gmpNewMapOptsTab nav nav-tabs">
		<li>
			<a id="gmpTabForNewMapMarkerOpts" href="#gmpEditMapMarkers">
				<button class="btn btn-success gmpAddNewMarkerBtn" onclick="gmpAddNewMarker({markerForm: '#gmpAddMarkerToEditMap'}); return false;">
					<span class="gmpIcon gmpIconMarker"></span>
					<?php langGmp::_e('Add New Marker')?>
				</button>
				<span class="gmpTabElemSimpTxt" disabled="disabled">
					<span class="gmpIconSimpMarker"></span>
					<?php langGmp::_e('Markers');?>
				</span>
				<span class="gmp-tabs-btns">
					<?php
						echo htmlGmp::button(array(
							'attrs' => 'id="AddMаrkerToMap" class="btn btn-success gmpAddSaveMarkerBtn" type="submit"', 
							'value' => "<span class='gmpIcon gmpIconAdd'></span>". langGmp::_('Save')));
					?>
					<button class="btn btn-danger removeMarkerFromForm" onclick="gmpRemoveMarkerItemFromMapForm(); return false;" disabled="disabled">
						<span class="gmpIcon gmpIconReset"></span>
						<?php langGmp::_e('Remove');?>
					</button>
				</span>
			</a>
		</li>
		<li>
			<a id="gmpTabForNewMapOpts" class="btn btn-primary gmpTabForNewMapOpts" href="#gmpEditMapProperties">
				<span class="gmpTabElemSimpTxt" disabled="disabled">
					<span class="gmpIconSimpMarker"></span>
					<?php langGmp::_e('Map Properties');?>
				</span>
				<button class="btn btn-success gmpSaveEditedMapBtn" id="gmpSaveEditedMap">
					<span class="gmpIcon gmpIconSuccess"></span>
					<?php langGmp::_e('Save Map');?>
				</button>
			</a>
		</li>
	</ul>
	<p id="gmpSaveEditedMapMsg"></p>
    <div class="gmpNewMapForms">
         <!-- Map Start -->
		<div class="gmpMapContainer">
            <div class="gmpMapWrapper">
                <div class="clearfix"></div>
                <div class="gmpDrawedNewMapOpts"></div>
                <div class="gmpNewMapPreview" id="gmpEditMapsContainer"></div>
                <div style="clear:both"></div>
                <?php dispatcherGmp::doAction('underMapAdminFormData');?>
            </div>
            <div class="gmpNewMapTabs tab-content">
                <div class="" id="newMapSubmitBtn">
                    <div class="gmpExistsMapOperations">
                       <div class="gmpMapOperationsMessages">
							<div style="float: left; padding-right: 10px; margin-top: -3px;" class="gmpShortCodePreview gmpShortCodePreviewForEditMap">
								<span>[ready_google_map id='']</span>
							</div>
                            <span class="editMapNameShowing text-info"><?php langGmp::_e('Map ID')?>: 
								<span class="gmpEditingMapId text-default"></span>
							</span>
                       </div>
                    </div>
                </div>
                <div class="tab-pane" id="gmpEditMapProperties">
                    <?php echo $this->mapForm; ?>
                </div>
                <div class="tab-pane" id="gmpEditMapMarkers">
                    <div id="gmpMarkerMapFormShell">
                        <?php echo $this->markerForm; ?>
                    </div>
                    <div class="gmpFormRow">
                        <label class="gmpFormLabel"><?php langGmp::_e('Markers List')?></label>
                        <br />
                        <table id="gmpMapMarkersTable" class="gmpTable dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th><?php langGmp::_e('ID')?></th>
                                    <th><?php langGmp::_e('Name')?></th>
                                    <th><?php langGmp::_e('Lat / Lon')?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
		</div>
		<!-- Map End-->
    </div>
</div>    
   