<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_new_plugin
 * @subpackage Mwb_new_plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace mwb_new_plugin_public.
 *
 * @package    Mwb_new_plugin
 * @subpackage Mwb_new_plugin/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_new_plugin_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mnp_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, MWB_NEW_PLUGIN_DIR_URL . 'public/css/mwb-new-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mnp_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, MWB_NEW_PLUGIN_DIR_URL . 'public/js/mwb-new-plugin-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'mnp_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

	}

}
