<?php
namespace BellaBeautySpace\Admin\Installer\Importer;

use BellaBeautySpace\Core\Utils\API_Requests;
use BellaBeautySpace\Core\Utils\File_Manager;
use BellaBeautySpace\Core\Utils\Utils;

use Elementor\Plugin as Elementor_Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Templates handler class is responsible for different methods on importing "Elementor" plugin templates.
 */
class Elementor_Templates {

	/**
	 * Cached data.
	 */
	private static $cached_import_status = array();

	/**
	 * Elementor Templates Import constructor.
	 */
	public function __construct() {
		add_action( 'cmsmasters_set_import_status', array( get_called_class(), 'set_import_status' ) );

		add_action( 'cmsmasters_set_apply_demo_status', array( get_called_class(), 'set_apply_demo_status' ) );

		add_action( 'cmsmasters_set_backup_options', array( get_called_class(), 'set_backup_options' ) );

		if ( self::activation_status() && API_Requests::check_token_status() ) {
			add_action( 'admin_init', array( $this, 'admin_init_actions' ) );

			add_action( 'elementor/template-library/after_save_template', array( $this, 'set_import_templates_ids' ), 10, 2 );
		}
	}

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return ( did_action( 'elementor/loaded' ) && class_exists( 'Cmsmasters_Elementor_Addon' ) );
	}

	/**
	 * Set import status.
	 *
	 * @param string $status Import status, may be pending or done.
	 */
	public static function set_import_status( $status = 'pending' ) {
		$demo = Utils::get_demo();

		if ( 'done' !== get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_import" ) ) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_import", $status );
		}

		if ( 'done' !== get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_woocommerce_import" ) ) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_woocommerce_import", $status );
		}

		if ( 'done' !== get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_pmpro_import" ) ) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_pmpro_import", $status );
		}

		if ( 'done' !== get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_givewp_import" ) ) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_givewp_import", $status );
		}

		if ( 'done' !== get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_tribe-events_import" ) ) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_tribe-events_import", $status );
		}
	}

	/**
	 * Set apply demo status.
	 *
	 * @param string $status Apply demo status, may be pending or done.
	 */
	public static function set_apply_demo_status( $status = 'pending' ) {
		update_option( 'cmsmasters_bella-beauty_elementor_templates_apply_demo', $status );
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

		$options = get_option( 'cmsmasters_elementor_documents_locations', array() );

		update_option( 'cmsmasters_bella-beauty_' . Utils::get_demo() . '_elementor_documents_locations', $options );

		do_action( 'cmsmasters_remove_all_elementor_locations' );
	}

	/**
	 * Actions on admin_init hook.
	 */
	public function admin_init_actions() {
		if ( wp_doing_ajax() ) {
			return;
		}

		if (
			isset( $_POST['tgmpa-page'] ) &&
			'tgmpa-install-plugins' === $_POST['tgmpa-page']
		) {
			return;
		}

		$demo = Utils::get_demo();

		if ( 'pending' === get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_import", 'done' ) ) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_import", 'done' );

			$this->import_templates( 'templates_path' );
		}

		if (
			class_exists( 'woocommerce' ) &&
			'pending' === get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_woocommerce_import", 'done' )
		) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_woocommerce_import", 'done' );

			$this->import_templates( 'templates_woocommerce_path' );
		}

		if (
			function_exists( 'pmpro_is_plugin_active' ) &&
			'pending' === get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_pmpro_import", 'done' )
		) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_pmpro_import", 'done' );

			$this->import_templates( 'templates_pmpro_path' );
		}

		if (
			class_exists( 'Give' ) &&
			'pending' === get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_givewp_import", 'done' )
		) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_givewp_import", 'done' );

			$this->import_templates( 'templates_givewp_path' );
		}

		if (
			class_exists( 'Tribe__Events__Main' ) &&
			'pending' === get_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_tribe-events_import", 'done' )
		) {
			update_option( "cmsmasters_bella-beauty_{$demo}_elementor_templates_tribe-events_import", 'done' );

			$this->import_templates( 'templates_tribe-events_path' );
		}

		if ( 'pending' === get_option( 'cmsmasters_bella-beauty_elementor_templates_apply_demo', 'done' ) ) {
			if ( false === get_option( "cmsmasters_bella-beauty_{$demo}_elementor_documents_locations" ) ) {
				if ( ! did_action( 'cmsmasters_remove_unique_elementor_locations' ) ) {
					do_action( 'cmsmasters_remove_unique_elementor_locations' );
				}
			} else {
				$locations = get_option( "cmsmasters_bella-beauty_{$demo}_elementor_documents_locations", array() );

				update_option( 'cmsmasters_elementor_documents_locations', $locations );

				if ( ! did_action( 'cmsmasters_restore_elementor_locations' ) ) {
					do_action( 'cmsmasters_restore_elementor_locations' );
				}
			}

			update_option( 'cmsmasters_bella-beauty_elementor_templates_apply_demo', 'done' );
		}
	}

	/**
	 * Import templates.
	 */
	protected function import_templates( $data_key ) {
		if (
			! empty( self::$cached_import_status[ $data_key ] ) &&
			'done' === self::$cached_import_status[ $data_key ]
		) {
			return;
		}

		$file_path = $this->get_api_data( $data_key );

		if ( empty( $file_path ) ) {
			return;
		}

		self::$cached_import_status[ $data_key ] = 'done';

		$file_path = File_Manager::download_temp_file( $file_path, $data_key . '-' . uniqid() . '.zip' );

		$extracted_files = Elementor_Plugin::$instance->uploads_manager->extract_and_validate_zip( $file_path, [ 'json' ] );

		if ( is_wp_error( $extracted_files ) ) {
			// Delete the temporary extraction directory, since it's now not necessary.
			Elementor_Plugin::$instance->uploads_manager->remove_file_or_dir( $extracted_files['extraction_directory'] );

			error_log( "Templates import: Error with extracted files from {$file_path}" );

			return;
		}

		$demo = Utils::get_demo();

		foreach ( $extracted_files['files'] as $template_file_path ) {
			$template_data = json_decode( File_Manager::get_file_contents( $template_file_path ), true );

			if ( empty( $template_data ) || ! isset( $template_data['page_settings']['cmsmasters_document_export_id'] ) ) {
				error_log( 'Templates import: Invalid template file data' );

				continue;
			}

			$imported_templates_ids = get_option( "cmsmasters_bella-beauty_{$demo}_elementor_import_templates_ids" );

			if ( isset( $imported_templates_ids[ $template_data['page_settings']['cmsmasters_document_export_id'] ] ) ) {
				error_log( 'Skipping import template ' . $template_data['title'] . ', this template already imported' );

				continue;
			}

			$source = Elementor_Plugin::$instance->templates_manager->get_source( 'local' );

			$source->import_template( basename( $template_file_path ), $template_file_path );
		}

		// Delete the temporary extraction directory, since it's now not necessary.
		Elementor_Plugin::$instance->uploads_manager->remove_file_or_dir( $extracted_files['extraction_directory'] );

		@unlink( $file_path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}

	/**
	 * Get API data.
	 *
	 * @param string $data_key Data key.
	 * @param type param What_is_it.
	 *
	 * @return string
	 */
	protected function get_api_data( $data_key ) {
		$data = API_Requests::post_request( 'get-elementor-templates', array( 'demo' => Utils::get_demo() ) );

		if ( is_wp_error( $data ) ) {
			error_log( $data->get_error_message() );

			return '';
		}

		if ( ! isset( $data[ $data_key ] ) ) {
			return '';
		}

		return $data[ $data_key ];
	}

	/**
	 * Set import templates ids.
	 *
	 * @param int $template_id Template id.
	 * @param array $template_data Template data.
	 */
	public function set_import_templates_ids( $template_id, $template_data ) {
		$demo = Utils::get_demo();

		$templates_ids = get_option( "cmsmasters_bella-beauty_{$demo}_elementor_import_templates_ids" );

		if ( false === $templates_ids ) {
			$templates_ids = array();
		}

		if ( ! isset( $template_data['page_settings']['cmsmasters_document_export_id'] ) ) {
			return;
		}

		$old_id = $template_data['page_settings']['cmsmasters_document_export_id'];

		if ( empty( $old_id ) ) {
			return;
		}

		$templates_ids[ $old_id ] = $template_id;

		update_option( "cmsmasters_bella-beauty_{$demo}_elementor_import_templates_ids", $templates_ids, false );
	}

	/**
	 * Change templates ids in posts _elementor_data on import.
	 *
	 * @param array $element Elementor element.
	 * @param array $templates_ids Templates ids.
	 *
	 * @return array Elementor element.
	 */
	public static function change_import_templates_ids( $element, $templates_ids = array() ) {
		if ( empty( $element['widgetType'] ) ) {
			return $element;
		}

		if (
			(
				'cmsmasters-blog-grid' === $element['widgetType'] ||
				'cmsmasters-blog-slider' === $element['widgetType'] ||
				'cmsmasters-archive-posts' === $element['widgetType']
			) &&
			! empty( $element['settings']['blog_template_id'] )
		) {
			$old_id = $element['settings']['blog_template_id'];

			if ( isset( $templates_ids[ $old_id ] ) ) {
				$element['settings']['blog_template_id'] = strval( $templates_ids[ $old_id ] );
			}
		} elseif ( 'cmsmasters-blog-featured' === $element['widgetType'] ) {
			if ( ! empty( $element['settings']['post_featured_template_id'] ) ) {
				$old_id = $element['settings']['post_featured_template_id'];

				if ( isset( $templates_ids[ $old_id ] ) ) {
					$element['settings']['post_featured_template_id'] = strval( $templates_ids[ $old_id ] );
				}
			}

			if ( ! empty( $element['settings']['post_regular_template_id'] ) ) {
				$old_id = $element['settings']['post_regular_template_id'];

				if ( isset( $templates_ids[ $old_id ] ) ) {
					$element['settings']['post_regular_template_id'] = strval( $templates_ids[ $old_id ] );
				}
			}
		} elseif ( 'cmsmasters-offcanvas' === $element['widgetType'] ) {
			if ( ! empty( $element['settings']['content_block'] ) ) {
				foreach ( $element['settings']['content_block'] as $index => $args ) {
					if ( ! empty( $args['saved_section'] ) ) {
						$old_id = $args['saved_section'];

						if ( isset( $templates_ids[ $old_id ] ) ) {
							$element['settings']['content_block'][ $index ]['saved_section'] = strval( $templates_ids[ $old_id ] );
						}
					}

					if ( ! empty( $args['template_id'] ) ) {
						$old_id = $args['template_id'];

						if ( isset( $templates_ids[ $old_id ] ) ) {
							$element['settings']['content_block'][ $index ]['template_id'] = strval( $templates_ids[ $old_id ] );
						}
					}
				}
			}
		} elseif (
			(
				'cmsmasters-woo-products' === $element['widgetType'] ||
				'cmsmasters-woo-archive-products' === $element['widgetType'] ||
				'cmsmasters-woo-product-related' === $element['widgetType'] ||
				'cmsmasters-woo-products-slider' === $element['widgetType'] ||
				'cmsmasters-tribe-events-events-grid' === $element['widgetType'] ||
				'cmsmasters-tribe-events-events-slider' === $element['widgetType']
			) &&
			! empty( $element['settings']['cmsmasters_template_id'] )
		) {
			$old_id = $element['settings']['cmsmasters_template_id'];

			if ( isset( $templates_ids[ $old_id ] ) ) {
				$element['settings']['cmsmasters_template_id'] = strval( $templates_ids[ $old_id ] );
			}
		} elseif (
			'cmsmasters-template' === $element['widgetType'] &&
			! empty( $element['settings']['template_id'] )
		) {
			$old_id = $element['settings']['template_id'];

			if ( isset( $templates_ids[ $old_id ] ) ) {
				$element['settings']['template_id'] = strval( $templates_ids[ $old_id ] );
			}
		} elseif ( 'cmsmasters-tabs' === $element['widgetType'] ) {
			if ( ! empty( $element['settings']['tabs'] ) ) {
				foreach ( $element['settings']['tabs'] as $index => $args ) {
					if ( ! empty( $args['saved_section'] ) ) {
						$old_id = $args['saved_section'];

						if ( isset( $templates_ids[ $old_id ] ) ) {
							$element['settings']['tabs'][ $index ]['saved_section'] = strval( $templates_ids[ $old_id ] );
						}
					}

					if ( ! empty( $args['saved_template'] ) ) {
						$old_id = $args['saved_template'];

						if ( isset( $templates_ids[ $old_id ] ) ) {
							$element['settings']['tabs'][ $index ]['saved_template'] = strval( $templates_ids[ $old_id ] );
						}
					}
				}
			}
		} elseif ( 'cmsmasters-toggles' === $element['widgetType'] ) {
			if ( ! empty( $element['settings']['toggles'] ) ) {
				foreach ( $element['settings']['toggles'] as $index => $args ) {
					if ( ! empty( $args['saved_section'] ) ) {
						$old_id = $args['saved_section'];

						if ( isset( $templates_ids[ $old_id ] ) ) {
							$element['settings']['toggles'][ $index ]['saved_section'] = strval( $templates_ids[ $old_id ] );
						}
					}

					if ( ! empty( $args['saved_template'] ) ) {
						$old_id = $args['saved_template'];

						if ( isset( $templates_ids[ $old_id ] ) ) {
							$element['settings']['toggles'][ $index ]['saved_template'] = strval( $templates_ids[ $old_id ] );
						}
					}
				}
			}
		} elseif (
			'cmsmasters-time-popup' === $element['widgetType'] &&
			! empty( $element['settings']['cms_popup_id'] )
		) {
			$old_id = $element['settings']['cms_popup_id'];

			if ( isset( $templates_ids[ $old_id ] ) ) {
				$element['settings']['cms_popup_id'] = strval( $templates_ids[ $old_id ] );
			}
		}

		foreach ( $element['settings'] as $setting_key => $setting_value ) {
			if ( '__dynamic__' === $setting_key && ! empty( $setting_value ) ) {
				$element['settings'][ $setting_key ] = self::change_import_popup_templates_ids( $setting_value, $templates_ids );
			} elseif ( is_array( $setting_value ) ) {
				foreach ( $setting_value as $inner_setting_key => $inner_setting_value ) {
					if ( ! empty( $inner_setting_value['__dynamic__'] ) ) {
						$element['settings'][ $setting_key ][ $inner_setting_key ]['__dynamic__'] = self::change_import_popup_templates_ids( $inner_setting_value['__dynamic__'], $templates_ids );
					}
				}
			}
		}

		return $element;
	}

	/**
	 * Change dynamic popup templates IDs on import.
	 *
	 * @param array $settings Dynamic settings.
	 * @param array $templates_ids Templates ids to replace.
	 */
	public static function change_import_popup_templates_ids( $settings = array(), $templates_ids = array() ) {
		if ( empty( $settings ) ) {
			return $settings;
		}

		foreach ( $settings as $setting_key => $setting_value ) {
			if ( false === strpos( $setting_value, 'cmsmasters-action-popup' ) ) {
				continue;
			}

			preg_match( '/settings="(.*?)"/', $setting_value, $popup_setting_match );
			$popup_setting = urldecode( $popup_setting_match[1] );
			$popup_setting = json_decode( $popup_setting );

			if ( empty( $popup_setting->popup_id ) ) {
				continue;
			}

			$old_id = $popup_setting->popup_id;

			if ( ! isset( $templates_ids[ $old_id ] ) ) {
				continue;
			}

			$settings[ $setting_key ] = str_replace( $old_id, $templates_ids[ $old_id ], $settings[ $setting_key ] );
		}

		return $settings;
	}

}
