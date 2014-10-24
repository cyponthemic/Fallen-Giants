<?php
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
	
	wp_enqueue_style( 'font-museo-slab', get_stylesheet_directory_uri().'/font/MuseoSlab/Webfonts/museoslab_500_macroman/stylesheet.css' );
	
	wp_enqueue_style( 'font-museo-slab-ita', get_stylesheet_directory_uri().'/font/MuseoSlabItalic/Webfonts/museoslab_500italic_macroman/stylesheet.css' );
	
	wp_enqueue_style( 'font-museo-sans',get_stylesheet_directory_uri().'/font/MuseoSans/Webfonts/museosans_500_macroman/stylesheet.css' );
	
	wp_enqueue_style( 'font-museo-sans-ita', get_stylesheet_directory_uri().'/font/MuseoSansItalic/Webfonts/museosans_500italic_macroman/stylesheet.css' );
	
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('parent-style')  );
}

add_editor_style( get_stylesheet_uri());

function remove_sticky_class($classes) {
  $classes = array_diff($classes, array("sticky"));
  return $classes;
}
add_filter('post_class','remove_sticky_class');


/* Showing attributes */

function get_vintage(){
$terms = get_the_terms( $product->id, 'pa_vintage');
foreach ( $terms as $term ) { echo $term->name; }
}

function get_vineyard(){
$terms = get_the_terms( $product->id, 'pa_vineyard');
foreach ( $terms as $term ) { echo $term->name; }
}

function get_variety(){
$terms = get_the_terms( $product->id, 'pa_variety');
foreach ( $terms as $term ) { echo $term->name; }
}

function get_winery(){
$terms = get_the_terms( $product->id, 'pa_winery');
foreach ( $terms as $term ) { echo $term->name; }
}

function get_wine_title(){
echo '<h3 class=winery-title>';
get_winery();
echo '</h3>';
echo '<h3 class=vineyard-title><i>';
get_vineyard();
echo '</i></h3>';
echo '<h3 class=variety-title>';
get_variety();
echo '</h3>';
echo '<h3 class=vintage-title>';
get_vintage();
echo '</h3>';
}
/* add_action('woocommerce_before_single_product_summary','get_vintage'); */