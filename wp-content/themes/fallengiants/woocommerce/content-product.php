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
<div class="row winerow" data-equalizer>

	<div class="medium-5 medium-offset-1 content-product-image columns" data-equalizer-watch>
	<a href="<?php the_permalink(); ?>">

<?php /* echo get_the_post_thumbnail($post->ID,'shop_single',);  */
			if (class_exists('MultiPostThumbnails')) : 
		MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'bottle-shot');
		endif;
		?>

	</a>
	</div>
	<div class="medium-4 content-product-text left columns" data-equalizer-watch>
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
	
		<a href="<?php the_permalink(); ?>" class="button large-12 small-12">Order now</a>
	<!-- <?php do_action( 'woocommerce_after_shop_loop_item' ); ?> -->
	</div>
</div>