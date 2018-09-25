<?php
add_filter( 'product_type_options', 'add_matrix_price_product_option' );
function add_matrix_price_product_option( $product_type_options ) {
	$product_type_options['matrix_price'] = array(
		'id'            => '_matrix_price',
		'wrapper_class' => 'show_if_simple',
		'label'         => __( 'Matrix price', 'sadecweb' ),
		'description'   => __( '', 'sadecweb' ),
		'default'       => 'no'
	);
	return $product_type_options;
}

add_filter( 'woocommerce_product_data_tabs', 'wtf_matrix_price_product_panel_tabs' );
function wtf_matrix_price_product_panel_tabs($tabs) {
    // Adds the new tab
    $tabs['matrix_price'] = array(
		'label'		=> __( 'Matrix Prices', 'sadecweb' ),
		'target'	=> 'wtf_matrix_price_options_tabs',
		'class'		=> array( 'show_if_matrix_price' )
    );
	return $tabs;
}

function matrix_price_custom_style() {
	global $post;
	?><style>
		#woocommerce-product-data ul.wc-tabs li.matrix_price_options a:before { font-family: WooCommerce; content: '\e01e'; }
	</style>
	<script>
	jQuery( document ).ready( function( $ ) {
		$( 'input#_matrix_price' ).change( function() {
			var is_matrix_price = $( 'input#_matrix_price:checked' ).size();
			$( '.show_if_matrix_price' ).hide();
			$( '.hide_if_matrix_price' ).hide();
			if ( is_matrix_price ) {
				$( '.hide_if_matrix_price' ).hide();
			}
			if ( is_matrix_price ) {
				$( '.show_if_matrix_price' ).show();
			} 
		});
		$( 'input#_matrix_price' ).trigger( 'change' );
		var data = {'action': 'export_matrix_prices', 'id': '<?php echo $post->ID; ?>'}
		$.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', data, function(response){
			var uri = 'data:application/json;charset=UTF-8,' + JSON.stringify(response);
			$('#export_matrix_price').attr('href', uri);
			$('#export_matrix_price').attr('download', 'data_matrix_prices_product(<?php echo $post->ID; ?>)-'+ new Date().toLocaleDateString() +'.json');
			$('.feature_import').show();
		});
		$('#import_matrix_price').click(function(){
			var _closset = $(this).parent();
			var _input = _closset.find('input');
			var formdata = new FormData();
			formdata.append('action', 'wmp_import_matrix_prices');
			formdata.append('id', '<?php echo $post->ID; ?>');
			formdata.append('data_import', _input[0].files[0], _input[0].files[0].name);
			$.ajax({
				url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				type: 'POST',
				dataType: 'json',
				processData: false,
				contentType:false,
				data: formdata,
				success: function(){
					location.reload();
				}
			});
		});
	});
	</script><?php
}
add_action( 'admin_head', 'matrix_price_custom_style' );

