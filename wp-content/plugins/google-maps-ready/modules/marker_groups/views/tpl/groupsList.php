<div class="gmpMarkerListTable groupListConOpts tab-pane active">
    <div class="gmpMarkerListsOPerations">
        <a class="btn btn-success" onclick="gmpAddNewGroup()">
            <span class="gmpIcon gmpIconAdd"></span>
            <?php langGmp::_e('Add New')?>
        </a>
        <?php /*?><a class='btn btn-success' onclick="gmpRefreshGroupList()">
            <span class='gmpIcon gmpIconRefresh'></span>
            <?php langGmp::_e("Refresh")?>
        </a><?php */?>
     </div>
    <div class="gmpGTablecon">
        <?php echo @$this->tableContent; ?>
    </div>
</div>
<div class="gmpGroupForm groupListConOpts tab-pane">
    <div class="gmpGroupFormContainer">
        <?php echo htmlGmp::formStart('gmpGroupForm', array('attrs' => 'id="gmpGroupForm"')); ?>
        <fieldset>
            <legend><?php langGmp::_e('Save Group')?></legend>
            <div class="gmpFormRow">
				<label for="group_title" class="gmpFormLabel"><?php langGmp::_e("Group Title")?></label>
				<div class='gmpFormElemCon'>
					<?php echo htmlGmp::input('title', array(
						'attrs' => 'required="required" class="gmpInputLarge gmpHintElem" id="group_title"',
						'hint' => langGmp::_('Title For Group')));?>
	            </div>
            </div>
            <div class="gmpFormRow">
				<label for="group_description" class="gmpFormLabel"><?php langGmp::_e("Group Description")?></label>
				<div class='gmpFormElemCon'>
				<?php echo htmlGmp::textarea('description', array(
					'attrs' => 'id="group_description" class="gmpInputLarge gmpHintElem"',
					'hint' => langGmp::_('Description For Goup')));?>
	            </div>
            </div>
            <div class="gmpMarkerEditformBtns">
				<a id="gmpSave_group_button" class="btn btn-success" onclick="return gmpSaveGroup()">
					<span class="gmpIcon gmpIconAdd"></span>
					<?php langGmp::_e('Save');?>
				</a>
				<a  id="gmpReset_group_button" class="btn btn-danger" onclick="gmpResetGroupForm()">
					<span class="gmpIcon gmpIconReset"></span><?php langGmp::_e('Reset');?>
				</a>
               <div id="gmpGroupOptsMsg"></div>
			</div>
        </fieldset>
		<?php echo htmlGmp::formEnd();?>
    </div>
</div>