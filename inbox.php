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
    add_post_meta($job_post_id , 'Job Type'          , $params['job_type']          , true);
    add_post_meta($job_post_id , 'Contact Name'      , $params['contact_name']      , true);
    add_post_meta($job_post_id , 'Contact Email'     , $params['contact_email']     , true);
    add_post_meta($job_post_id , 'Contact Telephone' , $params['contact_telephone'] , true);
    add_post_meta($job_post_id , 'Contact URL'       , $params['contact_url']       , true);
    add_post_meta($job_post_id , 'Days to Advertise' , $params['days_to_advertise'] , true);
    add_post_meta($job_post_id , 'Application Email' , $params['application_email'] , true);
    add_post_meta($job_post_id , 'Application URL'   , $params['application_url']   , true);
    add_post_meta($job_post_id , 'Job Reference'     , $params['job_reference']     , true);
    add_post_meta($job_post_id , 'Job Title'         , $params['job_title']         , true);
    add_post_meta($job_post_id , 'Job Type'          , $params['job_type']          , true);
    add_post_meta($job_post_id , 'Job Duration'      , $params['job_duration']      , true);
    add_post_meta($job_post_id , 'Job Startdate'     , $params['job_startdate']     , true);
    add_post_meta($job_post_id , 'Job Skills'        , $params['job_skills']        , true);
    add_post_meta($job_post_id , 'Job Description'   , $params['job_description']   , true);
    add_post_meta($job_post_id , 'Job Location'      , $params['job_location']      , true);
    add_post_meta($job_post_id , 'Job Industry'      , $params['job_industry']      , true);
    add_post_meta($job_post_id , 'Salary Currency'   , $params['salary_currency']   , true);
    add_post_meta($job_post_id , 'Salary From'       , $params['salary_from']       , true);
    add_post_meta($job_post_id , 'Salary To'         , $params['salary_to']         , true);
    add_post_meta($job_post_id , 'Salary Per'        , $params['salary_per']        , true);
    add_post_meta($job_post_id , 'Salary Benefits'   , $params['salary_benefits']   , true);
    add_post_meta($job_post_id , 'Salary'            , $params['salary']            , true);
  }

  echo 'Added ' . $params['job_reference'];
}

// Process a 'delete' command.

function broadbean_delete($params) {
  $posts = get_posts(array(
    'meta_key'   => 'Job Reference',
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
