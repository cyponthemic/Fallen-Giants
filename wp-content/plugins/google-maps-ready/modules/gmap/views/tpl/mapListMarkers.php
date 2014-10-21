<div class="gmpMarkersListOfMap">
<?php foreach($this->map['markers'] as $marker){ ?>
	<div class="gmpMItem">
		<a href="#" onclick="gmpEditMarkerItem(<?php echo $marker['id']?>); return false;"><?php echo $marker['title']?></a>
	</div>
<?php } ?>
</div>