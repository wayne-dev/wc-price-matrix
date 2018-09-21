<?php
function get_price_matrix ($selection_matrix = array(), $table, $x, $y){
	return $selection_matrix[(int)$table][(int)$y][(int)$x] ? $selection_matrix[(int)$table][(int)$y][(int)$x] : null ;
}
function near_coor($array = array(),$value){
	$key = 0 ;
	foreach($array as $key => $ele){
		if($value <= $ele)
			return $key ;
	}
	return $key ;
}

function product_in_matrix_is_purchasable($product){
	if(!product_in_matrix ($product->get_id()) || !isset($_REQUEST['get_instant']))
		return false;
	if(!isset($_REQUEST['lining'])){
		//wc_add_notice("Lining is empty!", 'error' );	
		return false;
	}
	if(!isset($_REQUEST['instant_drop']) || !$_REQUEST['instant_drop']){
		//wc_add_notice("drop is empty!", 'error' );	
		return false;
	}
	if(!isset($_REQUEST['instant_width'])|| !$_REQUEST['instant_width']){
		//wc_add_notice("width is empty!", 'error' );	
		return false;
	}
	if(!isset($_REQUEST['heading'])|| !$_REQUEST['heading']){
		//wc_add_notice("width is empty!", 'error' );	
		return false;
	}
	return true;
}
/*
add_filter( 'woocommerce_is_purchasable', function ( $purchasable, $product ){
	if( !product_in_matrix_is_purchasable() &&	is_product()){
        $purchasable = false;
	}
    return $purchasable;
}, 10, 2 );
add_filter( 'woocommerce_get_price_html', function( $price, $product ){
	if ( product_in_matrix ($product->get_id()) && ('' === $product->get_price() || 0 == $product->get_price() ) ) {
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
}, 100, 5 );*/
add_action('woocommerce_before_add_to_cart_button',function(){
	if(isset($_REQUEST['get_instant']) && $_REQUEST['get_instant'] == 1) {
		if(isset($_REQUEST['lining'],$_REQUEST['instant_width'],$_REQUEST['instant_drop']) && $_REQUEST['lining']&& $_REQUEST['instant_width']&& $_REQUEST['instant_drop']){
			require_once(WTP_PLUGIN_PATH."inc/data.php");
			global $addon_matrix,$selection_matrix,$instant;
			$lining = $_REQUEST['lining'] ;
			$headings = $addon_matrix[$lining] ;
			if( $_REQUEST['instant_width'] && $_REQUEST['instant_drop']){
				$instant_width = $_REQUEST['instant_width'] ; 
				$instant_drop = $_REQUEST['instant_drop'] ;
				$cubit = $_REQUEST['_cubit'] ?  $_REQUEST['_cubit'] : 'cm';

				$cubit_width = $instant['width'][$cubit];
				$cubit_drop	 = $instant['drop'][$cubit];
				$x_width 	= near_coor($cubit_width,$instant_width);
				$y_drop 	= near_coor($cubit_drop,$instant_drop);
				?>
				<div class = 'heading_select' >
				<?php
				foreach($headings as $heading => $table){
					?>
					<label> 
					<?php echo $heading; ?> 
					<?php if($instant_width && $instant_drop){ 
						$price = get_price_matrix($selection_matrix,$table,$x_width ,$y_drop) ;
					?>
					(<?php echo wc_price ($price) ;  ?>)
					<?php } ?>
					
					<input data-matrix_heading = '<?php echo $heading; ?>' type = 'radio' name = 'matrix_table' value = '<?php echo $table; ?> '/>
					</label>
					<?php
				}
				?>
					<input type = 'hidden' name = 'matrix_heading' value = ''/>
					<input type = 'hidden' name = 'matrix_lining' value = '<?php echo $lining; ?> '/>
					<input type = 'hidden' name = 'x_width' value = '<?php echo $x_width; ?> '/>
					<input type = 'hidden' name = 'y_drop' value = '<?php echo $y_drop; ?> '/>
					
					<input type = 'hidden' name = 'cubit' value = '<?php echo $cubit; ?> '/>
					<input type = 'hidden' name = 'instant_drop' value = '<?php echo $instant_drop; ?> '/>
					<input type = 'hidden' name = 'instant_width' value = '<?php echo $instant_width; ?> '/>
				</div>
				<?php
				
			}
		}
	}
});
add_action('woocommerce_before_add_to_cart_form',function(){

	global $product;
	?>
	<?php
	$lining = 'Thermal' ;
	if(isset($_REQUEST['instant_width'])){
		$instant_width = $_REQUEST['instant_width'] ;
	}
	if(isset($_REQUEST['instant_drop'])){
		$instant_drop = $_REQUEST['instant_drop'] ;
	}
	//if(isset($_REQUEST['get_instant']) && $_REQUEST['get_instant'] == 1) {
		require_once(WTP_PLUGIN_PATH."inc/data.php");
		global $addon_matrix,$selection_matrix,$instant;
		$lining = $_REQUEST['lining'] ;
		$cubit = $_REQUEST['_cubit'] ?  $_REQUEST['_cubit'] : 'cm';
	//}
	?>
	<style>
	form.cart *[type="submit"],form.cart *[name="quantity"],.single-product .summary p.price{display:none;}
	</style>
	<form>
		<label> cm <input <?php checked($cubit,'cm') ; ?>type = 'radio' name = '_cubit' value = 'cm'/></label>
		<label> inch <input <?php checked($cubit,'inch') ; ?>type = 'radio' name = '_cubit' value = 'inch'/></label>
		<p>
			<label>Width (<span class = '_cubit'><?php echo $cubit; ?></span>)</label>
			<input class = 'input_cubit' type = 'number' step = 0.01 name = "instant_width" value = '<?php echo $instant_width; ?>'  />
		</p>
		<p>
			<label>Drop (<span class = '_cubit'><?php echo $cubit; ?></span>)</label>
			<input class = 'input_cubit' type = 'number' step = 0.01 name = "instant_drop" value = '<?php echo $instant_drop; ?>'  />
		</p>

		<div id = 'lining_option'>
		<?php foreach($addon_matrix as $title => $value) { ?>
			<label> <?php echo $title; ?> <input <?php checked($title,$lining) ; ?> type = 'radio' name = 'lining' value = '<?php echo $title; ?>'/></label>
		<?php } ?>
		</div>
		<button type = 'submit' value = 1 name = 'get_instant'>Get an instant price</button>
	</form>
   <script>
        jQuery(function($){
			//var max_width = <?php echo $max_width; ?>,max_height = <?php echo $max_height; ?>;
            /*$(document).on('change', '.price_box input,.price_box *[name="product_side"]', function(){
				var price = calculate();
			});
			function calculate(){
				var width = $('[name="add_to_cart_width"]').val(),
					height = $('[name="add_to_cart_height"]').val(),
					unit_price = $('.price_box').data('price_' + product_side) ,
				
				return price ;
			}*/

			$(document).on('change', '*[name="_cubit"]', function(){
				$('._cubit').text($(this).val());
				$('.input_cubit').val('');
			});
			$(document).on('change', '.heading_select *[name="matrix_table"]', function(){
				var matrix_heading = $(this).data('matrix_heading');
				$('.heading_select *[name="matrix_heading"]').val(matrix_heading);
				$('form.cart *[type="submit"],form.cart *[name="quantity"]').show();
			});
			<?php //if(!product_in_matrix_is_purchasable($product)){ ?>
				//$('form.cart *[type="submit"],form.cart *[name="quantity"],.single-product .summary p.price').hide();
			<?php //} ?>
		});
   </script>
 	<?php
	//wc_clear_notices();	
});
add_filter( 'woocommerce_add_cart_item_data', function($cart_item_data, $product_id, $variation_id, $quantity){
	if($_REQUEST['matrix_table'] && $_REQUEST['x_width'] && $_REQUEST['y_drop']  ){
		$matrix_table 	= $_REQUEST['matrix_table'] ;
		$x_width 		= $_REQUEST['x_width'] ;
		$y_drop 		= $_REQUEST['y_drop'] ;
		$instant_drop 	= $_REQUEST['instant_drop'] ;
		$instant_width 		= $_REQUEST['instant_width'] ;
		require_once(WTP_PLUGIN_PATH."inc/data.php");
		global $addon_matrix,$selection_matrix,$instant;
		
		$price = get_price_matrix($selection_matrix,$matrix_table,$x_width ,$y_drop) ;

		
		$cart_item_data['y_drop'] 				= $y_drop;
		$cart_item_data['x_width'] 				= $x_width;
		$cart_item_data['cubit'] 				= $_REQUEST['cubit'];
		$cart_item_data['instant_drop'] 		= $_REQUEST['instant_drop'];
		$cart_item_data['instant_width'] 		= $_REQUEST['instant_width'];
		$cart_item_data['matrix_lining'] 		= $_REQUEST['matrix_lining'];
		$cart_item_data['matrix_heading'] 		= $_REQUEST['matrix_heading'];
		$cart_item_data['_in_pricing_matrix']	= $price;
	}
	return $cart_item_data;
} ,200,4);
add_action( 'woocommerce_after_cart_item_name', function($cart_item, $cart_item_key){

	if($cart_item['matrix_lining'] && $cart_item['matrix_heading']  ){
		$matrix_lining 		= $cart_item['matrix_lining'];
		$matrix_heading 	= $cart_item['matrix_heading'];
		$instant_width 	= $cart_item['instant_width'];
		$instant_drop 	= $cart_item['instant_drop'];
		$cubit 	= $cart_item['cubit'];
		?>
		<p>Lining: <?php echo $matrix_lining ; ?></p>
		<p>Heading: <?php echo $matrix_heading ; ?></p>
		<p>Drop: <?php echo $instant_drop . ' ' . $cubit ; ?></p>
		<p>Width: <?php echo $instant_width . ' ' . $cubit ; ?></p>
		<?php
	}
}, 11, 2 );
add_action( 'woocommerce_before_calculate_totals', function ( $cart_object ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	
	//$price = get_price_matrix($selection_matrix,$table,$x_width ,$y_drop) ;
    foreach ( $cart_object->cart_contents as $cart_item_key => $value ) {
		if(isset($value["_in_pricing_matrix"]) ){
			$_in_pricing_matrix = $value["_in_pricing_matrix"] ;
			$value['data']->set_price( $_in_pricing_matrix);
		}
    }
	return $cart_object ;
}, 10, 1 );

function product_in_matrix ($id){
	if( $id == 49 ) 
		return true;
	return false;	
}
?>