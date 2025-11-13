<?php
namespace BellaBeautySpace\Admin\Installer\Merlin;

use BellaBeautySpace\Core\Utils\API_Requests;
use BellaBeautySpace\Core\Utils\File_Manager;
use BellaBeautySpace\Core\Utils\Utils;
use BellaBeautySpace\ThemeConfig\Theme_Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merlin' ) ) {
	exit;
}

/**
 * Installer config.
 *
 * Main class for installer config.
 */
class Config extends \Merlin {

	/**
	 * Demos list.
	 */
	private $demos_list = array();

	/**
	 * Config constructor.
	 *
	 * @param array $config Package-specific configuration args.
	 * @param array $strings Text for the different elements.
	 */
	public function __construct( $config = array(), $strings = array() ) {
		parent::__construct( $config, $strings );

		if ( true !== $this->dev_mode ) {
			// Has this theme been setup yet?
			$already_setup = get_option( 'merlin_' . $this->slug . '_completed' );

			// Return if Merlin has already completed it's setup.
			if ( $already_setup ) {
				return;
			}
		}

		$this->remove_plugins_activation_early_redirect();

		add_action( 'admin_init', array( $this, 'init_actions' ) );

		add_filter( $this->theme->template . '_merlin_steps', array( $this, 'change_steps' ) );

		add_filter( 'merlin_is_theme_registered', array( $this, 'check_theme_registration' ) );

		add_filter( 'merlin_import_files', array( $this, 'set_import_files' ) );

		add_action( 'wp_ajax_cmsmasters_installer', array( $this, 'run_installer' ) );
	}

	/**
	 * Init actions.
	 */
	public function init_actions() {
		// Do not proceed, if we're not on the right page.
		if ( empty( $_GET['page'] ) || $this->merlin_url !== $_GET['page'] ) {
			return;
		}

		$current_step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

		if (
			'child' !== $current_step &&
			'plugins' !== $current_step &&
			'content' !== $current_step
		) {
			delete_option( 'cmsmasters_bella-beauty_installer_type' );
			delete_option( 'cmsmasters_bella-beauty_content_import' );
		}

		if ( 'ready' === $current_step ) {
			$demo = Utils::get_demo();

			delete_option( "cmsmasters_bella-beauty_{$demo}_content_import_files" );

			do_action( 'cmsmasters_import_ready' );

			if ( false === get_option( "cmsmasters_bella-beauty_{$demo}_content_import_status" ) ) {
				do_action( 'cmsmasters_remove_unique_elementor_locations' );
			}
		}

		$this->enqueue_assets();

		$this->remove_plugins_activation_redirect();
	}

