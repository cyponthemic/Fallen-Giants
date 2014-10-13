<?php
/**

Template Name: One page layout

 */

get_header(); ?>

<div class="row">
	<div id="primary" class="site-content small-12 medium-12 columns">
		<div class="homepage">	
		
		
		</div>
		<div id="content" role="main">
		
		<?php 
		global $post;
                $args = array(
                    'post_type' => 'page',
                    'order' => 'ASC'
                );
                $the_query = new WP_Query( $args );         
            ?>
            <?php if ( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?> 

            <?php 
            
            get_template_part($post->post_name); ?>

            <?php endwhile; endif; ?>
            
		</div>
	</div>


</div>

<?php get_footer(); ?>