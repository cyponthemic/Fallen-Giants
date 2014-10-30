<?php
/*
Plugin Name: Loading Page
Plugin URI: http://wordpress.dwbooster.com/content-tools/loading-page
Description: Loading Page plugin performs a pre-loading of images on your website and displays a loading progress screen with percentage of completion. Once everything is loaded, the screen disappears.
Version: 1.0.1
Author: CodePeople
Author URI: http://www.codepeople.net
License: GPLv2
*/

// CONST
define('LOADING_PAGE_PLUGIN_DIR', dirname(__FILE__));
define('LOADING_PAGE_PLUGIN_URL', plugins_url('', __FILE__));
define('LOADING_PAGE_TD', 'loading_page_text_domain');

include LOADING_PAGE_PLUGIN_DIR.'/includes/admin_functions.php';

/**
* Plugin activation
*/
register_activation_hook( __FILE__, 'loading_page_install' );
if(!function_exists('loading_page_install')){        
	function loading_page_install() {
        // Set the default options here
        $loading_page_options = array(
            'foregroundColor'           => '#FFFFFF',
            'backgroundColor'           => '#000000',
            'enabled_loading_screen'    => true,
            'loading_screen'            => 'bar',
            'lp_loading_screen_display_in'  => 'all',
			'lp_loading_screen_display_in_pages' => '',
            'displayPercent'            => true,
            'backgroundImage'           => '',
            'backgroundImageRepeat'     => 'repeat',
            'fullscreen'                => 0,
            'pageEffect'                => 'none'
        );
        
        update_option('loading_page_options', $loading_page_options);
        
	} // End loading_page_install
} // End plugin activation

/*
*   Plugin initializing
*/
add_action( 'init', 'loading_page_init');
if(!function_exists('loading_page_init')){
    function loading_page_init(){
        if(!is_admin()){
            $op = get_option('loading_page_options');
            if($op){
                add_action( 'wp_head',  'loading_page_replace_the_header', 99 );
                add_action('wp_enqueue_scripts', 'loading_page_enqueue_scripts', 1);
            }    
        }
    } // End loading_page_init
}

/*
*   Admin initionalizing
*/
add_action('admin_init', 'loading_page_admin_init');
if(!function_exists('loading_page_admin_init')){
    function loading_page_admin_init(){
        // Load the associated text domain
        load_plugin_textdomain( LOADING_PAGE_TD, false, LOADING_PAGE_PLUGIN_DIR . '/languages/' );	
        
        // Set plugin links
        $plugin = plugin_basename(__FILE__);
        add_filter('plugin_action_links_'.$plugin, 'loading_page_links');
        
        // Load resources
        add_action('admin_enqueue_scripts', 'loading_page_admin_resources');
        
    } // End loading_page_admin_init
}

if(!function_exists('loading_page_links')){
    function loading_page_links($links){
        // Custom link
        $custom_link = '<a href="http://wordpress.dwbooster.com/contact-us" target="_blank">'.__('Request custom changes', LOADING_PAGE_TD).'</a>'; 
		array_unshift($links, $custom_link); 
        
        // Settings link
        $settings_link = '<a href="options-general.php?page=loading-page.php">'.__('Settings').'</a>'; 
		array_unshift($links, $settings_link); 
        
		return $links; 
    } // End loading_page_customization_link
}

// Set the settings menu option
add_action('admin_menu', 'loading_page_settings_menu');
if(!function_exists('loading_page_settings_menu')){
    function loading_page_settings_menu(){
        // Add to admin_menu
		add_options_page('Loading Page', 'Loading Page', 'edit_posts', basename(__FILE__), 'loading_page_settings_page'); 
    } // End loading_page_settings_menu
}

if(!function_exists('loading_page_replace_the_header')){
    function loading_page_replace_the_header($the_header){
        if( loading_page_loading_screen() )
        {
            echo '<style>body{visibility:hidden;}</style>';
        }
    }
}

if(!function_exists('loading_page_admin_resources')){
    function loading_page_admin_resources($hook){
        if(strpos($hook, "loading-page") !== false){
			wp_enqueue_media();
            wp_enqueue_style( 'farbtastic' );
            wp_enqueue_script( 'farbtastic' );
		    wp_enqueue_style( 'thickbox' );
            wp_enqueue_script( 'thickbox' );
            
            wp_enqueue_script('lp-admin-script', LOADING_PAGE_PLUGIN_URL.'/js/loading-page-admin.js', array('jquery', 'thickbox', 'farbtastic'));
        }
    } // End loading_page_admin_resources
} 

