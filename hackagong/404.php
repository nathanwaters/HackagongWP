<?php get_header(); ?>

	<!-- OPEN .section -->
	<section class="section clearfix" id="not-found">

		<div class="section-heading clearfix">
			<div class="container">
				<div class="section-heading-content sixteen columns">
		  			<h1>404</h1>
		  		</div>
		  	</div>
		</div>

		<div class="section-content container">

			<div class="page-text sixteen columns">
			  <?php echo do_shortcode(stripslashes($data['ab_404_message_str'])); ?>
			</div>

		</div>
	 
	<!-- CLOSE .section -->
	</section>

<!-- WordPress Hook -->
<?php get_footer(); ?>