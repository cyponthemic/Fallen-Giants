<?php if($this->marker['titleLink']) { ?>
	<a href="<?php echo $this->marker['titleLink']['link']?>" target="_blank">
<?php } ?>
<?php echo $this->marker['title'];?>
<?php if($this->marker['titleLink']) { ?>
	</a>
<?php } ?>
