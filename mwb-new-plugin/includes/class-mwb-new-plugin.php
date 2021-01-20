<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_new_plugin
 * @subpackage Mwb_new_plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mwb_new_plugin
 * @subpackage Mwb_new_plugin/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_new_plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mwb_new_plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'MWB_NEW_PLUGIN_VERSION' ) ) {

			$this->version = MWB_NEW_PLUGIN_VERSION;
		} else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'mwb-new-plugin';

		$this->mwb_new_plugin_dependencies();
		$this->mwb_new_plugin_locale();
		$this->mwb_new_plugin_admin_hooks();
		$this->mwb_new_plugin_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_new_plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_new_plugin_i18n. Defines internationalization functionality.
	 * - Mwb_new_plugin_Admin. Defines all hooks for the admin area.
	 * - Mwb_new_plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_new_plugin_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-new-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-new-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb-new-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb-new-plugin-public.php';

		$this->loader = new Mwb_new_plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_new_plugin_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_new_plugin_locale() {

		$plugin_i18n = new Mwb_new_plugin_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_new_plugin_admin_hooks() {

		$mnp_plugin_admin = new Mwb_new_plugin_Admin( $this->mnp_get_plugin_name(), $this->mnp_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $mnp_plugin_admin, 'mnp_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $mnp_plugin_admin, 'mnp_admin_enqueue_scripts' );

		// Add settings menu for mwb-new-plugin.
		$this->loader->add_action( 'admin_menu', $mnp_plugin_admin, 'mnp_options_page' );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $mnp_plugin_admin, 'mnp_admin_submenu_page', 15 );
		$this->loader->add_filter( 'mnp_general_settings_array', $mnp_plugin_admin, 'mnp_admin_general_settings_page', 10 );

		// Add Custom post type.
		$this->loader->add_action( 'init', $mnp_plugin_admin, 'mwb_custom_post_type', 99 );
		// Add display of Custom post type with default posts.
		$this->loader->add_action( 'pre_get_posts', $mnp_plugin_admin, 'mwb_add_custom_post_types', 99 );
		// Save meta box data.
		$this->loader->add_action( 'save_post', $mnp_plugin_admin, 'mwb_save_form_data', 99 );
		// Add init action for next schedule check in cron.
		$this->loader->add_action( 'init', $mnp_plugin_admin, 'mwb_check_next_schedule', 99 );
		// Scheduling a cron using custom interval.
		$this->loader->add_filter( 'cron_schedules', $mnp_plugin_admin, 'mwb_cron_callback', 99 );
		// Custom hook for cron event.
		$this->loader->add_filter( 'bl_cron_hook', $mnp_plugin_admin, 'mwb_custom_hook_callback', 99 );
		// Scheduling a cron for export using custom interval.
		$this->loader->add_filter( 'cron_schedules', $mnp_plugin_admin, 'mwb_cron_export_callback', 99 );
		// Add init action for next export schedule check in cron.
		$this->loader->add_action( 'init', $mnp_plugin_admin, 'mwb_check_next_export_schedule', 99 );
		// Custom hook for cron event for export.
		$this->loader->add_filter( 'bl_export_hook', $mnp_plugin_admin, 'mwb_custom_hook_export_callback', 99 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_new_plugin_public_hooks() {

		$mnp_plugin_public = new Mwb_new_plugin_Public( $this->mnp_get_plugin_name(), $this->mnp_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $mnp_plugin_public, 'mnp_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $mnp_plugin_public, 'mnp_public_enqueue_scripts' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function mnp_run() {
		$this->loader->mnp_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function mnp_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mwb_new_plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function mnp_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function mnp_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_mnp_plug tabs.
	 *
	 * @return  Array       An key=>value pair of mwb-new-plugin tabs.
	 */
	public function mwb_mnp_plug_default_tabs() {

		$mnp_default_tabs = array();

		$mnp_default_tabs['mwb-new-plugin-general'] = array(
			'title'       => esc_html__( 'General Setting', 'mwb-new-plugin' ),
			'name'        => 'mwb-new-plugin-general',
		);
		$mnp_default_tabs = apply_filters( 'mwb_mnp_plugin_standard_admin_settings_tabs', $mnp_default_tabs );

		$mnp_default_tabs['mwb-new-plugin-system-status'] = array(
			'title'       => esc_html__( 'System Status', 'mwb-new-plugin' ),
			'name'        => 'mwb-new-plugin-system-status',
		);

		return $mnp_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_mnp_plug_load_template( $path, $params = array() ) {

		$mnp_file_path = MWB_NEW_PLUGIN_DIR_PATH . $path;

		if ( file_exists( $mnp_file_path ) ) {

			include $mnp_file_path;
		} else {

			/* translators: %s: file path */
			$mnp_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'mwb-new-plugin' ), $mnp_file_path );
			$this->mwb_mnp_plug_admin_notice( $mnp_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $mnp_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function mwb_mnp_plug_admin_notice( $mnp_message, $type = 'error' ) {

		$mnp_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$mnp_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$mnp_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$mnp_classes .= 'notice-success is-dismissible';
				break;

			default:
				$mnp_classes .= 'notice-error is-dismissible';
		}

		$mnp_notice  = '<div class="' . esc_attr( $mnp_classes ) . '">';
		$mnp_notice .= '<p>' . esc_html( $mnp_message ) . '</p>';
		$mnp_notice .= '</div>';

		echo wp_kses_post( $mnp_notice );
	}


	/**
	 * Show wordpress and server info.
	 *
	 * @return  Array $mnp_system_data       returns array of all wordpress and server related information.
	 * @since  1.0.0
	 */
	public function mwb_mnp_plug_system_status() {
		global $wpdb;
		$mnp_system_status = array();
		$mnp_wordpress_status = array();
		$mnp_system_data = array();

		// Get the web server.
		$mnp_system_status['web_server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

		// Get PHP version.
		$mnp_system_status['php_version'] = function_exists( 'phpversion' ) ? phpversion() : __( 'N/A (phpversion function does not exist)', 'mwb-new-plugin' );

		// Get the server's IP address.
		$mnp_system_status['server_ip'] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '';

		// Get the server's port.
		$mnp_system_status['server_port'] = isset( $_SERVER['SERVER_PORT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_PORT'] ) ) : '';

		// Get the uptime.
		$mnp_system_status['uptime'] = function_exists( 'exec' ) ? @exec( 'uptime -p' ) : __( 'N/A (make sure exec function is enabled)', 'mwb-new-plugin' );

		// Get the server path.
		$mnp_system_status['server_path'] = defined( 'ABSPATH' ) ? ABSPATH : __( 'N/A (ABSPATH constant not defined)', 'mwb-new-plugin' );

		// Get the OS.
		$mnp_system_status['os'] = function_exists( 'php_uname' ) ? php_uname( 's' ) : __( 'N/A (php_uname function does not exist)', 'mwb-new-plugin' );

		// Get WordPress version.
		$mnp_wordpress_status['wp_version'] = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : __( 'N/A (get_bloginfo function does not exist)', 'mwb-new-plugin' );

		// Get and count active WordPress plugins.
		$mnp_wordpress_status['wp_active_plugins'] = function_exists( 'get_option' ) ? count( get_option( 'active_plugins' ) ) : __( 'N/A (get_option function does not exist)', 'mwb-new-plugin' );

		// See if this site is multisite or not.
		$mnp_wordpress_status['wp_multisite'] = function_exists( 'is_multisite' ) && is_multisite() ? __( 'Yes', 'mwb-new-plugin' ) : __( 'No', 'mwb-new-plugin' );

		// See if WP Debug is enabled.
		$mnp_wordpress_status['wp_debug_enabled'] = defined( 'WP_DEBUG' ) ? __( 'Yes', 'mwb-new-plugin' ) : __( 'No', 'mwb-new-plugin' );

		// See if WP Cache is enabled.
		$mnp_wordpress_status['wp_cache_enabled'] = defined( 'WP_CACHE' ) ? __( 'Yes', 'mwb-new-plugin' ) : __( 'No', 'mwb-new-plugin' );

		// Get the total number of WordPress users on the site.
		$mnp_wordpress_status['wp_users'] = function_exists( 'count_users' ) ? count_users() : __( 'N/A (count_users function does not exist)', 'mwb-new-plugin' );

		// Get the number of published WordPress posts.
		$mnp_wordpress_status['wp_posts'] = wp_count_posts()->publish >= 1 ? wp_count_posts()->publish : __( '0', 'mwb-new-plugin' );

		// Get PHP memory limit.
		$mnp_system_status['php_memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'mwb-new-plugin' );

		// Get the PHP error log path.
		$mnp_system_status['php_error_log_path'] = ! ini_get( 'error_log' ) ? __( 'N/A', 'mwb-new-plugin' ) : ini_get( 'error_log' );

		// Get PHP max upload size.
		$mnp_system_status['php_max_upload'] = function_exists( 'ini_get' ) ? (int) ini_get( 'upload_max_filesize' ) : __( 'N/A (ini_get function does not exist)', 'mwb-new-plugin' );

		// Get PHP max post size.
		$mnp_system_status['php_max_post'] = function_exists( 'ini_get' ) ? (int) ini_get( 'post_max_size' ) : __( 'N/A (ini_get function does not exist)', 'mwb-new-plugin' );

		// Get the PHP architecture.
		if ( PHP_INT_SIZE == 4 ) {
			$mnp_system_status['php_architecture'] = '32-bit';
		} elseif ( PHP_INT_SIZE == 8 ) {
			$mnp_system_status['php_architecture'] = '64-bit';
		} else {
			$mnp_system_status['php_architecture'] = 'N/A';
		}

		// Get server host name.
		$mnp_system_status['server_hostname'] = function_exists( 'gethostname' ) ? gethostname() : __( 'N/A (gethostname function does not exist)', 'mwb-new-plugin' );

		// Show the number of processes currently running on the server.
		$mnp_system_status['processes'] = function_exists( 'exec' ) ? @exec( 'ps aux | wc -l' ) : __( 'N/A (make sure exec is enabled)', 'mwb-new-plugin' );

		// Get the memory usage.
		$mnp_system_status['memory_usage'] = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 ) : 0;

		// Get CPU usage.
		// Check to see if system is Windows, if so then use an alternative since sys_getloadavg() won't work.
		if ( stristr( PHP_OS, 'win' ) ) {
			$mnp_system_status['is_windows'] = true;
			$mnp_system_status['windows_cpu_usage'] = function_exists( 'exec' ) ? @exec( 'wmic cpu get loadpercentage /all' ) : __( 'N/A (make sure exec is enabled)', 'mwb-new-plugin' );
		}

		// Get the memory limit.
		$mnp_system_status['memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'mwb-new-plugin' );

		// Get the PHP maximum execution time.
		$mnp_system_status['php_max_execution_time'] = function_exists( 'ini_get' ) ? ini_get( 'max_execution_time' ) : __( 'N/A (ini_get function does not exist)', 'mwb-new-plugin' );

		// Get outgoing IP address.
		$mnp_system_status['outgoing_ip'] = function_exists( 'file_get_contents' ) ? file_get_contents( 'http://ipecho.net/plain' ) : __( 'N/A (file_get_contents function does not exist)', 'mwb-new-plugin' );

		$mnp_system_data['php'] = $mnp_system_status;
		$mnp_system_data['wp'] = $mnp_wordpress_status;

		return $mnp_system_data;
	}

	/**
	 * Generate html components.
	 *
	 * @param  string $mnp_components    html to display.
	 * @since  1.0.0
	 */
	public function mwb_mnp_plug_generate_html( $mnp_components = array() ) {
		if ( is_array( $mnp_components ) && ! empty( $mnp_components ) ) {
			foreach ( $mnp_components as $mnp_component ) {
				switch ( $mnp_component['type'] ) {

					case 'hidden':
					case 'number':
					case 'password':
					case 'email':
					case 'text':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $mnp_component['id'] ); ?>"><?php echo esc_html( $mnp_component['title'] ); // WPCS: XSS ok. ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $mnp_component['type'] ) ); ?>">
								<input
								name="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								id="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								type="<?php echo esc_attr( $mnp_component['type'] ); ?>"
								value="<?php echo esc_attr( $mnp_component['value'] ); ?>"
								class="<?php echo esc_attr( $mnp_component['class'] ); ?>"
								placeholder="<?php echo esc_attr( $mnp_component['placeholder'] ); ?>"
								/>
								<p class="mnp-descp-tip"><?php echo esc_html( $mnp_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'textarea':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $mnp_component['id'] ); ?>"><?php echo esc_html( $mnp_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $mnp_component['type'] ) ); ?>">
								<textarea
								name="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								id="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								class="<?php echo esc_attr( $mnp_component['class'] ); ?>"
								rows="<?php echo esc_attr( $mnp_component['rows'] ); ?>"
								cols="<?php echo esc_attr( $mnp_component['cols'] ); ?>"
								placeholder="<?php echo esc_attr( $mnp_component['placeholder'] ); ?>"
								><?php echo esc_textarea( $mnp_component['value'] ); // WPCS: XSS ok. ?></textarea>
								<p class="mnp-descp-tip"><?php echo esc_html( $mnp_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'select':
					case 'multiselect':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $mnp_component['id'] ); ?>"><?php echo esc_html( $mnp_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $mnp_component['type'] ) ); ?>">
								<select
								name="<?php echo esc_attr( $mnp_component['id'] ); ?><?php echo ( 'multiselect' === $mnp_component['type'] ) ? '[]' : ''; ?>"
								id="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								class="<?php echo esc_attr( $mnp_component['class'] ); ?>"
								<?php echo 'multiselect' === $mnp_component['type'] ? 'multiple="multiple"' : ''; ?>
								>
								<?php
								foreach ( $mnp_component['options'] as $mnp_key => $mnp_val ) {
									?>
									<option value="<?php echo esc_attr( $mnp_key ); ?>"
										<?php
										if ( is_array( $mnp_component['value'] ) ) {
											selected( in_array( (string) $mnp_key, $mnp_component['value'], true ), true );
										} else {
											selected( $mnp_component['value'], (string) $mnp_key );
										}
										?>
										>
										<?php echo esc_html( $mnp_val ); ?>
									</option>
									<?php
								}
								?>
								</select> 
								<p class="mnp-descp-tip"><?php echo esc_html( $mnp_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'checkbox':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc"><?php echo esc_html( $mnp_component['title'] ); ?></th>
							<td class="forminp forminp-checkbox">
								<label for="<?php echo esc_attr( $mnp_component['id'] ); ?>"></label>
								<input
								name="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								id="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								type="checkbox"
								class="<?php echo esc_attr( isset( $mnp_component['class'] ) ? $mnp_component['class'] : '' ); ?>"
								value="1"
								<?php checked( $mnp_component['value'], '1' ); ?>
								/> 
								<span class="mnp-descp-tip"><?php echo esc_html( $mnp_component['description'] ); // WPCS: XSS ok. ?></span>

							</td>
						</tr>
						<?php
						break;

					case 'radio':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $mnp_component['id'] ); ?>"><?php echo esc_html( $mnp_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $mnp_component['type'] ) ); ?>">
								<fieldset>
									<span class="mnp-descp-tip"><?php echo esc_html( $mnp_component['description'] ); // WPCS: XSS ok. ?></span>
									<ul>
										<?php
										foreach ( $mnp_component['options'] as $mnp_radio_key => $mnp_radio_val ) {
											?>
											<li>
												<label><input
													name="<?php echo esc_attr( $mnp_component['id'] ); ?>"
													value="<?php echo esc_attr( $mnp_radio_key ); ?>"
													type="radio"
													class="<?php echo esc_attr( $mnp_component['class'] ); ?>"
												<?php checked( $mnp_radio_key, $mnp_component['value'] ); ?>
													/> <?php echo esc_html( $mnp_radio_val ); ?></label>
											</li>
											<?php
										}
										?>
									</ul>
								</fieldset>
							</td>
						</tr>
						<?php
						break;

					case 'button':
						?>
						<tr valign="top">
							<td scope="row">
								<input type="button" class="button button-primary" 
								name="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								id="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								value="<?php echo esc_attr( $mnp_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
						<?php
						break;

					case 'submit':
						?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								id="<?php echo esc_attr( $mnp_component['id'] ); ?>"
								value="<?php echo esc_attr( $mnp_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
						<?php
						break;

					default:
						break;
				}
			}
		}
	}
}
