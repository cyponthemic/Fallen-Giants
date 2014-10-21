<p>
For a tutorial and more information on how to transfer your maps from "Comprehensive Google Map Plugin" to "Maps Marker Pro", please visit <a href="https://www.mapsmarker.com/cgmp-transfer" target="_blank">https://www.mapsmarker.com/cgmp-transfer</a>.
<br/><br/>
To start the transfer, please click the button <strong>step 1/2: create "Maps Marker Pro" maps</strong> below, which will create the according maps within your Maps Marker Pro installation.
<br/>
After that step it is advised to review those maps. With step 2/2 you can then automatically replace all existing CGMP shortcodes with the new "Maps Marker Pro" shortcodes.<br/>
</p>
<div class="tools-tabs">
	<ul class="tools-tabs-nav hide-if-no-js">
		<li class="current">
			<a href="#">Transfer control panel</a>
		</li>        
	</ul>

	<div class="tools-tab-body" id="settings" style="width: 90%;">
		<div class="tools-tab-content settings" >
				<form action='' name='' id='' method='post' style="clear:both;">
                                EXPORTED_MSG
				<table style="width:100%;"><tr><td>TRANSFERE_BUTTON</td><td>RESET_BUTTON</td></tr></table>
                                
				<div id='google-map-container-exports' style='margin-top: 30px; clear:both;'>
				
				<hr noshade="noshade" size="1"/>
				
				POSTS_WITH_SHORTCODES
			
			</div><br /><br />
			
		</form>
		</div>
	</div>

    
</div>

<style type="text/css">
    
#google-map-container-exports table {
        overflow:hidden;
        border:1px solid #d3d3d3;
        background:#fefefe;
        width:100%;
        margin:5% auto 0;
        margin-top: 20px;
        -moz-border-radius:5px; /* FF1+ */
        -webkit-border-radius:5px; /* Saf3-4 */
        border-radius:5px;
        -moz-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
        -webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
    }
    
    #google-map-container-exports table th, #google-map-container-exports table td {padding:18px 28px 18px; text-align:center; }
    
    #google-map-container-exports table th {padding-top:22px; text-shadow: 1px 1px 1px #fff; background:#e8eaeb;}
    
    #google-map-container-exports table td {border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0;}
    
   
    
    
    /*
    Background gradients are completely unnessary but a neat effect.
    */
    
    #google-map-container-exports table td {
        background: -moz-linear-gradient(100% 25% 90deg, #fefefe, #f9f9f9);
        background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f9f9f9), to(#fefefe));
    }
    
  
    
   #google-map-container-exports table  th {
        background: -moz-linear-gradient(100% 20% 90deg, #e8eaeb, #ededed);
        background: -webkit-gradient(linear, 0% 0%, 0% 20%, from(#ededed), to(#e8eaeb));
    }
    
    /*
    I know this is annoying, but we need dditional styling so webkit will recognize rounded corners on background elements.
    Nice write up of this issue: http://www.onenaught.com/posts/266/css-inner-elements-breaking-border-radius
    
    And, since we've applied the background colors to td/th element because of IE, Gecko browsers also need it.
    */
    
   #google-map-container-exports table  tr:first-child th.first {
        -moz-border-radius-topleft:5px;
        -webkit-border-top-left-radius:5px; /* Saf3-4 */
    }
    
   #google-map-container-exports table  tr:first-child th.last {
        -moz-border-radius-topright:5px;
        -webkit-border-top-right-radius:5px; /* Saf3-4 */
    }
    
   #google-map-container-exports table  tr:last-child td.first {
        -moz-border-radius-bottomleft:5px;
        -webkit-border-bottom-left-radius:5px; /* Saf3-4 */
    }
    
  #google-map-container-exports table   tr:last-child td.last {
        -moz-border-radius-bottomright:5px;
        -webkit-border-bottom-right-radius:5px; /* Saf3-4 */
    }
    #google-map-container-exports table tr td.shortcode{
        text-align: left;
    }

</style> 
