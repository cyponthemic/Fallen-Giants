<table class="gmpTable mapsTable" id="gmpMapsListTable">
	<thead>
		<tr>
			<?php foreach($this->displayColumns as $col) { ?>
			<th class="">
				<?php echo $col['label']?>
			</th>	
			<?php }?>
			<?php /*?><th class="gmpTableThMini">
				<?php echo langGmp::_('Id');?>
			</th>
			<th class="gmpTableThSmall">
				<?php echo langGmp::_('Title');?>
			</th>
			<th class="gmpTableThLarge">
				<?php echo langGmp::_('Description');?>
			</th>
			<th class="gmpTableThMax">
				<?php echo langGmp::_('Html options');?>
			</th>
			<th class="gmpTableThLarge">
				<?php echo langGmp::_('Markers');?>
			</th>
			<th class="thOperations">
				<?php echo langGmp::_('Operations');?>
			</th><?php */?>
		</tr>
	</thead>
	<tbody></tbody>
	<?php /*if(!empty($this->mapsArr) && is_array($this->mapsArr)) { ?>
	<tbody>
		<?php foreach($this->mapsArr as $map){ ?>
		<tr id="map_row_<?php echo $map['id'];?>">
			<td><?php echo $map['id']?></td>
			<td><?php echo $map['title']?></td>
			<td><?php echo $map['description']?></td>
			<td>
				<?php
				foreach($map['html_options'] as $k => $v) {
					echo $k. " : ". $v. "\n";
				}
				?>
				<hr/>
				<div class="gmpShortCodePreview">
				   <p><b><?php langGmp::_e('Shortcode')?>:</b></p>
				   <span>[ready_google_map id='<?php echo $map['id'];?>']</span>
				</div>
			</td>
			<td>
				<div class="gmpMarkersListOfMap">
				<?php foreach($map['markers'] as $marker){ ?>
					<div class="gmpMItem">
						<?php echo "<a onclick='gmpEditMarkerItem(".$marker['id'].")'>".$marker['title']."</a>";?>
					</div>
				<?php } ?>
			   </div>
			</td>
			<td class="gmpExistsMapActions">
				<a class="gmpMapEditBtn btn btn-warning gmpEditBtn" id="<?php echo $map['id'];?>" onclick="gmpEditMap(<?php echo $map['id'];?>);">
					<span class="gmpIcon gmpIconEdit"></span>
					<?php langGmp::_e('Edit');?>
				</a>
				<a class="gmpMapRemoveBtn gmpRemoveBtn btn btn-danger" id="<?php echo $map['id'];?>" onclick="gmpRemoveMap(<?php echo $map['id'];?>);">
					<span class="gmpIcon gmpIconRemove"></span>
					<?php langGmp::_e('Delete');?>
				</a>
				<div id="gmpRemoveElemLoader__<?php echo $map['id'];?>"></div>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<?php }*/?>
</table>
<script type="text/javascript">
// <!--
jQuery(document).ready(function(){
	gmpMapsTblColumns = <?php echo utilsGmp::jsonEncode($this->displayColumns)?>;
});
// -->
</script>