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
			wp_nav_menu( array( 'theme_location' => 'footer-menu', 'menu_class' => 'list', 'container' => 'nav', 'container_class' => 'small-4 medium-4 columns' ) );
			echo '</div>';
		} ?>
	</footer>
</div>
<?php wp_footer(); ?>
<!-- Scripts -->   

   <script>
  jQuery(document).resize(function ($) {
  	if ( $("#bghomepage").height!= "90vh") {
    $("#bghomepage").height($(window).height()*0.9);
    }
	});
</script>
   
  <script>
  jQuery(document).resize(function ($) {
  	
    $(".content-product-image").height($(".content-product-text").height()*1);
    
	});
	
	
	jQuery(function($) {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});
	
	
	
</script>

	<!-- <script src=" <?php echo get_stylesheet_directory_uri();?>/js/parallax.js"></script> -->
	<!-- <script>var scene = document.getElementById('scene');	var parallax = new Parallax(scene);	</script>  -->

   <!--  <script src=" <?php echo get_stylesheet_directory_uri();?>/js/vendor/modernizr.js"></script> -->
   <!--  <script src=" <?php echo get_stylesheet_directory_uri();?>/js/vendor/jquery.js"></script> -->
   <!-- <script src=" <?php echo get_stylesheet_directory_uri();?>/js/foundation.min.js"></script> -->
    <!-- <script>    $(document).foundation();     </script> -->
</div>
</body>
 
</html>