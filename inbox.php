<?php
/*
Broadbean Inbox Handler
*/


// Load the Wordpress environment.

$root = $_SERVER['DOCUMENT_ROOT'];

if (file_exists($root.'/wp-load.php')) {
  require_once($root.'/wp-load.php');
} else {
  echo 'Uh oh, cannot find wp-load.php';
}

// Look-up Broadbean username/password settings.

$username = get_option('broadbean_username');
$password = get_option('broadbean_password');

// Parse the xml we should have received in the request body.

$xml = trim(file_get_contents('php://input'));
$params = json_decode(json_encode(simplexml_load_string($xml)), 1);

// Guard against incorrect params.

if ($params['username'] !== $username || $params['password'] !== $password) {
  echo 'Incorrect username and/or password';
  die();
}

// Process an 'add' command.

function broadbean_add($params) {
  $job_post_id = wp_insert_post(array(
    'post_type'     => 'job',
    'post_title'    => $params['job_title'],
    'post_category' => array($params['job_category']),
    'post_content'  => $params['job_description']
  ));

  if ($job_post_id != 0) {
    add_post_meta($job_post_id , 'job_type'          , $params['job_type']          , true);
    add_post_meta($job_post_id , 'contact_name'      , $params['contact_name']      , true);
    add_post_meta($job_post_id , 'contact_email'     , $params['contact_email']     , true);
    add_post_meta($job_post_id , 'contact_telephone' , $params['contact_telephone'] , true);
    add_post_meta($job_post_id , 'contact_url'       , $params['contact_url']       , true);
    add_post_meta($job_post_id , 'days_to_advertise' , $params['days_to_advertise'] , true);
    add_post_meta($job_post_id , 'application_email' , $params['application_email'] , true);
    add_post_meta($job_post_id , 'application_url'   , $params['application_url']   , true);
    add_post_meta($job_post_id , 'job_reference'     , $params['job_reference']     , true);
    add_post_meta($job_post_id , 'job_title'         , $params['job_title']         , true);
    add_post_meta($job_post_id , 'job_type'          , $params['job_type']          , true);
    add_post_meta($job_post_id , 'job_duration'      , $params['job_duration']      , true);
    add_post_meta($job_post_id , 'job_startdate'     , $params['job_startdate']     , true);
    add_post_meta($job_post_id , 'job_skills'        , $params['job_skills']        , true);
    add_post_meta($job_post_id , 'job_description'   , $params['job_description']   , true);
    add_post_meta($job_post_id , 'job_location'      , $params['job_location']      , true);
    add_post_meta($job_post_id , 'job_industry'      , $params['job_industry']      , true);
    add_post_meta($job_post_id , 'salary_currency'   , $params['salary_currency']   , true);
    add_post_meta($job_post_id , 'salary_from'       , $params['salary_from']       , true);
    add_post_meta($job_post_id , 'salary_to'         , $params['salary_to']         , true);
    add_post_meta($job_post_id , 'salary_per'        , $params['salary_per']        , true);
    add_post_meta($job_post_id , 'salary_benefits'   , $params['salary_benefits']   , true);
    add_post_meta($job_post_id , 'salary'            , $params['salary']            , true);
  }

  echo 'Added ' . $params['job_reference'];
}

// Process a 'delete' command.

function broadbean_delete($params) {
  $posts = get_posts(array(
    'meta_key'   => 'job_reference',
    'meta_value' => $params['job_reference'],
    'post_type'  => 'job',
    'post_status' => 'any'
  ));

  foreach ($posts as $post) {
    wp_delete_post($post->ID);
    echo 'Deleted ' . $params['job_reference'];
  }
}

// Perform the processing based on <command>.

switch ($params['command']) {
case 'add':
  broadbean_add($params);
  break;
case 'delete':
  broadbean_delete($params);
  break;
}

die();

?>
