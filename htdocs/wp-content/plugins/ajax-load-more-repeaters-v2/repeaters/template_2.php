<div class="page-header">
                  <div class="header-video" style="width: 1070px;">
      <?php if ( has_post_thumbnail() ) { 
      the_post_thumbnail(array(1280,750));
   }?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <p> <?php the_excerpt(); ?> </p></div><div style="float: none; clear:none;"></div>  </div>