<?php
/*
  Plugin Name: matrix price
  Plugin URI: https://sadecweb.com/
  Description: 
  Version: 1.0
  Author: Sadecweb
  Author URI: https://www.sadecweb.com/
  Text Domain: sadecweb
  Domain Path: /languages
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WC_PLUGIN_FILE.
if ( ! defined( 'WTP_PLUGIN_PATH' ) ) {
    define( 'WTP_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
    define( 'WTP_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}
global $selection_matrix,$addon_matrix,$instant;

$selection_matrix[1] = $table1 ;
$selection_matrix[2] = $table2 ;
$selection_matrix[3] = $table3 ;
$selection_matrix[4] = $table4 ;
$selection_matrix[5] = $table5 ;

$addon_matrix['Thermal']['Pencil Pleat'] = 1 ;// Thermal + Pencil Pleat => table #1
$addon_matrix['Thermal']['Eyelet'] = 4 ;// Thermal + Eyelet => table #4
$addon_matrix['Handsewn Inter lined']['Eyelet'] = 3 ;// Thermal + Eyelet => table #3
$addon_matrix['Blackout']['Pencil Pleat'] = 2 ;// Blackout + PencilPleat => table #2
$addon_matrix['Blackout']['Eyelet'] = 5 ;// Blackout + Eyelet => table #5

$instant['width'] = array(
	'cm' 	=> array(127,193,260,329,398,546),
	'inch' 	=> array(50,76,102,130,157,215)
) ;
$instant['drop'] = array(
	'cm' 	=> array(137,183,228,274,330),
	'inch' 	=> array(54,72,90,108,130)
) ;
//require_once("inc/admin_page.php");
//require_once("inc/product_type.php");
require_once("inc/functions.php");
require_once("inc/frontend.php");
require_once("inc/woocommerce.php");
