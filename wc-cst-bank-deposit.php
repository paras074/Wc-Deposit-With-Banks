<?php
/**
 * Plugin Name: WC Bank Deposit
 * Plugin URI:  https://perfectwebservices.com/
 * Description: Each product amount based percentage set and by country based .
 * Version:     1.0.6
 * Author:      Perfect Web Services
 * WC tested up to:      5.5
 */

/**
 * Check if WooCommerce is active
 */
 if ( ! defined( "CUSTOM_DIR_PATH" ) ) {
    define( "CUSTOM_DIR_PATH", plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CUSTOM_DIR_URL' ) ) {
    define( 'CUSTOM_DIR_URL', plugin_dir_url( __FILE__ ) );
}

 add_action("init","cb_backend");
 function cb_backend(){
   wp_enqueue_style( 'bank-style', CUSTOM_DIR_URL . 'css/ct-bank-style.css' );
   //wp_enqueue_script( 'bank-js', CUSTOM_DIR_URL . 'js/ct-script.js' );
}
add_action("admin_init","admin_cb_backend");
 function admin_cb_backend(){
   wp_enqueue_style( 'admin-bank-style', CUSTOM_DIR_URL . 'css/ad-ct-bank-style.css' );
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text_new' ); 
function woocommerce_custom_single_add_to_cart_text_new() {
	
	global $product;
	 $selected_country  = wcpbc_get_woocommerce_country();
 $country= WC()->countries->countries[ $selected_country];
	  if ( 'SG' == $selected_country ) {
		 $base_price = get_post_meta($product->get_id(), '_singapore_price', true);
		 $webprice_part = get_post_meta($product->get_id(), '_singapore_price', true);
		 if( $product->is_on_sale() ) {
			 $shop_price_full = get_post_meta($product->get_id(), '_shop_sale_price', true);
		 }else{
		$shop_price_full = get_post_meta($product->get_id(), '_shop_price', true);	 
		 }
		 
	
	$total='';

if($base_price < 1000){
	$percent='';
}elseif($base_price > '1000' && $base_price <= '10000' ){
	$percent='5';
	$full_amount=$shop_price_full;
}elseif($base_price > '10000' ){
	$percent='2';
	$full_amount=$shop_price_full;
} 
 $total =  ( $percent/100) * $base_price;

 $due_deposit=$base_price - $total; 
	if(!empty($percent)){
$unit = intval( $base_price ); 
 $decimal = sprintf( '%02d', ( $base_price-$unit ) * 100 ); 		
	return  __(  get_woocommerce_currency_symbol().''.number_format($base_price,2) , 'woocommerce' ); 
	}
	
	  }else{
		
		$_product = wc_get_product( $product->get_id() );

 $base_price_sg = get_post_meta($product->get_id(), '_singapore_price', true);
$base_price = $_product->get_price();
		$unit = intval( $base_price ); 
 $decimal = sprintf( '%02d', ( $base_price-$unit ) * 100 ); 
		if( $product->is_on_sale() ) {
			 $shop_price_full = get_post_meta($product->get_id(), '_shop_sale_price', true);
		 }else{
		$shop_price_full = get_post_meta($product->get_id(), '_shop_price', true);	 
		 }
$full_amount='';
if($base_price_sg < 30000){
	$percent='';
}elseif($base_price_sg > '30000' ){
	$percent='2';
}
 $total =  ( $percent/100) * $base_price;
$due_deposit=$base_price - $total;
if(!empty($percent)){
	
return __(  get_woocommerce_currency_symbol().''.number_format($base_price,2), 'woocommerce' ); 
}	

}
if($total==''){
	return __( get_woocommerce_currency_symbol().''.number_format($base_price,2), 'woocommerce' ); 
}

}
/*
 * Content before "Add to cart" Button.
 */
 add_action( 'woocommerce_before_add_to_cart_button', 'add_content_before_addtocart_button_func' );
 function add_content_before_addtocart_button_func(){
	global $product;
	 $selected_country  = wcpbc_get_woocommerce_country();
 $country= WC()->countries->countries[ $selected_country];
	  if ( 'SG' == $selected_country ) {
$base_price = get_post_meta($product->get_id(), '_singapore_price', true);
		 $webprice_part = get_post_meta($product->get_id(), '_singapore_price', true);
		
		if( $product->is_on_sale() ) {
			 $shop_price_full = get_post_meta($product->get_id(), '_shop_sale_price', true);
		 }else{
		$shop_price_full = get_post_meta($product->get_id(), '_shop_price', true);	 
		 }
	
	$full_amount='';
$full='yes';
if($base_price <= 1000){
	$percent='';
}elseif($base_price > '1000' && $base_price <= '10000' ){
	$percent='5';
	$full_amount=$shop_price_full;
}elseif($base_price > '10000' && $base_price <= '50000' ){
	$percent='2';
	$full_amount=$shop_price_full;
}elseif($base_price > '50000' ){
	$percent='2';
	$full_amount=$shop_price_full;
	$full='no';
} 
 $total =  ( $percent/100) * $base_price;

 $due_deposit=$base_price - $total; 
$html='';
if(!empty($percent)){
	echo "<div class='custm_payment_s'>
		<h4>Payment Options</h4> 
		<p>".$percent."% credit card deposit with balance by bank transfer:</p>
	 </div>";
}else{
	echo "<div class='custm_payment_s'>
				 
				<p>Full Credit Card Payment:</p></div>"; 
}
	  }else{
		 $_product = wc_get_product( $product->get_id() );
		$base_price = $_product->get_price();
		 $base_price_sg = get_post_meta($product->get_id(), '_singapore_price', true);
		if( $product->is_on_sale() ) {
			 $shop_price_full = get_post_meta($product->get_id(), '_shop_sale_price', true);
		 }else{
		$shop_price_full = get_post_meta($product->get_id(), '_shop_price', true);	 
		 }
		$full_amount='';
		$full_text='';
		if($base_price_sg <= 30000){
			$percent='';
			$full_text='Full Credit Card Payment:';
		}elseif($base_price_sg > '30000' ){
			$percent='2';
		}
		$total =  ( $percent/100) * $base_price;
		$due_deposit=$base_price - $total;
		if(!empty($percent)){  
				 echo "<div class='custm_payment_s'>
				<h4>Payment Options</h4> 
				<p>".$percent."% credit card deposit with balance by bank transfer:</p>
			 </div>"; 
				  
				  
			  }
			  if(!empty($full_text)){
				   echo "<div class='custm_payment_s'>
				 
				<p>".$full_text."</p></div>"; 
				  
			  }
	 
 }
 
 }
add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart_button_func' );
function add_content_after_addtocart_button_func() {
	global $product;
	 $selected_country  = wcpbc_get_woocommerce_country();
 $country= WC()->countries->countries[ $selected_country];
	  if ( 'SG' == $selected_country ) {
		 $base_price = get_post_meta($product->get_id(), '_singapore_price', true);
		 $webprice_part = get_post_meta($product->get_id(), '_singapore_price', true);
		if( $product->is_on_sale() ) {
			$shop_price_full = get_post_meta($product->get_id(), '_shop_sale_price', true);	
			/* if(empty(get_post_meta($product->get_id(), '_shop_sale_price', true))){
				$shop_price_full = $base_price;	
			} */
		 }else{
		 $shop_price_full = get_post_meta($product->get_id(), '_shop_price', true);	 
		 }
	
	$full_amount='';
   $full='yes';
if($base_price <= 1000){
	$percent='';
}elseif($base_price > '1000' && $base_price <= '10000' ){
	$percent='5';
	$full_amount=$shop_price_full;
}elseif($base_price > '10000' && $base_price <= '50000' ){
	$percent='2';
	$full_amount=$shop_price_full;
}elseif($base_price > '50000' ){
	$percent='2';
	$full_amount=$shop_price_full;
	 $full='no';
} 
 $total =  ( $percent/100) * $base_price;

 $due_deposit=$base_price - $total; 
$html='';
if(!empty($percent)){ 
if($full=='yes'){
	 $style="display:block";
	}else{
	 $style="display:none";
	}
$unit = intval( $full_amount ); 
 $decimal = sprintf( '%02d', ( $full_amount-$unit ) * 100 );     // Echo content.
        $html .= '<div class="bnk-pp-payment-btn-wraper">
                <input type="hidden" name="deposit_percent" value="'.$percent.'">
                <input type="hidden" id="deposit_full_amount" name="deposit_full_amount" value="'.number_format($base_price,2).'">
                <input type="hidden" name="current_deposit" value="'.number_format($total,2).'">
                <input type="hidden" name="due_deposit" value="'.number_format($due_deposit,2).'">
				 <input type="hidden" id="deposit_mode_val" name="deposit_mode" value="by_deposit_partial">
				 <input type="hidden" id="deposit_shop_full" name="deposit_shop_full" value="'.number_format($full_amount,2).'">
				<ul class="bnk-pp-payment-terms">';
								
				$html .='<li  class="part_pay_text">
				<label for="bnk_pp_partial_payment">
				<p>Our bank account details will be sent via email within 24 hours.<!--span class="payment_amount">'. get_woocommerce_currency_symbol().''.$total.'</span--></p>
				</label>
				</li>';
				$html .= '<li style="'.$style.'" class="full_pay_text">
				<label for="bnk_pp_full_payment">
				<span class="or_text">OR</span><span id="cst_text_sp">Full Credit Card Payment:</span><strong><a href="javascript:void(0)" id="by_deposit_partial_full"> '.get_woocommerce_currency_symbol().''.number_format($full_amount,2).' </a> </strong> 
				</label>
				</li>';
				$html .='</ul> </div> ';
		
   echo $html;
   }
	 }else{
		$_product = wc_get_product( $product->get_id() );
		$base_price = $_product->get_price();
		$base_price_sg = get_post_meta($product->get_id(), '_singapore_price', true);
		if( $product->is_on_sale() ) {
			 $shop_price_full = get_post_meta($product->get_id(), '_shop_sale_price', true);
			/* if(empty(get_post_meta($product->get_id(), '_shop_sale_price', true))){
				$shop_price_full = $base_price;	
			} */
		 }else{
		$shop_price_full = get_post_meta($product->get_id(), '_shop_price', true);	 
		 }
$full_amount='';
if($base_price_sg <= 30000){
	$percent='';
	$full_amount=$shop_price_full;
}elseif($base_price_sg > '30000' ){
	$percent='2';
	
}

 $total =  ( $percent/100) * $base_price;
$due_deposit=$base_price - $total;
if(!empty($percent)){ 

    // Echo content.
        echo '<div class="bnk-pp-payment-btn-wraper">
                
                <input type="hidden" name="deposit_percent" value="'.$percent.'">
                <input type="hidden" id="deposit_full_amount" name="deposit_full_amount" value="'.number_format($base_price,2).'">
                <input type="hidden" name="current_deposit" value="'.number_format($total,2).'">
                <input type="hidden" name="due_deposit" value="'.number_format($due_deposit,2).'">
                <input type="hidden" id="deposit_mode_val" name="deposit_mode" value="by_deposit_partial">
                <ul class="bnk-pp-payment-terms">
				<li class="part_pay_text">
				<label for="bnk_pp_partial_payment">
				<p><span class="textsimple">Our bank account details will be sent via email within 24 hours.</span> <!--span class="payment_amount">'. get_woocommerce_currency_symbol().''.$total.'</span--></p>
				</label></li></ul></div>';
				
   }	 
	 }
 
   
}

function wpdocs_your_function() {
    if ( is_admin() ) {
        return;
    }
 
 ?>
 <script>
 jQuery(document).on('click', '#bnk_pp_partial_payment', function () {
        jQuery('.mep-product-payment-plans').slideDown(200);
    });
   jQuery(document).on('click', '#by_deposit_partial_full', function () {
	   jQuery('#deposit_mode_val').val('by_deposit_partial_full');
	   jQuery('#deposit_full_amount').val(jQuery('#deposit_shop_full').val());
	    jQuery(".single_add_to_cart_button").trigger("click");
        //jQuery('.mep-product-payment-plans').slideDown(200);
    });
    jQuery(document).on('click', '#bnk_pp_full_payment', function () {
        jQuery('.mep-product-payment-plans').slideUp(200);
    });
	</script>
 <?php
}
 
add_action( 'wp_footer', 'wpdocs_your_function' );

function add_percentage_value_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
WC()->session->set('enable_api_discount_partial',false);
WC()->session->set('enable_api_discount_full',false);
WC()->session->set('enable_api_discount_other_country',false);
	$deposit_mode = filter_input( INPUT_POST, 'deposit_mode' );
	$deposit_percent = filter_input( INPUT_POST, 'deposit_percent' );
	$current_deposit = filter_input( INPUT_POST, 'current_deposit' );
	$due_deposit = filter_input( INPUT_POST, 'due_deposit' );
	$deposit_full_amount = filter_input( INPUT_POST, 'deposit_full_amount' );

	
	$cart_item_data['deposit_mode'] = $deposit_mode;
	$cart_item_data['deposit_full_amount'] = $deposit_full_amount;
	$cart_item_data['product_amount'] = ($current_deposit + $due_deposit) ;
	$cart_item_data['deposit_percent'] = $deposit_percent;
	$cart_item_data['current_deposit'] = $current_deposit;
	$cart_item_data['due_deposit'] = $due_deposit;

	return $cart_item_data;
}

add_filter( 'woocommerce_add_cart_item_data', 'add_percentage_value_to_cart_item', 10, 3 );
// Set custom cart item price
add_action( 'woocommerce_cart_calculate_fees','custom_tax_surcharge_for_swiss', 10, 1 );
function custom_tax_surcharge_for_swiss( $cart ) {
    if ( is_admin() && ! defined('DOING_AJAX') ) return;
 $selected_country  = wcpbc_get_woocommerce_country();
$country= WC()->countries->countries[ $selected_country];
/*  echo "<pre>";
print_r($cart); 
echo "</pre>"; */
	foreach($cart->cart_contents as $cartdata ){
		$deposit_mode=$cartdata['deposit_mode'];
		$product_id = $cartdata['product_id'];
	}
   
    
//if(  $value["deposit_mode"]== 'by_deposit_partial')  {
	if($deposit_mode=='by_deposit_partial_full' ){
		//$applied_coupons = $cart->get_applied_coupons();
		//echo "<pre>";
	// print_r($cart);
    # $taxes = array_sum( $cart->taxes ); // <=== This is not used in your function

    // Calculation
    //$surcharge = ( $cart->cart_contents_total  ) * $percent / 100;

    // Add the fee (tax third argument disabled: false)
    //$cart->add_fee( __( 'TAX', 'woocommerce')." ($percent%)", $surcharge, false );
   }	
	

    
}

add_action( 'woocommerce_cart_totals_after_order_total', 'bbloomer_show_total_discount_cart_checkout', 9999 );
add_action( 'woocommerce_review_order_after_order_total', 'bbloomer_show_total_discount_cart_checkout', 9999 );
 
function bbloomer_show_total_discount_cart_checkout() {
    
   $discount_total = 0;
  $selected_country  = wcpbc_get_woocommerce_country();
 $country= WC()->countries->countries[ $selected_country];
/*   echo "<pre>";
 print_r(WC()->cart->get_cart());echo "</pre>";  */
   foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
/* echo "<pre>";	   
 print_r($values);echo "</pre>";	 */
      $product = $values['data'];
	  $discounthtml='';
	  if($selected_country=='SG'){
 if ( $values['deposit_mode']=='by_deposit_partial') {
	  if( WC()->session->get('enable_api_discount_partial') ) {
		  $discounthtml .='<tr><th>Coupon: </th><td data-title="You Saved"> <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">-'.get_woocommerce_currency_symbol().'</span>'. WC()->session->get('price_discount').'</span><a href="javascript:void(0);" class="" onclick="checkcoupon();" ><img class="remove_coupon" src="https://staging.bjluxury.com/wp-content/uploads/2023/03/remove_coupon.png" /></a><span   style="display:none" class="removecoupon_loader"><img src="https://staging.bjluxury.com/wp-content/uploads/2023/03/original_spinner.gif" /></span></td></tr>';
		  $total=number_format(str_replace(",","", $values['deposit_full_amount']) - WC()->session->get('price_discount') ,2);
	     $discounthtml .= '<th>Total </th><td>'.get_woocommerce_currency_symbol().''.$total.'</td></tr>';
	  }	 
      echo $discounthtml.'<tr><th>Deposit Due Now </th><td data-title="You Saved">'.get_woocommerce_currency_symbol().''.$values['current_deposit'].'</td></tr><tr><th>Balance Payment Due (via Bank Transfer)</th><td data-title="You Saved">'.get_woocommerce_currency_symbol().''.$values['due_deposit'].'</td></tr><tr>';
	   if( WC()->session->get('enable_api_discount_partial') ) {
		   
	   }else{
		 echo '<th>Total </th><td>'.get_woocommerce_currency_symbol().''.$values['deposit_full_amount'].'</td></tr>';
	    
	   }
	echo '<tr><th>Shipping to  </th><td>'.$country.'</td></tr>';  
      } else{
		if( WC()->session->get('enable_api_discount_full') ) {
		  echo '<tr><th>Coupon: </th><td data-title="You Saved"> <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">-'.get_woocommerce_currency_symbol().'</span>'. WC()->session->get('price_discount').'</span><a href="javascript:void(0);" class="" onclick="checkcoupon();" ><img class="remove_coupon" src="https://staging.bjluxury.com/wp-content/uploads/2023/03/remove_coupon.png" /></a><span   style="display:none" class="removecoupon_loader"><img src="https://staging.bjluxury.com/wp-content/uploads/2023/03/original_spinner.gif" /></span></td></tr>'; 
		 
		 }  
		  if( WC()->session->get('enable_api_discount_full') ) {
			 
		   $total=number_format(str_replace(",","", $values['deposit_full_amount']) - WC()->session->get('price_discount') ,2);
	 echo '<th>Total </th><td>'.get_woocommerce_currency_symbol().''.$total.'</td></tr>';
	   }else{
		 echo '<th>Total</th><td>'.get_woocommerce_currency_symbol().''.$values['deposit_full_amount'].'</td></tr>';
	    
	   }
		 echo '<tr><th>Shipping to  </th><td>'.$country.'</td></tr>';  
	  }
	  }else{
/* echo "<pre>";		 
print_r($values);echo "</pre>";	 */
      if( WC()->session->get('enable_api_discount_other_country') ) {
			echo '<tr><th>Coupon: </th><td data-title="You Saved"> <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">-'.get_woocommerce_currency_symbol().'</span>'. WC()->session->get('price_discount').'</span><a href="javascript:void(0);" class="" onclick="checkcoupon();" ><img class="remove_coupon" src="https://staging.bjluxury.com/wp-content/uploads/2023/03/remove_coupon.png" /></a><span   style="display:none" class="removecoupon_loader"><img src="https://staging.bjluxury.com/wp-content/uploads/2023/03/original_spinner.gif" /></span></td></tr>';   
			
			
		   $total=number_format($values['after_discount_other_country_amount'] ,2);
	 echo '<th>Total </th><td>'.get_woocommerce_currency_symbol().''.$values['after_discount_other_country_amount'].'</td></tr>';
	   }else{
		  echo '<tr><th>Total</th><td>'.get_woocommerce_currency_symbol().''.number_format($values['line_total'],2) .'</td></tr><tr><th>Shipping to  </th><td>'.$country.'</td></tr>';
	    
	   }

		 
	  }
   }
             
    
}

 function calculate_embossing_fee( $cart_object ) {
   
       // print_r($cart_object->cart_contents);
        foreach ( $cart_object->cart_contents as $key => $value ) {
			 $selected_country  = wcpbc_get_woocommerce_country(); 
			  if($selected_country=='SG'){
            if(  $value["deposit_mode"]== 'by_deposit_partial')  {
                $value['data']->set_price($value['current_deposit']);
              }elseif($value["deposit_mode"]== 'by_deposit_partial_full' && !empty($value['deposit_full_amount'])){
				  
				if( WC()->session->get('enable_api_discount_full') ) {
//echo $value['deposit_full_amount'];
$total=number_format(str_replace(",","", $value['deposit_full_amount']) - WC()->session->get('price_discount') ,2);
    //$total=number_format($value['line_total'] - WC()->session->get('price_discount') ,2);
	
$value['data']->set_price($total);	

				}else{
					$value['data']->set_price($value['deposit_full_amount']);	
					}					
				  
			 
			 }
			 
		}else{
			if( WC()->session->get('enable_api_discount_other_country') ) {
				//echo $value['after_discount_other_country_amount'];
				
				$value['data']->set_price($value['after_discount_other_country_amount']);	
			}else{
				//print_r($value);
				//$value['data']->set_price($value['line_total']);	
			}
			
			
		}
        }
 
}
add_action( 'woocommerce_before_calculate_totals', 'calculate_embossing_fee', 99 );
add_action( 'woocommerce_checkout_create_order', 'bbloomer_set_singapore_city', 9999, 2 );
 
