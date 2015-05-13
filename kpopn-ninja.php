<?php

/*
Plugin Name: Kpopn Ninja
Plugin URI: https://github.com/wake/kpopn-support
Description: 針對 Kpopn 網站所撰寫的服務套組
Version: 0.1
Author: Wake
Author URI: http://wake.gs
*/

  if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    $config = array(
      'slug' => plugin_basename (__FILE__), // this is the slug of your plugin
      'proper_folder_name' => 'kpopn-ninja', // this is the name of the folder your plugin lives in
      'api_url' => 'https://api.github.com/repos/wake/kpopn-ninja', // the github API url of your github repo
      'raw_url' => 'https://raw.github.com/wake/kpopn-ninja/master', // the github raw url of your github repo
      'github_url' => 'https://github.com/wake/kpopn-ninja', // the github url of your github repo
      'zip_url' => 'https://github.com/wake/kpopn-ninja/zipball/master', // the zip url of the github repo
      'sslverify' => true // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
      'requires' => '3.0', // which version of WordPress does your plugin require?
      'tested' => '4.2.2', // which version of WordPress is your plugin tested up to?
      'readme' => 'README.MD' // which file to use as the readme for the version number
    );
    new WPGitHubUpdater($config);
  }

  /**
   *
   * Kpopn Ninja actions
   *
   */

  add_action ('admin_menu', 'kpopn_ninja_post_menu_box');
  add_action ('save_post', 'kpopn_ninja_save_post_receiver', 10, 2);

  function kpopn_ninja_post_menu_box () {
    add_meta_box ('kpopn-ninja', 'Kpopn Ninja', 'kpopn_ninja_post_meta_box', 'post', 'normal', 'high' );
  }

  function kpopn_ninja_post_meta_box ($object, $box) {

    ?>
    <input type="hidden" name="kpopn_ninja_nonce" value="<?php echo wp_create_nonce (plugin_basename (__FILE__)); ?>" />
    <?

    $meta = get_post_meta ($object->ID, 'Kpopn Ninja', true);

    do_action ('kpopn_ninja_post_box_item', $object, $box, $meta);
  }

  function kpopn_ninja_save_post_receiver ($post_id, $post) {

    if (! wp_verify_nonce ($_POST['kpopn_ninja_nonce'], plugin_basename (__FILE__)))
      return $post_id;

    if (! current_user_can ('edit_post', $post_id))
      return $post_id;

    $meta_value = get_post_meta ($post_id, 'Kpopn Ninja', true);
    $post_value = $_POST['kpopn_ninja'];

    $post_value = apply_filters ('kpopn_ninja_save_post_value', $post_value);

    if ('' ==  $meta_value)
      add_post_meta ($post_id, 'Kpopn Ninja', $post_value, true);

    elseif (! isset ($meta_value[$k]) || $meta_value[$k] != $post_value[$k])
      update_post_meta ($post_id, 'Kpopn Ninja', $post_value + $meta_value);
  }


  /**
   *
   * FB like 數量異常補救
   *
   */

  //require_once dirname( __FILE__ ) . '/fblike-with-shortlink.php';

