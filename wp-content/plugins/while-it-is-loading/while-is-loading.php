<?php
/**
 * @package While Loading
 * @version 3.0
 */
/*
Plugin Name: While Loading
Plugin URI: http://wordpress.org/extend/plugins/while-it-is-loading/
Description: It shows a screen while all content page is being loaded. After the content has been rendered, it disappears.
Author: Garmur
Version: 3.0
Author URI: https://google.com/+GeorgeGarro
Tags: lazy load, loading, gear, screen, personalization
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JVGHP29EWE85G
Text Domain: while-loading
Domain Path: /i18n/
*/

/*	Copyright 2014 Garmur

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!defined('ABSPATH'))
	die("You don't have access.");

// Make sure we don't expose any info if called directly
if(!function_exists('add_action')) {
	echo 'Hi there! You cannot access here. We are sorry.';
	exit;
}

if(!defined('WL_PLUGIN_DIR_IMG'))
	define('WL_PLUGIN_DIR_IMG',WP_PLUGIN_DIR . '/' .trim(dirname(plugin_basename(__FILE__) ), '/') . '/img');
if(!defined('WL_PLUGIN_URL_IMG'))
	define('WL_PLUGIN_URL_IMG', plugins_url( '/img' , __FILE__ ));

function wl_gear(){
	do_action('wl_gear');
}
add_action('after_theme_setup','wl_gear');

if(!class_exists('WhileLoading')){
	final class WhileLoading{
		public function __construct(){
			add_action('admin_menu',array($this,'addSubPage'));
			add_action('plugins_loaded',array($this,'addI18n'));
			add_action('wp_head',array($this,'writeOnHeadTheme'));
			add_action('plugin_action_links_'.basename( dirname( __FILE__ ) ).'/'.basename( __FILE__ ), array($this,'addLinkSettings'), 10, 4);
			add_action('admin_enqueue_scripts',array($this,'enqueueColorPicker'));
			add_action('wl_gear',array($this,'writeOnBodyTheme'));
			add_action('wp_print_styles',array($this,'enqueueFeStyles'));
			register_uninstall_hook(__FILE__,array('WhileLoading','onUninstall'));
			if(get_option('carga_en_vista')){
				add_action('wp_head',array($this,'enqueueLazyScripts'));
				add_filter('post_thumbnail_html',array($this,'loadTempImages'),PHP_INT_MAX);
				add_filter('the_content',array($this,'loadTempImages'),PHP_INT_MAX);
				add_filter('widget_text',array($this,'loadTempImages'),PHP_INT_MAX);
				add_filter('get_avatar',array($this,'loadTempImages'),PHP_INT_MAX);
			}
		}

		public function loadTempImages($contHTML){
			if( is_admin() || is_feed() || is_preview() || empty( $contHTML ) )
				return $contHTML;
			if (strpos( $contHTML, 'data-src' ) !== false)
				return $contHTML;
			$contHTML = preg_replace_callback('#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', array($this, 'replaceCallback'), $contHTML);
			return $contHTML;
		}

		function enqueueLazyScripts() {
			wp_enqueue_script('img-loading',plugins_url('js/img-loading.js',__FILE__));
		}

		private function replaceCallback($matches){
			$tempImage = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
			if (preg_match('/ data-lazy *= *"false" */', $matches[0])){
				return '<img' . $matches[1] . 'src="' . $matches[2] . '"' . $matches[3] . '>';
			} else {
				return '<img' . $matches[1] . 'src="' . $tempImage . '" data-src="' . $matches[2] . '"' . $matches[3] . '><noscript><img' . $matches[1] . 'src="' . $matches[2] . '"' . $matches[3] . '></noscript>';
			}
		}

		public static function onUninstall(){
			delete_option('el_color');
			delete_option('la_opacidad');
			delete_option('la_transparencia');
			delete_option('el_titulo');
			delete_option('el_dibujo');
			delete_option('carga_en_vista');
			delete_option('wl-admin-notice');
		}

		public function enqueueColorPicker(){
			wp_enqueue_script('color-handler',plugins_url('js/color-handler.js', __FILE__ ),array('wp-color-picker'),false,true);
			wp_enqueue_style('wp-color-picker');
		}

		public function enqueueFeStyles(){
			wp_enqueue_style('wl-style', plugins_url('/css/wl-style.css' , __FILE__ ));
		}

		public function checkColor($value){
			if(preg_match('/^#[a-f0-9]{6}$/i',$value)){
				return true;
			}
			return false;
		}

		public function addSubPage(){
			add_submenu_page('options-general.php',__('While the page is loading','while-loading'),__('While loading','while-loading'),'manage_options','loading-settings',array($this,'adminForm'));
		}

		public function addI18n(){
			load_plugin_textdomain('while-loading',false,basename(dirname(__FILE__)).'/i18n');
		}

		public function addLinkSettings($links){
			$settings_link = '<a href="options-general.php?page=loading-settings">'.__('Settings','while-loading').'</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		public function adminForm(){
			if(isset($_POST['enviar'])){
				$color = $_POST['colorFondo'];
				$opacidad = $_POST['opacidad'];
				$titulo = $_POST['titulo'];
				$dibujo = $_POST['imagenDeCarga'];
				$cargaEnVista = isset($_POST['carga_en_vista']) ? $_POST['carga_en_vista'] : '';

				$color = $this->checkColor($color) ? $color : '#000000';
				$opacidad = (intval($opacidad) > 1 || intval($opacidad) < 0) ? 1 : $opacidad;

				update_option('el_color',$color);
				update_option('la_opacidad',$opacidad);
				update_option('el_titulo',$titulo);
				update_option('el_dibujo',$dibujo);
				update_option('carga_en_vista',$cargaEnVista);

				echo '<div class="updated settings-error"><p><strong>'.__('Options have been saved.','while-loading').'</strong></p></div>';
			}
			$color = get_option('el_color','#000000');
			$opacidad = get_option('la_opacidad','0.95');
			$titulo = get_option('el_titulo',__('Page loading','while-loading'));
			$dibujo = get_option('el_dibujo','2');
			$cargaEnVista = get_option('carga_en_vista',false);

		?>
			<div class="wrap">
				<h2><?php _e( 'While Loading Options', 'while-loading'); ?></h2>
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<table class="form-table">
						<tr>
							<th scope="row"><label for="select_background"><?php _e('Background Color','while-loading'); ?></label></th>
							<td><input class="wl-color-picker" type="text" id="select_background" name="colorFondo" value="<?php echo $color; ?>" />
							<p class="description"><?php _e('Hexadecimal color.','while-loading');?></p></td>
						</tr>
						<tr>
							<th scope="row"><label for="select_transparency"><?php _e('Opacity','while-loading'); ?></label></th>
							<td><input type="range" min="0" max="1" step="0.05" id="select_transparency" name="opacidad" value="<?php echo $opacidad; ?>" />
							<p class="description"><?php _e('The opacity for background is from 0 to 1.','while-loading');?></p></td>
						</tr>
						<tr>
							<th scope="row"><label for="select_titulo"><?php _e('Title','while-loading'); ?></label></th>
							<td><input class="regular-text" type="text" id="select_titulo" name="titulo" value="<?php echo $titulo; ?>" placeholder="<?php _e('We are downloading.','while-loading'); ?>" />
							<p class="description"><?php _e('A little title.','while-loading');?></p></td>
						</tr>
						<tr>
							<th scope="row"><label for="select_picture"><?php _e('Graphic', 'while-loading'); ?></label></th>
							<td>
								<?php echo '<select name="imagenDeCarga">';
								$index = -1;
								foreach($this->listImages(WL_PLUGIN_DIR_IMG) as $image){
									echo '<option value="'.++$index.'"'. ($index == get_option('el_dibujo') ? ' selected' : '') .'>'.$image['name'].'</option>';
								}
								echo '</select>';
								?>
							</td>
						</tr>
						<tr class="option-site-visibility">
							<th scope="row"><?php _e('Lazy load', 'while-loading'); ?></th>
							<td><fieldset><legend class="screen-reader-text"><span><?php _e('Activate lazy load', 'while-loading'); ?> </span></legend>
								<label for="carga_en_vista"><input name="carga_en_vista" type="checkbox" id="carga_en_vista" value="1" <?php checked(get_option('carga_en_vista'),1); ?> />
								<?php _e('Show image only when it is visible in viewport.','while-loading'); ?></label>
								<p class="description"><?php _e('Images outside of viewport will not be loaded until user scrolls to them.','while-loading');?></p>
							</fieldset></td>
						</tr>
					</table>
					<p class="submit"><input class="button button-primary" type="submit" name="enviar" value="<?php _e('Save Settings','while-loading'); ?>" /></p>
				</form>
				<div id="postimagediv">
					<h3><?php _e('Note:','while-loading'); ?></h3>
					<p><?php printf(__('Please, write this code %s just after body tag of your WordPress theme.','while-loading'), '<code>&lt;?php wl_gear(); ?&gt;</code>'); ?></p>
					<p style="color:red;"><?php printf(__('Most times the %1$s tag is in %2$s of your theme and you can modify it from the %3$s.','while-loading'),'<code>&lt;body&gt;</code>','<b>header.php</b>','<a href="'.get_site_url().'/wp-admin' .(is_multisite() ? '/network' : '') .'/theme-editor.php?file=header.php&theme='.get_template('').'">'.__('theme editor','while-loading').'</a>'); ?></p>
					<div class="inside">
						<img src="<?php echo plugins_url('/ejemplo.jpg',__FILE__); ?>" />
					</div>
					<small>You can show your appreciation.</small>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="JVGHP29EWE85G">
						<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but11.gif" border="0" name="submit" alt="Donate for Garmur">
						<img alt="Donate for this plugin" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
			</div>
		<?php
		}

		private function listImages($f){
			$folder = opendir($f);
			$files = array();
			while($current = readdir($folder)){
				if($current != '.' && $current != '..' && $current != '.htaccess')
					/* if(is_dir($f.$current))
						$this->listImages($f.$current.'/'); */
					if(preg_match('/^.*\.(gif|jpg|png)$/', $f.$current))
						$files[] = array('name'=>$current,'type'=>'image');
					if(preg_match('/^.*\.(svg)$/', $f.$current))
						$files[] = array('name'=>$current,'type'=>'svg');
			}
			return $files;
		}

		public function writeOnHeadTheme(){
		?>
			<script>
			function desvanecer(){
				var dibujo = document.getElementById('display');
				if(dibujo != null){
					dibujo.style.opacity -= 0.03;
					if(dibujo.style.opacity < 0.0)
						dibujo.parentNode.removeChild(dibujo);
					else
						setTimeout(function(){desvanecer()},30);
				}
			}
			setTimeout(function(){if(document.getElementById('display') != null)desvanecer();},9999);
			window.addEventListener('load',desvanecer);
			</script>
		<?php
		}

		public function writeOnBodyTheme(){
			$style = 'background-color:'. get_option('el_color') . ';opacity:'. get_option('la_opacidad').';';
			$ggm = -1;?>
			<div id="display" style="<?php echo $style; ?>">
				<h1 id="loading"><?php echo get_option('el_titulo'); ?></h1>
			<?php
			foreach($catalog = $this->listImages(WL_PLUGIN_DIR_IMG) as $image){
				if(get_option('el_dibujo') != ++$ggm){
					if(count($catalog)-1 < get_option('el_dibujo')){?>
						<svg id="engranaje" xmlns="http://www.w3.org/2000/svg" version="1.1">
						<g>
						<rect height="20" width="240" y="110" x="0" fill="grey" id="svg3"/>
						<rect height="240" width="20" y="0" x="110" fill="grey" id="svg4"/>
						<rect height="20" width="240" y="110" x="0" transform="rotate(22.5 120 120)" fill="grey" id="svg5"/>
						<rect height="20" width="240" y="110" x="0" transform="rotate(45 120 120)" fill="grey" id="svg6"/>
						<rect height="20" width="240" y="110" x="0" transform="rotate(67.5 120 120)" fill="grey" id="svg7"/>
						<rect height="20" width="240" y="110" x="0" transform="rotate(113 120 120)" fill="grey" id="svg8"/>
						<rect height="20" width="240" y="110" x="0" transform="rotate(135.5 120 120)" fill="grey" id="svg9"/>
						<rect height="20" width="240" y="110" x="0" transform="rotate(160 120 120)" fill="grey" id="svg10"/>
						<circle r="90" cy="120" cx="120" fill="#7f7f7f" id="cover"/>
						<circle r="70" cy="120" cx="120" fill="white" id="cover2"/>
						<circle r="60" cy="120" cx="120" fill="url(#garmur)" id="svg2"/>
						</g>
						<defs>
						<radialGradient spreadMethod="pad" id="garmur">
						<stop offset="0.6" stop-color="#7f7f7f"/>
						<stop offset="1" stop-opacity="0.9" stop-color="white"/>
						</radialGradient>
						</defs>
						</svg>
					<?php
					}
					continue;
				}
				switch($image['type']){
					case 'image':?>
						<img src="<?php echo WL_PLUGIN_URL_IMG.'/'.$image['name']; ?>" alt="loading icon" />
					<?php
						break;
					case 'svg':?>
						<object data="<?php echo WL_PLUGIN_URL_IMG.'/'.$image['name']; ?>" type="image/svg+xml"></object>
					<?php
						break;
				}
				break;
			}?>
			</div>
			<?php
		}
	}
}

if(class_exists('WhileLoading')){
	global $WhileLoading;
	$WhileLoading = new WhileLoading();
}

/*Agradezco a los que me dieron el tiempo. - Thank you, GBSF.*/
?>