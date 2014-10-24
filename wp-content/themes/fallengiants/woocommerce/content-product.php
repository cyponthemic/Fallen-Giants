<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

/*
// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );
*/

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

/*
// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
*/
?>
<div class="row winerow">
	<div class="large-1 large-offset-1 columns"></div>
	<!-- <?php do_action( 'woocommerce_before_shop_loop_item' ); ?> -->
	<div class="large-4  large-offset-1 columns">
	<a href="<?php the_permalink(); ?>">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			echo get_the_post_thumbnail(); 
			/* do_action( 'woocommerce_before_shop_loop_item_title' ); */
		?>
	</a>
	</div>
	<div class="large-4 large-offset-1 columns">
		<?php get_wine_title(); ?>
		
		<p>
		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			 woocommerce_template_single_excerpt();
			/* do_action( 'woocommerce_after_shop_loop_item_title' ); */
		?>
		</p>
	
		<a href="<?php the_permalink(); ?>" class="button large-12">ORDER NOW</a>
	<!-- <?php do_action( 'woocommerce_after_shop_loop_item' ); ?> -->
	</div>
	<div class="large-1 columns"></div>
</div>