<!-- OPEN .detail-info -->
<section class="detail-info three columns alpha">

	<ul>
		<li class="date">
			<p><?php the_time('d M y'); ?></p>
                </li>

                <li class="author">
			<p><?php the_author(); ?></p>
                </li>

                <li class="categories">
        		<p><?php the_category(', ') ?></p> 
                </li>
                <?php if( has_tag() ) { ?>
                <li class="tags">
        		<p><?php the_tags(''); ?></p>
                </li>
                <?php } ?>
                <?php if($external_link!= ""){?>
                <li class="link">
                        <p><a href="<?php echo $external_link; ?>" target="_blank">Link</a></p>
                </li>
                <?php } ?>

                <li class="comments">
                        <p><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></p>
                </li>
   	</ul>
   
<!-- CLOSE .detail-info -->
</section>