<?php
$options = array();
foreach($this->map['html_options'] as $k => $v) {
	$options[] = $k. ': '. $v;
}
echo implode(', ', $options);
?>
<hr/>
<div class="gmpShortCodePreview">
   <p><b><?php langGmp::_e('Shortcode')?>:</b></p>
   <span><?php echo $this->generatedShortcode?></span>
</div>