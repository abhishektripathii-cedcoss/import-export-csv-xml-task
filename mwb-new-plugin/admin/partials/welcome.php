<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the welcome html.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_new_plugin
 * @subpackage Mwb_new_plugin/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-mnp-main-wrapper">
	<div class="mwb-mnp-go-pro">
		<div class="mwb-mnp-go-pro-banner">
			<div class="mwb-mnp-inner-container">
				<div class="mwb-mnp-name-wrapper" id="mwb-mnp-page-header">
					<h3><?php esc_html_e( 'Welcome To MakeWebBetter', 'mwb-new-plugin' ); ?></h4>
					</div>
				</div>
			</div>
			<div class="mwb-mnp-inner-logo-container">
				<div class="mwb-mnp-main-logo">
					<img src="<?php echo esc_url( MWB_NEW_PLUGIN_DIR_URL . 'admin/images/logo.png' ); ?>">
					<h2><?php esc_html_e( 'We make the customer experience better', 'mwb-new-plugin' ); ?></h2>
					<h3><?php esc_html_e( 'Being best at something feels great. Every Business desires a smooth buyer’s journey, WE ARE BEST AT IT.', 'mwb-new-plugin' ); ?></h3>
				</div>
				<div class="mwb-mnp-active-plugins-list">
					<?php
					$mwb_mnp_all_plugins = get_option( 'mwb_all_plugins_active', false );
					if ( is_array( $mwb_mnp_all_plugins ) && ! empty( $mwb_mnp_all_plugins ) ) {
						?>
						<table class="mwb-mnp-table">
							<thead>
								<tr class="mwb-plugins-head-row">
									<th><?php esc_html_e( 'Plugin Name', 'mwb-new-plugin' ); ?></th>
									<th><?php esc_html_e( 'Active Status', 'mwb-new-plugin' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ( is_array( $mwb_mnp_all_plugins ) && ! empty( $mwb_mnp_all_plugins ) ) { ?>
									<?php foreach ( $mwb_mnp_all_plugins as $mnp_plugin_key => $mnp_plugin_value ) { ?>
										<tr class="mwb-plugins-row">
											<td><?php echo esc_html( $mnp_plugin_value['plugin_name'] ); ?></td>
											<?php if ( isset( $mnp_plugin_value['active'] ) && '1' != $mnp_plugin_value['active'] ) { ?>
												<td><?php esc_html_e( 'NO', 'mwb-new-plugin' ); ?></td>
											<?php } else { ?>
												<td><?php esc_html_e( 'YES', 'mwb-new-plugin' ); ?></td>
											<?php } ?>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
						<?php
					}
					?>
				</div>
			</div>
		</div>
