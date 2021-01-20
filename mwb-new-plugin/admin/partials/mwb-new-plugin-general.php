<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
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
global $mnp_mwb_mnp_obj;
$mnp_genaral_settings = apply_filters( 'mnp_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<div class="mnp-secion-wrap">
	<table class="form-table mnp-settings-table">
		<?php
			$mnp_general_html = $mnp_mwb_mnp_obj->mwb_mnp_plug_generate_html( $mnp_genaral_settings );
			echo esc_html( $mnp_general_html );
		?>
	</table>
</div>
