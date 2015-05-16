<?php

/*
Plugin Name: Kpopn Ninja
Plugin URI: https://github.com/wake/kpopn-ninja
Description: 針對 Kpopn 網站所撰寫的服務套組
Version: 0.4.1
Author: Wake
Author URI: http://wake.gs
*/

  define ('_KPOPN_NINJA_PATH', dirname (__FILE__));

  if (is_admin ()) {

    if (isset ($_GET['ninja-action']) && $_GET['ninja-action'] == 'update') {
      define ('WP_GITHUB_FORCE_UPDATE', true);
    }

    require_once _KPOPN_NINJA_PATH . '/vendors/updater.php';

    $config = array (
      'slug' => plugin_basename (__FILE__),
      'proper_folder_name' => 'kpopn-ninja',
      'api_url' => 'https://api.github.com/repos/wake/kpopn-ninja',
      'raw_url' => 'https://raw.github.com/wake/kpopn-ninja/master',
      'github_url' => 'https://github.com/wake/kpopn-ninja',
      'zip_url' => 'https://github.com/wake/kpopn-ninja/zipball/master',
      'sslverify' => true,
      'requires' => '3.0',
      'tested' => '4.2.2',
      'readme' => 'README.MD',
      'access_token' => '',
    );

    new WP_GitHub_Updater ($config);

    /**
     *
     * 增加檢查更新的選項和執行動作
     *
     */

    add_filter ("network_admin_plugin_action_links_{$config['slug']}", 'kpopn_ninja_update_manually_option');
    add_filter ("plugin_action_links_{$config['slug']}", 'kpopn_ninja_update_manually_option');

    function kpopn_ninja_update_manually_option ($links) {
      $settings_link = '<a href="plugins.php?ninja-action=update">' . __( '檢查更新', 'Kpopn Ninja Updater' ) . '</a>';
      array_unshift ($links, $settings_link);
      return $links;
    }
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
   * FB metatags
   *
   */

  require_once _KPOPN_NINJA_PATH . '/missions/fb-metatag.php';
