<?php
/*
function get_unit_price ($product_id, $role, $single = true){
    $key = '_value_single_';
    if(!$single){
        $key = '_value_double_';
    }
    $key .= $role;
   // $price = get_meta_box($product_id, $key, true);

   $price = get_post_meta($product_id, $key, true);
    return $price;
} */

add_action( 'wp_ajax_export_matrix_prices', 'export_matrix_prices' );
function export_matrix_prices(){
    $product_id = $_POST['id'];
    $_value_matrix = get_post_meta($product_id, '_value_matrix', true);
    wp_send_json($_value_matrix);
    wp_die();
}