<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Mwb_new_plugin
 *
 * @wordpress-plugin
 * Plugin Name:       mwb-new-plugin
 * Plugin URI:        https://makewebbetter.com/product/mwb-new-plugin/
 * Description:       Your Basic Plugin
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       mwb-new-plugin
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      4.9.5
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Define plugin constants.
 *
 * @since             1.0.0
 */
function define_mwb_new_plugin_constants() {

	mwb_new_plugin_constants( 'MWB_NEW_PLUGIN_VERSION', '1.0.0' );
	mwb_new_plugin_constants( 'MWB_NEW_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
	mwb_new_plugin_constants( 'MWB_NEW_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
	mwb_new_plugin_constants( 'MWB_NEW_PLUGIN_SERVER_URL', 'https://makewebbetter.com' );
	mwb_new_plugin_constants( 'MWB_NEW_PLUGIN_ITEM_REFERENCE', 'mwb-new-plugin' );
}


/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since             1.0.0
 */
function mwb_new_plugin_constants( $key, $value ) {

	if ( ! defined( $key ) ) {

		define( $key, $value );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mwb-new-plugin-activator.php
 */
function activate_mwb_new_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-new-plugin-activator.php';
	Mwb_new_plugin_Activator::mwb_new_plugin_activate();
	$mwb_mnp_active_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_mnp_active_plugin ) && ! empty( $mwb_mnp_active_plugin ) ) {
		$mwb_mnp_active_plugin['mwb-new-plugin'] = array(
			'plugin_name' => __( 'mwb-new-plugin', 'mwb-new-plugin' ),
			'active' => '1',
		);
	} else {
		$mwb_mnp_active_plugin = array();
		$mwb_mnp_active_plugin['mwb-new-plugin'] = array(
			'plugin_name' => __( 'mwb-new-plugin', 'mwb-new-plugin' ),
			'active' => '1',
		);
	}
	update_option( 'mwb_all_plugins_active', $mwb_mnp_active_plugin );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mwb-new-plugin-deactivator.php
 */
function deactivate_mwb_new_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-new-plugin-deactivator.php';
	Mwb_new_plugin_Deactivator::mwb_new_plugin_deactivate();
	$mwb_mnp_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_mnp_deactive_plugin ) && ! empty( $mwb_mnp_deactive_plugin ) ) {
		foreach ( $mwb_mnp_deactive_plugin as $mwb_mnp_deactive_key => $mwb_mnp_deactive ) {
			if ( 'mwb-new-plugin' === $mwb_mnp_deactive_key ) {
				$mwb_mnp_deactive_plugin[ $mwb_mnp_deactive_key ]['active'] = '0';
			}
		}
	}
	update_option( 'mwb_all_plugins_active', $mwb_mnp_deactive_plugin );
}

register_activation_hook( __FILE__, 'activate_mwb_new_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_mwb_new_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mwb-new-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mwb_new_plugin() {
	define_mwb_new_plugin_constants();

	$mnp_plugin_standard = new Mwb_new_plugin();
	$mnp_plugin_standard->mnp_run();
	$GLOBALS['mnp_mwb_mnp_obj'] = $mnp_plugin_standard;

}
run_mwb_new_plugin();

// Add rest api endpoint for plugin.
add_action( 'rest_api_init', 'mnp_add_default_endpoint' );

/**
 * Callback function for endpoints.
 *
 * @since    1.0.0
 */
function mnp_add_default_endpoint() {
	register_rest_route(
		'mnp-route',
		'/mnp-dummy-data/',
		array(
			'methods'  => 'POST',
			'callback' => 'mwb_mnp_default_callback',
			'permission_callback' => 'mwb_mnp_default_permission_check',
		)
	);
}

/**
 * API validation
 * @param 	Array 	$request 	All information related with the api request containing in this array.
 * @since    1.0.0
 */
function mwb_mnp_default_permission_check($request) {

	// Add rest api validation for each request.
	$result = true;
	return $result;
}

/**
 * Begins execution of api endpoint.
 *
 * @param   Array $request    All information related with the api request containing in this array.
 * @return  Array   $mwb_mnp_response   return rest response to server from where the endpoint hits.
 * @since    1.0.0
 */
