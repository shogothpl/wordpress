<?php
define( 'OPTIONS_SLUG', 'virtue_premium' );
define( 'LANGUAGE_SLUG', 'virtue' );
load_theme_textdomain('virtue', get_template_directory() . '/languages');

/*
 * Init Theme Options
 */
require_once locate_template('/themeoptions/framework.php');          		// Options framework
require_once locate_template('/themeoptions/options.php');          		// Options framework
require_once locate_template('/themeoptions/options/virtue_extension.php'); // Options framework extension
require_once locate_template('/kt_framework/extensions.php');        		// Remove options from the admin

/*
 * Init Theme Startup/Core utilities
 */
require_once locate_template('/lib/utils.php');           		            // Utility functions
require_once locate_template('/lib/init.php');            					// Initial theme setup and constants
require_once locate_template('/lib/sidebar.php');         					// Sidebar class
require_once locate_template('/lib/config.php');          					// Configuration
require_once locate_template('/lib/cleanup.php');        					// Cleanup
require_once locate_template('/lib/custom-nav.php');        				// Nav Options
require_once locate_template('/lib/nav.php');            					// Custom nav modifications
require_once locate_template('/lib/metaboxes.php');     					// Custom metaboxes
require_once locate_template('/lib/gallery_metabox.php');     				// Custom Gallery metaboxes
require_once locate_template('/lib/taxonomy-meta-class.php');   			// Taxonomy meta boxes
require_once locate_template('/lib/taxonomy-meta.php');         			// Taxonomy meta boxes
require_once locate_template('/lib/comments.php');        					// Custom comments modifications
require_once locate_template('/lib/post-types.php');      					// Post Types
require_once locate_template('/lib/Mobile_Detect.php');        				// Mobile Detect
require_once locate_template('/lib/aq_resizer.php');      					// Resize on the fly
require_once locate_template('/lib/revslider-activate.php');   				// Plugin Activation

/*
 * Init Shortcodes
 */
require_once locate_template('/lib/kad_shortcodes/shortcodes.php');      					// Shortcodes
require_once locate_template('/lib/kad_shortcodes/carousel_shortcodes.php');   				// Carousel Shortcodes
require_once locate_template('/lib/kad_shortcodes/custom_carousel_shortcodes.php');   		// Carousel Shortcodes
require_once locate_template('/lib/kad_shortcodes/testimonial_shortcodes.php');   			// Carousel Shortcodes
require_once locate_template('/lib/kad_shortcodes/testimonial_form_shortcode.php');   		// Carousel Shortcodes
require_once locate_template('/lib/kad_shortcodes/blog_shortcodes.php');   					// Blog Shortcodes
require_once locate_template('/lib/kad_shortcodes/image_menu_shortcodes.php'); 				// image menu Shortcodes
require_once locate_template('/lib/kad_shortcodes/google_map_shortcode.php');  				// Map Shortcodes
require_once locate_template('/lib/kad_shortcodes/portfolio_shortcodes.php'); 				// Portfolio Shortcodes
require_once locate_template('/lib/kad_shortcodes/portfolio_type_shortcodes.php'); 			// Portfolio Shortcodes
require_once locate_template('/lib/kad_shortcodes/staff_shortcodes.php'); 					// Staff Shortcodes
require_once locate_template('/lib/kad_shortcodes/gallery.php');      						// Gallery Shortcode

/*
 * Init Widgets
 */
require_once locate_template('/lib/premium_widgets.php'); 					// Gallery Widget
require_once locate_template('/lib/widgets.php');         					// Sidebars and widgets

/*
 * Template Hooks
 */
require_once locate_template('/lib/custom.php');          					// Custom functions
require_once locate_template('/lib/authorbox.php');         				// Author box
require_once locate_template('/lib/breadcrumbs.php');         				// Breadcrumbs
require_once locate_template('/lib/template_hooks.php'); 					// Template Hooks
require_once locate_template('/lib/custom-woocommerce.php'); 				// Woocommerce functions

/*
 * Load Scripts
 */
require_once locate_template('/lib/admin_scripts.php');    					// Icon functions
require_once locate_template('/lib/scripts.php');        					// Scripts and stylesheets
require_once locate_template('/lib/custom_css.php'); 						// Fontend Custom CSS

/*
 * Updater
 */
require_once locate_template('/lib/wp-updates-theme.php');
$KT_UpdateChecker = new ThemeUpdateChecker('virtue_premium', 'https://kernl.us/api/v1/theme-updates/567242b41c90572e711087ef/');

/*
 * Admin Shortcode Btn
 */
function virtue_shortcode_init() {
	if(is_admin()){ if(kad_is_edit_page()){require_once locate_template('/lib/kad_shortcodes.php');	}}
}
add_action('init', 'virtue_shortcode_init');
//remove kad_sidebar on custom post type 
add_filter('kadence_display_sidebar', 'kad_sidebar_on_special_page');

function kad_sidebar_on_special_page($sidebar) {
	global $post;
	if ($post->post_type == "news") {
    return false;
  }
  return $sidebar;
} 
/* additional shortcodes */
// [bartag foo="foo-value"]
function custom_post_dis_func( $atts ) {
	$testatt="this is content";
    $atsa = shortcode_atts( array(
        'insert_post' => 'default',
    ), $atts );

$page = get_page_by_title( $atsa['insert_post'], OBJECT, 'post'); 
$post_id = $page->ID;
$queried_post = get_post($post_id);

return $queried_post->post_content;

}
add_shortcode( 'custom_post_display', 'custom_post_dis_func' );

function show_latest_news_func( $atts ) {
	$cat_id=get_cat_ID( 'news' );
$args = array( 'numberposts' => '1','category' => $cat_id );
	$recent_posts = wp_get_recent_posts( $args );
	foreach( $recent_posts as $recent ){
		  $banner_out = '  <div class="header-video">'.get_the_post_thumbnail( $recent["ID"], 'orginal' ).'
        
    <h2>'.$recent["post_title"].'</h2>
    <p>'.$recent["post_content"].'</p></div><div style="float: none; clear:none;"></div>';
	
	//	return get_the_post_thumbnail( $recent["ID"], 'large' ). '<li><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a></li> ';
	return $banner_out;
	}

}
add_shortcode( 'show_latest_from_cat', 'show_latest_from_cat_func' );

function show_latest_news_func( $atts ) {
	$atsa = shortcode_atts( array(
        'category' => 'default',
    ), $atts );
	
	$cat_id=get_cat_ID( $atsa['category'] );
$args = array( 'numberposts' => '1','category' => $cat_id );
	$recent_posts = wp_get_recent_posts( $args );
	foreach( $recent_posts as $recent ){
		  $banner_out = '  <div class="header-video">'.get_the_post_thumbnail( $recent["ID"], 'orginal' ).'
        
    <h2>'.$recent["post_title"].'</h2>
    <p>'.$recent["post_content"].'</p></div><div style="float: none; clear:none;"></div>';
	
	//	return get_the_post_thumbnail( $recent["ID"], 'large' ). '<li><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a></li> ';
	return $banner_out;
	}

}
add_shortcode( 'show_latest_from_cat', 'show_latest_from_cat_func' );