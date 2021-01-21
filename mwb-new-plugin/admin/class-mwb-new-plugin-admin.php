<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_new_plugin
 * @subpackage Mwb_new_plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_new_plugin
 * @subpackage Mwb_new_plugin/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_new_plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function mnp_admin_enqueue_styles( $hook ) {

		wp_enqueue_style( 'mwb-mnp-select2-css', MWB_NEW_PLUGIN_DIR_URL . 'admin/css/mwb-new-plugin-select2.css', array(), time(), 'all' );

		wp_enqueue_style( $this->plugin_name, MWB_NEW_PLUGIN_DIR_URL . 'admin/css/mwb-new-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function mnp_admin_enqueue_scripts( $hook ) {

		wp_enqueue_script( 'mwb-mnp-select2', MWB_NEW_PLUGIN_DIR_URL . 'admin/js/mwb-new-plugin-select2.js', array( 'jquery' ), time(), false );

		wp_register_script( $this->plugin_name . 'admin-js', MWB_NEW_PLUGIN_DIR_URL . 'admin/js/mwb-new-plugin-admin.js', array( 'jquery', 'mwb-mnp-select2' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name . 'admin-js',
			'mnp_admin_param',
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'reloadurl' => admin_url( 'admin.php?page=mwb_new_plugin_menu' ),
			)
		);

		wp_enqueue_script( $this->plugin_name . 'admin-js' );
	}

	/**
	 * Adding settings menu for mwb-new-plugin.
	 *
	 * @since    1.0.0
	 */
	public function mnp_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( __( 'MakeWebBetter', 'mwb-new-plugin' ), __( 'MakeWebBetter', 'mwb-new-plugin' ), 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), MWB_NEW_PLUGIN_DIR_URL . 'admin/images/mwb-logo.png', 15 );
			$mnp_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $mnp_menus ) && ! empty( $mnp_menus ) ) {
				foreach ( $mnp_menus as $mnp_key => $mnp_value ) {
					add_submenu_page( 'mwb-plugins', $mnp_value['name'], $mnp_value['name'], 'manage_options', $mnp_value['menu_link'], array( $mnp_value['instance'], $mnp_value['function'] ) );
				}
			}
		}
	}


	/**
	 * Mwb-new-plugin mnp_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function mnp_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => __( 'mwb-new-plugin', 'mwb-new-plugin' ),
			'slug'      => 'mwb_new_plugin_menu',
			'menu_link' => 'mwb_new_plugin_menu',
			'instance'  => $this,
			'function'  => 'mnp_options_menu_html',
		);
		return $menus;
	}


	/**
	 * Mwb-new-plugin mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require MWB_NEW_PLUGIN_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * mwb-new-plugin admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function mnp_options_menu_html() {

		include_once MWB_NEW_PLUGIN_DIR_PATH . 'admin/partials/mwb-new-plugin-admin-display.php';
	}

	/**
	 * Mwb-new-plugin admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $mnp_settings_general Settings fields.
	 */
	public function mnp_admin_general_settings_page( $mnp_settings_general ) {
		$mnp_settings_general = array(
			array(
				'title'       => __( 'Text Field Demo', 'mwb-new-plugin' ),
				'type'        => 'text',
				'description' => __( 'This is text field demo follow same structure for further use.', 'mwb-new-plugin' ),
				'id'          => 'mnp_text_demo',
				'value'       => '',
				'class'       => 'mnp-text-class',
				'placeholder' => __( 'Text Demo', 'mwb-new-plugin' ),
			),
			array(
				'title'       => __( 'Number Field Demo', 'mwb-new-plugin' ),
				'type'        => 'number',
				'description' => __( 'This is number field demo follow same structure for further use.', 'mwb-new-plugin' ),
				'id'          => 'mnp_number_demo',
				'value'       => '',
				'class'       => 'mnp-number-class',
				'placeholder' => '',
			),
			array(
				'title'       => __( 'Password Field Demo', 'mwb-new-plugin' ),
				'type'        => 'password',
				'description' => __( 'This is password field demo follow same structure for further use.', 'mwb-new-plugin' ),
				'id'          => 'mnp_password_demo',
				'value'       => '',
				'class'       => 'mnp-password-class',
				'placeholder' => '',
			),
			array(
				'title'       => __( 'Textarea Field Demo', 'mwb-new-plugin' ),
				'type'        => 'textarea',
				'description' => __( 'This is textarea field demo follow same structure for further use.', 'mwb-new-plugin' ),
				'id'          => 'mnp_textarea_demo',
				'value'       => '',
				'class'       => 'mnp-textarea-class',
				'rows'        => '5',
				'cols'        => '10',
				'placeholder' => __( 'Textarea Demo', 'mwb-new-plugin' ),
			),
			array(
				'title'       => __( 'Select Field Demo', 'mwb-new-plugin' ),
				'type'        => 'select',
				'description' => __( 'This is select field demo follow same structure for further use.', 'mwb-new-plugin' ),
				'id'          => 'mnp_select_demo',
				'value'       => '',
				'class'       => 'mnp-select-class',
				'placeholder' => __( 'Select Demo', 'mwb-new-plugin' ),
				'options'     => array(
					'INR' => __( 'Rs.', 'mwb-new-plugin' ),
					'USD' => __( '$', 'mwb-new-plugin' ),
				),
			),
			array(
				'title'       => __( 'Multiselect Field Demo', 'mwb-new-plugin' ),
				'type'        => 'multiselect',
				'description' => __( 'This is multiselect field demo follow same structure for further use.', 'mwb-new-plugin' ),
				'id'          => 'mnp_multiselect_demo',
				'value'       => '',
				'class'       => 'mnp-multiselect-class mwb-defaut-multiselect',
				'placeholder' => __( 'Multiselect Demo', 'mwb-new-plugin' ),
				'options'     => array(
					'INR' => __( 'Rs.', 'mwb-new-plugin' ),
					'USD' => __( '$', 'mwb-new-plugin' ),
				),
			),
			array(
				'title'       => __( 'Checkbox Field Demo', 'mwb-new-plugin' ),
				'type'        => 'checkbox',
				'description' => __( 'This is checkbox field demo follow same structure for further use.', 'mwb-new-plugin' ),
				'id'          => 'mnp_checkbox_demo',
				'value'       => '',
				'class'       => 'mnp-checkbox-class',
				'placeholder' => __( 'Checkbox Demo', 'mwb-new-plugin' ),
			),

			array(
				'title'       => __( 'Radio Field Demo', 'mwb-new-plugin' ),
				'type'        => 'radio',
				'description' => __( 'This is radio field demo follow same structure for further use.', 'mwb-new-plugin' ),
				'id'          => 'mnp_radio_demo',
				'value'       => '',
				'class'       => 'mnp-radio-class',
				'placeholder' => __( 'Radio Demo', 'mwb-new-plugin' ),
				'options'     => array(
					'yes' => __( 'YES', 'mwb-new-plugin' ),
					'no'  => __( 'NO', 'mwb-new-plugin' ),
				),
			),

			array(
				'type'        => 'button',
				'id'          => 'mnp_button_demo',
				'button_text' => __( 'Button Demo', 'mwb-new-plugin' ),
				'class'       => 'mnp-button-class',
			),
		);
		return $mnp_settings_general;
	}
	/**
	 * Custom Post Type function for Products
	 *
	 * @return void
	 */
	public function mwb_custom_post_type() {
		register_post_type(
			'wpcust_product',
			array(
				'labels'               => array(
					'name'          => __( 'Products', 'textdomain' ),
					'singular_name' => __( 'Product', 'textdomain' ),
				),
				'public'               => true,
				'has_archive'          => true,
				'supports'             => array(
					'thumbnail',
					'title',
					'editor',
				),
				'register_meta_box_cb' => array( $this, 'add_custom_meta_box' ),
			)
		);
	}
	/**
	 * Add Meta-boxes function
	 *
	 * @return void
	 */
	public function add_custom_meta_box() {

		add_meta_box(
			'Product_id',
			'Product Meta Box',
			array( $this, 'meta_box_html' ),
			'wpcust_product',
			'side'
		);

	}
	/**
	 * Meta box html function
	 *
	 * @param array $posts comment.
	 * @return void
	 */
	public function meta_box_html( $posts ) {
		$price  = ! empty( get_post_meta( get_the_ID(), 'product_price', true ) ) ? get_post_meta( get_the_ID(), 'product_price', true ) : '';
		$sku    = ! empty( get_post_meta( get_the_ID(), 'product_sku', true ) ) ? get_post_meta( get_the_ID(), 'product_sku', true ) : '';
		$review = ! empty( get_post_meta( get_the_ID(), 'product_review', true ) ) ? get_post_meta( get_the_ID(), 'product_review', true ) : '';
		?>
		<p>
			<label for="price">PRICE</label>
			<input type="number" id="price" name="product_price" value="<?php echo esc_html( $price ); ?>">
		</p>
		<input type="hidden" name="nonce" id="nonce" value="<?php echo esc_html( wp_create_nonce() ); ?>">

		<p>
			<label for="sku"> S K U </label>
			<input type="text" id="sku" name="product_sku" value="<?php echo esc_html( $sku ); ?>">
		</p>
		<p>
			<label for="review">Review</label>
			<input type="text" id="review" name="product_review" value="<?php echo esc_html( $review ); ?>">
		</p>
		<?php
	}
	/**
	 * Save Form data function getting from custom Meta box.
	 *
	 * @param  array $post field argument.
	 * @return void
	 */
	public function mwb_save_form_data( $post ) {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce ) ) {
			$data = array(
				'product_price'  => ! empty( sanitize_text_field( wp_unslash( $_POST['product_price'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST ['product_price'] ) ) : '',
				'product_sku'    => ! empty( sanitize_text_field( wp_unslash( $_POST['product_sku'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST ['product_sku'] ) ) : '',
				'product_review' => ! empty( sanitize_text_field( wp_unslash( $_POST['product_review'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST ['product_review'] ) ) : '',
			);
			foreach ( $data as $key => $value ) {
				if ( array_key_exists( $key, $_POST ) ) {
					update_post_meta(
						$post,
						$key,
						$value
					);
				}
			}
		}
	}

	/**
	 * Custom post display with default post function
	 *
	 * @param object $query comment.
	 */
	public function mwb_add_custom_post_types( $query ) {

		if ( is_home() && $query->is_main_query() ) {

			$query->set( 'post_type', array( 'post', 'wpcust_product' ) );
		}

		return $query;
	}
	/**
	 * Cron schedule interval function
	 *
	 * @param array $schedules comment.
	 * @return schedules
	 */
	public function mwb_cron_callback( $schedules ) { 
		$schedules['twenty_minutes'] = array(
			'interval' => 36000,
			'display'  => esc_html__( 'Every Twenty minutes' ),
		);
		return $schedules;
	}

	/**
	 * Checking next schedule function
	 *
	 * @return void
	 */
	public function mwb_check_next_schedule() {
		if ( ! wp_next_scheduled( 'bl_cron_hook' ) ) {
			wp_schedule_event( time(), 'twenty_minutes', 'bl_cron_hook' );
		}
	}
	/**
	 * Custom cron hook callback function
	 *
	 * @return void
	 */
	public function mwb_custom_hook_callback() {
		$xml = simplexml_load_file( MWB_NEW_PLUGIN_DIR_PATH . 'product.xml' ) or die( 'Error: Cannot create object' );
		if ( ! empty( $xml ) ) {
			foreach ( $xml as $record ) {
				$title  = strval( $record->TITLE );
				$review = strval( $record->REVIEWS );
				$price  = strval( $record->PRICE ) ;
				$sku    = strval( $record->SKU ) ;

				$my_post   = array(
					'post_title'   => $title,
					'post_content' => '',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_type'    => 'wpcust_product',
					'meta_input'   => array(
						'product_price'  => $price,
						'product_sku'    => $sku,
						'product_review' => $review,
					),
				);
				wp_insert_post( $my_post );

			}
		}
	}
	/**
	 * Cron schedule interval function for export data
	 *
	 * @param array $schedules comment.
	 * @return schedules
	 */
	public function mwb_cron_export_callback( $schedules ) { 
		$schedules['fifteen_minutes'] = array(
			'interval' => 900,
			'display'  => esc_html__( 'Every fifteen hh minutes' ),
		);
		return $schedules;
	}

	/**
	 * Checking next schedule function for export
	 *
	 * @return void
	 */
	public function mwb_check_next_export_schedule() {
		if ( ! wp_next_scheduled( 'bl_export_hook' ) ) {
			wp_schedule_event( time(), 'fifteen_minutes', 'bl_export_hook' );
		}
	}
	/**
	 * Export Csv data function
	 *
	 * @return void
	 */
	public function mwb_custom_hook_export_callback() {
		// header('Content-Type: text/csv');
		// header('Content-Disposition: attachment; filename="export.csv"');
		// header('Pragma:  no cache');
		// header('Expires: 0');

		$file    = MWB_NEW_PLUGIN_DIR_PATH . 'export.csv';
		$db_file = fopen( $file, 'w+' );
		// $fp = fopen('php://output', 'w+');

		$args = array(
			'numberposts' => 100,
			'post_type'   => 'wpcust_product',
		);

		$data = get_posts( $args );

		fputcsv( $db_file, array( 'Title', 'SKU', 'Review' ) );
		// fputcsv( $fp, array( 'Title', 'SKU', 'Review' ) );

		foreach ( $data as $post ) {

			$postsa = $post->post_title;
			$postsb = get_post_meta( $post->ID, 'product_sku', true );
			$postsc = get_post_meta( $post->ID, 'product_review', true );
			fputcsv( $db_file, array( $postsa, $postsb, $postsc ) );
			// fputcsv( $fp, array( $postsa, $postsb, $postsc ), ',');

		}
		// fclose($fp );
		fclose( $db_file );

	}

	/**
	 * Cron schedule interval function for batches
	 *
	 * @param array $schedules comment.
	 * @return schedules
	 */
	public function mwb_cron_batch_callback( $schedules ) { 
		$schedules['thirty_minutes'] = array(
			'interval' => 1800,
			'display'  => esc_html__( 'Every Thirty minutes' ),
		);
		return $schedules;
	}

	/**
	 * Checking next schedule function for batch display
	 *
	 * @return void
	 */
	public function mwb_check_next_batch_schedule() {
		if ( ! wp_next_scheduled( 'bl_batch_hook' ) ) {
			wp_schedule_event( time(), 'thirty_minutes', 'bl_batch_hook' );
		}
	}
	/**
	 * Custom cron hook callback function for batch display
	 *
	 * @return void
	 */
	public function mwb_custom_hook_batch_callback() {
		$i = ( get_option( 'displaybatch', true ) ) ? get_option( 'displaybatch', true ) : 0;

		if ( empty( get_option( 'displaybatch' ) ) ) {
			$k = 10;
		} else {
			$k = $k + 10;
		}

		$temp = $i;
		$con  = get_option( 'displaybatch' );
		$xml  = simplexml_load_file( MWB_NEW_PLUGIN_DIR_PATH . 'product.xml' ) or die( 'Error: Cannot create object' );
		if ( ! empty( $xml ) ) {
			foreach ( $xml as $record ) {
				if ( $con > 0 ) {
					$con = $con - 1;
					continue;
				}
				$i = $i + 1;
				$title  = strval( $record->TITLE );
				$review = strval( $record->REVIEWS );
				$price  = strval( $record->PRICE ) ;
				$sku    = strval( $record->SKU ) ;

				$my_post = array(
					'post_title'   => $title,
					'post_content' => '',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_type'    => 'wpcust_product',
					'meta_input'   => array(
						'product_price'  => $price,
						'product_sku'    => $sku,
						'product_review' => $review,
					),
				);

				if ( ( $temp + 10 ) == $i ) {
					break;
				}
				wp_insert_post( $my_post );

			}
			update_option( 'displaybatch', $i );
		}

	}

}
