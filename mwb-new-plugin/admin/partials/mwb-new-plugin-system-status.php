<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html for system status.
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
// Template for showing information about system status.
global $mnp_mwb_mnp_obj;
$mnp_default_status = $mnp_mwb_mnp_obj->mwb_mnp_plug_system_status();
$mnp_wordpress_details = is_array( $mnp_default_status['wp'] ) && ! empty( $mnp_default_status['wp'] ) ? $mnp_default_status['wp'] : array();
$mnp_php_details = is_array( $mnp_default_status['php'] ) && ! empty( $mnp_default_status['php'] ) ? $mnp_default_status['php'] : array();
?>
<div class="mwb-mnp-table-wrap">
	<div class="mwb-mnp-table-inner-container">
		<table class="mwb-mnp-table" id="mwb-mnp-wp">
			<thead>
				<tr>
					<th><?php esc_html_e( 'WP Variables', 'mwb-new-plugin' ); ?></th>
					<th><?php esc_html_e( 'WP Values', 'mwb-new-plugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $mnp_wordpress_details ) && ! empty( $mnp_wordpress_details ) ) { ?>
					<?php foreach ( $mnp_wordpress_details as $wp_key => $wp_value ) { ?>
						<?php if ( isset( $wp_key ) && 'wp_users' != $wp_key ) { ?>
							<tr>
								<td><?php echo esc_html( $wp_key ); ?></td>
								<td><?php echo esc_html( $wp_value ); ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="mwb-mnp-table-inner-container">
		<table class="mwb-mnp-table" id="mwb-mnp-php">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Sysytem Variables', 'mwb-new-plugin' ); ?></th>
					<th><?php esc_html_e( 'System Values', 'mwb-new-plugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $mnp_php_details ) && ! empty( $mnp_php_details ) ) { ?>
					<?php foreach ( $mnp_php_details as $php_key => $php_value ) { ?>
						<tr>
							<td><?php echo esc_html( $php_key ); ?></td>
							<td><?php echo esc_html( $php_value ); ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
