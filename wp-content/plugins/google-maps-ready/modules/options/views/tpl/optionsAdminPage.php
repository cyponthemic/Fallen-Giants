<link rel="stylesheet" type="text/css" href="<?php echo GMP_CSS_PATH. 'gmpTabsContent.css';?>" />
<link rel="stylesheet" type="text/css" href="<?php echo GMP_CSS_PATH. 'bootstrap.min.css"';?>" />
<style type="text/css">
	.gmpMarkerInfoWindow{
		width:<?php echo $this->indoWindowSize['width'];?>px;
		height:<?php echo $this->indoWindowSize['height'];?>px;
	}
</style>
<?php 
     wp_enqueue_script('thickbox');
     wp_enqueue_script('media-models');
     wp_enqueue_script('media-upload');
     wp_enqueue_media();
?>
<script type="text/javascript">
	gmpDefaultOpenTab = "<?php echo $this->defaultOpenTab;?>";
</script>
<script type="text/javascript"  src="https://maps.googleapis.com/maps/api/js?&sensor=false"> </script>
<div id="gmpAdminOptionsTabs">
    <h1>
        <?php echo GMP_WP_PLUGIN_NAME?>
    </h1>
	<div class="gmpSingleBtnContainer">
		<a class="btn btn-primary gmpShowNewMapFormBtn">
			<span class="gmpIcon gmpIcongmpAddNewMap"></span>
			<?php langGmp::_e('Add New Map');?>
		</a>
	</div>
	<ul class="nav nav-tabs gmpMainTab" >
		<?php foreach($this->tabsData as $tId => $tData) { ?>
		<li class="<?php echo $tId?> ">
			<a href="#<?php echo $tId ?>">
				<span class="gmpIcon gmpIcon<?php echo $tId ?>"></span>
				<?php langGmp::_e($tData['title'])?>
			</a>
		</li>
		<?php }?>
	</ul>
	<?php foreach($this->tabsData as $tId => $tData) { ?>
	<div id="<?php echo $tId?>" class="tab-pane" >
		<?php echo $tData['content']; ?>
	</div>
	<?php }?>
</div>
