<?php
/*
Template Name: User profile
*/



$wpdb->hide_errors(); 
if (!is_user_logged_in()) { 
  auth_redirect(); 
}
nocache_headers();

global $userdata; 
get_currentuserinfo(); // grabs the user info and puts into vars

if(!empty($_POST['action'])) {
  require_once(ABSPATH . 'wp-admin/includes/user.php');
  require_once(ABSPATH . WPINC . '/registration.php');
  check_admin_referer('update-profile_' . $user_ID);

  update_user_meta($userdata->ID, 'hg_profile_des', isset($_POST['role_des']));
  update_user_meta($userdata->ID, 'hg_profile_dev', isset($_POST['role_dev']));
  update_user_meta($userdata->ID, 'hg_profile_mar', isset($_POST['role_mar']));
  update_user_meta($userdata->ID, 'hg_profile_biz', isset($_POST['role_biz']));
  update_user_meta($userdata->ID, 'hg_profile_match', isset($_POST['matchmaking']));

  $errors = edit_user($user_ID);
  if ( is_wp_error( $errors ) ) {
    foreach( $errors->get_error_messages() as $message )
      $errmsg = "$message";
  }

  if($errmsg == '') {
    do_action('personal_options_update');
    $d_url = $_POST['dashboard_url'];
    wp_redirect( get_option("siteurl").'?page_id='.$post->ID.'&updated=true' );
  } else {
    $errmsg = '<div class="box-red">** ' . $errmsg . ' **</div>';
    $errcolor = 'style="background-color:#FFEBE8;border:1px solid #CC0000;"';
  }
}

$role_des = get_user_meta($userdata->ID, 'hg_profile_des', true);
$role_dev = get_user_meta($userdata->ID, 'hg_profile_dev', true);
$role_mar = get_user_meta($userdata->ID, 'hg_profile_mar', true);
$role_biz = get_user_meta($userdata->ID, 'hg_profile_biz', true);
$matchmaking = get_user_meta($userdata->ID, 'hg_profile_match', true);

get_header();
?>
<!-- OPEN #blog -->
<section class="section clearfix" id="blog">

	<div class="section-heading blog-heading clearfix">
		<div class="container">
			<div class="section-heading-content sixteen columns">
				<h1>Profile</h1>
			</div>
		</div>
	</div>

	<div class="section-content container">
    <?php if ( isset($_GET['updated']) ) {
      $d_url = $_GET['d'];?>
      <p class="message"><?php _e('Your profile has been updated.','cp')?></p>
    <?php } ?>
    <?php echo $errmsg; ?>
    <form name="profile" action="" method="post">
    <?php wp_nonce_field('update-profile_' . $user_ID) ?>
    <input type="hidden" name="from" value="profile" />
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="checkuser_id" value="<?php echo $userdata->ID ?>" />
    <input type="hidden" name="dashboard_url" value="<?php echo get_option("dashboard_url"); ?>" />
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $userdata->ID; ?>" />
    <table class="form-table" style="640px;">
    <tr>
    <th><label for="user_login"><?php _e('Username','cp'); ?></label></th>
    <td><input type="text" name="user_login" class="mid2" id="user_login" value="<?php echo $userdata->user_login; ?>" size="35" maxlength="100" disabled /></td>
    </tr>
    <tr>
    <th><label for="first_name"><?php _e('First Name','cp') ?></label></th>
    <td><input type="text" name="first_name" class="mid2" id="first_name" value="<?php echo $userdata->first_name ?>" size="35" maxlength="100" /></td>
    </tr>
    <tr>
    <th><label for="last_name"><?php _e('Last Name','cp') ?></label></th>
    <td><input type="text" name="last_name" class="mid2" id="last_name" value="<?php echo $userdata->last_name ?>" size="35" maxlength="100" /></td>
    </tr>
    <tr>
    <th><label for="email"><?php _e('Email','cp') ?></label></th>
    <td><input type="text" name="email" class="mid2" id="email" value="<?php echo $userdata->user_email ?>" size="35" maxlength="100" /></td>
    </tr>
    <tr>
    <th><label for="url"> Website URL</label></th>
    <td><input type="text" name="url" class="mid2" id="url" value="<?php echo $userdata->user_url ?>" size="35" maxlength="100" /></td>
    </tr>
    <tr>
      <th><label> Roles</label></th>
      <td>
        <p><input type="checkbox" class="mid2" name="role_des" <?php if($role_des) echo 'CHECKED'; ?>> Designer</p>
        <p><input type="checkbox" class="mid2" name="role_dev" <?php if($role_dev) echo 'CHECKED'; ?>> Developer</p>
        <p><input type="checkbox" class="mid2" name="role_mar" <?php if($role_mar) echo 'CHECKED'; ?>> Marketing</p>
        <p><input type="checkbox" class="mid2" name="role_biz" <?php if($role_biz) echo 'CHECKED'; ?>> Business</p>
      </td>
    </tr>
    <tr>
      <th><label for="matchmaking">Need team</label></th>
      <td>
        <p><input type="checkbox" class="mid2" name="matchmaking" <?php if($matchmaking) echo 'CHECKED'; ?>> Check if you need to be matched to a team</p>
      </td>
    </tr>
    <tr>
    <th><label for="description">Bio</label></th>
    <td><textarea name="description" class="mid2" id="description" rows="8" cols="50"><?php echo $userdata->description ?></textarea></td>
    </tr>
    </table>
    <p><input type="submit" value="Update Profile"></p>
    </form>
  </div>


<?php get_footer(); ?>