function bbloomer_set_singapore_city( $order, $data ) {

  foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) { 
if ( $values['deposit_mode']=='by_deposit_partial') {
 $order->update_meta_data('_deposit_mode', $values['deposit_mode']);
 $order->update_meta_data('_current_deposit', $values['current_deposit']);
 $order->update_meta_data('_deposit_full_amount', $values['deposit_full_amount']);
 $order->update_meta_data('_due_deposit', $values['due_deposit']);
 $order->update_meta_data('_deposit_percent',  $values['deposit_percent']);
  
}   
 }

 
}

add_action( 'woocommerce_admin_order_item_headers', 'download_image_admin_order_item_headers', 10, 0 );
function download_image_admin_order_item_headers(){
	 global $post;
    // The Order ID
    $order_id = $post->ID;
if (get_post_meta($order_id, '_deposit_mode', true)  == 'by_deposit_partial'){
    echo '<th class="item sortable" colspan="1" data-sort="string-ins">' . __( 'Downloads', 'woocommerce' ) .'</th>';
}
}

add_action( 'woocommerce_admin_order_item_values', 'download_image_order_item_values', 10, 3 );
function download_image_order_item_values( $_product, $item, $item_id ){
    // Calling global $post to get the order ID
    global $post;
    // The Order ID
    $order_id = $post->ID;
if (get_post_meta($order_id, '_deposit_mode', true)  == 'by_deposit_partial'){
    // the Product ID and variation ID (if different of zero for variations)
    $product_id = $item['product_id'];
    $variation_id = $item['variation_id'];
    
    // If is not a variable product we replace the variation ID by the product ID
    if (empty($variation_id)) $variation_id = $product_id;

    // HERE ==> Getting an instance of product object, Avoiding an error:
    // "Fatal error: Call to a member function get_gallery_attachment_ids()"
    $product = new WC_Product($product_id);
    // the Product post object
    $post_product = $product->post;

    $attachment_count = count($product->get_gallery_attachment_ids());
    $gallery = $attachment_count > 0 ? '[product-gallery]' : '';

    // CODE ERROR ===> This was returning empty before. You need to put
    // the product ID in get_post_thumbnail_id() function to get something
    $props = wc_get_product_attachment_props(get_post_thumbnail_id($product_id), $post_product);

    // Testing $props output (array not empty) => comment/uncomment line below
    

    $image = get_the_post_thumbnail( $product->id, apply_filters('single_product_large_thumbnail_size', 'shop_single' ), array(
        'title' => $props['title'],
        'alt' => $props['alt'],
    ));



    // Added a condition to avoid other line items than products (like shipping line)
    if(!empty($product_id))
        echo apply_filters(
                'woocommerce_single_product_image_html', sprintf(
                        '<td class="name" colspan="1" ><a style="text-decoration: none;clear:both;float: left;margin-top: 5px;" href="%s" download = "Order#' . $order_id . '-' . $variation_id . '"><input type = "button" value="Download image"/></a></td>', esc_url($props['url'])
                ), $product->id
        );
}
 }
