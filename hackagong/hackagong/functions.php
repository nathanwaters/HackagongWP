<?php

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__file__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'sdk');

define('HG_DIR', get_stylesheet_directory());

require_once ('facebook.php');
require_once ('cache.class.php');

function hg_user_profilepic($userid=false,$class="") {
  $_template = '<img alt="" src="%s" class="avatar social_user_icon %s">';
  if(!$userid) {
    $userid = get_current_user_id();
  }
  if(!$userid) return null;
  $l = get_user_meta($userid, 'hg_profile_url', true);
  if($l) {
    return sprintf($_template, $l, $class);
  }
  $fb = get_user_meta($userid, 'hg_facebook_id', true);
  if($fb) {
    if(!is_file(get_stylesheet_directory().'/sdk/cache/'.$fb.'.jpg')) {
      file_put_contents(get_stylesheet_directory().'/sdk/cache/'.$fb.'.jpg', file_get_contents(get_bloginfo('template_directory').'/sdk/timthumb.php?src=http://graph.facebook.com/'.$fb.'/picture?type=large&w=75&h=75'));
    }
    return sprintf($_template, get_stylesheet_directory_uri().'/sdk/cache/'.$fb.'.jpg', $class);
  }
  return get_avatar($user_id);
}

function hg_facebook_user_tiles() {
  $c = new Cache();
  $c->setCachePath(HG_DIR .'/sdk/cache/');
  $c->seed = NONCE_SALT;
  $c->eraseExpired();

  $_template = '<div id="faces-wrap">
		<div class="faces">%s</div>
	</div>';
	
	$_template_item = sprintf('<img src="%s/sdk/cache/%%s.jpg">', get_bloginfo('template_directory'));
	
	$output = "";
	
  if ($c->isCached('attending')) {
    $attending = $c->retrieve('attending');
  } else {
  
    $facebook = new Facebook(array(
      'appId'  => FACEBOOK_EVENT_APP_ID,
      'secret' => FACEBOOK_EVENT_APP_KEY,
    ));
    
    $attending = array();
    $access_token = $facebook->getAccessToken();
    $params = array('access_token' => $access_token);
      
    $objs = $facebook->api(FACEBOOK_EVENT_APP_ID.'/events', 'GET', $params);
    
    foreach($objs['data'] as $data) {
      
        $objs2 = $facebook->api($data['id'].'/invited', 'GET', $params);
      
        foreach ($objs2['data'] as $attendee) {
          if ($attendee['rsvp_status'] == 'attending' || $attendee['rsvp_status'] == 'unsure') {
            if(!is_file(get_stylesheet_directory().'/sdk/cache/'.$attendee['id'].'.jpg')) {
              file_put_contents(get_stylesheet_directory().'/sdk/cache/'.$attendee['id'].'.jpg', file_get_contents(get_bloginfo('template_directory').'/sdk/timthumb.php?src=http://graph.facebook.com/'.$attendee['id'].'/picture?type=large&w=75&h=75'));
            }
            $attending[] = $attendee['id'];
          }
        }
      
    }
  
    $c->store('attending', shuffle($attending), 60*60*24*7);
  }
  
  if(!is_array($attending) || sizeof($attending) < 10) return "";
  
  foreach ($attending as $id) {
    $output .= sprintf($_template_item, $id);
  }
  
  return sprintf($_template, $output);
}
