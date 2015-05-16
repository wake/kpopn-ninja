<?php

  /**
   *
   * FB metatag
   *
   */

  add_action ('wp_head', 'kpopn_ninja_add_metatags');

  function kpopn_ninja_add_metatags () {

    /*
    $options = get_option(NETWORKPUB_WIDGET_NAME_INTERNAL);
    if (!is_array($options)) {
      return;
    }
    if (!array_key_exists('networkpub_metatags_facebook', $options)) {
      return;
    }
    $networkpub_metatags_facebook = $options['networkpub_metatags_facebook'];
    $networkpub_metatags_googleplus = $options['networkpub_metatags_googleplus'];
    if (!$networkpub_metatags_facebook and !$networkpub_metatags_googleplus) {
      return;
    }
    */

    global $posts;

    //Site name
    $og_site_name = get_bloginfo('name');

    //Set defaults for video types
    $og_video = '';
    $og_video_type = '';

    //Post or Page
    if (is_single() || is_page()) {

      //Post data
      $post_data = get_post($posts[0]->ID, ARRAY_A);

      //Title
      $og_title = kn_prepare_text($post_data['post_title']);

      //Link
      $og_link = get_permalink($posts[0]->ID);

      /*
      //Image or Video
      $networkpub_post_image_video = get_post_meta($posts[0]->ID, 'networkpub_post_image_video', true);
      if($networkpub_post_image_video == 'image') {
        $og_link_image = kn_thumbnail_link($posts[0]->ID, $post_data['post_content']);
      } else {
        $og_link_image = get_post_meta($posts[0]->ID, 'networkpub_video_picture', true);
        $og_video = get_post_meta($posts[0]->ID, 'networkpub_video_source', true);
        $og_video_type = "application/x-shockwave-flash";
      }
      */

      // $og_link_image = kn_thumbnail_link($posts[0]->ID, $post_data['post_content']);

      if (function_exists('get_post_thumbnail_id') and function_exists('wp_get_attachment_image_src')) {
        $src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $networkpub_thumbnail_size);
        if ($src) {
          $src = $src[0];
          $og_link_image = $src;
        }
      }

      //Content
      if (!empty($post_data['post_excerpt'])) {
        $og_desc = $post_data['post_excerpt'];
      } else {
        $og_desc = $post_data['post_content'];
      }

      $og_desc = kn_prepare_text($og_desc);

      /*
      //Facebook Page Type
      $post_ogtypefacebook = get_post_meta($posts[0]->ID, 'networkpub_ogtype_facebook', true);

      if ($post_ogtypefacebook) {
        $og_type = $post_ogtypefacebook;
      } else {
        if (!empty($options['networkpub_facebook_page_type']))  {
          $og_type = $options['networkpub_facebook_page_type'];
        } else {
          $og_type = 'article';
        }
      }
      */

      $og_type = 'article';

    } else {

      //Title
      $og_title = kn_prepare_text($og_site_name);

      //Link
      $og_link = get_bloginfo('url');

      //Image Link
      $og_link_image = '';

      //Desc
      $og_desc = get_bloginfo('description');
      $og_desc = kn_prepare_text($og_desc);

      //Type
      $og_type = 'website';
    }

    /*
    if (!empty($options['networkpub_lang_facebook'])) {
      $og_locale = $options['networkpub_lang_facebook'];
    } else {
      $og_locale = 'en_US';
    }
    if (!empty($options['networkpub_facebook_app_id'])) {
      $og_fb_app_id = $options['networkpub_facebook_app_id'];
    } else {
      $og_fb_app_id = '';
    }
    */

    $og_locale = 'en_US';
    $og_fb_app_id = '159756847440228';

    kn_build_meta_facebook ($og_site_name, $og_title, $og_link, $og_link_image, $og_desc, $og_type, $og_locale, $og_fb_app_id, $og_video, $og_video_type);

    /*
    //Google Plus Page Type
    if (!empty($options['networkpub_googleplus_page_type'])) {
      $og_type_google = $options['networkpub_googleplus_page_type'];
    } else {
      $og_type_google = 'Article';
    }
    if ($networkpub_metatags_facebook) {
      kn_build_meta_facebook($og_site_name, $og_title, $og_link, $og_link_image, $og_desc, $og_type, $og_locale, $og_fb_app_id, $og_video, $og_video_type);
    }
    if ($networkpub_metatags_googleplus) {
      networkpub_build_meta_googleplus($og_title, $og_link_image, $og_desc, $og_type_google);
    }
    */
    return;
  }

  function kn_build_meta_facebook($og_site_name, $og_title, $og_link, $og_link_image, $og_desc, $og_type, $og_locale, $og_fb_app_id, $og_video, $og_video_type) {
    $opengraph_meta = '';
    if ($og_site_name) {
      $opengraph_meta .= "\n<meta property=\"og:site_name\" content=\"" . $og_site_name . "\" />";
    }
    if ($og_title) {
      $opengraph_meta .= "\n<meta property=\"og:title\" content=\"" . $og_title . "\" />";
    }
    if ($og_link) {
      $opengraph_meta .= "\n<meta property=\"og:url\" content=\"" . $og_link . "\" />";
    }
    if ($og_link_image) {
      $opengraph_meta .= "\n<meta property=\"og:image\" content=\"" . $og_link_image . "\" />";
    }
    if ($og_video) {
      $opengraph_meta .= "\n<meta property=\"og:video\" content=\"" . $og_video . "\" />";
      if ($og_video_type) {
        $opengraph_meta .= "\n<meta property=\"og:video:type\" content=\"" . $og_video_type . "\" />";
      }
    }
    if ($og_desc) {
      $opengraph_meta .= "\n<meta property=\"og:description\" content=\"" . $og_desc . "\" />";
    }
    if ($og_type) {
      $opengraph_meta .= "\n<meta property=\"og:type\" content=\"" . $og_type . "\" />";
    }
    if ($og_locale) {
      $opengraph_meta .= "\n<meta property=\"og:locale\" content=\"" . strtolower($og_locale) . "\" />";
    }
    if ($og_fb_app_id) {
      $opengraph_meta .= "\n<meta property=\"fb:app_id\" content=\"" . trim($og_fb_app_id) . "\" />";
    }
    echo "\n<!-- Facebook OG metatags -->" . $opengraph_meta . "\n<!-- End Facebook OG metatags-->\n";
  }

  /*
  function networkpub_build_meta_googleplus($og_title, $og_link_image, $og_desc, $og_type) {
    $opengraph_meta = '';
    if ($og_title) {
      $opengraph_meta = "\n<meta itemprop=\"name\"  content=\"" . $og_title . "\" />";
    }
    if ($og_link_image) {
      $opengraph_meta .= "\n<meta itemprop=\"image\" content=\"" . $og_link_image . "\" />";
    }
    if ($og_desc) {
      $opengraph_meta .= "\n<meta itemprop=\"description\" content=\"" . $og_desc . "\" />";
    }
    if ($og_type) {
      $opengraph_meta .= "\n<meta itemprop=\"type\" content=\"" . $og_type . "\" />";
    }
    echo "\n<!-- Google Plus metatags added by WordPress plugin - Network Publisher. Get it at: http://wordpress.org/extend/plugins/network-publisher/ -->" . $opengraph_meta . "\n<!-- End Google Plus metatags-->\n";
  }
  */

