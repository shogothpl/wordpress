<?php
/**
 * Plugin Name: Cool video banner
 * Plugin URI: ......
 * Description: This plugin displays YT video banner.
 * Version: 1.0.0
 * Author: Bart Dzidowski
 * Author URI: ......
 * License: GPL2
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$header_1 = '<link rel="stylesheet" href="css/style.css?v1.1">';
$bottom_1 = '</script><script src="js/script.js?v1.1"></script>';
function generate_banner($banner_title, $banner_description, $image_url, $mp4_url, $yt_link)  {
   $banner_out = '  <div class="header-video">
    <img src="img/masthead.jpg"
         class="header-video__media"
         data-video-URL="https://www.youtube.com/embed/Scxs7L0vhZ4"
         data-teaser="video/teaser-video"
         data-video-width="560"
         data-video-height="315">
    <h2>Title</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vel massa ut risus eleifend consequat. Praesent tincidunt aliquet purus, in tempus purus imperdiet at.</p>     
    <a href="https://www.youtube.com/embed/Scxs7L0vhZ4" class="header-video__play-trigger" id="header-video__play-trigger">Play</a>
    <button type="button" class="header-video__close-trigger" id="header-video__close-trigger">Close</button>
  </div>';
    return true;
}

add_action ( 'create_cool_videobanner', 'generate_banner' );


add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	add_menu_page('My Plugin Settings', 'Plugin Settings', 'administrator', 'my-plugin-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
}

function my_plugin_settings_page() {
}
?>