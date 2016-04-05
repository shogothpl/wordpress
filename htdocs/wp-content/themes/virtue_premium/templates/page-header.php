
<div class="page-header">
                <?php
				global $post;
//echo "pageid: ".$post->ID;
			$postname=	get_post_meta($post->ID, 'Video_header_page', true);
			
			
$page = get_page_by_title($postname, OBJECT, 'post'); 
$post_id = $page->ID;
$queried_post = get_post($post_id);
?>
<p><?php echo $queried_post->post_content; ?></p>
<?php echo $queried_post->post_title; echo $post_id?>

<?php 
echo do_shortcode( '[ajax_load_more post_type="post" category="news"]' );
?>

<?php
the_field('VIDEO-HEADER_title', $post_id);
the_field('VIDEO-HEADER_description', $post_id);
$customtitle=get_field('VIDEO-HEADER_title', $post_id);
$customdescription=get_field('VIDEO-HEADER_description', $post_id);

//echo $customtitle.$customdescription;
generate_banner($customtitle,$customdescription,'','','');
?>
  <?php global $post; 
  if(is_page()) {$bsub = get_post_meta( $post->ID, '_kad_subtitle', true ); if(!empty($bsub)) echo '<p class="subtitle"> '.__($bsub).' </p>'; }
   else if(is_category()) {  echo '<p class="subtitle">'.__(category_description()).' </p>';}
   	?>
</div>