function mwb_mnp_default_callback( $request ) {
	require_once MWB_NEW_PLUGIN_DIR_PATH . 'includes/class-mwb-new-plugin-api-process.php';
	$mwb_mnp_api_obj = new Mwb_new_plugin_Api_Process();
	$mwb_mnp_resultsdata = $mwb_mnp_api_obj->mwb_mnp_default_process( $request );
	if ( is_array( $mwb_mnp_resultsdata ) && isset( $mwb_mnp_resultsdata['status'] ) && 200 == $mwb_mnp_resultsdata['status'] ) {
		unset( $mwb_mnp_resultsdata['status'] );
		$mwb_mnp_response = new WP_REST_Response( $mwb_mnp_resultsdata, 200 );
	} else {
		$mwb_mnp_response = new WP_Error( $mwb_mnp_resultsdata );
	}
	return $mwb_mnp_response;
}


// Add settings link on plugin page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'mwb_new_plugin_settings_link' );

/**
 * Settings link.
 *
 * @since    1.0.0
 * @param   Array $links    Settings link array.
 */
function mwb_new_plugin_settings_link( $links ) {

	$my_link = array(
		'<a href="' . admin_url( 'admin.php?page=mwb_new_plugin_menu' ) . '">' . __( 'Settings', 'mwb-new-plugin' ) . '</a>',
	);
	return array_merge( $my_link, $links );
}
 //add_action( 'init', 'cb_xml_test' );
// /**
//  * Xml cb callback.
//  */
 //function cb_xml_test() {

// 	$xml = new DOMDocument();
// // 	$xml->load( MWB_NEW_PLUGIN_DIR_PATH . 'product.xml' );
  //$xml = simplexml_load_file( MWB_NEW_PLUGIN_DIR_PATH . 'product.xml' ) or die( 'Error: Cannot create object' );
 
//   foreach($xml as $record) {
// 	$review = strval($record->REVIEWS);
// 	$price = $record->PRICE;
// 	$sku = $record->SKU;
// 	print_r($review);
// 	die( 'hello' );

	// echo $price.'|';
	//echo $sku;
	//print_r (MWB_NEW_PLUGIN_DIR_PATH);
	
	// echo 'p'.$price.'-';
	//echo 'r'.$review.'-';
// echo $xml->record[0]->TITLE . "<br>";
// echo $xml->record[0]->SKU . "<br>";
// 	// print_r( $xml);
	
//  }
//  $sku = $xml->record[0]->SKU;
//  }


// Code for exporting the csv data.
//add_action('init', 'export_csv_file' );

// function export_csv_file(){
	
// 	header('Content-Type: text/csv');
// 	header('Content-Disposition: attachment; filename="sample.csv"');
// 	// $list = array(
// 	// 	'product_name',
// 	// 	'product_content',
// 	// );
// 	// $args = array(
// 	// 	'post_type'   => 'wpcust_product',
// 	// 	'post_status' => 'publish',
// 	// );
// 	$args = array(
// 		'numberposts' => 100,
// 		'post_type'   => 'wpcust_product',
// 	);
	   
// 	  $latest_books = get_posts( $args );
// 	  $posts = array();
// 	$fp = fopen('php://output', 'w+');

 
// 	  foreach ( $latest_books as $post ) {
// 		 //array_push($posts ,$post->post_title);
// 		//fputcsv($fp, $posts, ',');
// 		$posts[] .= $post->post_title;
// 		$posts[] .=  get_post_meta( $post->ID, 'product_price', true ) ;
// 		$posts[] .=  get_post_meta( $post->ID, 'product_sku', true ) ;
// 		$posts[] .=  get_post_meta( $post->ID, 'product_review', true ) ;

// 	  }
// 	  //echo "<pre>";
// 	  //print_r($posts);


// 	//die('hello');

// 	// $user_CSV[0] = array('first_name', 'last_name', 'age');

// 	// // very simple to increment with i++ if looping through a database result 
// 	// $user_CSV[1] = array('Quentin', 'Del Viento', 34);
// 	// $user_CSV[2] = array('Antoine', 'Del Torro', 55);
// 	// $user_CSV[3] = array('Arthur', 'Vincente', 15);

// 	foreach ($posts as $line) {
// 		// though CSV stands for "comma separated value"
// 		// in many countries (including France) separator is ";"
// 		fputcsv($fp, $line, ',');
// 		//print_r( $line);
// 		//echo '<br>';
// 	}
// 	//die('msg');
// 	fclose($fp);

// }