add_action('woocommerce_admin_order_totals_after_discount', 'custom_admin_order_totals_after_discount', 10, 1 );
function custom_admin_order_totals_after_discount( $order_id ) {
$order = wc_get_order( $order_id );
$items = $order->get_items();
foreach ( $items as $item ) {
  $product_id = $item->get_product_id();
   
}
   
 $_product = wc_get_product( $product_id );
 $price = $_product->get_price();
if (get_post_meta($order_id, '_deposit_mode', true)  == 'by_deposit_partial'){
    // Output
    ?>
	<script>
jQuery(document).ready(function(){
   jQuery('body').addClass('bank-transfer-order');
});
	</script>
        <tr>
            <td class="label">Subtotal:</td>
            <td width="1%"></td>
            <td class="custom-total"><?php echo get_woocommerce_currency_symbol().$price; ?></td>
        </tr>
		<tr>
            <td class="label">Total:</td>
            <td width="1%"></td>
            <td class="custom-total"><?php echo get_woocommerce_currency_symbol().$price; ?></td>
        </tr>
    <?php
}
}
add_action('woocommerce_admin_order_totals_after_tax', 'custom_admin_order_totals_after_tax', 10, 1 );
function custom_admin_order_totals_after_tax( $order_id ) {
$order = wc_get_order( $order_id );
$items = $order->get_items();
foreach ( $items as $item ) {
  $product_id = $item->get_product_id();
   
}


 $_product = wc_get_product( $product_id );
 $price = $_product->get_price();
if (get_post_meta($order_id, '_deposit_mode', true)  == 'by_deposit_partial'){
	$selected_country=get_post_meta($order_id, '_shipping_country', true);
	$country= WC()->countries->countries[ $selected_country];
    // Output
    ?>
	
        <tr>
            <td class="label">Deposit Due Now:</td>
            <td width="1%"></td>
            <td class="custom-total"><?php echo get_woocommerce_currency_symbol().get_post_meta($order_id, '_current_deposit', true); ?></td>
        </tr>
		<tr>
            <td class="label">Balance Payment Due (via Bank Transfer):</td>
            <td width="1%"></td>
            <td class="custom-total"><?php echo get_woocommerce_currency_symbol().get_post_meta($order_id, '_due_deposit', true); ?></td>
        </tr>
		
    <?php
}
}
add_filter( 'woocommerce_get_order_item_totals', 'customize_order_item_totals', 10, 3 );
function customize_order_item_totals( $total_rows, $order, $tax_display ){
    // You can make changes below
  /*   print_r($order->get_id(););
	die; */
	//$selected_country  = wcpbc_get_woocommerce_country();
	
 $order_id=$order->get_id();
	$selected_country=get_post_meta($order_id, '_shipping_country', true);
	$Zone=get_post_meta($order_id, '_wcpbc_pricing_zone', true);
	 $VoucherCode  = $order->get_meta('_VoucherCode', true );
  $VoucherValue = $order->get_meta('_VoucherValue', true );
	//echo "<pre>";
	//print_r($Zone);
	
 $currency=$Zone['currency'];
	$country= WC()->countries->countries[ $selected_country];
	 $total=get_post_meta($order_id, '_current_deposit', true)+get_post_meta($order_id, '_due_deposit', true);
	if (get_post_meta($order_id, '_deposit_mode', true)  == 'by_deposit_partial'){
		if($VoucherCode!='' && $VoucherValue!=''){
	$total_rows['coupon_cst']['label'] = __( 'Coupon:', 'woocommerce' ); // The row shipping label
    $total_rows['coupon_cst']['value'] = '-'.$currency.' '.$VoucherValue;	
	$total_rows['total_cst']['label'] = __( 'Total:', 'woocommerce' ); // The row shipping label
	$newtotal= number_format(str_replace(",","", get_post_meta($order->get_id(), '_deposit_full_amount', true))-$VoucherValue,2);
    $total_rows['total_cst']['value'] = $currency.' '.$newtotal;
	}
	
    $total_rows['cart_subtotal']['value'] = get_woocommerce_currency_symbol().$total; // The row shipping value
    
	
	$total_rows['order_total_cst']['label'] = __( 'Total Received:', 'woocommerce' ); // The row shipping label
	$total_rows['order_total_cst']['value'] = $total_rows['order_total']['value']; // The row shipping label
    unset($total_rows['order_total']);
   // unset($total_rows['order_total']);
	//$total_rows['order_total']['value'] =  get_woocommerce_currency_symbol().get_post_meta($order_id, '_current_deposit', true);
   $total_rows['pay_now']['label'] = __( 'Balance Payment Due (via Bank Transfer):', 'woocommerce' ); // The row shipping label
    $total_rows['pay_now']['value'] =  $currency.' '. get_post_meta($order_id, '_due_deposit', true);
	
	}else{
		if($VoucherCode!='' && $VoucherValue!=''){
			
			
			 $total=(get_post_meta($order->get_id(), '_order_total', true) + get_post_meta($order->get_id(), '_VoucherValue', true));
	$total_rows['cart_subtotal']['value'] = get_woocommerce_currency_symbol().$total;
	$total_rows['coupon_cst']['label'] = __( 'Coupon:', 'woocommerce' ); // The row shipping label
    $total_rows['coupon_cst']['value'] = '-'.$currency.' '.$VoucherValue;	
	
	$total_rows['order_total_cst']['label'] = __( 'Total Received:', 'woocommerce' ); // The row shipping label
	$total_rows['order_total_cst']['value'] = $total_rows['order_total']['value']; // The row shipping label
    unset($total_rows['order_total']);
	 //unset($total_rows['order_total']);
	}else{
	$total_rows['order_total_cst']['label'] = __( 'Total Received:', 'woocommerce' ); // The row shipping label
	$total_rows['order_total_cst']['value'] = $total_rows['order_total']['value'];	
		
	}
	// unset($total_rows['order_total']);
	}
	
	if (get_post_meta($order_id, '_deposit_mode', true)  == 'by_deposit_partial'){
		if($VoucherCode=='' && $VoucherValue==''){
	$total_rows['total_cst']['label'] = __( 'Total:', 'woocommerce' ); // The row shipping label
    $total_rows['total_cst']['value'] = $currency.' '.get_post_meta($order->get_id(), '_deposit_full_amount', true);
		}
	}
	 $total_rows['country']['label'] = __( 'Shipping to:', 'woocommerce' ); // The row shipping label
    $total_rows['country']['value'] =  $country;
/* 	echo "<pre>";
 print_r($total_rows);
 echo "</pre>"; */
    return $total_rows;
}



