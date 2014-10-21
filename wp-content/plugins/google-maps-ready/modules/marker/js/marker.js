function gmpPrepareMarkerTblDescCells(cells) {
	cells.each(function(){
		// Wrap each cell content - into gmpMarkerDescTblWrap
		jQuery(this).html( jQuery('<div class="gmpMarkerDescTblWrap" style="" />').html(jQuery(this).html()) );
	});
}
// Find all td.description cells and wrap all their content - into gmpMarkerDescTblWrap
// tbl can be jQuery obj or element selector
function gmpWrapMarkersTblDesc(tbl) {
	if(typeof(tbl) === 'string')
		tbl = jQuery(tbl);
	gmpPrepareMarkerTblDescCells( tbl.find('td.description') );
}