function kn_thumbnail_link($post_id, $post_content) {
  $options = get_option(NETWORKPUB_WIDGET_NAME_INTERNAL);
  if (is_array($options)) {
    if ((!empty($options['networkpub_custom_field_image'])) && (!empty($options['networkpub_custom_field_image_url']))) {
      $networkpub_custom_field_image = $options['networkpub_custom_field_image'];
      $networkpub_custom_field_image_url = $options['networkpub_custom_field_image_url'];
      $post_data_custom = get_post_custom($post_id, ARRAY_A);
      if ((!empty($post_data_custom[$networkpub_custom_field_image]) ) ) {
        $post_data_custom_image = $post_data_custom[$networkpub_custom_field_image][0];
        if ( ( !networkpub_endswith($networkpub_custom_field_image_url, "/") && !networkpub_startswith($post_data_custom_image, "/") )  )  {
          return $networkpub_custom_field_image_url . '/' . $post_data_custom_image;
        } elseif ( !networkpub_endswith($networkpub_custom_field_image_url, "/") && networkpub_startswith($post_data_custom_image, "/") ) {
          return $networkpub_custom_field_image_url . $post_data_custom_image;
        } elseif ( networkpub_endswith($networkpub_custom_field_image_url, "/") && !networkpub_startswith($post_data_custom_image, "/") ) {
          return $networkpub_custom_field_image_url . $post_data_custom_image;
        } elseif ( networkpub_endswith($networkpub_custom_field_image_url, "/") && networkpub_startswith($post_data_custom_image, "/") ){
          $networkpub_custom_field_image_url = rtrim($networkpub_custom_field_image_url, "/");
          return $networkpub_custom_field_image_url . $post_data_custom_image;
        }
      }
    }  elseif ((!empty($options['networkpub_custom_field_image'])) && (empty($options['networkpub_custom_field_image_url']))) {
      $networkpub_custom_field_image = $options['networkpub_custom_field_image'];
      $post_data_custom = get_post_custom($post_id, ARRAY_A);
      if ((!empty($post_data_custom[$networkpub_custom_field_image]))) {
        return $post_data_custom[$networkpub_custom_field_image][0];
      }
    }
  }
  if (is_array($options)) {
    if (!empty($options['networkpub_thumbnail_size'])) {
      $networkpub_thumbnail_size = $options['networkpub_thumbnail_size'];
    }
  }
  if (function_exists('get_post_thumbnail_id') and function_exists('wp_get_attachment_image_src')) {
    $src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $networkpub_thumbnail_size);
    if ($src) {
      $src = $src[0];
      return $src;
    }
  }
  if (!$post_content) {
    return False;
  }
  if (class_exists("DOMDocument") and function_exists('simplexml_import_dom')) {
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    if (!($doc->loadHTML($post_content))) {
      return False;
    }
    try {
      $xml = @simplexml_import_dom($doc);
      if ($xml) {
        $images = $xml -> xpath('//img');
        if (!empty($images)) {
          return (string)$images[0]['src'];
        }
      } else {
        return False;
      }
    } catch (Exception $e) {
      return False;
    }
  }
  return False;
}

  function kn_prepare_text($text) {
    $text = stripslashes($text);
    $text = strip_tags($text);
    $text = preg_replace("/\[.*?\]/", '', $text);
    $text = preg_replace('/([\n \t\r]+)/', ' ', $text);
    $text = preg_replace('/( +)/', ' ', $text);
    $text = preg_replace('/\s\s+/', ' ', $text);
    $text = kn_prepare_string($text, 310);
    $text = kn_smart_truncate($text, 300);
    $text = trim($text);
    $text = htmlspecialchars($text);
    return $text;
  }
function kn_prepare_string($string, $string_length) {
  $final_string = '';
  $utf8marker = chr(128);
  $count = 0;
  while (isset($string{$count})) {
    if ($string{$count} >= $utf8marker) {
      $parsechar = substr($string, $count, 2);
      $count += 2;
    } else {
      $parsechar = $string{$count};
      $count++;
    }
    if ($count > $string_length) {
      return $final_string;
    }
    $final_string = $final_string . $parsechar;
  }
  return $final_string;
}


function kn_smart_truncate($string, $required_length) {
  $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
  $parts_count = count($parts);
  $length = 0;
  $last_part = 0;
  for (; $last_part < $parts_count; ++$last_part) {
    $length += strlen($parts[$last_part]);
    if ($length > $required_length) {
      break;
    }
  }
  return implode(array_slice($parts, 0, $last_part));
}
