<?php
global $addon_matrix;
global $selection_matrix;
global $instant;

$table1[0][0] = 125.07;	$table1[0][1] = 217.14; $table1[0][2] = 241.89; $table1[0][3] = 342.21; $table1[0][4] = 366.96; $table1[0][5] = 483.78; 
$table1[1][0] = 141.57;	$table1[1][1] = 241.89; $table1[1][2] = 274.89; $table1[1][3] = 375.21; $table1[1][4] = 408.21; $table1[1][5] = 566.28; 
$table1[2][0] = 154.77;	$table1[2][1] = 266.64; $table1[2][2] = 311.19; $table1[2][3] = 423.06; $table1[2][4] = 465.96; $table1[2][5] = 615.78; 
$table1[3][0] = 182.325;$table1[3][1] = 313.5; 	$table1[3][2] = 366.3; 	$table1[3][3] = 496.65; $table1[3][4] = 547.8; 	$table1[3][5] = 712.8; 
$table1[4][0] = 198;	$table1[4][1] = 336.6;  $table1[4][2] = 397.65; $table1[4][3] = 501.6;  $table1[4][4] = 594; 	$table1[4][5] = 762.3; 

$table2[0][0] = 136.44;	$table2[0][1] = 236.88; $table2[0][2] = 263.88;	$table2[0][3] = 373.32; $table2[0][4] = 400.32; $table2[0][5] = 527.76; 
$table2[1][0] = 154.44;	$table2[1][1] = 263.88; $table2[1][2] = 299.88; $table2[1][3] = 409.32; $table2[1][4] = 445.32; $table2[1][5] = 617.76; 
$table2[2][0] = 168.84;	$table2[2][1] = 290.88; $table2[2][2] = 339.48; $table2[2][3] = 461.52; $table2[2][4] = 508.32; $table2[2][5] = 671.76; 
$table2[3][0] = 198.9;	$table2[3][1] = 342; 	$table2[3][2] = 399.6; 	$table2[3][3] = 541.8; 	$table2[3][4] = 597.6; 	$table2[3][5] = 777.6; 
$table2[4][0] = 216;	$table2[4][1] = 367.2; 	$table2[4][2] = 433.8; 	$table2[4][3] = 547.2; 	$table2[4][4] = 648; 	$table2[4][5] = 831.6; 

$table3[0][0] = 228.9;	$table3[0][1] = 415.8;	$table3[0][2] = 447.3;	$table3[0][3] = 644.7;	$table3[0][4] = 676.2;	$table3[0][5] = 894.6; 
$table3[1][0] = 249.9;	$table3[1][1] = 447.3;	$table3[1][2] = 489.3;	$table3[1][3] = 686.7;	$table3[1][4] = 728.7;	$table3[1][5] = 999.6; 
$table3[2][0] = 266.7;	$table3[2][1] = 478.8;	$table3[2][2] = 535.5;	$table3[2][3] = 747.6;	$table3[2][4] = 802.2;	$table3[2][5] = 1062.6; 
$table3[3][0] = 301.77;	$table3[3][1] = 538.44;	$table3[3][2] = 605.64;	$table3[3][3] = 841.26;	$table3[3][4] = 906.36;	$table3[3][5] = 1186.08; 
$table3[4][0] = 321.72;	$table3[4][1] = 567.84;	$table3[4][2] = 645.54;	$table3[4][3] = 847.56;	$table3[4][4] = 965.16;	$table3[4][5] = 1249.08; 

$table4[0][0] = 154.11;	$table4[0][1] = 275.22;	$table4[0][2] = 299.97;	$table4[0][3] = 429.33;	$table4[0][4] = 454.08;	$table4[0][5] = 599.94; 
$table4[1][0] = 170.61;	$table4[1][1] = 299.97;	$table4[1][2] = 332.97;	$table4[1][3] = 462.33;	$table4[1][4] = 495.33;	$table4[1][5] = 682.44; 
$table4[2][0] = 183.81;	$table4[2][1] = 324.72;	$table4[2][2] = 369.27;	$table4[2][3] = 510.18;	$table4[2][4] = 553.08;	$table4[2][5] = 731.94; 
$table4[3][0] = 211.365;$table4[3][1] = 371.58;	$table4[3][2] = 424.38;	$table4[3][3] = 583.77;	$table4[3][4] = 634.92;	$table4[3][5] = 828.96; 
$table4[4][0] = 227.04;	$table4[4][1] = 394.68;	$table4[4][2] = 455.73;	$table4[4][3] = 588.72;	$table4[4][4] = 681.12;	$table4[4][5] = 878.46; 



$table5[0][0] = 168.12;	$table5[0][1] = 300.24;	$table5[0][2] = 327.24;	$table5[0][3] = 468.36;	$table5[0][4] = 495.36;	$table5[0][5] = 654.48; 
$table5[1][0] = 186.12;	$table5[1][1] = 327.24;	$table5[1][2] = 363.24;	$table5[1][3] = 504.36;	$table5[1][4] = 540.36;	$table5[1][5] = 744.48; 
$table5[2][0] = 200.52;	$table5[2][1] = 354.24;	$table5[2][2] = 402.84;	$table5[2][3] = 556.56;	$table5[2][4] = 603.36;	$table5[2][5] = 798.48; 
$table5[3][0] = 230.58;	$table5[3][1] = 405.36;	$table5[3][2] = 462.96;	$table5[3][3] = 636.84;	$table5[3][4] = 692.64;	$table5[3][5] = 904.32; 
$table5[4][0] = 247.68;	$table5[4][1] = 430.56;	$table5[4][2] = 497.16;	$table5[4][3] = 642.24;	$table5[4][4] = 743.04;	$table5[4][5] = 958.32; 

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
























?>