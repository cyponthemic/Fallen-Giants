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

?>
<a href="<?php the_permalink(); ?>">
<div class="upsell-label columns"<?php

    if ( $thumbnail_id = get_post_thumbnail_id() ) {
        if ( $image_src = wp_get_attachment_image_src( $thumbnail_id, 'normal-bg' ) )
            printf( ' style="background-image: url(%s);"', $image_src[0] );     
    }

?>>
<h3 class="variety"><?php get_variety();?><br><span><?php get_vintage();?></span></h3>
</div>
</a>