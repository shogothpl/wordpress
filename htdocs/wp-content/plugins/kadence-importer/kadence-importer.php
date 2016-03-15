<?php 
/*
Plugin Name: Kadence Importer
Description: Choose the demo and click import. Easy Kadence Themes demo site Imports. 
Version: 0.9
Author: Kadence Themes
Author URI: http://kadencethemes.com/
License: GPLv2 or later
Text Domain: kadence-importer
*/

if(!defined('KT_IMPORTER_PATH')){
	define('KT_IMPORTER_PATH', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR );
}
if(!defined('KT_IMPORTER_URL')){
	define('KT_IMPORTER_URL', plugin_dir_url(__FILE__) );
}
if(!defined('KT_IMPORTER_BASENAME')){
	define('KT_IMPORTER_BASENAME', plugin_basename( __FILE__ ) );
}
    class Kadence_Importer {

    	public function __construct() {
    		add_action('init', array( $this, 'init' ) );
    		add_action( 'wp_ajax_kadence_import_vp_demo_data', array( $this, 'kadence_importer_virtue_premium_ajax') );
    		add_action( 'wp_ajax_kadence_import_pp_demo_data', array( $this, 'kadence_importer_pinnacle_premium_ajax') );
    		add_action( 'wp_ajax_kadence_import_v_demo_data', array( $this, 'kadence_importer_virtue_ajax') );
    		add_action( 'wp_ajax_kadence_import_p_demo_data', array( $this, 'kadence_importer_pinnacle_ajax') );
    	}

    	public function init() {

    		add_action( 'admin_menu', array( $this, 'kadence_importer_page' ) );
    		if ( isset ( $_GET['page'] ) && $_GET['page'] == "kadence-importer" ) {
    			 	add_action( 'admin_head', array( $this, 'kadence_importer_admin_styles' ) );
    			 	add_action( 'admin_footer', array( $this, 'kadence_importer_admin_scripts' ) );
    		}
    		add_filter( 'plugin_action_links_' . KT_IMPORTER_BASENAME, array( $this, 'add_action_link' ), 10, 2 );
    	}

		public function kadence_importer_page() {
			add_management_page(__( 'Kadence Demo Content Importer', 'kadence_importer' ), __( 'Kadence Importer', 'kadence_importer' ), 'edit_theme_options', 'kadence-importer', array($this, 'import_page_content') );
		}
		function add_action_link( $links, $file ) {

				$settings_link = '<a href="' . esc_url( admin_url( 'tools.php?page=kadence-importer' ) ) . '">' . __( 'Importer', 'kadence-importer' ) . '</a>';
				array_unshift( $links, $settings_link );

			return $links;
		}
		public function kt_themename() {
			$the_theme = wp_get_theme();
			$child = $the_theme->get('template');
			if( isset( $child ) && !empty( $child ) ) {
				if($the_theme->get('template') == 'virtue') {
					return "Virtue";
				} else if ($the_theme->get('template') == 'virtue_premium'){
					return "Virtue - Premium";
				} else if ($the_theme->get('template') == 'pinnacle'){
					return "Pinnacle";
				} else if ($the_theme->get('template') == 'pinnacle_premium'){
					return "Pinnacle Premium";
				} else {
					return "notkadence";
				}
			} else {
				return $the_theme->get('Name');
			}
		}
		public function import_page_content() {
			// Stupid hack for Wordpress alerts and warnings
    		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
        	include_once( 'kadence_importer_content.php' );
		}
		public function kt_check_woocommerce() {
    		if (class_exists('woocommerce'))  {
    			return true;
    		}
    		return false;
		}
		public function kt_check_revslider() {
    		if( class_exists('UniteFunctionsRev'))  {
    			return true;
    		}
    		return false;
		}
		public function kt_check_kadenceslider() {
    		if( is_plugin_active('kadence-slider/kadence-slider.php'))  {
    			return true;
    		}
    		return false;
		}
		public function kt_check_pagebuilder() {
    		if( is_plugin_active('siteorigin-panels/siteorigin-panels.php'))  {
    			return true;
    		}
    		return false;
		}
		public function kt_check_visualeditor() {
    		if( is_plugin_active('black-studio-tinymce-widget/black-studio-tinymce-widget.php'))  {
    			return true;
    		}
    		return false;
		}
		public function kt_check_virtuetoolkit() {
    		if( is_plugin_active('virtue-toolkit/virtue_toolkit.php'))  {
    			return true;
    		}
    		return false;
		}
		public function kadence_importer_admin_styles() {
			?>
            <link rel='stylesheet' id='importer-css' href='<?php echo KT_IMPORTER_URL ?>importer.css' type='text/css' media='all'/>
           <?php
		}

		public function kadence_importer_admin_scripts() {
			?>
			<script
                id="kadence-importer-admin-js"
                src='<?php echo KT_IMPORTER_URL ?>importer.js'>
            </script>
           <?php
		}
		public function kadence_demo_theme_options( $theme_options_file, $options_name ) {

			// Import Theme Options
			$theme_options_file = file_get_contents( $theme_options_file );
			$imported_options = json_decode( $theme_options_file, true );
			$imported_options = maybe_unserialize($imported_options);
			// Only if there is data
      			if ( !empty( $imported_options ) || is_array( $imported_options ) ) {
					update_option($options_name, $imported_options );
				}

		}
		public function kadence_demo_woocommerce() {

			$woopages = array(
					'woocommerce_shop_page_id' => 'Shop',
					'woocommerce_cart_page_id' => 'Cart',
					'woocommerce_checkout_page_id' => 'Checkout',
					'woocommerce_myaccount_page_id' => 'My Account',
				);
				foreach($woopages as $woo_page_name => $woo_page_title) {
					$woopage = get_page_by_title( $woo_page_title );
					if(isset( $woopage ) && $woopage->ID) {
						update_option($woo_page_name, $woopage->ID); // Front Page
					}
				}

				// We no longer need to install pages
				delete_option( '_wc_needs_pages' );
				delete_transient( '_wc_activation_redirect' );

				// Flush rules after install
				flush_rewrite_rules();

		}
		public function kadence_demo_import_file($theme_xml_file) {

			global $wpdb;
				if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

				if ( ! class_exists( 'WP_Importer' ) ) {
					$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
					include $wp_importer;
				}

				if ( ! class_exists('WP_Import') ) {
					$wp_import = KT_IMPORTER_PATH . 'importer/wordpress-importer.php';
					include $wp_import;
				}
				$WPimporter = new WP_Import();
				$WPimporter->fetch_attachments = true;
				ob_start();
				$WPimporter->import($theme_xml_file);
				ob_end_clean();

		}
		public function kadence_importer_virtue_premium_ajax() {

			if( !isset($_POST['demo_switch']) ) {
				$demo_switch = 'style01';
			} else {
				$demo_switch = $_POST['demo_switch'];
			}

			switch($demo_switch) {
				case 'style02':
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/virtue_premium/site_style_02/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_02/site_style_02.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_02/style_02_widgets.json';
					$hasrevslider = false;
					$homepage_title = 'Home';

				break;
				case 'style03':
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/virtue_premium/site_style_03/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_03/site_style_03.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_03/style_03_widgets.json';
					$hasrevslider = true;
					$revslider_directory = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_03/rev_sliders/';
					$homepage_title = 'Home';
				break;
				case 'style04':
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/virtue_premium/site_style_04/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_04/site_style_04.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_04/style_04_widgets.json';
					$hasrevslider = true;
					$revslider_directory = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_04/rev_sliders/';
					$homepage_title = 'Home';
				break;
				default:
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/virtue_premium/site_style_01/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_01/site_style_01.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_01/style_01_widgets.json';
					$hasrevslider = true;
					$revslider_directory = KT_IMPORTER_PATH . 'importer/virtue_premium/site_style_01/rev_sliders/';
					$homepage_title = 'Home';
			}
			$message = "Imported";

			// Import Content
			$this->kadence_demo_import_file($theme_xml_file);

			//Woocommerce
			if( class_exists('Woocommerce') && $hasshop == true ) {
				$this->kadence_demo_woocommerce();
			}

			// Import Theme Options
			$this->kadence_demo_theme_options($theme_options_file, 'virtue_premium');

			// Assign Imported Menus
			$menulocations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();

			if($menus) {
				foreach($menus as $menu) {
					if( $demo_switch == 'style01' ) {
						if( $menu->name == 'Main1') {
							$menulocations['primary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Main1' ) {
							$menulocations['mobile_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Top1' ) {
							$menulocations['topbar_navigation'] = $menu->term_id;
						}
					} elseif( $demo_switch == 'style02' ) {
						if( $menu->name == 'LeftMenu2' ) {
							$menulocations['primary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'RightMenu2' ) {
							$menulocations['secondary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Mobile2' ) {
							$menulocations['mobile_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Footer2' ) {
							$menulocations['footer_navigation'] = $menu->term_id;
						}
					} elseif( $demo_switch == 'style03' ) {
						if( $menu->name == 'Main3' ) {
							$menulocations['primary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Second3' ) {
							$menulocations['secondary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Main3' ) {
							$menulocations['mobile_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Top3' ) {
							$menulocations['topbar_navigation'] = $menu->term_id;
						}
					} elseif( $demo_switch == 'style04' ) {
						if( $menu->name == 'Main4' ) {
							$menulocations['primary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Main4' ) {
							$menulocations['mobile_navigation'] = $menu->term_id;
						}
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $menulocations );

			// Import Widgets
			if ( ! class_exists('Widget_Importer_Exporter') ) {
				$wp_widget_import = KT_IMPORTER_PATH . 'importer/widget_import.php';
				include $wp_widget_import;
			}
			$widget_data = file_get_contents( $widgets_file );
			$widget_data = json_decode( $widget_data );
			$wie_import_results = kt_wie_import_data( $widget_data );

			// Import REV SLIDER
			if( class_exists('UniteFunctionsRev') && $hasrevslider == true ) {
				foreach( glob( $revslider_directory . '*.zip' ) as $filename ) {
					$filename = basename($filename);
					$rev_files[] = $revslider_directory . $filename;
				}

				foreach( $rev_files as $rev_file ) { // finally import rev slider data files
					$filepath = $rev_file;
					$slider = new RevSlider();
					ob_start();
					$slider->importSliderFromPost(true, true, $filepath);
					ob_end_clean();
				}
			}
			// Set reading options
			$homepage = get_page_by_title( $homepage_title );
			if(isset( $homepage ) && $homepage->ID) {
				update_option('show_on_front', 'page');
				update_option('page_on_front', $homepage->ID); // Front Page
			}

			echo $message;

			exit;
		}
		public function kadence_importer_virtue_ajax() {
		global $wpdb;
			if( !isset($_POST['demo_switch']) ) {
				$demo_switch = 'style01';
			} else {
				$demo_switch = $_POST['demo_switch'];
			}

			switch($demo_switch) {
				case "style02":
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/virtue/site_style_02/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/virtue/site_style_02/site_style_02.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/virtue/site_style_02/style_02_widgets.json';
					$homepage_title = 'Home';
				break;
				default:
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/virtue/site_style_01/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/virtue/site_style_01/site_style_01.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/virtue/site_style_01/style_01_widgets.json';
					$homepage_title = 'Home';
			}
			$message = "Imported";

			// Import Content
			$this->kadence_demo_import_file($theme_xml_file);

			//Woocommerce
			if( class_exists('Woocommerce') && $hasshop == true ) {
				$this->kadence_demo_woocommerce();
			}

			// Import Theme Options
			$this->kadence_demo_theme_options($theme_options_file, 'virtue');

			// Assign Imported Menus
			$menulocations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();

			if($menus) {
				foreach($menus as $menu) {
					if( $demo_switch == 'style01' ) {
						if( $menu->name == 'MainMenu1') {
							$menulocations['primary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'MainMenu21' ) {
							$menulocations['mobile_navigation'] = $menu->term_id;
						} else if( $menu->name == 'TopMenu1' ) {
							$menulocations['topbar_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Resources' ) {
							$menulocations['footer_navigation'] = $menu->term_id;
						}
					} else if ($demo_switch == 'style02') {
						if( $menu->name == 'MainMenu2') {
							$menulocations['primary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'TopMenu2' ) {
							$menulocations['topbar_navigation'] = $menu->term_id;
						} else if( $menu->name == 'SecondaryMenu2' ) {
							$menulocations['secondary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'MainMenu2' ) {
							$menulocations['mobile_navigation'] = $menu->term_id;
						}
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $menulocations );

			// Import Widgets
			if ( ! class_exists('Widget_Importer_Exporter') ) {
				$wp_widget_import = KT_IMPORTER_PATH . 'importer/widget_import.php';
				include $wp_widget_import;
			}
			$widget_data = file_get_contents( $widgets_file );
			$widget_data = json_decode( $widget_data );
			$wie_import_results = kt_wie_import_data( $widget_data );

			echo $message;

			exit;
		}
		public function kadence_importer_pinnacle_ajax() {
		global $wpdb;
			if( !isset($_POST['demo_switch']) ) {
				$demo_switch = 'style01';
			} else {
				$demo_switch = $_POST['demo_switch'];
			}

			switch($demo_switch) {
				default:
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/pinnacle/site_style_01/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/pinnacle/site_style_01/site_style_01.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/pinnacle/site_style_01/style_01_widgets.json';
					$homepage_title = 'Home';
			}
			$message = "Imported";

			// Import Content
			$this->kadence_demo_import_file($theme_xml_file);

			//Woocommerce
			if( class_exists('Woocommerce') && $hasshop == true ) {
				$this->kadence_demo_woocommerce();
			}

			// Import Theme Options
			$this->kadence_demo_theme_options($theme_options_file, 'pinnacle');

			// Assign Imported Menus
			$menulocations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();

			if($menus) {
				foreach($menus as $menu) {
					if( $demo_switch == 'style01' ) {
						if( $menu->name == 'Main1') {
							$menulocations['primary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Top1' ) {
							$menulocations['topbar_navigation'] = $menu->term_id;
						}
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $menulocations );

			// Import Widgets
			if ( ! class_exists('Widget_Importer_Exporter') ) {
				$wp_widget_import = KT_IMPORTER_PATH . 'importer/widget_import.php';
				include $wp_widget_import;
			}
			$widget_data = file_get_contents( $widgets_file );
			$widget_data = json_decode( $widget_data );
			$wie_import_results = kt_wie_import_data( $widget_data );

			echo $message;

			exit;

		}
		public function kadence_importer_pinnacle_premium_ajax() {

			if( !isset($_POST['demo_switch']) ) {
				$demo_switch = 'style01';
			} else {
				$demo_switch = $_POST['demo_switch'];
			}

			switch($demo_switch) {
				case 'style02':
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/pinnacle_premium/site_style_02/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/pinnacle_premium/site_style_02/site_style_02.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/pinnacle_premium/site_style_02/style_02_widgets.json';
					$hasrevslider = false;
					$homepage_title = 'Home';

				break;
				case 'style03':
					$hasshop = false;
					$theme_options_file = KT_IMPORTER_URL . '/importer/pinnacle_premium/site_style_03/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/pinnacle_premium/site_style_03/site_style_03.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/pinnacle_premium/site_style_03/style_03_widgets.json';
					$hasrevslider = false;
					$homepage_title = null;
				break;
				default:
					$hasshop = true;
					$theme_options_file = KT_IMPORTER_URL . '/importer/pinnacle_premium/site_style_01/theme_options.json';
					$theme_xml_file = KT_IMPORTER_PATH . 'importer/pinnacle_premium/site_style_01/site_style_01.xml.gz';
					$widgets_file = KT_IMPORTER_PATH . 'importer/pinnacle_premium/site_style_01/style_01_widgets.json';
					$hasrevslider = true;
					$revslider_directory = KT_IMPORTER_PATH . 'importer/pinnacle_premium/site_style_01/rev_sliders/';
					$homepage_title = 'Home';
			}
			$message = "Imported";

			// Import Content
			$this->kadence_demo_import_file($theme_xml_file);

			//Woocommerce
			if( class_exists('Woocommerce') && $hasshop == true ) {
				$this->kadence_demo_woocommerce();
			}

			// Import Theme Options
			$this->kadence_demo_theme_options($theme_options_file, 'pinnacle');

			// Assign Imported Menus
			$menulocations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();

			if($menus) {
				foreach($menus as $menu) {
					if( $demo_switch == 'style01' ) {
						if( $menu->name == 'Main1') {
							$menulocations['primary_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Top1' ) {
							$menulocations['topbar_navigation'] = $menu->term_id;
						}
					} elseif( $demo_switch == 'style02' ) {
						if( $menu->name == 'LeftMenu2' ) {
							$menulocations['left_navigation'] = $menu->term_id;
						} else if( $menu->name == 'RightMenu2' ) {
							$menulocations['right_navigation'] = $menu->term_id;
						} else if( $menu->name == 'MobileMenu2' ) {
							$menulocations['mobile_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Footer2' ) {
							$menulocations['footer_navigation'] = $menu->term_id;
						}
					} elseif( $demo_switch == 'style03' ) {
						if( $menu->name == 'MainMenu3' ) {
							$menulocations['topbar_navigation'] = $menu->term_id;
						} else if( $menu->name == 'MainMenu3' ) {
							$menulocations['mobile_navigation'] = $menu->term_id;
						}
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $menulocations );

			// Import Widgets
			if ( ! class_exists('Widget_Importer_Exporter') ) {
				$wp_widget_import = KT_IMPORTER_PATH . 'importer/widget_import.php';
				include $wp_widget_import;
			}
			$widget_data = file_get_contents( $widgets_file );
			$widget_data = json_decode( $widget_data );
			$wie_import_results = kt_wie_import_data( $widget_data );

			// Import REV SLIDER
			if( class_exists('UniteFunctionsRev') && $hasrevslider == true ) {
				foreach( glob( $revslider_directory . '*.zip' ) as $filename ) {
					$filename = basename($filename);
					$rev_files[] = $revslider_directory . $filename;
				}

				foreach( $rev_files as $rev_file ) { // finally import rev slider data files
					$filepath = $rev_file;
					$slider = new RevSlider();
					ob_start();
					$slider->importSliderFromPost(true, true, $filepath);
					ob_end_clean();
				}
			}
			// Set reading options
			$homepage = get_page_by_title( $homepage_title );
			if(isset( $homepage ) && $homepage->ID) {
				update_option('show_on_front', 'page');
				update_option('page_on_front', $homepage->ID); // Front Page
			} else {
				update_option('show_on_front', 'posts'); 
			}

			echo $message;

			exit;
		}

		
}
new Kadence_Importer();

require_once('wp-updates-plugin.php');
new WPUpdatesPluginUpdater_984( 'http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__));