<?php

if(!function_exists('curl_init')) {
  not_found();
}

require 'facebook/facebook.php';

class Api_Methods {
  
  function get_login_fb($user, $pass) {
    return array('fb_login' => true, 'user'=>$user, 'pass'=>$pass);
  }
  
  function get_login_tw() {
    
  }
  
  function get_login_gg() {
    
  }
  
  function get_fb($code) {
    if(!$code) not_found();
    $url = "https://graph.facebook.com/oauth/access_token?" .
      'client_id=' . FACEBOOK_APP_ID . 
      '&redirect_uri=http://' . $_SERVER['HTTP_HOST'] . '/api/fb' .
      '&client_secret=' .  FACEBOOK_APP_KEY .
      '&code=' . urlencode($code);
    // var_dump($_SERVER)
    // print $url;
    $ret = http_get($url);
    if(isset($ret['error']) || !isset($ret['access_token'])) {
      server_error($ret['error']['message']);
    } 
    $at = $ret['access_token'];
    $sig = _gen_sig($at);
    
    $url = "https://graph.facebook.com/me?access_token=" . $at;
    $dat = http_get($url);

    if(!isset($dat['id'])) {
      return server_error('invalid record');
    }

    $user_id = email_exists($dat['email']);

    if(!is_file(get_stylesheet_directory().'/sdk/cache/'.$dat['id'].'.jpg')) {
      file_put_contents(get_stylesheet_directory().'/sdk/cache/'.$dat['id'].'.jpg', file_get_contents(get_bloginfo('template_directory').'/sdk/timthumb.php?src=http://graph.facebook.com/'.$dat['id'].'/picture?type=large&w=75&h=75'));
    }

    if($user_id) {
      // Existing user.
      $user_data  = get_userdata( $user_id );
      $user_login = $user_data->user_login;
      
      // @TODO do a check against user meta to make sure its the same user
      
    } else {
      // New user.
      if(!isset($dat['username'])) {
        $dat['username'] = $dat['first_name'].'_'.$dat['last_name'];
      }
      
      $userdata = array(
        'user_login' => $dat['username'],
        'user_email' => $dat['email'],
        'first_name' => $dat['first_name'],
        'last_name' => $dat['last_name'],
        'user_url' => $dat['link'],
        'user_pass' => wp_generate_password() 
        );
      $user_id = wp_insert_user( $userdata );
      
      if(is_wp_error($user)) {
        return server_error('Something went wrong with creating your user.');
      }
    
      // switch off the wordpress bar, which is on by default
      update_user_meta($user_id, 'show_admin_bar_front', false);

      if(is_file(get_stylesheet_directory().'/sdk/cache/'.$dat['id'].'.jpg')) {
        update_user_meta($user_id, 'hg_profile_url', get_stylesheet_directory_uri().'/sdk/cache/'.$dat['id'].'.jpg');
      }
    }
    
    // log the user in..
    wp_set_auth_cookie($user_id, true);
        
    // store login details
    update_user_meta($user_id, 'hg_facebook', true);
    update_user_meta($user_id, 'hg_facebook_id', $dat['id']);
    update_user_meta($user_id, 'hg_facebook_acess_token', $at);
    update_user_meta($user_id, 'hg_facebook_sig', $sig);
    
    $json_user_info = json_encode(array(
      'username'=>$dat['username'],
      'email'=>$dat['email'],
      'access_token'=>$at,
      'sig'=>$sig
      ));
    
    require_once('templates/oauth_popup_close.php');
  }
}

$api_method = '';
$req_uri = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$req_method = strtolower($_SERVER['REQUEST_METHOD']);
// print_r($_SERVER);
$params = array();

if(isset($_GET['m'])) {
  $api_method = $_GET['m'];
} elseif( strpos($req_uri, '/') > 0) {
  $m = explode('/', $req_uri, 2);
  $api_method = $m[1];  
} else {
  print $req_uri;
  not_found();
}

if($req_method == 'get') {
  unset($_GET['m']);
  foreach($_GET as $k => $v) {
    $params[$k] = $v;
  }
} else {
  foreach($_POST as $k => $v) {
    $params[$k] = $v;
  }
}


if(method_exists('Api_Methods',  $req_method . '_' .$api_method)) {
  $ret = call_user_func_array(array('Api_Methods', $req_method . '_' .$api_method), $params);
  if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: text/javascript; charset=UTF-8');
    print json_encode($ret);
  } else {
    print $ret;
  }
  die();
} else {
  not_found();
}

// Functions

function not_found() {
  header('HTTP/1.0 404 Not Found', true, 404);
  print '<html><h1>404 - File Not Found</h1>';
  die();
}

function server_error($m) {
  header('HTTP/1.0 500 Server Error', true, 500);
  print '<html><h1>500 - Server Error</h1>';
  if($m) print "<p>$m</p>";
  die();
}

function is_json() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function get_user_by_meta($meta_key, $meta_value) {
  global $wpdb;

  $sql = "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '%s' AND meta_value = '%s'";
  return $wpdb->get_var( $wpdb->prepare( $sql, $meta_key, $meta_value ) );
}

function _gen_sig($data) {
  return hash( 'SHA256', AUTH_KEY . $data );
}

function _ver_sig($data, $signature, $redirect_to) {
  $generated_signature = _gen_sig( $data );

  if( $generated_signature != $signature ) {
    wp_safe_redirect( $redirect_to );
    exit();
  }
}

function http_get($url) {
  $curl = curl_init();
  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt( $curl, CURLOPT_URL, $url );
  curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
  $html = curl_exec( $curl );
  $ct = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
  if(substr($ct, 0, 15)=="text/javascript") {
    $ret = json_decode($html, true);
  } else {
    parse_str($html, $ret);
  }
  curl_close( $curl );
  return $ret;
}