	/**
	 * Enqueue assets.
	 */
	protected function enqueue_assets() {
		// Styles
		wp_enqueue_style(
			'bella-beauty-installer',
			File_Manager::get_css_assets_url( 'installer', null, 'default', true ),
			array( 'merlin' ),
			'1.0.0',
			'screen'
		);

		// Scripts
		wp_enqueue_script(
			'bella-beauty-installer',
			File_Manager::get_js_assets_url( 'installer' ),
			array( 'merlin' ),
			'1.0.0',
			true
		);

		wp_localize_script(
			'bella-beauty-installer', 'installer_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'type' => get_option( 'cmsmasters_bella-beauty_installer_type' ),
				'content_import' => get_option( 'cmsmasters_bella-beauty_content_import' ),
				'wpnonce' => wp_create_nonce( 'cmsmasters_bella-beauty_installer_nonce' ),
			)
		);
	}

	/**
	 * Remove plugins redirect on activation.
	 */
	protected function remove_plugins_activation_redirect() {
		delete_transient( 'cptui_activation_redirect' );
		update_option( 'wpforms_activation_redirect', true );
	}

	/**
	 * Remove plugins redirect on activation.
	 */
	protected function remove_plugins_activation_early_redirect() {
		if ( defined( 'PMPRO_VERSION' ) ) {
			update_option( 'pmpro_dashboard_version', PMPRO_VERSION, 'no' );
		}

		delete_transient( 'elementor_activation_redirect' );
		delete_transient( '_sp_activation_redirect' );
		add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
		add_filter( 'fs_redirect_on_activation_interactive-geo-maps', '__return_false' );
		add_filter( 'fs_redirect_on_activation_ajax-search-for-woocommerce', '__return_false' );

		if ( class_exists( '\Give_Cache' ) ) {
			\Give_Cache::delete( \Give_Cache::get_key( '_give_activation_redirect' ) );
		}
	}

	/**
	 * Output the header.
	 */
	protected function header() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Get the current step.
		$current_step = strtolower( $this->steps[ $this->step ]['name'] );
		$body_classes = 'merlin__body merlin__body--' . $current_step;

		if (
			'plugins' === $current_step &&
			(
				! did_action( 'elementor/loaded' ) ||
				! class_exists( 'Cmsmasters_Elementor_Addon' )
			)
		) {
			$body_classes .= ' no_required_plugins';
		}

		if ( 'demos' === $current_step ) {
			$body_classes .= ' cmsmasters-demos-count-' . count( $this->demos_list );
		}
		?>

		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<?php printf( esc_html( $strings['title%s%s%s%s'] ), '<ti', 'tle>', esc_html( $this->theme->name ), '</title>' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_print_scripts' ); ?>
		</head>
		<body class="<?php echo esc_attr( $body_classes ); ?>">
		<?php
	}

	/**
	 * Add the admin page.
	 */
	public function admin_page() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Do not proceed, if we're not on the right page.
		if ( empty( $_GET['page'] ) || $this->merlin_url !== $_GET['page'] ) {
			return;
		}

		if ( ob_get_length() ) {
			ob_end_clean();
		}

		$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

		// Use minified libraries if dev mode is turned on.
		$suffix = ( ( true === $this->dev_mode ) ) ? '' : '.min';

		// Enqueue styles.
		wp_enqueue_style( 'merlin', trailingslashit( $this->base_url ) . $this->directory . '/assets/css/merlin' . $suffix . '.css', array( 'wp-admin' ), MERLIN_VERSION );

		// Enqueue javascript.
		wp_enqueue_script( 'merlin', trailingslashit( $this->base_url ) . $this->directory . '/assets/js/merlin' . $suffix . '.js', array( 'jquery-core' ), MERLIN_VERSION );

		$texts = array(
			'something_went_wrong' => esc_html__( 'Something went wrong. Please refresh the page and try again!', 'merlin-wp' ),
		);

		// Localize the javascript.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			// Check first if TMGPA is included.
			wp_localize_script(
				'merlin', 'merlin_params', array(
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'tgm_bulk_url'     => $this->tgmpa->get_tgmpa_url(),
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'wpnonce'          => wp_create_nonce( 'merlin_nonce' ),
					'texts'            => $texts,
				)
			);
		} else {
			// If TMGPA is not included.
			wp_localize_script(
				'merlin', 'merlin_params', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wpnonce' => wp_create_nonce( 'merlin_nonce' ),
					'texts'   => $texts,
				)
			);
		}

		ob_start();

		if ( 'demos' === $this->step ) {
			$data = API_Requests::get_request( 'get-demos-list' );

			if ( is_wp_error( $data ) ) {
				error_log( $data->get_error_message() );
			} else {
				$this->demos_list = $data;
			}
		}

		/**
		 * Start the actual page content.
		 */
		$this->header(); ?>
		<div class="merlin__outer">
		<div class="merlin__wrapper">

			<div class="merlin__content merlin__content--<?php echo esc_attr( strtolower( $this->steps[ $this->step ]['name'] ) ); ?>">

				<?php
				// Content Handlers.
				$show_content = true;

				if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
					$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
				}

				if ( $show_content ) {
					$this->body();
				}
				?>

			<?php $this->step_output(); ?>

			</div>

			<?php echo sprintf( '<a class="return-to-dashboard" href="%s">%s</a>', esc_url( admin_url( '/' ) ), esc_html( $strings['return-to-dashboard'] ) ); ?>

			<?php $ignore_url = wp_nonce_url( admin_url( '?' . $this->ignore . '=true' ), 'merlinwp-ignore-nounce' ); ?>

			<?php echo sprintf( '<a class="return-to-dashboard ignore" href="%s">%s</a>', esc_url( $ignore_url ), esc_html( $strings['ignore'] ) ); ?>

		</div>
		</div>

		<?php $this->footer(); ?>

		<?php
		exit;
	}

	/**
	 * Change steps.
	 */
	public function change_steps( $steps ) {
		$steps_order = array(
			'welcome',
			'license',
			'demos',
			'child',
			'plugins',
			'content',
			'ready',
		);

		$steps_out = array();

		foreach ( $steps_order as $step_order ) {
			if ( 'demos' === $step_order ) {
				$steps_out[ $step_order ] = array(
					'name' => esc_html__( 'Demos', 'bella-beauty' ),
					'view' => array( $this, 'demos' ),
				);
			} elseif ( 'content' === $step_order && 'disabled' === get_option( 'cmsmasters_bella-beauty_content_import' ) ) {
				continue;
			} elseif ( isset( $steps[ $step_order ] ) ) {
				$steps_out[ $step_order ] = $steps[ $step_order ];
			}
		}

		return $steps_out;
	}

	/**
	 * Theme license step.
	 */
	protected function license() {
		$is_theme_registered = $this->is_theme_registered();
		$action_url = $this->theme_license_help_url;
		$required = $this->license_required;

		$is_theme_registered_class = ( $is_theme_registered ) ? ' is-registered' : null;

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header = ! $is_theme_registered ? $strings['license-header%s'] : $strings['license-header-success%s'];
		$action = $strings['license-tooltip'];
		$label = $strings['license-label'];
		$skip = $strings['btn-license-skip'];
		$next = $strings['btn-next'];
		$paragraph = ! $is_theme_registered ? $strings['license%s'] : $strings['license-success%s'];
		$install = $strings['btn-license-activate'];
		
		echo '<div class="merlin__content--transition">' .
			wp_kses( $this->svg( array( 'icon' => 'license' ) ), $this->svg_allowed_html() ) .
			'<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
				<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
			</svg>' .
			'<h1>' . esc_html( sprintf( $header, $this->theme_name ) ) . '</h1>';

			if ( $is_theme_registered ) {
				echo '<p class="cmsmasters-merlin-license__registered-notice">' . esc_html__( 'The theme is already registered, so you can go to the next step!', 'bella-beauty' ) . '</p>';
			}

			if ( ! $is_theme_registered ) {
				echo '<div class="cmsmasters-merlin-license">';

					if ( 'envato-elements' === Theme_Config::MARKETPLACE ) {
						echo '<div class="cmsmasters-merlin-license__source-code">
							<label>
								<input type="radio" name="cmsmasters_merlin_license__source_code" value="purchase-code" checked="checked" />
								<span>' . esc_html__( 'I bought the theme on Themeforest', 'bella-beauty' ) . '</span>
							</label>
							<label>
								<input type="radio" name="cmsmasters_merlin_license__source_code" value="envato-elements-token" />
								<span>' . esc_html__( 'I downloaded the theme from Envato Elements', 'bella-beauty' ) . '</span>
							</label>
						</div>';
					}

					echo '<div class="cmsmasters-merlin-license__code cmsmasters-merlin-license--purchase-code">
						<div class="cmsmasters-merlin-license__code-wrapper">
							<input type="text" name="cmsmasters_merlin_license__purchase_code" placeholder="' . esc_attr__( 'Enter Your Purchase code', 'bella-beauty' ) . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
						</div>';

						if ( 'templatemonster' !== Theme_Config::MARKETPLACE ) {
							echo '<span class="cmsmasters-merlin-license__code-description cmsmasters-merlin-license__code-description-bottom">' .
								sprintf(
									esc_html__( 'Where can I find my %1$s?', 'bella-beauty' ),
									'<a href="' . esc_url( 'https://docs.cmsmasters.net/blog/how-to-find-your-envato-purchase-code/' ) . '" target="_blank">' .
										esc_html__( 'purchase code', 'bella-beauty' ) .
									'</a>'
								) .
							'</span>';
						}

					echo '</div>';

					if ( 'envato-elements' === Theme_Config::MARKETPLACE ) {
						echo '<div class="cmsmasters-merlin-license__code cmsmasters-merlin-license--envato-elements-token">
							<span class="cmsmasters-merlin-license__code-description cmsmasters-merlin-license__code-description-top">' .
								sprintf(
									esc_html__( 'In order to activate the theme you need to %1$s', 'bella-beauty' ),
									'<a href="' . esc_url( 'https://api.extensions.envato.com/extensions/begin_activation?extension_id=cmsmasters-envato-elements&extension_type=envato-wordpress&extension_description=' . wp_get_theme()->get( 'Name' ) . ' (' . get_home_url() . ')&utm_content=settings' ) . '" target="_blank">' .
										esc_html__( 'generate Envato Elements token', 'bella-beauty' ) .
									'</a>'
								) .
							'</span>
							<div class="cmsmasters-merlin-license__code-wrapper">
								<input type="text" name="cmsmasters_merlin_license__envato_elements_token" placeholder="' . esc_attr__( 'Envato Elements Token', 'bella-beauty' ) . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
								<a href="https://docs.cmsmasters.net/how-to-activate-the-theme-using-the-envato-elements-token/" alt="' . esc_attr( $action ) . '" target="_blank">
									<span class="hint--top" aria-label="' . esc_attr( $action ) . '">' .
										wp_kses( $this->svg( array( 'icon' => 'help' ) ), $this->svg_allowed_html() ) .
									'</span>
								</a>
							</div>
						</div>';
					}

					echo '<p class="cmsmasters-merlin-license__notice"></p>';

					if ( 'templatemonster' !== Theme_Config::MARKETPLACE ) {
						echo '<div class="cmsmasters-merlin-license__user-info">
							<h3 class="cmsmasters-merlin-license__user-info--title">' . esc_html__( 'Register your copy', 'bella-beauty' ) . '</h3>
							<p class="cmsmasters-merlin-license__user-info--text">' . esc_html__( 'Get information about promotions, new themes and theme updates directly to your inbox', 'bella-beauty' ) . '</p>
							<div class="cmsmasters-merlin-license__user-info--item">
								<input type="text" name="cmsmasters_merlin_license__user_name" placeholder="' . esc_attr__( 'Your Name', 'bella-beauty' ) . '" />
							</div>
							<div class="cmsmasters-merlin-license__user-info--item">
								<input type="text" name="cmsmasters_merlin_license__user_email" placeholder="' . esc_attr__( 'Your Email', 'bella-beauty' ) . '" />
							</div>
							<p class="cmsmasters-merlin-license__user-info--privacy">' .
								sprintf(
									esc_html__( 'Your data is stored and processed in accordance with our %1$s', 'bella-beauty' ),
									'<a href="' . esc_url( 'https://cmsmasters.studio/privacy-policy/' ) . '" target="_blank">' .
										esc_html__( 'Privacy Policy', 'bella-beauty' ) .
									'</a>'
								) .
							'</p>
						</div>';
					}

				echo '</div>';
			}

		echo '</div>';

		echo '<footer class="merlin__content__footer ' . esc_attr( $is_theme_registered_class ) . '">';

			if ( ! $is_theme_registered ) {
				if ( ! $required ) {
					echo '<a href="' . esc_url( $this->step_next_link() ) . '" class="merlin__button merlin__button--skip merlin__button--proceed">' . esc_html( $skip ) . '</a>';
				}

				echo '<a href="' . esc_url( $this->step_next_link() ) . '" class="merlin__button merlin__button--next button-next js-merlin-license-activate-button" data-callback="activate_license">
					<span class="merlin__button--loading__text">' . esc_html( $install ) . '</span>' .
					$this->loading_spinner() .
				'</a>';
			} else {
				echo '<a href="' . esc_url( $this->step_next_link() ) . '" class="merlin__button merlin__button--next merlin__button--proceed merlin__button--colorchange">' . esc_html( $next ) . '</a>';
			}
			
			wp_nonce_field( 'merlin' );

		echo '</footer>';
		
		$this->logger->debug( __( 'The license activation step has been displayed', 'merlin-wp' ) );
	}

	/**
	 * Activate the theme (license key) via AJAX.
	 */
	public function _ajax_activate_license() {
		if ( ! check_ajax_referer( 'merlin_nonce', 'wpnonce' ) ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => esc_html__( 'Yikes! The theme activation failed. Please try again or contact support.', 'bella-beauty' ),
				)
			);
		}

		$error_code = '';
		$source_code = empty( $_POST['source_code'] ) ? 'purchase-code' : $_POST['source_code'];

		if ( 'purchase-code' === $source_code && empty( $_POST['purchase_code'] ) ) {
			$error_code = 'empty_purchase_code';
		} elseif ( 'envato-elements-token' === $source_code && empty( $_POST['envato_elements_token'] ) ) {
			$error_code = 'empty_envato_elements_token';
		}

		if ( ! empty( $error_code ) ) {
			wp_send_json(
				array(
					'success' => false,
					'code' => $error_code,
					'error_field' => 'license_key',
					'message' => esc_html__( 'License key field is empty', 'bella-beauty' ),
				)
			);
		}

		API_Requests::generate_token( array(
			'user_name' => empty( $_POST['user_name'] ) ? '' : $_POST['user_name'],
			'user_email' => empty( $_POST['user_email'] ) ? '' : $_POST['user_email'],
			'source_code' => $source_code,
			'purchase_code' => empty( $_POST['purchase_code'] ) ? '' : $_POST['purchase_code'],
			'envato_elements_token' => empty( $_POST['envato_elements_token'] ) ? '' : $_POST['envato_elements_token'],
			'input_data_source' => 'installer',
		) );

		wp_send_json(
			array(
				'done' => 1,
				'success' => true,
				'message' => sprintf( esc_html( $this->strings['license-json-success%s'] ), $this->theme_name ),
			)
		);
	}

	/**
	 * Check, if the theme is currently registered.
	 *
	 * @return bool.
	 */
	public function check_theme_registration() {
		return API_Requests::check_token_status();
	}

	/**
	 * Demos step.
	 */
	protected function demos() {
		$parent_class = 'cmsmasters-installer-demos';

		echo $this->get_demos_notice();

		echo '<div class="' . esc_attr( $parent_class ) . '">' .
			'<ul class="' . esc_attr( $parent_class ) . '__list">';

		foreach ( $this->demos_list as $demo_key => $demo_args ) {
			$name = ( isset( $demo_args['name'] ) ? $demo_args['name'] : false );
			$preview_url = ( isset( $demo_args['preview_url'] ) ? $demo_args['preview_url'] : false );
			$preview_img_url = ( isset( $demo_args['preview_img_url'] ) ? $demo_args['preview_img_url'] : false );

			echo '<li class="' . esc_attr( $parent_class ) . '__item">' .
				'<figure class="' . esc_attr( $parent_class ) . '__item-image">' .
					'<span class="dashicons dashicons-format-image"></span>' .
					( $preview_img_url ? '<img src="' . esc_url( $preview_img_url ) . '" />' : '' ) .
					( $preview_url ? '<a href="' . esc_url( $preview_url ) . '" target="_blank" class="' . esc_attr( $parent_class ) . '__item-preview"><span title="' . esc_attr( $name ) . '">' . esc_html__( 'Demo Preview', 'bella-beauty' ) . '</span></a>' : '' ) .
				'</figure>' .
				'<div class="' . esc_attr( $parent_class ) . '__item-info">' .
					( $name ? '<h3 class="' . esc_attr( $parent_class ) . '__item-title">' . esc_html( $name ) . '</h3>' : '' ) .
					'<div class="' . esc_attr( $parent_class ) . '__item-buttons">' .
						'<a href="' . esc_url( $this->step_next_link() ) . '" class="cmsmasters-install-button cmsmasters-custom" data-key="' . esc_attr( $demo_key ) . '">' . esc_html__( 'Manual', 'bella-beauty' ) . '</a>' .
						'<div class="' . esc_attr( $parent_class ) . '__item-buttons-express-wrap">' .
							'<label>' .
								esc_html__( 'Import dummy content?', 'bella-beauty' ) .
								'<input type="checkbox" checked="checked" class="cmsmasters-import-content-status" />' .
							'</label>' .
							'<a href="' . esc_url( $this->step_next_link() ) . '" class="cmsmasters-install-button cmsmasters-express" data-key="' . esc_attr( $demo_key ) . '">' . esc_html__( 'One-click Install', 'bella-beauty' ) . '</a>' .
						'</div>' .
					'</div>' .
				'</div>' .
			'</li>';
		}

			echo '</ul>' .
		'</div>';

		update_option( 'cmsmasters_bella-beauty_installation_status', 'run' );
	}

	/**
	 * Get demos step notice.
	 *
	 * @return string Notice HTML.
	 */
	public function get_demos_notice() {
		$limits_to_increase = $this->get_server_limits_to_increase();
		$php_modules_to_include = $this->get_php_modules_to_include();

		if ( empty( $limits_to_increase ) && empty( $php_modules_to_include ) ) {
			return '';
		}

		$notice_content = $this->get_notice_img( 'demos-notice.svg' );

		$notice_content .= $this->get_notice_text( esc_html__( 'Your theme provides demo content for a ready website, including all pages, post types, templates and other elements, so in order for it to be installed please make sure your server has appropriate settings:', 'bella-beauty' ) );

		if ( ! empty( $limits_to_increase ) ) {
			$notice_content .= $this->get_notice_title( esc_html__( 'Increase the PHP configuration limits to at least:', 'bella-beauty' ) );

			$notice_content .= $this->get_notice_list( $limits_to_increase, 'grouped' );
		}

		if ( ! empty( $php_modules_to_include ) ) {
			$notice_content .= $this->get_notice_title( esc_html__( 'Enable PHP modules:', 'bella-beauty' ) );

			$notice_content .= $this->get_notice_list( $php_modules_to_include, 'separated' );
		}

		$notice_content .= $this->get_notice_info( sprintf(
			esc_html__( 'You can find more information %s', 'bella-beauty' ),
			'<a href="https://docs.cmsmasters.net/requirements/" target="_blank">' . esc_html__( 'here', 'bella-beauty' ) . '</a>'
		) );

		return $this->get_notice( $notice_content, array( 'cmsmasters-installer-notice--demos' ) );
	}

	/**
	 * Get server limits to increase for pre installation notice.
	 *
	 * @return string pre installation notice part.
	 */
	public function get_server_limits_to_increase() {
		if ( ! function_exists( 'ini_get' ) ) {
			return array();
		}

		$recommended_limits = array(
			'max_execution_time' => '300',
			'max_input_time' => '300',
			'post_max_size' => '64M',
			'upload_max_filesize' => '64M',
			'memory_limit '=> '256M',
		);

		$limits = array();

		foreach ( $recommended_limits as $key => $value ) {
			$ini_limit = ini_get( $key );
			$ini_limit = ( -1 == $ini_limit || 0 == $ini_limit ? $value : $ini_limit );

			if ( wp_convert_hr_to_bytes( $value ) > wp_convert_hr_to_bytes( $ini_limit ) ) {
				$limits[] = $key . ' ' . $value;
			}
		}

		return $limits;
	}

	/**
	 * Get php modules to include for pre installation notice.
	 *
	 * @return string pre installation notice part.
	 */
	public function get_php_modules_to_include() {
		$test_php_extensions = \WP_Site_Health::get_instance()->get_test_php_extensions();

		if ( 'good' === $test_php_extensions['status'] ) {
			return array();
		}

		$pattern = '/<\/span?[^>]+>\s(.*?)<\/li/';

		preg_match_all( $pattern, $test_php_extensions['description'], $matches );

		if ( ! is_array( $matches[1] ) || empty( $matches[1] ) ) {
			return array();
		}

		return $matches[1];
	}

	/**
	 * Run installer.
	 */
	public function run_installer() {
		$type = ! isset( $_POST['type'] ) ? false : $_POST['type'];
		$content_import = ! isset( $_POST['content_import'] ) ? false : $_POST['content_import'];
		$demo_key = ! isset( $_POST['demo_key'] ) ? false : $_POST['demo_key'];

		if (
			false === $type ||
			false === $content_import ||
			false === $demo_key
		) {
			wp_send_json_error( array(
				'code' => 'invalid_demo_data',
				'message' => 'Invalid demo data.',
			), 403 );
		}

		update_option( 'cmsmasters_bella-beauty_installer_type', $type, false );

		update_option( 'cmsmasters_bella-beauty_content_import', $content_import, false );

		if ( 'demos' !== Theme_Config::IMPORT_TYPE ) {
			Utils::set_demo_kit( $demo_key );
		}

		if ( 'only_kit' === Theme_Config::IMPORT_TYPE ) {
			$demo_key = 'main';
		}

		Utils::set_demo( $demo_key );

		$this->set_demo_content_import_files( $demo_key );

		do_action( 'cmsmasters_set_import_status', 'pending' );

		do_action( 'cmsmasters_remove_temp_data' );
	}

	/**
	 * Set demo content import files.
	 *
	 * @param string $demo_key Demo key.
	 */
	public function set_demo_content_import_files( $demo_key ) {
		$data = API_Requests::post_request( 'get-demo-files', array( 'demo' => $demo_key ) );

		if ( is_wp_error( $data ) ) {
			error_log( $data->get_error_message() );

			return;
		}

		if ( empty( $data ) ) {
			return;
		}
		
		update_option( "cmsmasters_bella-beauty_{$demo_key}_content_import_files", $data, false );
	}

	/**
	 * Set files for demo import.
	 */
	public function set_import_files( $files ) {
		$import_files = get_option( 'cmsmasters_bella-beauty_' . Utils::get_demo() . '_content_import_files' );

		if ( false !== $import_files ) {
			$files = $import_files;
		}

		return $files;
	}

	/**
	 * Get the import steps HTML output.
	 *
	 * @param array $import_info The import info to prepare the HTML for.
	 *
	 * @return string
	 */
	public function get_import_steps_html( $import_info ) {
		ob_start();
		?>
			<?php foreach ( $import_info as $slug => $available ) : ?>
				<?php
				if ( ! $available ) {
					continue;
				}
				?>

				<li class="merlin__drawer--import-content__list-item status status--Pending" data-content="<?php echo esc_attr( $slug ); ?>">
					<input type="checkbox" name="default_content[<?php echo esc_attr( $slug ); ?>]" class="checkbox checkbox-<?php echo esc_attr( $slug ); ?>" id="default_content_<?php echo esc_attr( $slug ); ?>" value="1" checked>
					<label for="default_content_<?php echo esc_attr( $slug ); ?>">
						<i></i><span><?php 
						if ( 'content' === $slug ) {
							echo esc_html__( 'Dummy Content', 'bella-beauty' );
						} elseif ( 'widgets' === $slug ) {
							echo esc_html__( 'Sidebars Widgets', 'bella-beauty' );
						} elseif ( 'options' === $slug ) {
							echo esc_html__( 'Customizer Settings', 'bella-beauty' );
						} else {
							echo esc_html( ucfirst( str_replace( '_', ' ', $slug ) ) );
						}
						?></span>
					</label>
				</li>

			<?php endforeach; ?>
		<?php

		return ob_get_clean();
	}

	/**
	 * Do content's AJAX
	 */
	public function _ajax_content() {
		static $content = null;

		$selected_import = intval( $_POST['selected_index'] );

		if ( null === $content ) {
			$content = $this->get_import_data( $selected_import );
		}

		if ( ! check_ajax_referer( 'merlin_nonce', 'wpnonce' ) || empty( $_POST['content'] ) && isset( $content[ $_POST['content'] ] ) ) {
			$this->logger->error( __( 'The content importer AJAX call failed to start, because of incorrect data', 'merlin-wp' ) );

			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Invalid content!', 'merlin-wp' ),
				)
			);
		}

		$json         = false;
		$this_content = $content[ $_POST['content'] ];

		if ( isset( $_POST['proceed'] ) ) {
			if ( is_callable( $this_content['install_callback'] ) ) {
				$this->logger->info(
					__( 'The content import AJAX call will be executed with this import data', 'merlin-wp' ),
					array(
						'title' => $this_content['title'],
						'data'  => $this_content['data'],
					)
				);

				$logs = call_user_func( $this_content['install_callback'], $this_content['data'] );

				if ( 'content' === $_POST['content'] && class_exists( 'mp_timetable\classes\models\Import' ) ) {
					$mptt_content_url = $this->import_files[0]['import_mptt_file_url'];

					if ( ! empty( $mptt_content_url ) ) {
						$mptt_import = new \mp_timetable\classes\models\Import();

						$mptt_import->fetch_attachments = true;

						$mptt_import->process_start( $mptt_content_url );
					}
				}

				if ( $logs ) {
					$json = array(
						'done'    => 1,
						'message' => $this_content['success'],
						'debug'   => '',
						'logs'    => $logs,
						'errors'  => '',
					);

					// The content import ended, so we should mark that all posts were imported.
					if ( 'content' === $_POST['content'] ) {
						$json['num_of_imported_posts'] = 'all';
					}
				}
			}
		} else {
			$json = array(
				'url'            => admin_url( 'admin-ajax.php' ),
				'action'         => 'merlin_content',
				'proceed'        => 'true',
				'content'        => $_POST['content'],
				'_wpnonce'       => wp_create_nonce( 'merlin_nonce' ),
				'selected_index' => $selected_import,
				'message'        => $this_content['installing'],
				'logs'           => '',
				'errors'         => '',
			);
		}

		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) );
			wp_send_json( $json );
		} else {
			$this->logger->error(
				__( 'The content import AJAX call failed with this passed data', 'merlin-wp' ),
				array(
					'selected_content_index' => $selected_import,
					'importing_content'      => $_POST['content'],
					'importing_data'         => $this_content['data'],
				)
			);

			wp_send_json(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Error', 'merlin-wp' ),
					'logs'    => '',
					'errors'  => '',
				)
			);
		}
	}

	protected function content() {
		echo '<div class="cmsmasters-installer-exiting-step-message">
			<p>' . esc_html__( 'Please do not reload or leave the page, the import is still in progress. This will take some time, please wait.', 'bella-beauty' ) . '</p>
		</div>';

		parent::content();
	}

	protected function ready() {
		if ( class_exists( 'ElementorPro\Plugin' ) ) {
			$notice_content = $this->get_notice_img( 'ready-notice.svg' );
			
			$notice_content .= $this->get_notice_title( esc_html__( 'Please Note:', 'bella-beauty' ) );

			$notice_content .= $this->get_notice_text( esc_html__( 'CMSmasters Elementor Addon and Elementor Pro are both very powerful plugins that work seamlessly together. However, they both provide the Template Builder functionality, and to make your website look exactly like the demo one after import, CMSmasters Elementor Addon templates are prioritized by default.', 'bella-beauty' ) );
			
			$notice_content .= $this->get_notice_info( sprintf(
				esc_html__( 'You can change the priority at any time by adjusting your settings %1$s.', 'bella-beauty' ),
				'<a href="' . admin_url( 'admin.php?page=cmsmasters-addon-settings#tab-pro' ) . '" target="_blank">' . esc_html__( 'here', 'bella-beauty' ) . '</a>'
			) );

			$notice_content .= $this->get_notice_info( sprintf(
				esc_html__( 'More information about template priority can be found %1$s.', 'bella-beauty' ),
				'<a href="https://docs.cmsmasters.net/how-to-manage-template-priority-between-cmsmasters-elementor-addon-and-elementor-pro/" target="_blank">' . esc_html__( 'here', 'bella-beauty' ) . '</a>'
			) );

			$notice_content .= $this->get_notice_button( array(
				'tag' => 'button',
				'text' => esc_html__( 'I understand', 'bella-beauty' ),
				'add_classes' => array( 'cmsmasters-installer-notice__close-js' ),
			) );

			echo $this->get_notice( $notice_content );
		}

		parent::ready();
	}

	public function get_notice( $content = '', $add_classes = array() ) {
		if ( empty( $content ) ) {
			return '';
		}

		$add_class = array( 'cmsmasters-installer-notice' );

		$add_class = array_merge( $add_class, $add_classes );

		$out = '<div class="' . esc_attr( implode( ' ', $add_class ) ) . '">
			<div class="cmsmasters-installer-notice__outer">
				<span class="cmsmasters-installer-notice__close cmsmasters-installer-notice__close-js"></span>
				<div class="cmsmasters-installer-notice__inner">' .
					$content .
				'</div>
			</div>
		</div>';

		return $out;
	}

	/**
	 * Get notice list.
	 *
	 * @param array $items List items.
	 * @param string $type grouped/separated type of list.
	 *
	 * @return array List HTML.
	 */
	public function get_notice_list( $items = array(), $type = 'grouped' ) {
		if ( empty( $items ) ) {
			return '';
		}

		$out = '<ul class="cmsmasters-installer-notice__list cmsmasters-installer-notice__list-' . esc_attr( $type ) . '">';

			foreach ( $items as $item ) {
				$out .= '<li>' . esc_html( $item ) . '</li>';
			}
		
		$out .= '</ul>';

		return $out;
	}

	public function get_notice_title( $text = '' ) {
		if ( empty( $text ) ) {
			return '';
		}

		return '<p class="cmsmasters-installer-notice__title">' . wp_kses_post( $text ) . '</p>';
	}

	public function get_notice_img( $name = '' ) {
		if ( empty( $name ) ) {
			return '';
		}

		$src = trailingslashit( $this->base_url ) . $this->directory . "/assets/images/{$name}";

		return '<div class="cmsmasters-installer-notice__img">
			<img src="' . $src . '" />
		</div>';
	}

	public function get_notice_info( $text = '' ) {
		if ( empty( $text ) ) {
			return '';
		}

		return '<div class="cmsmasters-installer-notice__info">
			<p>' . wp_kses_post( $text ) . '</p>
		</div>';
	}

	public function get_notice_text( $text = '' ) {
		if ( empty( $text ) ) {
			return '';
		}

		return '<div class="cmsmasters-installer-notice__text">
			<p>' . wp_kses_post( $text ) . '</p>
		</div>';
	}

	public function get_notice_button( $atts = array() ) {
		$req_vars = array(
			'tag' => 'button', // button/a tag
			'text' => '',
			'link' => '',
			'target' => '_blank',
			'add_classes' => array(),
			'add_attrs' => array(),
		);

		foreach ( $req_vars as $var_key => $var_value ) {
			if ( array_key_exists( $var_key, $atts ) ) {
				$$var_key = $atts[ $var_key ];
			} else {
				$$var_key = $var_value;
			}
		}

		if ( empty( $text ) ) {
			return '';
		}

		$button_attrs = array(
			'class' => esc_attr( implode( ' ', array_merge( array( 'cmsmasters-installer-notice__button' ), $add_classes ) ) ),
		);

		if ( 'a' === $tag && ! empty( $link ) ) {
			$button_attrs['href'] = esc_url( $link );
			$button_attrs['target'] = esc_attr( $target );
		}

		$button_attrs_out = '';

		foreach ( $button_attrs as $button_attr_key => $button_attr_value ) {
			$button_attrs_out .= $button_attr_key . '="' . $button_attr_value . '"';
		}

		if ( ! empty( $add_attrs ) ) {
			$button_attrs_out .= implode( ' ', $add_attrs );
		}

		return '<div class="cmsmasters-installer-notice__button-wrap">
			<' . $tag . ' ' . $button_attrs_out . '>
				<span>' . esc_html( $text ) . '</span>
			</' . $tag . '>
		</div>';
	}

}
