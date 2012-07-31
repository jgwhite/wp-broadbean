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

if (trim($params['username']) !== $username || trim($params['password']) !== $password) {
  echo 'Incorrect username and/or password';
  die();
}

// Process an 'add' command.

function broadbean_add($params) {
  $job_post_id = wp_insert_post(array(
    'post_type'     => 'job',
    'post_title'    => trim($params['job_title']),
    'post_content'  => trim($params['job_description']),
    'tags_input'    => array(trim($params['job_category']), trim($params['job_duration']))
  ));

  if ($job_post_id != 0) {
    add_post_meta($job_post_id , 'job_type'          , trim($params['job_type'])          , true);
    add_post_meta($job_post_id , 'contact_name'      , trim($params['contact_name'])      , true);
    add_post_meta($job_post_id , 'contact_email'     , trim($params['contact_email'])     , true);
    add_post_meta($job_post_id , 'contact_telephone' , trim($params['contact_telephone']) , true);
    add_post_meta($job_post_id , 'contact_url'       , trim($params['contact_url'])       , true);
    add_post_meta($job_post_id , 'days_to_advertise' , trim($params['days_to_advertise']) , true);
    add_post_meta($job_post_id , 'application_email' , trim($params['application_email']) , true);
    add_post_meta($job_post_id , 'application_url'   , trim($params['application_url'])   , true);
    add_post_meta($job_post_id , 'job_reference'     , trim($params['job_reference'])     , true);
    add_post_meta($job_post_id , 'job_category'      , trim($params['job_category'])      , true);
    add_post_meta($job_post_id , 'job_title'         , trim($params['job_title'])         , true);
    add_post_meta($job_post_id , 'job_type'          , trim($params['job_type'])          , true);
    add_post_meta($job_post_id , 'job_duration'      , trim($params['job_duration'])      , true);
    add_post_meta($job_post_id , 'job_startdate'     , trim($params['job_startdate'])     , true);
    add_post_meta($job_post_id , 'job_skills'        , trim($params['job_skills'])        , true);
    add_post_meta($job_post_id , 'job_description'   , trim($params['job_description'])   , true);
    add_post_meta($job_post_id , 'job_location'      , trim($params['job_location'])      , true);
    add_post_meta($job_post_id , 'job_industry'      , trim($params['job_industry'])      , true);
    add_post_meta($job_post_id , 'salary_currency'   , trim($params['salary_currency'])   , true);
    add_post_meta($job_post_id , 'salary_from'       , trim($params['salary_from'])       , true);
    add_post_meta($job_post_id , 'salary_to'         , trim($params['salary_to'])         , true);
    add_post_meta($job_post_id , 'salary_per'        , trim($params['salary_per'])        , true);
    add_post_meta($job_post_id , 'salary_benefits'   , trim($params['salary_benefits'])   , true);
    add_post_meta($job_post_id , 'salary'            , trim($params['salary'])            , true);

    wp_update_post(array('ID' => $job_post_id, 'post_status' => 'publish'));
  }

  echo 'Added ' . $params['job_reference'];
}

// Process a 'delete' command.

function broadbean_delete($params) {
  $job_reference = trim($params['job_reference']);

  $posts = get_posts(array(
    'meta_key'   => 'job_reference',
    'meta_value' => $job_reference,
    'post_type'  => 'job',
    'post_status' => 'any'
  ));

  foreach ($posts as $post) {
    wp_delete_post($post->ID);
    echo 'Deleted ' . $params['job_reference'] . "\n";
  }
}

// Perform the processing based on <command>.

switch (strtolower(trim($params['command']))) {
case 'add':
  broadbean_add($params);
  break;
case 'delete':
  broadbean_delete($params);
  break;
}

die();

?>
