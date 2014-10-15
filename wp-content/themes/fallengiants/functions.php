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




