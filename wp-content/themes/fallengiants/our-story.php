<?php
/**

Template Name: Our story

 */

?>

<div class="row">
	<div id="primary" class="site-content small-12 medium-12 columns">
		<div id="content" role="main">

			

				<article id="<?php echo $post->post_name; ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
					<div class="row">
						<div class="entry-content large-6 medium-8 small-12 medium-centered columns">
							<?php the_content(); ?>
						</div>
					</div>
				</article>

		</div>
	</div>

</div>