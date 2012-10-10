<?php get_header(); ?>

<?php if (have_posts()) : ?>

	<?php
		global $data;
		$blog_page = $data['ab_blog_page'];
		$blog_page_title = get_page_by_path( $blog_page );
		$blog_page_id = $blog_page_title->ID;
	?>
		
	<div class="section clearfix">

		<div class="section-heading blog-heading clearfix">
			<div class="container">
				<div class="section-heading-content sixteen columns">
					<h1><?php echo do_shortcode(stripslashes($data['ab_blog_title_str'])); ?></h1>
					<div class="sub-heading">
						<span class="section-desc"><?php echo do_shortcode(stripslashes($data['ab_blog_tag_str'])); ?></span>
						<div class="controls-wrap">
							<span class="view-all">
								<a href="<?php echo get_permalink( $blog_page_id ); ?>"><?php echo do_shortcode(stripslashes($data['ab_blog_back_btn_str'])); ?></a>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php while (have_posts()) : the_post(); ?>

		<div class="container">

			<!-- OPEN article -->
			<article <?php post_class('clearfix eleven columns'); ?> id="<?php the_ID(); ?>">
		
				<?php 
					// The following determines what the post format is and shows the correct file accordingly
					$format = get_post_format();
					get_template_part( 'includes/post-type/'.$format );
					
					if($format == '')
					get_template_part( 'includes/post-type/standard' );
				?>
			
				<!-- OPEN .navigation .single-navigation .clearfix -->
				<div class="navigation single-navigation clearfix">
					
					<div class="nav-previous"><?php next_post_link('%link','<span>&larr;</span> %title', FALSE); ?></div>
					<div class="nav-next"><?php previous_post_link('%link','%title <span>&rarr;</span>', FALSE); ?></div> 

				<!-- END .navigation .single-navigation .clearfix -->
				</div>
					
				<div id="comment-area">
					<?php comments_template('', true); ?>
				</div>
				
			
			<!-- CLOSE article -->
			</article>

			<?php get_sidebar(); ?>

		</div>

		<?php endwhile; ?>

		<?php rewind_posts(); ?>

	</div>

	<!-- OPEN #blog -->
	<div class="section recent-blog clearfix" id="blog">

		<div class="section-heading blog-heading clearfix">
			<div class="container">
				<div class="section-heading-content sixteen columns">
					<h1><?php echo do_shortcode(stripslashes($data['ab_recent_posts_title_str'])); ?></h1>
					<div class="sub-heading">
						<span class="section-desc"></span>
					</div>
				</div>
			</div>
		</div>

		<?php 
			$number_more_blog_items = $data['ab_more_blog_items'];
		?>

		<?php
		  $type = 'post';
		  $per_page = $number_more_blog_items;
		  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		  $args=array(
			'post_type' => $type,
			'post_status' => 'publish',
			'post__not_in' => array($post->ID),
			'paged' => $paged,
			'posts_per_page' => $per_page,
			'ignore_sticky_posts'=> 1
		  );
		  $wp_query = NULL;
		  $wp_query = new WP_Query();
		  $wp_query->query($args);
		?>

		<?php if(have_posts()) : ?>

		<div class="container">

			<!-- OPEN .blog-items -->
			<ul class="blog-items">

			<?php while (have_posts()) : the_post();
				// The following determines what the post format is and shows the correct file accordingly
				$format = get_post_format();
				get_template_part( 'includes/post-type/'.$format );

				if($format == '')
				get_template_part( 'includes/post-type/standard' );
			endwhile; ?>
					
			<!-- CLOSE . blog-items .one-column -->
			</ul>

			<?php rewind_posts(); ?>

		</div>

		<?php endif; ?>

	<!-- CLOSE #blog -->
	</div>

<?php endif; ?>

<!-- WordPress Hook -->
<?php get_footer(); ?>