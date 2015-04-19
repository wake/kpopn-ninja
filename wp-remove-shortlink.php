<?php

/*
Plugin Name: Shortlink Removal
Plugin URI: http://www.douglasradburn.co.uk/
Description: Remove shortlink hook
Version: 1.0
Author: Douglas Radburn
Author URI: http://www.douglasradburn.co.uk
*/

remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
?>