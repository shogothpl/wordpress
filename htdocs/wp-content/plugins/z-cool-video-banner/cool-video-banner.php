
<?php
/**
 * Plugin Name: Z Cool video banner
 * Plugin URI: ......
 * Description: This plugin displays YT video banner.
 * Version: 1.0.0
 * Author: Bart Dzidowski
 * Author URI: ......
 * License: GPL2
 * http://zerosixthree.se/create-a-responsive-header-video-with-graceful-degradation/
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if(!defined('CVB_PATH')){
	define('CVB_PATH', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR );
}
if(!defined('CVB_URL')){
	define('CVB_URL', plugin_dir_url(__FILE__) );
}

 wp_enqueue_style('style_css', CVB_URL . 'css/style.css', false);
  wp_register_script('script_js', CVB_URL . 'js/script.js', false);
  wp_enqueue_script('style_css');
  wp_enqueue_script('script_js', array('jquery'), '', true );

function generate_banner($banner_title, $banner_description, $image_url, $mp4_url, $yt_link)  {
	if ($yt_link !='') { $ytgen='data-video-URL="'.$yt_link.'"'; $ytgenhtml='<a href="'.$yt_link.'" class="header-video__play-trigger" id="header-video__play-trigger">Play</a>
    <button type="button" class="header-video__close-trigger" id="header-video__close-trigger">Close</button>
'; } else {$ytgen='';$ytgenhtml='';}
    
	if ($mp4_url !='') { $mp4gen='data-teaser="'.$mp4_url.'"';} else {$mp4gen='';}
   $banner_out = '  <div class="header-video">
    <img src="'.$image_url.'"
         class="header-video__media"'.$ytgen.' '.$mp4gen.'"data-video-width="560"
         data-video-height="315">
    <h2>'.$banner_title.'</h2>
    <p>'.$banner_description.'</p>'.$ytgenhtml.'</div><div style="float: none; clear:none;"></div>';
  echo $banner_out;
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