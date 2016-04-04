<?php
/**
 * Plugin Name: Cool video banner
 * Plugin URI: ......
 * Description: This plugin displays YT video banner.
 * Version: 1.0.0
 * Author: Bart Dzidowski
 * Author URI: ......
 * License: GPL2
 * http://zerosixthree.se/create-a-responsive-header-video-with-graceful-degradation/
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$header_1 = '<link rel="stylesheet" href="css/style.css?v1.1">';
$bottom_1 = '</script><script src="js/script.js?v1.1"></script>';
function generate_banner($banner_title, $banner_description, $image_url, $mp4_url, $yt_link)  {
	if ($yt_link !='') { $ytgen='data-video-URL="'.$yt_link.'"'; $ytgenhtml='<a href="'.$yt_link.'" class="header-video__play-trigger" id="header-video__play-trigger">Play</a>
    <button type="button" class="header-video__close-trigger" id="header-video__close-trigger">Close</button>
'; } else {$ytgen='';$ytgenhtml='';}
    
	if ($mp4_url !='') { $mp4gen='data-teaser="'.$mp4_url.'"';} else {$mp4gen='';}
   $banner_out = '  <div class="header-video">
    <img src="img/'.$image_url.'"
         class="header-video__media"'.$ytgen.' '.$mp4gen.'"data-video-width="560"
         data-video-height="315">
    <h2>'.$banner_title.'</h2>
    <p>'.$banner_description.'</p>'.$ytgenhtml.'</div>';
  echo $header_1.$banner_out.$bottom_1;
   // return true;
}

add_action ( 'create_cool_videobanner', 'generate_banner' );


add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	add_menu_page('My Plugin Settings', 'Plugin Settings', 'administrator', 'my-plugin-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
}

function my_plugin_settings_page() {
}
?>