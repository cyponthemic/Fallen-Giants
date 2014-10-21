<?php
/**

Template Name: Find us
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
					<div class="small-4 columns googlemap">
					<h3> HALLS GAP ESTATE </h3>

							<p> 4113 Ararat - Halls Gap Road, Halls Gap 3381 </p>

							
							<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12722.008223280305!2d142.558765!3d-37.14076!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ace053bd69a6bed%3A0xca9a24605566a06e!2s4113+Ararat-Halls+Gap+Rd%2C+Halls+Gap+VIC+3381!5e0!3m2!1sen!2sau!4v1413350469518" width="100%" height="200" frameborder="0" style="border:3"></iframe>
							
							
							
					</div>
					<div class="entry-content small-8 medium-4 columns">
						<?php the_content(); ?>
					</div>
					<div class="small-4 columns mailinglist">
					<h3> JOIN OUR MAILING LIST </h3>
					<form>
					  <div class="row">
					    <div class="small-8">
					      <div class="row">
					        <div class="small-3 columns">
					          <label for="right-label" class="right inline">Name</label>
					        </div>
					        <div class="small-9 columns">
					          <input type="text" id="right-label" placeholder="Inline Text Input">
					        </div>
					      </div>
					      <div class="row">
					        <div class="small-3 columns">
					          <label for="right-label" class="right inline">Email</label>
					        </div>
					        <div class="small-9 columns">
					          <input type="text" id="right-label" placeholder="Inline Text Input">
					        </div>
					        
					      </div>
					      
					    </div>
					    <a href="#" class="button small-12">SUBMIT</a>
					  </div>
					</form>
					</div>
					</div>
					</div>
					
					
					
					</div>
					

				</article>

		</div>
	</div>

</div>

