<?php
/**

Template Name: One page layout

 */

get_header(); ?>
<div class="bghomepage">	
</div>
<div class="bgourstory">	
</div>
<div class="row">
	<div id="primary" class="site-content small-12 medium-12 columns">
		
		<!-- OUR-STORY -->
		<div id="content" role="main">
		
		<?php 
		global $post;
                $args = array(
                    'pagename' => 'our-story',
                    'order' => 'ASC'
                );
                $the_query = new WP_Query( $args );         
            ?>
            <?php if ( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?> 

            <?php 
            
            get_template_part($post->post_name); ?>

            <?php endwhile; endif; wp_reset_postdata(); ?>
            
		</div>
	</div>


</div>
<div class="bgourstoryend">	
</div>
<div class="row">
	<div id="primary" class="site-content small-12 medium-12 columns">
		
		<!-- OUR-WINES -->
		<div id="content" role="main">
		
		<?php 
		global $post;
                $args = array(
                    'pagename' => 'our-wines',
                    'order' => 'ASC'
                );
                $the_query = new WP_Query( $args );         
            ?>
            <?php if ( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?> 

            <?php 
            
            get_template_part($post->post_name); ?>

            <?php endwhile; endif; wp_reset_postdata(); ?>
            
		</div>
	</div>


</div>


<?php get_footer(); ?>