add_action( 'woocommerce_product_write_panels', 'wtf_matrix_price_product_panel_content' );
function wtf_matrix_price_product_panel_content() {
    // The new tab content
    global $post;
    ?>
    <div id="wtf_matrix_price_options_tabs" class="panel wc-metaboxes-wrapper" style="display: none;">
	<link rel='stylesheet' id='thickbox-css'  href='<?php echo WTP_PLUGIN_URL . 'css/price_box.css' ;?>' type='text/css' media='all' />
	<div class="feature_import" style="display: none;">
		<a href="javascript:void(0)" id="export_matrix_price"><?php _e('Export', 'sadecweb'); ?></a>
		<div class="import_field">
			<input type="file" id="impoft_value" name="impoft_value" accept=".json" />
			<a href="javascript:void(0)" id="import_matrix_price"><?php _e('Import', 'sadecweb'); ?></a>
		</div>
	</div>
	<div class='options_group'>
		<table id="table-prices" >
        <tbody>
		<?php
		global $addon_matrix,$selection_matrix,$instant;
		$_value_matrix = get_post_meta($post->ID, '_value_matrix', true);
        foreach($addon_matrix as $key => $addon) : 
        ?>
			<tr class="name-addon">
				<td><h3><?php _e('Lining','sadecweb') ?></h3></td>
				<td colspan="6"><h3><?php echo $key ?></h3></td>
			</tr>
			<?php if(isset($addon) && !empty($addon)) : 
				foreach($addon as $key => $subaddon) : ?>
					<tr class="name-subaddon">
						<td><h3><?php _e('Heading','sadecweb') ?></h3></td>
						<td colspan="6"><h3><?php echo $key ?></h3></td>
					</tr>
					<?php if(!empty($instant['width']) && !empty($instant['drop'])) : ?>
						<tr>
						<td class="title"></td>
						<?php foreach($instant['width']['cm'] as $key => $value) : ?>
							<td class="title"><?php echo $value ?> cm (<?php echo $instant['width']['inch'][$key] ?>")</td>
						<?php endforeach; ?>
						</tr>
						<?php foreach($instant['drop']['cm'] as $key_width => $value_width) : ?>
						<tr>
						<td class="title"><?php echo $value_width ?> cm (<?php echo $instant['drop']['inch'][$key_width] ?>")</td>
							<?php foreach($instant['width']['cm'] as $key_top => $value_top) : ?>
								<td class="value">
									<?php
										woocommerce_wp_text_input( 
											array(
											'id'				=> 	'_value_matrix['.$subaddon.']['.$key_width.']['.$key_top.']',
											'label'				=> 	__( '', 'sadecweb' ),
											'desc_tip'			=> 	'false',
											'description'		=> 	__( '', 'sadecweb' ),
											'data_type' 		=> 	'price',
											'type'				=> 	'number',
											'value'				=>	$_value_matrix[$subaddon][$key_width][$key_top],
											'custom_attributes'	=> 	array(
																		'min'	=> '1',
																		'step'	=> '0.1',
																		'size'	=> '5'
																	),
											) 
										);
									?>
								</td>
							<?php endforeach; ?>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    	</table>
	</div>
    </div>
    <?php
}

add_action( 'woocommerce_process_product_meta_simple', 'save_giftcard_option_fields'  );
function save_giftcard_option_fields( $post_id ) {
	if(isset($_POST['_value_matrix'])){
		update_post_meta($post_id, '_value_matrix', $_POST['_value_matrix']);
	}
}

add_action("save_post_product", function ($post_ID, $product, $update) {
    update_post_meta(
          $product->ID
        , "_matrix_price"
        , isset($_POST["_matrix_price"]) ? "yes" : "no"
    );
}, 10, 3);

add_action( 'wp_ajax_wtp_save_table_price', 'wtp_save_table_price' );
function wtp_save_table_price(){
	$data = $_POST['product_data'];
	foreach($data as $product){
		$product_id = $product['id'];
		foreach ($product['data'] as $meta_box) {
			update_post_meta( $product_id, $meta_box['meta'],  $meta_box['value'] );
		}
	}
}

add_action( 'wp_ajax_export_matrix_prices', 'export_matrix_prices' );
function export_matrix_prices(){
    $product_id = $_POST['id'];
    $_value_matrix = get_post_meta($product_id, '_value_matrix', true);
    wp_send_json($_value_matrix);
    wp_die();
}

add_action( 'wp_ajax_wmp_import_matrix_prices', 'wmp_import_matrix_prices' );
function wmp_import_matrix_prices(){
	$content = trim(file_get_contents($_FILES['data_import']['tmp_name']));
	$product_id = $_POST['id'];
	$decoded = json_decode($content, true);
    $_value_matrix = update_post_meta($product_id, '_value_matrix', $decoded);
    wp_send_json(array(''));
    wp_die();
}
