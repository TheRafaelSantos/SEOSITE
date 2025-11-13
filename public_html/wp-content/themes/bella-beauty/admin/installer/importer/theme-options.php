<?php
namespace BellaBeautySpace\Admin\Installer\Importer;

use BellaBeautySpace\Admin\Installer\Importer\Importer_Base;
use BellaBeautySpace\Core\Utils\API_Requests;
use BellaBeautySpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme_Options handler class is responsible for different methods on importing theme options.
 */
class Theme_Options extends Importer_Base {

	/**
	 * Options.
	 */
	protected $options = array();

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return true;
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return get_option( 'cmsmasters_bella-beauty_theme_options_import', $default );
	}

	/**
	 * Set import status.
	 *
	 * @param string $status Import status, may be pending or done.
	 */
	public static function set_import_status( $status = 'pending' ) {
		update_option( 'cmsmasters_bella-beauty_theme_options_import', $status );
	}

	/**
	 * Set exists options.
	 */
	protected function set_exists_options() {
		$this->options = get_option( 'cmsmasters_bella-beauty_' . Utils::get_demo() . '_theme_options', array() );
	}

	/**
	 * Set options from API.
	 */
	protected function set_api_options() {
		if ( ! empty( $this->options ) ) {
			return;
		}

		$data = API_Requests::post_request( 'get-theme-options', array( 'demo' => Utils::get_demo() ) );

		if ( is_wp_error( $data ) ) {
			error_log( $data->get_error_message() );

			return;
		}

		if ( empty( $data ) ) {
			return;
		}

		$data = json_decode( $data, true );

		if ( is_array( $data ) && ! empty( $data ) ) {
			$this->options = $data;
		}
	}

	/**
	 * Import options.
	 */
	protected function import_options() {
		if ( empty( $this->options ) ) {
			return;
		}

		update_option( 'cmsmasters_bella-beauty_options', $this->options );
	}

	/**
	 * Backup current options.
	 *
	 * @param bool $first_install First install trigger, if need to backup customer option from previous theme.
	 */
	public static function set_backup_options( $first_install = false ) {
		if ( $first_install ) {
			return;
		}

		$options = Utils::get_theme_options();

		if ( empty( $options ) ) {
			return;
		}

		update_option( 'cmsmasters_bella-beauty_' . Utils::get_demo() . '_theme_options', $options );
	}

}