// Add a custom coupon field before checkout payment section
add_action( 'woocommerce_review_order_before_payment', 'woocommerce_checkout_coupon_form_custom' );
function woocommerce_checkout_coupon_form_custom() {
    echo '<!--div class="checkout-coupon-toggle"><div class="woocommerce-info">' . sprintf(
        __("Have a coupon? %s"), '<a href="#" class="show-coupon">' . __("Click here to enter your code") . '</a>'
    ) . '</div></div-->';

    echo '<div class="coupon-form" style="margin-bottom:20px;" >
        <p>' . __("If you have a coupon code, please apply it below.") . '</p>
        <p class="form-row form-row-first woocommerce-validated">
            <input type="text" name="coupon_code" class="input-text" placeholder="' . __("Coupon code") . '" id="coupon_code" value="">
        </p>
        <p class="form-row form-row-last">
            <button type="button" class="button" name="apply_coupon" value="' . __("Apply coupon") . '">' . __("Apply coupon") . '</button><span style="display:none"  class="coupon_loader"><img src="https://staging.bjluxury.com/wp-content/uploads/2023/03/original_spinner.gif" /></span>
        </p>
		<p id="html_msg" ></p>
        <div class="clear"></div>
        
    </div>';
}

// jQuery - Send Ajax request
add_action( 'wp_footer', 'custom_checkout_jquery_script' );
function custom_checkout_jquery_script() {
    if ( is_checkout() && ! is_wc_endpoint_url() ) :
    ?>
    <script type="text/javascript">
	var $=jQuery;
	function checkcoupon(){
		$('.removecoupon_loader').show();
		//alert("woo_remove_coupon");
            $.ajax({
                type: 'POST',
                url: wc_checkout_params.ajax_url,
                data: {
                    'action': 'remove_checkout_coupon',
                    'coupon_code': '',
                },
                success: function (response) {
					var obj = jQuery.parseJSON( response );
					if(obj.msg=='done'){
						$('#id_VoucherCode').val('');
						$('#id_VoucherValue').val('');
						$('#html_msg').html('');
					}
                    $(document.body).trigger("update_checkout"); // Refresh checkout
                    //$('.woocommerce-error,.woocommerce-message').remove(); // Remove other notices
                    //$('input[name="coupon_code"]').val(''); // Empty coupon code input field
                    if(obj.msg=='error'){
					//$('form.checkout').before(obj.msg1); // Display notices
                     // Uncomment for testing
					}
                },
				complete: function(){
					$('.removecoupon_loader').hide();	
				}
            });
	}
	jQuery( function($){
		
        //$('.coupon-form').css("display", "none"); // Be sure coupon field is hidden
        
        // Show or Hide coupon field
        $('.checkout-coupon-toggle .show-coupon').on( 'click', function(e){
            $('.coupon-form').toggle(200);
            e.preventDefault();
        })
        
      
        
    });
	
    jQuery( function($){
        if (typeof wc_checkout_params === 'undefined')
            return false;

        var couponCode = '';

        $('input[name="coupon_code"]').on( 'input change', function(){
            couponCode = $(this).val();
        });

        $('button[name="apply_coupon"]').on( 'click', function(){
			$('.coupon_loader').show();
            $.ajax({
                type: 'POST',
                url: wc_checkout_params.ajax_url,
                data: {
                    'action': 'apply_checkout_coupon',
                    'coupon_code': couponCode,
                },
                success: function (response) {
					var obj = jQuery.parseJSON( response );
					if(obj.msg=='done'){
						$('#id_VoucherCode').val(couponCode);
						$('#id_VoucherValue').val(obj.price);
						$("#html_msg").attr('class', '');
						$('#html_msg').html(obj.message);						
						$('#html_msg').addClass('sucess_msg');
						$(document.body).trigger("update_checkout"); // Refresh checkout
					}
                  
                    // Remove other notices
                    $('input[name="coupon_code"]').val(''); // Empty coupon code input field
                    if(obj.msg=='error'){
						$("#html_msg").attr('class', '');
						$('#html_msg').html(obj.message);
						$('#html_msg').addClass('error_msg');
					//$('form.checkout').before(obj.msg1); // Display notices
                     // Uncomment for testing
					}
                },
				complete: function(){
					$('.coupon_loader').hide();	
				}
            });
        }); 
		$('.woo_remove_coupon').on( 'click', function(){
			
        });
    });
    </script>
    <?php
    endif;
}

