<?php
namespace BellaBeautySpace\Admin\Options\Pages;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Installation_Log handler class is responsible for different methods on installation-log theme options page.
 */
class Installation_Log extends Base\Base_Page {

	/**
	 * Get page title.
	 */
	public static function get_page_title() {
		return esc_attr__( 'Installation Log', 'bella-beauty' );
	}

	/**
	 * Get menu title.
	 */
	public static function get_menu_title() {
		return esc_attr__( 'Installation Log', 'bella-beauty' );
	}

	/**
	 * Visibility Status.
	 */
	public static function get_visibility_status() {
		if ( 'run' !== get_option( 'cmsmasters_bella-beauty_installation_status' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Render page content.
	 */
	public function render_content() {
		$upload_dir = wp_upload_dir();

		echo '<div class="cmsmasters-options-installation-log">
			<div class="cmsmasters-options-installation-log__text">
				<p>' .
					esc_html__( 'The installation log is a record of the activities and events that occur during the theme installation process.', 'bella-beauty' ) .
					'<br />' .
					esc_html__( 'It captures a wide range of information, including errors and warnings, and serves as a diagnostic tool for identifying errors that occur on a website.', 'bella-beauty' ) .
				'</p>
			</div>
			<div class="cmsmasters-options-installation-log__button-wrap">
				<a href="' . esc_url( $upload_dir['baseurl'] . '/merlin-wp/main.log' ) . '" class="button" download>' . esc_html__( 'Download Installation Log', 'bella-beauty' ) . '</a>
			</div>
		</div>';
	}

}
