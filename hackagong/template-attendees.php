<?php
/*
Template Name: attendees
*/


if (!is_user_logged_in()) { 
  auth_redirect(); 
}
nocache_headers();

get_header(); 

global $data;
$older_entries_text = $data['ab_older_entries_btn_str'];
$newer_entries_text = $data['ab_newer_entries_btn_str'];

?>

<!-- OPEN #blog -->
<section class="section clearfix" id="blog">

	<div class="section-heading blog-heading clearfix">
		<div class="container">
			<div class="section-heading-content sixteen columns">
				<h1>Attendees</h1>
			</div>
		</div>
	</div>

	<div class="section-content container">

		<?php
  		$blogusers = get_users('orderby=nicename&role=subscriber');

		?>

    <ul class="blog-items">
      <?php foreach ($blogusers as $user) { 
          $role_des = get_user_meta($user->ID, 'hg_profile_des', true);
          $role_dev = get_user_meta($user->ID, 'hg_profile_dev', true);
          $role_mar = get_user_meta($user->ID, 'hg_profile_mar', true);
          $role_biz = get_user_meta($user->ID, 'hg_profile_biz', true);
          $matchmaking = get_user_meta($user->ID, 'hg_profile_match', true);
      ?>
      <li class="blog-item sixteen columns">
        <figure class="six columns alpha">
          <?php echo hg_user_profilepic($user->ID)?>
  		  </figure>

        <div class="item-details ten columns omega">
          <div class="item-heading clearfix">
            <h2><?php echo $user->user_displayname ?></h2>
          </div>
          <div class="blog-excerpt">
            <p>Email: <?php echo $user->user_email ?></p>
            <p>Homepage: <?php echo $user->user_url ?></p>
            <p>Registered: <?php echo $user->user_registered ?></p>
            <?php if($role_des) echo '<p> Designer </p>'; ?>
            <?php if($role_dev) echo '<p> Developer </p>'; ?>
            <?php if($role_mar) echo '<p> Marketing </p>'; ?>
            <?php if($role_biz) echo '<p> Business </p>'; ?>
            <?php if($matchmaking) echo '<p class="matchmaking"> Requires Team </p>'; ?>
          </div>
          <div class="meta">
                <span class="date">21/04/1980</span>
                <span>//</span>
                <span class="comments">Message attendee</span>
          </div>
        </div>
      </li>
      <?php } ?>
    </ul>
	</div>
</div>

<!-- CLOSE #blog -->
</section>

<!-- WordPress Hook -->
<?php get_footer(); ?>