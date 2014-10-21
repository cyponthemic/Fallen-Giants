<?php
/**

Template Name: Cellar Door
 */

?>

<div class="row">
	<div id="primary" class="site-content small-12 medium-8 medium-centered columns">
		<div id="content" role="main">

			

				<article id="<?php echo $post->post_name; ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

				</article>

		</div>
	</div>

</div>

