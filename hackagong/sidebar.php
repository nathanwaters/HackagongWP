<?php

/* ==================================================

Sidebar Template

================================================== */

?>

<aside id="sidebar" class="five columns">

	<?php if (is_single()) { ?>
	
		<?php dynamic_sidebar('Blog Sidebar'); ?>
	
	<?php } else { ?>

		<?php dynamic_sidebar('Default Sidebar'); ?>

	<?php } ?>

</aside>