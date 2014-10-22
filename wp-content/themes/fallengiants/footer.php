<div id="footer">
	<div class="row">
	<div class="small-centered large-centered large-4 small-6 footer-logo columns">
	<?php echo  '<img src="';
      echo get_stylesheet_directory_uri();
      echo '/bg/logo.png">';
    ?>
	</div>
	</div>
	<footer class="row" role="contentinfo">
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Sidebar')) : ?>
		<?php endif; ?>
		<?php if ( has_nav_menu( 'footer-menu' ) ) {
			echo '<div class="row">';
			wp_nav_menu( array( 'theme_location' => 'footer-menu', 'menu_class' => 'inline-list', 'container' => 'nav', 'container_class' => 'small-12 medium-12 columns' ) );
			echo '</div>';
		} ?>
	</footer>
</div>
<?php wp_footer(); ?>
<!-- Scripts -->   

   
   
   <!--

	<script src=" <?php echo get_stylesheet_directory_uri();?>/js/parallax.js"></script>
	<script>var scene = document.getElementById('scene');	var parallax = new Parallax(scene);	</script> 
-->   
   <!--  <script src=" <?php echo get_stylesheet_directory_uri();?>/js/vendor/modernizr.js"></script> -->
   <!--  <script src=" <?php echo get_stylesheet_directory_uri();?>/js/vendor/jquery.js"></script> -->
   <!-- <script src=" <?php echo get_stylesheet_directory_uri();?>/js/foundation.min.js"></script> -->
    <!-- <script>    $(document).foundation();     </script> -->
</div>
</body>
 
</html>