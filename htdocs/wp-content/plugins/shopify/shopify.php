<?php
/*
Plugin Name: Shopify Importer
Plugin URI: http://www.seodenver.com/shopify-importer/
Description: Import products from a <a href="http://www.shopify.com/?ref=katz-web-services" rel="nofollow">Shopify.com</a> store as WordPress posts or pages
Author: Katz Web Services, Inc.
Version: 1.0
Author URI: http://www.katzwebservices.com
*/

add_action('admin_init', 'kws_shopify_init');

function kws_shopify_init() {
	global $pagenow;
	
	if($pagenow == 'import.php' || ($pagenow == 'admin.php' && isset($_REQUEST['import']) && $_REQUEST['import'] == 'shopify')) {
	
		if ( !defined('WP_LOAD_IMPORTERS') )
			return;
		
		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';
		
		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) )
				require_once $class_wp_importer;
		}
		
		require_once(ABSPATH.'wp-admin/includes/import.php');
		if(function_exists('register_importer')) {
			load_shopify_class();
			$Shopify_Import = new Shopify_Import();
			register_importer('shopify', __('Shopify'), __('Import product data from a Shopify CSV export file.'), array ($Shopify_Import, 'dispatch'));
		}
	}
	return;
}

function load_shopify_class() {
	if ( class_exists( 'WP_Importer' ) ) {
	class Shopify_Import extends WP_Importer {
	
		var $posts = array ();
		var $file;
		var $log = array();
		var $defaults = array(
			'shopify_importer_import_as_draft' => 'publish',
			'shopify_importer_import_type' => 'post',
			'shopify_importer_import_categories' => 'no',
	        'Handle' => null,
	        'Title' => null,
	        'Body (HTML)' => null,
	        'Vendor' => null,
	        'Type' => null,
	        'Tags' => null,
	        'Option1 Name' => null,
	        'Option1 Value' => null,
	        'Option2 Name' => null,
	        'Option2 Value' => null,
	        'Option3 Name' => null,
	        'Option3 Value' => null,
		    'Variant SKU' => null,
		    'Variant Grams' => null,
		    'Variant Inventory Tracker' =>  null,
		    'Variant Inventory Qty' => null,
		    'Variant Inventory Policy' => null,
		    'Variant Fulfillment Service' => null,
		    'Variant Price' => null,
		    'Variant Compare At Price' => null,
		    'Variant Requires Shipping' => null,
		    'Variant Taxable' => true,
		    'Image Src' => null
	    );
		
		
		function header() {
			echo '<div class="wrap">';
			screen_icon();
			echo '<h2>'.__('Import Products from Shopify').'</h2>';
		}
	
		function footer() {
			echo '</div>';
		}
	
		function greet() {
			$out = '';
			$out .= '<div class="narrow">';
			$out .= '<p>'.__('This importer allows you to <strong>extract products as posts</strong> from a Shopify CSV product export file into your blog. Lots of additional product data will be imported and added to products as <a href="http://codex.wordpress.org/Using_Custom_Fields">Custom Fields</a>. Update your template to access this information.').'</p>';
			$out .= '<ul class="ul-disc">';
			$out .= '<li>'.__('<strong>If a product does not yet exist</strong>, it will be created.').'</li>';
			$out .= '<li>'.__('<strong>If a product already exists</strong>, it will be updated.').'</li>';
			$out .= '</ul>';
			echo $out;
			$this->import_upload_form("admin.php?import=shopify&amp;step=1");
			echo '</div>';
		}
	
		function import_upload_form( $action ) {
		    $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
		    $size = wp_convert_bytes_to_hr( $bytes );
		    $upload_dir = wp_upload_dir();
		    if ( ! empty( $upload_dir['error'] ) ) :
		        ?><div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:'); ?></p>
		        <p><strong><?php echo $upload_dir['error']; ?></strong></p></div><?php
		    else :
		?>
		<div class="widefat form-table">
			<div class="wrap">
				<h3>Import Shopify .csv</h3>
				<form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo esc_attr(wp_nonce_url($action, 'import-upload')); ?>">
				<p>
			        <input name="shopify_importer_import_as_draft" type="hidden" value="publish" />
			        <label><input name="shopify_importer_import_as_draft" type="checkbox" <?php if (isset($_POST['shopify_importer_import_as_draft']) && $_POST['shopify_importer_import_as_draft'] == 'draft') { echo 'checked="checked"'; } ?> value="draft" /> Import products as draft <span class="description">(Default: Import products as published)</span></label>
			    </p>
			    <p>
			        <input name="shopify_importer_import_type" type="hidden" value="post" />
			        <label><input name="shopify_importer_import_type" type="checkbox" <?php if (isset($_POST['shopify_importer_import_type']) && $_POST['shopify_importer_import_type'] == 'page') { echo 'checked="checked"'; } ?> value="page" /> Import products as pages <span class="description">(Default: Import products as posts)</span></label>
			    </p>
			    <p>
			        <input name="shopify_importer_import_categories" type="hidden" value="no" />
			        <label><input name="shopify_importer_import_categories" type="checkbox" <?php if (isset($_POST['shopify_importer_import_categories']) && $_POST['shopify_importer_import_categories'] == 'yes') { echo 'checked="checked"'; } ?> value="yes" /> Import Vendors as categories (<strong>Note: </strong>"<em>Import as posts</em>" only)</label>
			    </p>
				<p>
					<label for="upload"><?php _e( 'Choose a .csv file from your computer:' ); ?> <small class="description">(<?php printf( __('Maximum size: %s' ), $size ); ?>)</small><br /><input type="file" id="upload" name="import" size="25" /></label>
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
				</p>
				<p class="submit"><input type="submit" class="submit button-primary" value="Upload file and import" /></p>
				</form>
			</div>
		</div>
		<?php
		    endif;
		}
	
		function import() {
			$file = wp_import_handle_upload();
			
			if ( isset($file['error']) ) {
				echo $file['error'];
				return;
			}
			
			$result = $this->post($file);
			
			if ( is_wp_error( $result ) )
				return $result;
			
			wp_import_cleanup($file['id']);
			do_action('import_done', 'Shopify');
			echo '<h3>';
			printf(__('All done. <a href="%s">Have fun!</a>'), get_option('home'));
			echo '</h3>';
		}
		
		// Handle POST submission
	    function post($file) {
	    	$output = '';
	    	$file = $file['file'];
	       if (empty($file)) {
	          $this->log['error'][] = 'No file uploaded, aborting.';
	          $messages = $this->print_messages();
	          echo $messages[0];
	          echo $messages[1];
	          return;
	      }
			$output = '<ol>';
			
			// Load the CSV processor
			require_once('CSVParser.php');
			
			$time_start = microtime(true);
			$csv = new File_CSV_DataSource;
			$this->stripBOM($file);
			
			if (!$csv->load($file)) {
			    $this->log['error'][] = 'Failed to load file, aborting.';
			    $messages = $this->print_messages();
		          echo $messages[0];
		          echo $messages[1];
			    return;
			}
			
			// pad shorter rows with empty values
			$csv->symmetrize();
			
			// WordPress sets the correct timezone for date functions somewhere
			// in the bowels of wp_insert_post(). We need strtotime() to return
			// correct time before the call to wp_insert_post().
			$tz = get_option('timezone_string');
			if ($tz && function_exists('date_default_timezone_set')) {
			    date_default_timezone_set($tz);
			}
			
			$skipped = 0;
			$imported = 0;
			$comments = 0;
			foreach ($csv->connect() as $csv_data) {
			    if ($output .= $this->create_post($csv_data)) {
			        $imported++;
			    } else {
			        $skipped++;
			    }
			}
			
			if (file_exists($file)) {
			    @unlink($file);
			}
			
			$exec_time = microtime(true) - $time_start;
			
			if ($skipped) {
			    $this->log['notice'][] = "<b>Skipped {$skipped} posts (most likely due to empty title, body and excerpt).</b>";
			}
			$this->log['notice'][] = sprintf("<b>Imported {$imported} posts and {$comments} comments in %.2f seconds.</b>", $exec_time);
	       $output .= '</ol>';
	       
	       $messages = $this->print_messages();
	       $output = $messages[0].$messages[1].$output;
	       echo $output;
	    }
	    
	    function create_post($data) {
	    	$output = "<li>".__('Importing post...');
	        $data = array_merge($this->defaults, $data);

	        $status = (isset($_POST['shopify_importer_import_as_draft']) && $_POST['shopify_importer_import_as_draft'] == ('publish' || 'draft')) ? $_POST['shopify_importer_import_as_draft'] : $$this->defaults['shopify_importer_import_as_draft'];
	        
	        $type = (isset($_POST['shopify_importer_import_type']) && $_POST['shopify_importer_import_type'] == ('post' || 'page')) ? $_POST['shopify_importer_import_type'] : $$this->defaults['shopify_importer_import_type'];
			
	        $new_post = array(
	            'post_name' => sanitize_title($data['Handle']), // Slug
	            'post_title' => convert_chars($data['Title']),
	            'post_content' => wpautop(convert_chars($data['Body (HTML)'])),
	            'post_status' => $status,
	            'post_type' => $type
	        );
	        
	        // We don't need to store this in the Custom Meta
	        unset($data['Body (HTML)']);
	        
			// pages don't have tags or categories
	        if ('page' !== $type) {
	            $new_post['tags_input'] = $data['Tags'];
	        }

			if($id = post_exists($new_post['post_title'], $new_post['post_content'])) {
			
				$new_post['ID'] = (int)$id;
				
				// Update Post
	        	$id = wp_update_post($new_post);
			
				if ( is_wp_error( $id ) )
					return $id;
				if (!$id) {
					$output .= "Couldn't get post ID";
					return;
				}
			
				// Add Custom Fields
				foreach($data as $key => $value) { update_post_meta($id, sanitize_user('Shopify '.$key), esc_attr($value)); }
				
				$output .= 'Updated !'. ' <a href="'.get_permalink($id).'">View '.$data['Title'].'</a>';
			} else { // A post does not yet exist.
	        	
	        	// Create Post
	        	$id = wp_insert_post($new_post);
			
				// Add Custom Fields
				foreach($data as $key => $value) { add_post_meta($id, sanitize_user('Shopify '.$key), esc_attr($value)); }
				$output .= 'Done !'. ' <a href="'.get_permalink($id).'">View '.$data['Title'].'</a>';				
			}
			
			// If you want to import categories, here we go!
			if(isset($_POST['shopify_importer_import_categories']) && $_POST['shopify_importer_import_categories'] == 'yes') {
				$categories = explode(',', $data['Vendor']);
				if(0 != count($categories)) { wp_create_categories($categories, $id); }
			}
			
			$output .= '</li>';

	        return $output;
	    }
	 
	    
		// delete BOM from UTF-8 file
	    function stripBOM($fname) {
	        $res = fopen($fname, 'rb');
	        if (false !== $res) {
	            $bytes = fread($res, 3);
	            if ($bytes == pack('CCC', 0xef, 0xbb, 0xbf)) {
	                $this->log['notice'][] = 'Getting rid of byte order mark...';
	                fclose($res);
	
	                $contents = file_get_contents($fname);
	                if (false === $contents) {
	                    trigger_error('Failed to get file contents.', E_USER_WARNING);
	                }
	                $contents = substr($contents, 3);
	                $success = file_put_contents($fname, $contents);
	                if (false === $success) {
	                    trigger_error('Failed to put file contents.', E_USER_WARNING);
	                }
	            } else {
	                fclose($res);
	            }
	        } else {
	            $this->log['error'][] = 'Failed to open file, aborting.';
	        }
	    }
		function dispatch() {
			if (empty ($_GET['step']))
				$step = 0;
			else
				$step = (int) $_GET['step'];
	
			$this->header();
	
			switch ($step) {
				case 0 :
					$this->greet();
					break;
				case 1 :
					check_admin_referer('import-upload');
					$result = $this->import();
					if ( is_wp_error( $result ) )
						echo $result->get_error_message();
					break;
			}
	
			$this->footer();
		}
		
		function print_messages() {
			$errors = $notices = '';
			if (!empty($this->log)) {
				if (!empty($this->log['error'])) {
			
					$errors =  '<div class="error">';
				
				    foreach ($this->log['error'] as $error) {
				       $errors .= '<p>'.$error.'</p>';
				    }
				    $errors .= '</div>';
				}
						
			    if (!empty($this->log['notice'])) {
			
			    $notices = '<div class="updated fade">';
			        foreach ($this->log['notice'] as $notice) {
			            $notices .= '<p>'.$notice.'</p>';
			        }
			    $notices .= '</div>';
			
			    }
			}
			return(array($errors,$notices));
			$this->log = array();
	    }
	    
		function Shopify_Import() {
			// Nothing.
		}
	}
	}
}
?>