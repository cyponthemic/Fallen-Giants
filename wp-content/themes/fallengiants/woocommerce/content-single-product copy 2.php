<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="row">
<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="row">
	<div class="small-12 medium-6 columns single-product-image">
	<?php
		/**
		 * woocommerce_before_single_product_summary hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 
		 Galleries
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		

  

	?>
	</div>
	<div class="small-12 medium-6 columns">
	<!-- <div class="summary entry-summary "> -->
		<div class=" single-product-title">
		<h1 style="text-transform:uppercase;"><?php get_winery(); ?></h1>
		<h2><i><?php get_vineyard(); ?></i> <?php get_variety(); ?></h2>
		<h3><?php get_vintage(); ?></h3>
		</div>
		<?php>woocommerce_template_single_excerpt();?>
		<div class=" single-product-price">
		
		<?php>woocommerce_simple_add_to_cart();?>
		<?php>woocommerce_template_single_price();?>
		</div>
		<div class="row">
		<?php woocommerce_upsell_display();?>
		</div>
	<!-- </div> -->
	</div><!-- .summary -->
	</div>
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />
</div>
</div><!-- #product-<?php the_ID(); ?> -->


<?php do_action( 'woocommerce_after_single_product' ); ?>
