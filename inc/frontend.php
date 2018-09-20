<?php
add_filter( 'woocommerce_is_purchasable', function ( $purchasable, $product ){
	if( $product->get_price() == 0 && 
		($_POST['add-to-cart']) && 
		(!$_POST['add_to_cart_width'] || !$_POST['add_to_cart_height']) && 
		!is_product() 
		){
		if(!$_POST['add_to_cart_width'])
			wc_add_notice("Width is empty!", 'error' );	
		if(!$_POST['add_to_cart_height'])
			wc_add_notice("Height is empty!", 'error' );	
        $purchasable = false;
	}

    return $purchasable;
}, 10, 2 );

add_filter( 'woocommerce_get_price_html', function( $price, $product ){
	if ( product_in_pricing_table ($product) && ('' === $product->get_price() || 0 == $product->get_price() ) ) {
		$price = 'Product price: ' . $price ;
	} 
	return $price;
}, 100, 2 );
add_filter( 'wc_price', function($return, $price, $args, $unformatted_price ){
	$args = apply_filters(
		'wc_price_args', wp_parse_args(
			$args, array(
				'ex_tax_label'       => false,
				'currency'           => '',
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimals'           => wc_get_price_decimals(),
				'price_format'       => get_woocommerce_price_format(),
			)
		)
	);

	$unformatted_price = $price;
	$negative          = $price < 0;
	$price             = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * -1 : $price ) );
	$price             = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

	if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
		$price = wc_trim_zeros( $price );
	}

	$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol( $args['currency'] ) . '</span>',  '<span class= "price_amout">' . $price. '</span>' );
	$return          = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

	if ( $args['ex_tax_label'] && wc_tax_enabled() ) {
		$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
	}
	 
	return $return;
}, 100, 5 );
add_action('woocommerce_before_add_to_cart_button',function(){
	global $product;
	$product_id = $product->get_id();
	$role = 'trade-only-pricing' ;
	$price_single = get_unit_price($product_id,$role);
	$price_double = get_unit_price($product_id,$role,false);
	
	?>
	<?php
		if(product_in_pricing_table ($product)){
			?>
			<style>
			.single-product .summary p.price,
			[name="add-to-cart"]{
				display:none;
			}
			</style>
			<?php
	?>
	<link rel='stylesheet' id='thickbox-css'  href='<?php echo WTP_PLUGIN_URL . 'css/frontend.css' ;?>' type='text/css' media='all' />
	<p><label class ='price_unit_label' >Single price unit: </label><?php echo wc_price($price_single); ?> / sq</p>
	<p><label class ='price_unit_label' >Double price unit: </label><?php echo wc_price($price_double); ?> / sq</p>
	<div class = 'price_box' data-price_single = '<?php echo ($price_single); ?>'  data-price_double = '<?php echo ($price_double); ?>' >
		<p>
			<label>Width (ft)</label>
			<select name = 'add_to_cart_width' >
				<option>0</option>
				<option value = 1 >1</option>
				<option value = 2 >2</option>
			</select>
		</p>
		<p>
			<label>Height (ft)</label>
			<select name = 'add_to_cart_height' >
				<option>0</option>
				<option value = 1 >1</option>
				<option value = 2 >2</option>
			</select>
		</p>
		<p>
			<label>
				Single: 
				<input type = 'radio' checked name = 'product_side' value = 'single' />
			</label>
			<label>
				Double: 
				<input type = 'radio' name = 'product_side' value = 'double' />
			</label>
		</p>
	</div>
   <script>
        jQuery(function($){
            $(document).on('change', '.price_box select,.price_box *[name="product_side"]', function(){
				var price = calculate();
				console.log(price);
			});
			function calculate(){
				var width = $('[name="add_to_cart_width"]').val(),
					height = $('[name="add_to_cart_height"]').val(),
					product_side = $('[name="product_side"]:checked').val(),
					product_side = product_side ? product_side : '' ,
					unit_price = $('.price_box').data('price_' + product_side) ,
					price = width * height * unit_price;
					
				$('[name="_in_pricing_table"]').remove();
				if(price){
					$('.single-product .summary p.price,[name="add-to-cart"]').show();
					$('.single-product .summary p.price span.price_amout').html(price);
					$('.price_box').append('<input type = "hidden" value = "'+price+'" name = "_in_pricing_table" />');
				}else{
					$('.single-product .summary p.price,[name="add-to-cart"]').hide();
					$('.single-product .summary p.price span.price_amout').html('');
				}
				
				return price ;
			}
		});
   </script>
 	<?php
		}
});
add_filter( 'woocommerce_add_cart_item_data', function($cart_item_data, $product_id, $variation_id, $quantity){
	if ( empty( $_REQUEST['_in_pricing_table'] ) || ! is_numeric( $_REQUEST['_in_pricing_table'] ) ) {
		return;
	}
	
	$cart_item_data['add_to_cart_width'] 	= $_REQUEST['add_to_cart_width'] . ' ft' ;
	$cart_item_data['add_to_cart_height'] 	= $_REQUEST['add_to_cart_height']. ' ft' ;
	$cart_item_data['product_side'] 		= $_REQUEST['product_side']  ;
	$cart_item_data['_in_pricing_table']	= $_REQUEST['_in_pricing_table']  ;
	return $cart_item_data;
} ,100,4);
add_action( 'woocommerce_after_cart_item_name', function($cart_item, $cart_item_key){

	$add_to_cart_width 	= $cart_item['add_to_cart_width'];
	$add_to_cart_height = $cart_item['add_to_cart_height'];
	$product_side 		= $cart_item['product_side'];
	?>
	<p>Width: <?php echo $add_to_cart_width ; ?></p>
	<p>Height: <?php echo $add_to_cart_height ; ?></p>
	<p>Side: <?php echo $product_side ; ?></p>
	<?php

}, 10, 2 );
add_action( 'woocommerce_before_calculate_totals', 'update_custom_price', 10, 1 );
function update_custom_price( $cart_object ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
    foreach ( $cart_object->cart_contents as $cart_item_key => $value ) {
		if(isset($value["_in_pricing_table"]) ){
			$_in_pricing_table = $value["_in_pricing_table"] ;
			$value['data']->set_price( $_in_pricing_table);
		}
    }
	return $cart_object ;
}
function product_in_pricing_table ($product){
	if( 1 )
		return true;
	return false;	
}
?>