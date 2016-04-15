<div class="col-md-4">
<div class="news-aligncenter">
   <?php if ( has_post_thumbnail() ) { 
      the_post_thumbnail(array(300,300));
   }?>
   <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
   <p class="entry-meta">
       <?php the_time("F d, Y"); ?>
   </p>
   <?php the_excerpt(); ?> 
</div>
</div>