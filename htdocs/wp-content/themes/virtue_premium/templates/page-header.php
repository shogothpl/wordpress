
<div class="page-header">
                <?php
global $post;
//echo "pageid: ".$post->ID;
$postname=	get_post_meta($post->ID, 'Video_header_page', true);
$page = get_page_by_title($postname, OBJECT, 'post'); 
$post_id = $page->ID;
$queried_post = get_post($post_id);

 //echo $queried_post->post_content;
 //echo $queried_post->post_title; echo $post_id;
 
//echo do_shortcode( '[ajax_load_more post_type="post" category="news"]' );

//the_field('VIDEO-HEADER_title', $post_id);
//the_field('VIDEO-HEADER_description', $post_id);

$customtitle=get_field('VIDEO-HEADER_title', $post_id);
$customdescription=get_field('VIDEO-HEADER_description', $post_id);
$customp4file=get_field('VIDEO-HEADER_mp4_video_file', $post_id);
$customytlink=get_field('VIDEO-HEADER_youtube_link', $post_id);
$custombackground=get_field('VIDEO-HEADER_background_image', $post_id);

//echo $customtitle.$customdescription;
generate_banner($customtitle,$customdescription,$custombackground,$customp4file,$customytlink);
?>
  <?php global $post; 
  if(is_page()) {$bsub = get_post_meta( $post->ID, '_kad_subtitle', true ); if(!empty($bsub)) echo '<p class="subtitle"> '.__($bsub).' </p>'; }
   else if(is_category()) {  echo '<p class="subtitle">'.__(category_description()).' </p>';}
   	?>
</div>