<?php
add_action('admin_menu', 'wpt_plugin_menu');
function wpt_plugin_menu() {
    add_menu_page(__('Pricing Table Products', 'sadecweb'), __('Pricing table ', 'sadecweb'), 'manage_options', 'pricing-table-product', 'wpt_plugin_options');

    add_submenu_page('table-pricing-product', __('Settings', 'sadecweb'), __('Settings', 'sadecweb'), 'manage_options', 'sub-page', 'wpt_plugin_settings');
}

function wpt_plugin_settings() {
?>
    
<?php
}

function wpt_plugin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    global $wp_roles;
    $roles = $wp_roles->get_names();
    $cats = array(599, 812, 61);
    $i = 0;
    $html_role = '';
    $html_title = '';
    $html_sub_title = '';
	$roles = array(
		'trade-only-pricing' => 'Trade ONLY Pricing',
		'wholesale-pricing' => 'Wholesale Pricing',
		'retail-pricing' => 'Retail Pricing',
		);
    foreach($roles as $key => $role) {
        $html_title .= '<td colspan="2" style="text-align: center;"><h4>'.$role.'</h4></td>';
        $html_sub_title .= '<td class="single" style="text-align: center;"><h4>Single</h4></td>
		<td class="double" style="text-align: center;"><h4>Double</h4></td>';
        $i += 2;
    }
	?>
 <link rel='stylesheet' id='thickbox-css'  href='<?php echo WTP_PLUGIN_URL . '/css/price_box.css' ;?>' type='text/css' media='all' />
   <h2 id = 'table_price_head'><?php _e( 'Pricing Table ', 'sadecweb' ); ?></h2>
    <table id="table-prices" >
        <tbody>
        <?php 
        foreach($cats as $cat_id) : 
        $term = get_term($cat_id, 'product_cat');
        ?>
			<tr class="name-cat">
				<td><h3><?php echo $term->name ?></h3></td>
				<td colspan="<?php echo $i; ?>"></td>
			</tr>
            <tr class="name-roles">
                <td  class = "product_name"></td>
                <?php echo $html_title ; ?>
            </tr>
            <tr class="name-roles-sub">
                <td class="product_name"></td>
                <?php echo $html_sub_title ; ?>
            </tr>
            <?php
            $args_post = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $cat_id,
                    )
                )
            );
            $products = get_posts($args_post);
            if(!empty($products)) :
                foreach($products as $product) :
                ?>
                <tr class="product" data-id="<?php echo $product->ID ?>">
                    <td class = "product_name"><?php echo get_the_title($product->ID); ?></td>
                    <?php
                    foreach($roles as $key => $role) {
                        echo '<td class="single"><input placeholder = "N/A" name="_value_single_'.$key.'" id="_value_single_'.$key.'" value="'.get_post_meta($product->ID, '_value_single_'.$key, true).'" size="10"/></td>';
                        echo '<td class="double"><input placeholder = "N/A" name="_value_double_'.$key.'" id="_value_double_'.$key.'" value="'.get_post_meta($product->ID, '_value_double_'.$key, true).'" size="10"/></td>';
                    }
	                ?>
                </tr>
            <?php 
                endforeach;
            endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
    <button id="button-save">Save</button>
    <script>
        jQuery(function($){
            $(document).on('click', '#button-save', function(){
                var row_products = [];
                $('#table-prices .product').each(function(){
                    var _id = $(this).data('id');
                    var _value = [];
                    $(this).find('input').each(function(){
                        _value.push({'meta': $(this).attr('name'), 'value': $(this).val()});
                    });
                    row_products.push({'id': _id, 'data': _value})
                });
                var data = {'action': 'wtp_save_table_price', 'product_data': row_products};
                $.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', data, function(response){
                    location.reload();
                });
            })
        });
    </script>
    <?php
}

function get_terms_dropdown_grade_level($taxonomies, $args){
    $myterms = get_terms($taxonomies, $args);
    $output ="<select multiple  name=''>"; //CHANGE ME!
    foreach($myterms as $term){
            $root_url = get_bloginfo('url');
            $term_taxonomy=$term->taxonomy;
            $term_slug=$term->slug;
            $term_name =$term->name;
            $link = $term_slug;
            $output .="<option value='".$link."'>".$term_name."</option>";
    }
    $output .="</select>";
    return $output;
}
?>