// Ajax receiver function
add_action( 'wp_ajax_remove_checkout_coupon', 'remove_checkout_coupon_ajax_receiver' );
add_action( 'wp_ajax_nopriv_remove_checkout_coupon', 'remove_checkout_coupon_ajax_receiver' );
function remove_checkout_coupon_ajax_receiver() {
$cart = WC()->cart->cart_contents;
			foreach( $cart as $cart_item_id=>$cart_item ) {			
				
			  $selected_country  = wcpbc_get_woocommerce_country();
 if($selected_country=='SG'){	
				
			if($cart_item['deposit_mode']=='by_deposit_partial') {
		     $cart_item['current_deposit'] = $cart_item['old_current_deposit'];
			$cart_item['due_deposit'] =$cart_item['old_due_deposit'];
			$cart_item['old_current_deposit'] = '';
			$cart_item['old_due_deposit'] =''; 
			}else{
			$cart_item['after_discount_full_amount'] = '';
			$cart_item['old_deposit_full_amount'] = ''; 
			}
			WC()->cart->cart_contents[$cart_item_id] = $cart_item;
			
 }else{
	
		   $cart_item['after_discount_other_country_amount'] = '';
			$cart_item['old_other_country_amount'] = ''; 
		    $cart_item['line_total']=WC()->session->get('old_other_country_amount');
			WC()->cart->cart_contents[$cart_item_id] = $cart_item;
 }
			}
			
			
			
			WC()->cart->set_session();
			WC()->session->set('enable_api_discount_partial',false);
			WC()->session->set('enable_api_discount_full',false);
			WC()->session->set('enable_api_discount_other_country',false);
			WC()->session->set('price_discount',''); 
			//wc_add_notice( __( 'Coupon has been removed.', 'woocommerce' ), 'success' );
            $res=json_encode(array('msg'=>'done','message'=>'Coupon has been removed.'));
			
			echo $res;
		
 die;	
	

}

