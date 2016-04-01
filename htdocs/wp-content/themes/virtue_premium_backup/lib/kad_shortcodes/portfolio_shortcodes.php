<?php 
//Shortcode for portfolio Posts
function kad_portfolio_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'orderby' => 'menu_order',
		'cat' => '',
		'order' => '',
		'offset' => null,
		'id' => (rand(10,100)),
		'columns' => '4',
		'lightbox' => 'false',
		'height' => '',
		'width' => '',
		'isostyle' => 'masonry',
		'filter' => 'false',
		'excerpt' => 'false',
		'showtypes' => 'true',
		'items' => '4'
), $atts));
	if(!empty($order) ) {
		$order = $order;
	} else if($orderby == 'menu_order' || $orderby == "title") {
		$order = 'ASC';
	} else {
		$order = 'DESC';
	} 
	if(empty($cat)) {
		$cat = '';
		$portfolio_cat_ID = '';
	} else {
		$portfolio_cat = get_term_by ('slug',$cat,'portfolio-type' );
		$portfolio_cat_ID = $portfolio_cat -> term_id;
	}
	if ($columns == '2') {
		$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';
		$slidewidth = 560;
		$slideheight = 560;
	} else if ($columns == '1') {
		$itemsize = 'tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; 
		$slidewidth = 560; 
		$slideheight = 560;
	} else if ($columns == '3'){
		$itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
		$slidewidth = 366;
		$slideheight = 366;
	} else if ($columns == '6'){
		$itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
		$slidewidth = 240;
		$slideheight = 240;
	} else if ($columns == '5'){
		$itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
		$slidewidth = 240;
		$slideheight = 240;
	} else {
		$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';
		$slidewidth = 270;
		$slideheight = 270;
	}
	if(!empty($height) && $height == 'none') {
		$slideheight = null;
	} else if(!empty($height)) {
		$slideheight = $height;
	}
	if(!empty($width)){
		$slidewidth = $width;
	}
	global $virtue_premium, $kt_portfolio_loop; 
	if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {
		$animate = 1;
	} else {
		$animate = 0;
	}
                 $kt_portfolio_loop = array(
                 	'lightbox' => $lightbox,
                 	'showexcerpt' => $excerpt,
                 	'showtypes' => $showtypes,
                 	'slidewidth' => $slidewidth,
                 	'slideheight' => $slideheight,
                 	);
ob_start(); ?>
	<?php if ($filter == "true") { ?>
      	<section id="options" class="clearfix">
			<?php global $virtue_premium; 
			if(!empty($virtue_premium['filter_all_text'])) {
				$alltext = $virtue_premium['filter_all_text'];
			} else {
				$alltext = __('All', 'virtue');
			}
			if(!empty($virtue_premium['portfolio_filter_text'])) {
				$portfoliofiltertext = $virtue_premium['portfolio_filter_text'];
			} else {
				$portfoliofiltertext = __('Filter Projects', 'virtue');
			}
			$termtypes = array( 'child_of' => $portfolio_cat_ID,);
			$categories= get_terms('portfolio-type', $termtypes);
			$count = count($categories);
			
						echo '<a class="filter-trigger headerfont" data-toggle="collapse" data-target=".filter-collapse"><i class="icon-tags"></i> '.$portfoliofiltertext.'</a>';
						echo '<ul id="filters" class="clearfix option-set filter-collapse">';
						echo '<li class="postclass"><a href="#" data-filter="*" title="All" class="selected"><h5>'.$alltext.'</h5><div class="arrow-up"></div></a></li>';
						if ( $count > 0 ){
							foreach ($categories as $category){ 
								$termname = strtolower($category->slug);
								$termname = preg_replace("/[^a-zA-Z 0-9]+/", " ", $termname);
								$termname = str_replace(' ', '-', $termname);
									echo '<li class="postclass"><a href="#" data-filter=".'.esc_attr($termname).'" title="" rel="'.esc_attr($termname).'"><h5>'.$category->name.'</h5><div class="arrow-up"></div></a></li>';
								}
				 		}
				 		echo "</ul>"; ?>
			</section>
            <?php } ?>
				<div class="home-portfolio">
						<div id="portfoliowrapper-<?php echo esc_attr($id);?>" class="rowtight init-isotope" data-fade-in="<?php echo esc_attr($animate);?>" data-iso-selector=".p-item" data-iso-style="<?php echo esc_attr($isostyle);?>" data-iso-filter="true"> 
            <?php $wp_query = null; 
				  $wp_query = new WP_Query();
					  $wp_query->query(array(
					  	'orderby' 			=> $orderby,
					  	'order' 			=> $order,
					  	'offset' 			=> $offset,
					  	'post_type' 		=> 'portfolio',
					  	'portfolio-type'	=> $cat,
					  	'posts_per_page' 	=> $items
					  	)
					  );
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

				<?php global $post; $terms = get_the_terms( $post->ID, 'portfolio-type' );
						if ( $terms && ! is_wp_error( $terms ) ) : 
							$links = array();
								foreach ( $terms as $term ) { $links[] = $term->slug;}
							$links = preg_replace("/[^a-zA-Z 0-9]+/", " ", $links);
							$links = str_replace(' ', '-', $links);	
							$tax = join( " ", $links );		
						else :	
							$tax = '';	
						endif;
						?>
				
				<div class="<?php echo esc_attr($itemsize);?> <?php echo strtolower($tax); ?> all p-item">
                	<?php do_action('kadence_portfolio_loop_start');
								get_template_part('templates/content', 'loop-portfolio'); 
						  		do_action('kadence_portfolio_loop_end');
							?>
            </div>
			<?php endwhile; else: ?>
				<li class="error-not-found"><?php _e('Sorry, no portfolio entries found.', 'virtue');?></li>
			<?php endif; ?>
          	</div> <!-- portfoliowrapper -->
            <?php $wp_query = null; wp_reset_query(); ?>
		</div><!-- /.home-portfolio -->

	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}