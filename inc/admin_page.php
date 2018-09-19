<?php
add_action('admin_menu', 'wpt_plugin_menu');
function wpt_plugin_menu() {
    add_menu_page(__('Table Price Products', 'sadecweb'), __('Table Price', 'sadecweb'), 'manage_options', 'table-prices-product', 'wpt_plugin_options');

    add_submenu_page('table-prices-product', __('Settings', 'sadecweb'), __('Settings', 'sadecweb'), 'manage_options', 'sub-page', 'wpt_plugin_settings');
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
    $cats = array(97, 95, 94);
    $i = 0;
    $html_role = '';
    $html_title = '';
    $html_sub_title = '';
    foreach($roles as $key => $role) {
        $html_title .= '<td colspan="2" style="text-align: center;"><h4>'.$role.'</h4></td>';
        $html_sub_title .= '<td style="text-align: center;"><h4>Single</h4></td><td style="text-align: center;"><h4>Double</h4></td>';
        $i += 2;
    }
	?>
    <style>
    #table-prices{
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    #table-prices tr td{
        border: 1px solid #000;
    }
    #button-save{
        padding: 15px 30px;
        border: 1px solid #ccc;
        background: #2196F3;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        color: #fff;
    }
    </style>
    <h2><?php _e( 'Table Prices', 'sadecweb' ); ?></h2>
    <table id="table-prices">
        <tbody>
        <?php 
        foreach($cats as $cat_id) : 
        $term = get_term($cat_id, 'product_cat');
        ?>
            <tr class="name-cat">
                <td colspan="<?php echo $i+1; ?>"><h3><?php echo $term->name ?></h3></td>
            <tr>
            <tr class="name-roles">
                <td></td>
                <?php echo $html_title ; ?>
            </tr>
            <tr class="name-roles-sub">
                <td></td>
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
                    <td><?php echo get_the_title($product->ID); ?></td>
                    <?php
                    foreach($roles as $key => $role) {
                        echo '<td class="single"><input name="_value_single_'.$key.'" id="_value_single_'.$key.'" value="'.get_post_meta($product->ID, '_value_single_'.$key, true).'" size="10"/></td>';
                        echo '<td class="double"><input name="_value_double_'.$key.'" id="_value_double_'.$key.'" value="'.get_post_meta($product->ID, '_value_double_'.$key, true).'" size="10"/></td>';
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