add_action( 'wp_ajax_apply_checkout_coupon', 'apply_checkout_coupon_ajax_receiver' );
add_action( 'wp_ajax_nopriv_apply_checkout_coupon', 'apply_checkout_coupon_ajax_receiver' );
function apply_checkout_coupon_ajax_receiver() {
	global $woocommerce;
    if ( isset($_POST['coupon_code']) && ! empty($_POST['coupon_code']) ) {
		
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) { 
		//print_r($values);
		   $product_id = $values['product_id'];
		   $deposit_mode = $values['deposit_mode'];
		   $deposit_full_amount = $values['deposit_full_amount'];
		   $due_deposit1 = $values['due_deposit'];
		   $current_deposit1 = $values['current_deposit'];
		   $deposit_percent = $values['deposit_percent'];
		   $total= $values['line_total'];
		}
		$selected_country  = wcpbc_get_woocommerce_country();  
		$country= WC()->countries->countries[ $selected_country];
	$currency=  get_woocommerce_currency_symbol();
	  
	$product = wc_get_product( $product_id );  
	
	$sku = $product->get_sku();
	$base_price = $product->get_price();
   $voucherfields = [ 
	   			'VoucherCode' => $_POST['coupon_code'], 
              	'ProductNo'=>$sku,
              	'CountryName' => $country ,
              	'Currency'=> trim($currency),
              	'WebPrice'=>$base_price,
              ]; 
 /* $voucherfields = [ 
	   			 "VoucherCode"=>"vamt_150",
    "ProductNo"=>"78600221",
    "CountryName"=>"Singapore",
    "Currency"=>"SGD",
    "WebPrice"=>"500"
              ];  */

	
///// api call here
//WC()->session->set('enable_api_discount_partial',false);
 $curl = curl_init();
//   $url = "http://bjapi.vmecs.com/api/WebAPI/A20007"; // old server api url 
     $url = "https://bjapitest.vmecst.com/api/WebAPI/A20008"; // new server api url  

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 25,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
     )
  ));
  
 curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($voucherfields));
  $response = curl_exec($curl);

 
 $err = curl_error($curl);
 curl_close($curl); 
 $data=json_decode($response);

 if($data->message=='Invalid'){
	//wc_clear_notices();
	echo $res=json_encode(array('message'=>$data->error_message,'msg'=>'error'));
       // wc_add_notice( __( $data->error_message, 'woocommerce' ), 'notice' );
       //wc_print_notices(); 
	  wp_die();	
	  
 }else{	 
	 if($data->message=="Valid"){
		   $selected_country  = wcpbc_get_woocommerce_country();
 if($selected_country=='SG'){
		 if($deposit_mode=='by_deposit_partial') {
			 
			 if( WC()->session->get('enable_api_discount_partial')){
				$res=json_encode(array('price'=>'0','msg'=>'error','message'=>'Already apply coupon Please remove first'));
			
			echo $res;
		
              die; 
			 }else{
				 
			 
			  $price= explode('.',$deposit_full_amount);
		    $deposit_full_amount1=  str_replace(",","", $price[0]);
			$newPrice= ($deposit_full_amount1 - $data->price_discount);
			$total =  ( $deposit_percent/100) * $newPrice; 
			$due_deposit=$newPrice-$total;
			$cart = WC()->cart->cart_contents;
			foreach( $cart as $cart_item_id=>$cart_item ) {
			$cart_item['current_deposit'] = number_format($total,2);
			$cart_item['due_deposit'] =number_format($due_deposit,2);
			$cart_item['old_current_deposit'] = $current_deposit1;
			$cart_item['old_due_deposit'] =$due_deposit1;
			WC()->cart->cart_contents[$cart_item_id] = $cart_item;
			}
			
			WC()->cart->set_session();
			WC()->session->set('enable_api_discount_partial',true);
			WC()->session->set('price_discount',$data->price_discount);
			//wc_add_notice( __( 'Coupon code applied successfully.', 'woocommerce' ), 'success' );
         $res=json_encode(array('price'=>$data->price_discount,'msg'=>'done','message'=>'Coupon code applied successfully.'));
			
			echo $res;
		
 die;
 }
		 }else{
			
			WC()->session->set('enable_api_discount_full',true);
			WC()->session->set('price_discount',$data->price_discount);
			//$deposit_full_amount=$value['deposit_full_amount'];
			
			$price= explode('.',$deposit_full_amount);
		    $deposit_full_amount1=  str_replace(",","", $price[0]);
			$newPrice= ($deposit_full_amount1 - $data->price_discount);
			//echo $price=$data->price_discount;
			
			$cart = WC()->cart->cart_contents;
			foreach( $cart as $cart_item_id=>$cart_item ) {
				// $total=number_format($cart_item['line_total'] - WC()->session->get('price_discount') ,2);
			$cart_item['deposit_full_amount'] = $deposit_full_amount;
			$cart_item['after_discount_full_amount'] = number_format($newPrice,2);
			$cart_item['old_deposit_full_amount'] = $deposit_full_amount;
			WC()->cart->cart_contents[$cart_item_id] = $cart_item;
			}
			WC()->cart->set_session(); 
			
             //update_post_meta( '154893', 'coupon_amount', $price );
		    // update_post_meta( '154893', '_coupon_code', $_POST['coupon_code'] );
		    //WC()->cart->add_discount( wc_format_coupon_code( wp_unslash('APICoupon' ) ) ); 
			//echo "done";
			$res=json_encode(array('price'=>$data->price_discount,'msg'=>'done','message'=>'Coupon code applied successfully.'));
			
			echo $res;
		
			exit; 
			
			
		 }
 }else{
	 
	        WC()->session->set('enable_api_discount_other_country',true);
			WC()->session->set('price_discount',$data->price_discount);
			 $total=  str_replace(",","", $total);
			 $newPrice= ($total - $data->price_discount);
			
			$cart = WC()->cart->cart_contents;
			foreach( $cart as $cart_item_id=>$cart_item ) {
			 $cart_item['after_discount_other_country_amount'] = number_format($newPrice,2);
		 	$cart_item['old_other_country_amount'] = number_format($total,2);
			WC()->cart->cart_contents[$cart_item_id] = $cart_item;
			}
			WC()->cart->set_session(); 
			
             //update_post_meta( '154893', 'coupon_amount', $price );
		    // update_post_meta( '154893', '_coupon_code', $_POST['coupon_code'] );
		    //WC()->cart->add_discount( wc_format_coupon_code( wp_unslash('APICoupon' ) ) ); 
			//echo "done";
			$res=json_encode(array('price'=>$data->price_discount,'msg'=>'done','message'=>'Coupon code applied successfully.'));
			
			echo $res;
		
			exit; 
	 
	 
	 
	 
	 
 }
		 		
		 
		  
		 
		 
		 
	 }
	 
 }

    } else {
        //wc_add_notice( WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ), 'error' );
		
		//$res=json_encode(array('e'=>WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ),'msg'=>'error'));
			  $res=json_encode(array('msg'=>'error','message'=>WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER )));
			echo $res;
			//wc_print_notices();
    wp_die();
    }
    
}


