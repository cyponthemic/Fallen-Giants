<?php
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
	
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
echo '<h2 class=winery-title>';
get_winery();
echo '</h3>';
echo '<h3 class=vineyard-title><i>';
get_vineyard();
echo ' Vineyard</i></h3>';
echo '<h3 class=variety-title>';
get_vintage();
echo ' ';
get_variety();
echo '</h3>';
}
/* add_action('woocommerce_before_single_product_summary','get_vintage'); */

if (class_exists('MultiPostThumbnails')) {

new MultiPostThumbnails(array(
'label' => 'Bottle shot',
'id' => 'bottle-shot',
'post_type' => 'product'
 ) );

 }
 
 
 /* Lightbox */
 
 add_filter('the_content', 'my_addlightboxrel');
function my_addlightboxrel($content) {
       global $post;
       $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
       $replacement = '<a$1href=$2$3.$4$5 rel="lightbox" title="'.$post->post_title.'"$6>';
       $content = preg_replace($pattern, $replacement, $content);
       return $content;
}

if ( ! isset( $content_width ) )
    $content_width = 1000;