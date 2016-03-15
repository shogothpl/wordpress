<div class="kt-updated kt-error importer-notice importer-notice-possible" style="display:none">
<p>
<strong><?php echo sprintf(__('Some Items may have not compleatly installed. Please reload the page and double check the imported data. If incorrect, please use %s and try again', 'kadence-importer'), '<a href="'.admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=wordpress-reset&amp;TB_iframe=true&amp;width=830&amp;height=472' ).'" class="thickbox" title="'.__('Reset WordPress plugin', 'kadence-importer').'">'.__('Reset WordPress plugin', 'kadence-importer').'</a>'); ?> 
</strong>
</p>
</div>

<div class="kt-updated importer-notice importer-notice-imported" style="display:none">
<p>
<strong><?php echo __('Demo data successfully imported.', 'kadence-importer'); ?></strong>
</p>
</div>

<div class="kt-updated kt-error importer-notice importer-notice-failed" style="display:none">
<p>
<strong><?php _e('Sorry but your import failed. Most likely, it cannot work with your webhost. You will have to ask your webhost to increase your PHP max_execution_time (or any other webserver timeout to at least 300 secs) and memory_limit (to at least 196M) temporarily.', 'kadence-importer'); ?></strong>
</p>
</div>
<div class="wrap kadence-importer-wrap">
<div id="kt_ajax_overlay">
<div class="ajaxnotice-kt"><?php echo __( 'Please Wait. This can take up to 10 minutes.', 'kadence-importer' ); ?></div>
</div>
    <h1><?php echo __( 'Kadence Themes Importer', 'kadence-importer' ); ?></h1>
    <h4 class="kt-subhead"><?php echo __( '*Please note using this importer will overide all your theme options setting.', 'kadence-importer' ); ?></h4>
    <div id="kt-results">&nbsp;</div>
    <?php 
    	$theme = $this->kt_themename();
    	switch($theme) {
    			case 'Virtue - Premium' :
    				if($this->kt_check_pagebuilder()) {
    					echo '<p class="kt-active-plugin">'.__('Page Builder Activated', 'kadence-importer').'</p>';
    				} else {
    					echo '<p class="kt-inactive-plugin">'.__('Page Builder Inactive.', 'kadence-importer').'<br>'.__('If you would like to install demo content with page builder elements please activate page builder', 'kadence-importer').'</p>';
    				}
    				if($this->kt_check_woocommerce()) {
    					echo '<p class="kt-active-plugin">'.__('Woocommerce Activated', 'kadence-importer').'</p>';
    				} else {
    					echo '<p class="kt-inactive-plugin kt-woonotice">'.__('Woocommerce Inactive.', 'kadence-importer').'<br>'.__('If you would like to install the shop demo please activate woocommerce', 'kadence-importer').'</p>';
    				}
    				if($this->kt_check_revslider()) {
    					echo '<p class="kt-active-plugin">'.__('Revolution Slider Activated', 'kadence-importer').'</p>';
    				} else {
    					echo '<p class="kt-inactive-plugin">'.__('Revolution Slider Inactive.', 'kadence-importer').'<br>'.__('If you would like to install the demo sliders activate the Revolution Slider.', 'kadence-importer').'</p>';
    				}

    				?>
    				<div class="import-action">
    				<h4 class="kt-choose"><?php echo __('Virtue Premium: Choose Your Demo Style', 'kadence-importer');?></h4>
    				<p class="sitelinks"><?php echo __('View Site Styles:', 'kadence-importer');?> 
    				<a href="http://themes.kadencethemes.com/virtue-premium/" target="_blank"><?php echo __('Site Style 01', 'kadence-importer');?></a> 
    				| <a href="http://themes.kadencethemes.com/virtue-premium-2/" target="_blank"><?php echo __('Site Style 02', 'kadence-importer');?></a> 
    				| <a href="http://themes.kadencethemes.com/virtue-premium-3/" target="_blank"><?php echo __('Site Style 03', 'kadence-importer');?></a> 
    				| <a href="http://themes.kadencethemes.com/virtue-premium-4/" target="_blank"><?php echo __('Site Style 04', 'kadence-importer');?></a>
    				</p>
    				<select class="demo-style">
					  <option value="style01"><?php echo __('Site Style 01', 'kadence-importer');?></option>
					  <option value="style02"><?php echo __('Site Style 02', 'kadence-importer');?></option>
					  <option value="style03"><?php echo __('Site Style 03', 'kadence-importer');?></option>
					  <option value="style04"><?php echo __('Site Style 04', 'kadence-importer');?></option>
					</select>
						<button id="kt-import-demo-vp" class="kt-import-demo-class button button-primary"><?php echo __('Import Demo Content', 'kadence-importer');?></button>
					</div>
					<?php 
    			
    			break;
    			case 'Pinnacle Premium' : 

    				if($this->kt_check_pagebuilder()) {
                        echo '<p class="kt-active-plugin">'.__('Page Builder Activated', 'kadence-importer').'</p>';
                    } else {
                        echo '<p class="kt-inactive-plugin">'.__('Page Builder Inactive.', 'kadence-importer').'<br>'.__('If you would like to install demo content with page builder elements please activate page builder', 'kadence-importer').'</p>';
                    }
                    if($this->kt_check_visualeditor()) {
                        echo '<p class="kt-active-plugin">'.__('Black Studio TinyMCE Widget Activated', 'kadence-importer').'</p>';
                    } else {
                        echo '<p class="kt-inactive-plugin">'.__('Black Studio TinyMCE Widget Inactive.', 'kadence-importer').'<br>'.__('If you would like to install demo content with page builder elements please activate Black Studio TinyMCE Widget', 'kadence-importer').'</p>';
                    }
                    if($this->kt_check_woocommerce()) {
                        echo '<p class="kt-active-plugin">'.__('Woocommerce Activated', 'kadence-importer').'</p>';
                    } else {
                        echo '<p class="kt-inactive-plugin kt-woonotice">'.__('Woocommerce Inactive.', 'kadence-importer').'<br>'.__('If you would like to install the shop demo please activate woocommerce', 'kadence-importer').'</p>';
                    }
                    if($this->kt_check_revslider()) {
                        echo '<p class="kt-active-plugin">'.__('Revolution Slider Activated', 'kadence-importer').'</p>';
                    } else {
                        echo '<p class="kt-inactive-plugin">'.__('Revolution Slider Inactive.', 'kadence-importer').'<br>'.__('If you would like to install the demo sliders activate the Revolution Slider.', 'kadence-importer').'</p>';
                    }
                    if($this->kt_check_kadenceslider()) {
                        echo '<p class="kt-active-plugin">'.__('Kadence Slider Activated', 'kadence-importer').'</p>';
                    } else {
                        echo '<p class="kt-inactive-plugin">'.__('Kadence Slider Inactive.', 'kadence-importer').'<br>'.__('If you would like to install the demo sliders activate the Kadence Slider.', 'kadence-importer').'</p>';
                    }

                    ?>
                    <div class="import-action">
                    <h4 class="kt-choose"><?php echo __('Pinnacle Premium: Choose Your Demo Style', 'kadence-importer');?></h4>
                    <p class="sitelinks"><?php echo __('View Site Styles:', 'kadence-importer');?> 
                    <a href="http://themes.kadencethemes.com/pinnacle-premium/" target="_blank"><?php echo __('Site Style 01', 'kadence-importer');?></a> 
                    | <a href="http://themes.kadencethemes.com/pinnacle-premium-2/" target="_blank"><?php echo __('Site Style 02', 'kadence-importer');?></a> 
                    | <a href="http://themes.kadencethemes.com/pinnacle-premium-3/" target="_blank"><?php echo __('Site Style 03', 'kadence-importer');?></a> 
                    </p>
                    <select class="demo-style">
                      <option value="style01"><?php echo __('Site Style 01', 'kadence-importer');?></option>
                      <option value="style02"><?php echo __('Site Style 02', 'kadence-importer');?></option>
                      <option value="style03"><?php echo __('Site Style 03', 'kadence-importer');?></option>
                    </select>
                        <button id="kt-import-demo-pp" class="kt-import-demo-class button button-primary"><?php echo __('Import Demo Content', 'kadence-importer');?></button>
                    </div>
                    <?php 
    			
    			break;
    			case 'Virtue' : 
    				if($this->kt_check_virtuetoolkit()) {
    					echo '<p class="kt-active-plugin">'.__('Virtue/Pinnacle Toolkit Activated', 'kadence-importer').'</p>';
    				} else {
    					echo '<p class="kt-inactive-plugin">'.__('Virtue/Pinnacle Toolkit Inactive.', 'kadence-importer').'<br>'.__('If you would like to install demo content with portfolio options please activate the toolkit.', 'kadence-importer').'</p>';
    				}
    				if($this->kt_check_woocommerce()) {
    					echo '<p class="kt-active-plugin">'.__('Woocommerce Activated', 'kadence-importer').'</p>';
    				} else {
    					echo '<p class="kt-inactive-plugin kt-woonotice">'.__('Woocommerce Inactive.', 'kadence-importer').'<br>'.__('If you would like to install the shop demo content please activate woocommerce.', 'kadence-importer').'</p>';
    				}

    				?>
    				<div class="import-action">
    				<h4 class="kt-choose"><?php echo __('Virtue: Choose Your Demo Style', 'kadence-importer');?></h4>
    				<p class="sitelinks"><?php echo __('View Site Styles:', 'kadence-importer');?>
    				<a href="http://themes.kadencethemes.com/virtue/" target="_blank"><?php echo __('Site Style 01', 'kadence-importer');?></a> 
    				| <a href="http://themes.kadencethemes.com/virtue2/" target="_blank"><?php echo __('Site Style 02', 'kadence-importer');?></a> 
    				</p>
    				<select class="demo-style">
					  <option value="style01"><?php echo __('Site Style 01', 'kadence-importer');?></option>
					  <option value="style02"><?php echo __('Site Style 02', 'kadence-importer');?></option>
					</select>
						<button id="kt-import-demo-v" class="kt-import-demo-class button button-primary"><?php echo __('Import Demo Content', 'kadence-importer');?></button>
					</div>
					<?php 
    			
    			break;
    			case 'Pinnacle' : 

    				if($this->kt_check_virtuetoolkit()) {
    					echo '<p class="kt-active-plugin">'.__('Virtue/Pinnacle Toolkit Activated', 'kadence-importer').'</p>';
    				} else {
    					echo '<p class="kt-inactive-plugin">'.__('Virtue/Pinnacle Toolkit Inactive. If you would like to install demo content with portfolio options please activate the toolkit.', 'kadence-importer').'</p>';
    				}
    				if($this->kt_check_woocommerce()) {
    					echo '<p class="kt-active-plugin">'.__('Woocommerce Activated', 'kadence-importer').'</p>';
    				} else {
    					echo '<p class="kt-inactive-plugin kt-woonotice">'.__('Woocommerce Inactive. If you would like to install the shop demo content please activate woocommerce.', 'kadence-importer').'</p>';
    				}

    				?>
    				<div class="import-action">
    				<h4 class="kt-choose"><?php echo __('Pinnacle: Choose Your Demo Style', 'kadence-importer');?></h4>
    				<p class="sitelinks"><?php echo __('View Site Styles:', 'kadence-importer');?>
    				<a href="http://themes.kadencethemes.com/pinnacle/" target="_blank"><?php echo __('Site Style 01', 'kadence-importer');?></a> 
    				</p>
    				<select class="demo-style">
					  <option value="style01"><?php echo __('Site Style 01', 'kadence-importer');?></option>
					</select>
						<button id="kt-import-demo-p" class="kt-import-demo-class button button-primary"><?php echo __('Import Demo Content', 'kadence-importer');?></button>
					</div>
					<?php 

    			break;
				default:
				
				echo __('No Kadence Theme activated. If you are using a child theme please activate the Parent Theme to import demo content.', 'kadence-importer');
	} ?>

<div class="import-warning">
	<p>
		<span style="color:#B94A48;">
			<?php echo __("Warning this can't be undone.", "kadence_importer");?>
		</span>
		<?php echo __("If you decide you want to completely remove everything on your site after testing out the demo content you can use this plugin:", "kadence_importer");?>
		<a href="https://wordpress.org/plugins/wordpress-reset/"><?php echo __("Wordpress Reset", "kadence_importer"); ?></a>.<br>
        <?php echo __("Also if you want to try a different demo after you've installed one it's best to use the reset plugin and clear your site before you install a second demo.", "kadence-importer"); ?>
	</p>
</div>
</div><!-- wrap -->