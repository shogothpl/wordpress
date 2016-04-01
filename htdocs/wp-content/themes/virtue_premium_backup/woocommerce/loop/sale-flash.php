<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $virtue_premium;
if(isset($virtue_premium['outofstocktag']) && $virtue_premium['outofstocktag'] == 1) {

	$availability = $product->get_availability();

 	if ($availability['availability'] == __('Out of stock', 'woocommerce')) : 
 		if(!empty($virtue_premium['sold_placeholder_text'])) {$sold_text = $virtue_premium['sold_placeholder_text'];} else {$sold_text = __( 'Sold', 'virtue');} 
    	echo '<span class="onsale headerfont kad-out-of-stock">' . $sold_text . '</span>'; 

      elseif ($product->is_on_sale()) : 
        if(!empty($virtue_premium['sale_placeholder_text'])) {$sale_text = $virtue_premium['sale_placeholder_text'];} else {$sale_text = __( 'Sale!', 'virtue');} 
      echo apply_filters('woocommerce_sale_flash', '<span class="onsale bg_primary headerfont">'.$sale_text.'</span>', $post, $product); 
     endif; ?>

  <?php } elseif ($product->is_on_sale()) { ?>
  	<?php if(!empty($virtue_premium['sale_placeholder_text'])) {$sale_text = $virtue_premium['sale_placeholder_text'];} else {$sale_text = __( 'Sale!', 'virtue');} ?>
	<?php echo apply_filters('woocommerce_sale_flash', '<span class="onsale bg_primary headerfont">'.$sale_text.'</span>', $post, $product); ?>
<?php } ?>