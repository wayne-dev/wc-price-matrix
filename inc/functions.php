<?php
function get_unit_price ($product_id, $role, $single = true){
    $key = '_value_single_';
    if(!$single){
        $key = '_value_double_';
    }
    $key .= $role;
    $price = wc_price(get_meta_box($product_id, $key, true));
    return $price;
} 