add_action('woocommerce_after_checkout_validation', 'rei_after_checkout_validation');

function rei_after_checkout_validation( $posted ) {
	global $woocommerce;


if ( isset($_POST['VoucherCode']) && ! empty($_POST['VoucherCode']) ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) { 
		   $product_id = $values['product_id'];
		 
		}
		$selected_country  = wcpbc_get_woocommerce_country();  
		$country= WC()->countries->countries[ $selected_country];
	$currency=  get_woocommerce_currency_symbol();
	  
	$product = wc_get_product( $product_id );  
	$sku = $product->get_sku();
	$base_price = $product->get_price();
   $voucherfields = [ 
	   			'VoucherCode' => $_POST['VoucherCode'], 
              	'ProductNo'=>$sku,
              	'CountryName' => $country ,
              	'Currency'=> $currency,
              	'WebPrice'=>$base_price,
              ]; 
 
	
///// api call here

 $curl = curl_init();
//   $url = "http://bjapi.vmecs.com/api/WebAPI/A20007"; // old server api url 
     $url = "https://bjapitest.vmecst.com/api/WebAPI/A20008"; // new server api url  

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 25,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
     )
  ));
  
 curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($voucherfields));
 $response = curl_exec($curl);

 
 $err = curl_error($curl);
 curl_close($curl); 
 $data=json_decode($response);

 if($data->message=='Invalid'){
	wc_clear_notices();
        wc_add_notice( __( $data->error_message, 'woocommerce' ), 'error' );
		}
		}




    

}
 add_action( 'woocommerce_after_order_notes', 'add_custom_checkout_hidden_field' );
function add_custom_checkout_hidden_field( $checkout ) {

    // Generating the VID number
    

    // Output the hidden field
    echo '<div id="user_link_hidden_checkout_field">
            <input type="hidden" class="input-hidden" name="VoucherCode" id="id_VoucherCode" value="">
            <input type="hidden" class="input-hidden" name="VoucherValue" id="id_VoucherValue" value="">
    </div>';
}


// Saving the hidden field value in the order metadata
add_action( 'woocommerce_checkout_update_order_meta', 'save_custom_checkout_hidden_field' );
function save_custom_checkout_hidden_field( $order_id ) {
   
        update_post_meta( $order_id, '_VoucherCode', sanitize_text_field( $_POST['VoucherCode'] ) );
    
	   update_post_meta( $order_id, '_VoucherValue', sanitize_text_field( $_POST['VoucherValue'] ) );
    
}