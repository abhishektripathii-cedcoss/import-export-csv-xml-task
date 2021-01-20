<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_new_plugin
 * @subpackage Mwb_new_plugin/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $mnp_mwb_mnp_obj;
$mnp_active_tab   = isset( $_GET['mnp_tab'] ) ? sanitize_key( $_GET['mnp_tab'] ) : 'mwb-new-plugin-general';
$mnp_default_tabs = $mnp_mwb_mnp_obj->mwb_mnp_plug_default_tabs();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-mnp-main-wrapper">
	<div class="mwb-mnp-go-pro">
		<div class="mwb-mnp-go-pro-banner">
			<div class="mwb-mnp-inner-container">
				<div class="mwb-mnp-name-wrapper">
					<p><?php esc_html_e( 'mwb-new-plugin', 'mwb-new-plugin' ); ?></p></div>
					<div class="mwb-mnp-static-menu">
						<ul>
							<li>
								<a href="<?php echo esc_url( 'https://makewebbetter.com/contact-us/' ); ?>" target="_blank">
									<span class="dashicons dashicons-phone"></span>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( 'https://docs.makewebbetter.com/hubspot-woocommerce-integration/' ); ?>" target="_blank">
									<span class="dashicons dashicons-media-document"></span>
								</a>
							</li>
							<?php $mnp_plugin_pro_link = apply_filters( 'mnp_pro_plugin_link', '' ); ?>
							<?php if ( isset( $mnp_plugin_pro_link ) && '' != $mnp_plugin_pro_link ) { ?>
								<li class="mwb-mnp-main-menu-button">
									<a id="mwb-mnp-go-pro-link" href="<?php echo esc_url( $mnp_plugin_pro_link ); ?>" class="" title="" target="_blank"><?php esc_html_e( 'GO PRO NOW', 'mwb-new-plugin' ); ?></a>
								</li>
							<?php } else { ?>
								<li class="mwb-mnp-main-menu-button">
									<a id="mwb-mnp-go-pro-link" href="#" class="" title=""><?php esc_html_e( 'GO PRO NOW', 'mwb-new-plugin' ); ?></a>
								</li>
							<?php } ?>
							<?php $mnp_plugin_pro = apply_filters( 'mnp_pro_plugin_purcahsed', 'no' ); ?>
							<?php if ( isset( $mnp_plugin_pro ) && 'yes' == $mnp_plugin_pro ) { ?>
								<li>
									<a id="mwb-mnp-skype-link" href="<?php echo esc_url( 'https://join.skype.com/invite/IKVeNkLHebpC' ); ?>" target="_blank">
										<img src="<?php echo esc_url( MWB_NEW_PLUGIN_DIR_URL . 'admin/images/skype_logo.png' ); ?>" style="height: 15px;width: 15px;" ><?php esc_html_e( 'Chat Now', 'mwb-new-plugin' ); ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="mwb-mnp-main-template">
			<div class="mwb-mnp-body-template">
				<div class="mwb-mnp-navigator-template">
					<div class="mwb-mnp-navigations">
						<?php
						if ( is_array( $mnp_default_tabs ) && ! empty( $mnp_default_tabs ) ) {

							foreach ( $mnp_default_tabs as $mnp_tab_key => $mnp_default_tabs ) {

								$mnp_tab_classes = 'mwb-mnp-nav-tab ';

								if ( ! empty( $mnp_active_tab ) && $mnp_active_tab === $mnp_tab_key ) {
									$mnp_tab_classes .= 'mnp-nav-tab-active';
								}
								?>
								
								<div class="mwb-mnp-tabs">
									<a class="<?php echo esc_attr( $mnp_tab_classes ); ?>" id="<?php echo esc_attr( $mnp_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=mwb_new_plugin_menu' ) . '&mnp_tab=' . esc_attr( $mnp_tab_key ) ); ?>"><?php echo esc_html( $mnp_default_tabs['title'] ); ?></a>
								</div>

								<?php
							}
						}
						?>
					</div>
				</div>

				<div class="mwb-mnp-content-template">
					<div class="mwb-mnp-content-container">
						<?php
							// if submenu is directly clicked on woocommerce.
						if ( empty( $mnp_active_tab ) ) {

							$mnp_active_tab = 'mwb_mnp_plug_general';
						}

							// look for the path based on the tab id in the admin templates.
						$mnp_tab_content_path = 'admin/partials/' . $mnp_active_tab . '.php';

						$mnp_mwb_mnp_obj->mwb_mnp_plug_load_template( $mnp_tab_content_path );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
