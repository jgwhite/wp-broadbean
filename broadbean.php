<?php
/*
Plugin Name: Broadbean
Plugin URI: http://github.com/jgwhite/wp-broadbean
Description: Provides endpoint and integration for Broadbean AdCourier.
Version: 1.0
Author: Jamie White
Author URI: http://jgwhite.co.uk/
License: MIT

Copyright (C) 2012 Jamie White, Lawrence Brown

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
IN THE SOFTWARE.
*/

class Broadbean {

  const basename = 'broadbean/broadbean.php';

  function activate() {
    self::add_rewrite_rule();
    flush_rewrite_rules();
  }

  function deactivate() {
    global $wp_rewrite;
    unset($wp_rewrite->non_wp_rules['broadbean-inbox$']);
    flush_rewrite_rules();
  }

  function init() {
    self::create_post_type();
  }

  function admin_init() {
    self::register_settings();
    self::add_rewrite_rule();
  }

  function admin_menu() {
    add_submenu_page(
      'options-general.php',
      'Broadbean',
      'Broadbean',
      'manage_options',
      'broadbean',
      array('Broadbean', 'render_admin_menu')
    );
  }

  function create_post_type() {
    register_post_type('job',
      array(
        'labels' => array(
          'name' => __( 'Jobs' ),
          'singular_name' => __( 'Job' )
        ),
        'public' => true,
        'has_archive' => true,
        'taxonomies' => array(
          'post_tag',
          'category'
        ),
        'supports' => array(
          'title',
          'editor',
          'revisions',
          'author',
          'excerpt',
          'thumbnail',
          'custom-fields'
        )
      )
    );
  }

  function register_settings() {
    register_setting('broadbean_settings', 'broadbean_username');
    register_setting('broadbean_settings', 'broadbean_password');
  }

  function add_rewrite_rule() {
    $url = plugins_url('inbox.php',  self::basename);
    $url = str_replace(site_url(), '', $url);
    $url = preg_replace('/^\/+/', '', $url);

    add_rewrite_rule('broadbean-inbox$', $url, 'top');
  }

  function action_links($links) {
    array_unshift($links, '<a href="options-general.php?page=broadbean">Settings</a>');
    return $links;
  }

  function render_admin_menu() {
?>
<div class="wrap">
  <h2>Broadbean Settings</h2>
  <form method="post" action="options.php">
    <?php settings_fields('broadbean_settings') ?>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row"><label for="broadbean_username">Username</label></th>
          <td><input type="text" name="broadbean_username" id="broadbean_username"
                     value="<?php echo get_option('broadbean_username') ?>"></td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="broadbean_password">Password</label></th>
          <td><input type="text" name="broadbean_password" id="broadbean_password"
                     value="<?php echo get_option('broadbean_password') ?>"></td>
        </tr>
      </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"></p>
  </form>
</div>
<?php
  }

}

register_activation_hook(Broadbean::basename, array('Broadbean', 'activate'));
register_deactivation_hook(Broadbean::basename, array('Broadbean', 'deactivate'));

add_filter('plugin_action_links_'.Broadbean::basename, array('Broadbean', 'action_links'));
add_action('init', array('Broadbean', 'init'));
add_action('admin_init', array('Broadbean', 'admin_init'));
add_action('admin_menu', array('Broadbean', 'admin_menu'));

?>
