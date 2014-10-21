<?php 
	if(!empty($this->marker['map'])){ ?>
		<a href="#" onclick="gmpShowEditMap(<?php echo $this->marker['map']['id']?>); return false;"><?php echo $this->marker['map']['title']?></a>
	<?php } else {
		langGmp::_e('No maps contain this marker');
	}
?>