if( !function_exists('loading_page_loading_screen') ){
    function loading_page_loading_screen(){
        global $post;
        $op = get_option('loading_page_options');
        $loadingScreen = 0;
        if( !empty( $op['enabled_loading_screen'] ) )
        {
            $pages = ( !empty( $op[ 'lp_loading_screen_display_in_pages' ] ) ) ? $op[ 'lp_loading_screen_display_in_pages' ] : '';
            $pages = str_replace( ' ', '', $pages );
            $pages = explode( ',', $pages );
            
            if(
                empty( $op[ 'lp_loading_screen_display_in' ] ) ||
                $op[ 'lp_loading_screen_display_in' ] == 'all' ||
                ( $op[ 'lp_loading_screen_display_in' ] == 'home' && ( is_home() || is_front_page() ) ) ||
                ( $op[ 'lp_loading_screen_display_in' ] == 'pages' && isset( $post ) && in_array( $post->ID, $pages )  )
            )
            {
                $loadingScreen = 1;
            }
        }
        return $loadingScreen;
    }
}

if(!function_exists('loading_page_enqueue_scripts')){
    function loading_page_enqueue_scripts(){
		global $post;
		
        $op = get_option('loading_page_options');
        wp_enqueue_style('codepeople-loading-page-style', LOADING_PAGE_PLUGIN_URL.'/css/loading-page'.(($op['pageEffect'] != 'none') ? '-'.$op['pageEffect'] : '').'.css');
        $required = array('jquery');
        
        $loadingScreen = loading_page_loading_screen();
        
        if( $loadingScreen ){
            $s = loading_page_get_screen($op['loading_screen']);
            if($s){
                if(!empty($s['style'])){
                    wp_enqueue_style('codepeople-loading-page-style-'.$s['id'], $s['style']);
                }
                
                if(!empty($s['script'])){
                    wp_enqueue_script('codepeople-loading-page-script-'.$s['id'], $s['script'], array('jquery'));
                    $required[] = 'codepeople-loading-page-script-'.$s['id'];
                }
            }    
            wp_enqueue_script('codepeople-loading-page-script', LOADING_PAGE_PLUGIN_URL.'/js/loading-page.js', $required);
            
            $loading_page_settings = array(
				'loadingScreen'   => $loadingScreen,
                'backgroundColor' => $op['backgroundColor'],
                'foregroundColor' => $op['foregroundColor'],
                'backgroundImage' => $op['backgroundImage'],
                'pageEffect'      => $op['pageEffect'],
                'backgroundRepeat'=> $op['backgroundImageRepeat'],
                'fullscreen'      => ( ( !empty( $op[ 'fullscreen' ] ) ) ? 1 : 0 ),
                'graphic'         => $op['loading_screen'],
                'text'            => ((!empty($op['displayPercent'])) ? $op['displayPercent'] : 0)
			);
			
			wp_localize_script('codepeople-loading-page-script', 'loading_page_settings', $loading_page_settings );
        }
    } // End loading_page_enqueue_scripts
}

