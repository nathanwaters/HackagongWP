<?php
/*
Template Name: API
Author: nik cubrilovic
*/

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__file__) . DIRECTORY_SEPARATOR . 'sdk');

require 'facebook/facebook.php';

class Api_Methods {
  
  function get_login_fb($user, $pass) {
    return array('fb_login' => true, 'user'=>$user, 'pass'=>$pass);
  }
}

$api_method = $_GET['m'];
$req_method = strtolower($_SERVER['REQUEST_METHOD']);
$params = array();

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
  header('Content-Type: text/javascript; charset=UTF-8');
  echo json_encode($ret);
  die();
} else {
  not_found();
}

function not_found() {
  header('HTTP/1.0 404 Not Found', true, 404);
  print '<html><h1>404 - File Not Found</h1>';
  die();
}
