<?php

/*
Plugin Name: WP Remove Shortlink
Plugin URI: http://wake.gs
Description: 同時移除文章內的 short link 並調整 rss feed 中的 guid
Version: 1.0
Author: Wake
Author URI: http://wake.gs
*/

remove_action ('wp_head', 'wp_shortlink_wp_head', 10, 0);


add_filter ('get_the_guid', 'use_guid_with_permalink');

function use_guid_with_permalink ($guid) {
  return get_permalink ();
}

?>