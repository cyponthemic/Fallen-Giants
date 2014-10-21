<a class="btn btn-warning gmpEditBtn gmpListActBtn" id="<?php echo $this->marker['id']?>" onclick="gmpEditMarkerItem(<?php echo $this->marker['id']?>); return false;">
	<span class="gmpIcon gmpIconEdit"></span>
	<?php langGmp::_e('Edit')?>
</a>
<a class="btn btn-danger gmpRemoveBtn gmpListActBtn" id="<?php echo $this->marker['id']?>" onclick="gmpRemoveMarkerItem(<?php echo $this->marker['id']?>); return false;">
	<span class="gmpIcon gmpIconRemove"></span>
	<?php langGmp::_e('Remove')?>
</a>
<span id="gmpMarkerListTableLoader_<?php echo $this->marker['id']?>"></span>