if(!function_exists('loading_page_settings_page')){
    function loading_page_settings_page(){
        if(isset($_POST['loading_page_nonce']) && wp_verify_nonce($_POST['loading_page_nonce'], __FILE__)){
            // Set the default options here
            $loading_page_options = array(
                'foregroundColor'           => (!empty($_POST['lp_foregroundColor'])) ? $_POST['lp_foregroundColor'] : '#FFFFFF',
                'backgroundColor'           => (!empty($_POST['lp_backgroundColor'])) ? $_POST['lp_backgroundColor'] : '#000000',
                'backgroundImage'           => $_POST['lp_backgroundImage'],
                'backgroundImageRepeat'     => $_POST['lp_backgroundRepeat'],
                'fullscreen'                => ( isset( $_POST['lp_fullscreen'] ) ) ? 1 : 0,
                'enabled_loading_screen'    => (isset($_POST['lp_enabled_loading_screen'])) ? true : false,
                'lp_loading_screen_display_in'  	 => ( isset( $_POST[ 'lp_loading_screen_display_in' ] ) ) ? $_POST[ 'lp_loading_screen_display_in' ] : 'all',
				'lp_loading_screen_display_in_pages' => $_POST[ 'lp_loading_screen_display_in_pages' ],
                'loading_screen'            => 'bar',
                'displayPercent'            => (isset($_POST['lp_displayPercent'])) ? true : false,
                'pageEffect'                => $_POST['lp_pageEffect']
            );
            
            if(update_option('loading_page_options', $loading_page_options)){
                print '<div class="updated">'.__('The Loading Page has been stored successfully', LOADING_PAGE_TD).'</div>';
            }else{
                print '<div class="error">'.__('The Loading Page settings could not be stored', LOADING_PAGE_TD).'</div>';
            }    
        }
        
        $loading_page_options = get_option('loading_page_options');
?>
        <div class="wrap">
            <h2><?php _e('Loading Page Settings', LOADING_PAGE_TD); ?></h2>
            <form method="post">
                <input type="hidden" name="loading_page_nonce" value="<?php print(wp_create_nonce(__FILE__)); ?>" />    
                <div class="postbox" style="min-width:760px;">
                    <h3 class='hndle' style="padding:5px;"><span><?php _e('Loading Screen', LOADING_PAGE_TD); ?></span></h3>
                    <div class="inside">
                        <p  style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;">If you want test the premium version of Loading Page go to the following links:<br/> <a href="http://demos.net-factor.com/loading-page/wp-login.php" target="_blank">Administration area: Click to access the administration area demo</a><br/> <a href="http://demos.net-factor.com/loading-page/" target="_blank">Public page: Click to access the Loading Page</a>
						</p>
                        <p><?php
                            print(
                                _e("Displays a loading screen until the webpage is ready, the screen shows the loading percent.",
                                LOADING_PAGE_TD)
                            );
                        ?></p>
                        <table class="form-table">
                            <tr>
                                <th><?php _e('Enable loading screen', LOADING_PAGE_TD); ?></th>
                                <td><input type="checkbox" name="lp_enabled_loading_screen" <?php echo(($loading_page_options['enabled_loading_screen']) ? 'CHECKED' : '' ); ?> /></td>
                            </tr>
                            <tr>
                                <th><?php _e('Display loading screen in', LOADING_PAGE_TD); ?></th>
                                <td>
									<div><input type="radio" name="lp_loading_screen_display_in" value="home" <?php echo(( isset( $loading_page_options['lp_loading_screen_display_in'] ) && $loading_page_options['lp_loading_screen_display_in'] == 'home' ) ? 'CHECKED' : '' ); ?> /> homepage only</div>
									<div><input type="radio" name="lp_loading_screen_display_in" value="all" <?php echo(( isset( $loading_page_options['lp_loading_screen_display_in'] ) && $loading_page_options['lp_loading_screen_display_in'] == 'all' ) ? 'CHECKED' : '' ); ?> /> all pages</div>
									<div><input type="radio" name="lp_loading_screen_display_in" value="pages" <?php echo(( isset( $loading_page_options['lp_loading_screen_display_in'] ) && $loading_page_options['lp_loading_screen_display_in'] == 'pages' ) ? 'CHECKED' : '' ); ?> /> the specific pages 
									<input type="text" name="lp_loading_screen_display_in_pages" value="<?php if( !empty( $loading_page_options['lp_loading_screen_display_in_pages'] ) ) print $loading_page_options['lp_loading_screen_display_in_pages']; ?>"> <span>In this case should be typed one, or more IDs for posts or pages, separated by the comma symbol ","</span></div>
									
								</td>
                            </tr>
                            <tr>
                                <?php $loading_screens = loading_page_get_screen_list();?>    
                                <th><?php _e('Select the loading screen', LOADING_PAGE_TD); ?></th>
                                <td>
                                    <select name="lp_loading_screen" DISABLED>
                                        <option value="bar">Bar screen</option>
                                    </select>
                                    <span style="color:#FF0000;">
                                        To display a different loading screen you require the commercial version of plugin <a href="http://wordpress.dwbooster.com/content-tools/loading-page" target="_blank">CLICK HERE</a>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Select background color', LOADING_PAGE_TD); ?></th>
                                <td><input type="text" name="lp_backgroundColor" id="lp_backgroundColor" value="<?php print(esc_attr($loading_page_options['backgroundColor'])); ?>" /><div id="lp_backgroundColor_picker"></div></td>
                            </tr>
                            <tr>
                                <th><?php _e('Select image as background', LOADING_PAGE_TD); ?></th>
                                <td>
                                    <input type="text" name="lp_backgroundImage" id="lp_backgroundImage" value="<?php print(esc_attr($loading_page_options['backgroundImage'])); ?>" />
                                    <input type="button" value="Browse" onclick="loading_page_selected_image('lp_backgroundImage');" /> 
                                    <select id="lp_backgroundRepeat" name="lp_backgroundRepeat">
                                        <option value="repeat" <?php if( $loading_page_options[ 'backgroundImageRepeat' ] == 'repeat' ) echo "SELECTED"; ?> >Tile</option>
                                        <option value="no-repeat" <?php if( $loading_page_options[ 'backgroundImageRepeat' ] == 'no-repeat' ) echo "SELECTED"; ?> >Center</option>
                                    </select>
                                    
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Display image in fullscreen', LOADING_PAGE_TD); ?></th>
                                <td>
                                    <input type="checkbox" name="lp_fullscreen" id="lp_fullscreen" <?php echo (( isset( $loading_page_options[ 'fullscreen' ] ) && $loading_page_options[ 'fullscreen' ] )   ? 'CHECKED' : '' );?> />
                                    <?php _e('(The fullscreen attribute can fail in some browsers)', LOADING_PAGE_TD); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Select foreground color', LOADING_PAGE_TD); ?></th>
                                <td><input type="text" name="lp_foregroundColor" id="lp_foregroundColor" value="<?php print(esc_attr($loading_page_options['foregroundColor'])); ?>" /><div id="lp_foregroundColor_picker"></div></td>
                            </tr>
                            <tr>
                                <th><?php _e('Apply the effect on page', LOADING_PAGE_TD); ?></th>
                                <td>
                                <select name="lp_pageEffect">
                                <?php
                                    $pageEffects = array('none', 'rotateInLeft');
                                    
                                    foreach($pageEffects as $value){
                                        print '<option value="'.$value.'" '.(($loading_page_options['pageEffect'] == $value) ? 'SELECTED' : '').'>'.$value.'</option>';
                                    }
                                ?>
                                    
                                </select>
                                <div>The premium version of plugin add the following effects: collapseIn, risingFromBottom, expandIn, fadeIn, fallFromTop, rotateInLeft, rotateInRight, rotateInRightWithoutToKeyframe, slideInSkew, tumbleIn, whirlIn</div>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Display loading percent', LOADING_PAGE_TD); ?></th>
                                <td><input type="checkbox" name="lp_displayPercent" <?php echo(($loading_page_options['displayPercent']) ? 'CHECKED' : '' ); ?> /></td>
                            </tr>
                        </table>
                    </div>
                </div>    
                <div class="postbox" style="min-width:760px;">
                    <h3 class='hndle' style="padding:5px;"><span><?php _e('Lazy Loading', LOADING_PAGE_TD); ?></span></h3>
                    <div class="inside">
                        <p><?php
                            print(
                                _e("To load only the images visible in the viewport to improve the loading rate of your website and reduce the bandwidth consumption.",
                                LOADING_PAGE_TD)
                            );
                        ?></p>
                        <p>
                            <span style="color:#FF0000;">
                                The lazy loading of images is available only in the commercial version of plugin <a href="http://wordpress.dwbooster.com/content-tools/loading-page" target="_blank">CLICK HERE</a>
                            </span>
                        </p>
                        <p><img src="<?php print(LOADING_PAGE_PLUGIN_URL.'/images/consumption_graph.png'); ?>" /></p>
                        <table class="form-table">
                            <tr>
                                <th><?php _e('Enable lazy loading', LOADING_PAGE_TD); ?></th>
                                <td><input type="checkbox" DISABLED /></td>
                            </tr>
                            <tr>
                                <th><?php _e('Select the image to load by default', LOADING_PAGE_TD); ?></th>
                                <td>
                                    <input type="text" DISABLED /><input type="button" value="Browse" DISABLED />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div><input type="submit" value="Update Settings" class="button-primary" /></div>
            </form>
        </div>    
<?php        
    } // End loading_page_settings_page
}
?>