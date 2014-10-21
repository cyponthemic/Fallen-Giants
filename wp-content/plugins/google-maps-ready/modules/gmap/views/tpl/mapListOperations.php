<a class="gmpMapEditBtn btn btn-warning gmpEditBtn" onclick="gmpShowEditMap(<?php echo $this->map['id'];?>); return false;">
	<span class="gmpIcon gmpIconEdit"></span>
	<?php langGmp::_e('Edit');?>
</a>
<a class="gmpMapRemoveBtn gmpRemoveBtn btn btn-danger" onclick="gmpRemoveMap(<?php echo $this->map['id'];?>); return false;">
	<span class="gmpIcon gmpIconRemove"></span>
	<?php langGmp::_e('Delete');?>
</a>
<div id="gmpRemoveElemLoader__<?php echo $this->map['id'];?>"></div>
