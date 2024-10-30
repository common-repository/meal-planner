<?php
/**
 * Plugin Name: Meal Planner
 * Plugin URI: http://webdesksolution.com/
 * Description: This plugin helps you to create the Meal packages that will allow users to purchase the meal plans as per their requirements.
 * Version: 1.0.0
 * Author: webdesksolution
 * Author URI: http://webdesksolution.com/
 */
define('MNG_PL_URL',plugin_dir_url( __FILE__ ));
function mng_get_meal_array(){
	$extra_field_array=array( 'deliverydate'=>'Delivery date',
		'gender' =>'Male or Female',
		'meal_plan' =>'Meal Plan', 
		'meal_plan_per_day' =>'Days Per Week', 				
		'meal_plan_breakfast' =>'Extra Breakfast' ,
		'meal_plan_lunch' =>'Extra Lunch' ,
		'meal_plan_dinner' =>'Extra Dinner', 
		'meal_plan_dessert' =>'Extra Dessert', 
		'meal_plan_snack' =>'Extra Snack' ,
		'_recurring' =>'yes',
		'food_allergies_or_any_specific_requirements'=>'Food Allergies or any Specific Requirements'
	);	
	return $extra_field_array;
}
add_filter ('init', 'mng_load_custom_library');
function mng_load_custom_library() {
	include('wds_meta_box.php');
	include('wds_woofunction.php');
}
add_action( 'wp_footer', 'mng_wptuts_scripts_basic' );
function mng_wptuts_scripts_basic(){
	wp_enqueue_script('jquery-ui-datepicker');
	
	wp_register_script( 'mng_js',  MNG_PL_URL. 'js/custom-script.js' );
	wp_enqueue_script( 'mng_js' );

	wp_register_style( 'mng_css', MNG_PL_URL . 'css/custom-style.css', array(), '20120208', 'all' );
	wp_enqueue_style( 'mng_css' );

	wp_register_style( 'mng_ui_css', MNG_PL_URL. 'css/jquery-ui.css', array(), '20120208', 'all' );
	wp_enqueue_style( 'mng_ui_css' );
}

?>
