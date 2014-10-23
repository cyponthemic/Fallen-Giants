<?php
/**

Template Name: One page layout

 */

get_header(); ?>
<div id="bghomepage" class="bghomepage">



<?php 	
 	echo  '<img class="mainlogo" src="';
      echo get_stylesheet_directory_uri();
      echo '/bg/logoblack.png">';
?>
</div>

<!-- +++++++++++++++++++++++++
		
		OUR-STORY 
			
		  +++++++++++++++++++++++++ -->

<div class="bgourstory">	
</div>
<div class="row">
	<div id="primary" class="site-content small-12 medium-12 columns">
		
		
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
<div class="bgourstoryend">	</div>

<!-- +++++++++++++++++++++++++
		
		OUR-WINES 
			
		  +++++++++++++++++++++++++ -->
		  
<div class="bgourwines">	</div>		  
<div class="row winescontent">
	<div id="primary" class="site-content small-12 medium-12 columns">

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
		<!-- OUR-WINES -->
		<div id="content" role="main">
		
		<div class="row">
	<?php
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 12
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				woocommerce_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	?>
</div><!--/.products-->
	</div>


</div>



</div>
<div class="bgourwinesend">	


</div>

<!-- +++++++++++++++++++++++++
		
		CELLAR DOOR 
			
		  +++++++++++++++++++++++++ -->
		  
<div class="bgcellardoor">	
</div>
<div class="row">
	<div id="primary" class="site-content small-12 medium-12 columns">
		
		
		<div id="content" role="main">
		
		<?php 
		global $post;
                $args = array(
                    'pagename' => 'cellar-door',
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
		  
		  
		  
<!-- +++++++++++++++++++++++++
		
		FIND US
			
		  +++++++++++++++++++++++++ -->

<div class="bgfindus">	
</div>
<div class="row">
	<div id="primary" class="site-content small-12 medium-12 columns">
		
		
		<div id="content" role="main">
		
		<?php 
		global $post;
                $args = array(
                    'pagename' => 'find-us',
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
<div class="bgfindusend">	</div>

<?php get_footer(); ?>