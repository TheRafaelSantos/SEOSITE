<?php
namespace BellaBeautySpace\ThemeConfig;

use BellaBeautySpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme Config.
 *
 * Main class for theme config.
 */
class Theme_Config {

	/**
	 * Product key.
	 */
	const PRODUCT_KEY = '62656c6c612d626561757479';

	/**
	 * Import type.
	 *
	 * demos - import content and all data from demo; apply all data from demo;
	 * kit - import content and all data from demo; apply only kit from demo;
	 * only_kit - import content and all data from main demo, and kit from current demo; apply only kit from demo;
	 */
	const IMPORT_TYPE = 'only_kit';

	/**
	 * Marketplace.
	 *
	 * themeforest - theme for themeforest
	 * envato-elements - theme for envato-elements
	 * templatemonster - theme for templatemonster
	 */
	const MARKETPLACE = 'envato-elements';

	/**
	 * Major versions.
	 */
	const MAJOR_VERSIONS = array();

	/**
	 * Default Colors.
	 */
	const PRIMARY_COLOR_DEFAULT = '#8D4D5D';
	const SECONDARY_COLOR_DEFAULT = '#222222';
	const TEXT_COLOR_DEFAULT = '#6B6668';
	const ACCENT_COLOR_DEFAULT = '#F7E4E7';
	const TERTIARY_COLOR_DEFAULT = '#A5AEA8';
	const BACKGROUND_COLOR_DEFAULT = '#ffffff';
	const ALTERNATE_COLOR_DEFAULT = '#FCF4F6';
	const BORDER_COLOR_DEFAULT = '#EAE6E7';

	/**
	 * Default Typography.
	 */
	const PRIMARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Butler';
	const PRIMARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';

	const SECONDARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const SECONDARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';

	const TEXT_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '18', 'unit' => 'px' );
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';
	const TEXT_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TEXT_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TEXT_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.45', 'unit' => 'em' );
	const TEXT_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const TEXT_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '500';
	const ACCENT_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'uppercase';
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const ACCENT_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const ACCENT_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.6', 'unit' => 'em' );
	const ACCENT_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '2', 'unit' => 'px' );
	const ACCENT_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '15', 'unit' => 'px' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';
	const TERTIARY_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TERTIARY_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TERTIARY_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.6', 'unit' => 'em' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const META_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const META_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const META_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '500';
	const META_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const META_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const META_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const META_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.7', 'unit' => 'em' );
	const META_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const META_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '13', 'unit' => 'px' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'uppercase';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.7', 'unit' => 'em' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '2', 'unit' => 'px' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const SMALL_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '15', 'unit' => 'px' );
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';
	const SMALL_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const SMALL_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const SMALL_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.75', 'unit' => 'em' );
	const SMALL_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const SMALL_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H1_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Butler';
	const H1_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '68', 'unit' => 'px' );
	const H1_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';
	const H1_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H1_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H1_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H1_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.1', 'unit' => 'em' );
	const H1_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H1_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H2_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Butler';
	const H2_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '56', 'unit' => 'px' );
	const H2_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';
	const H2_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H2_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H2_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H2_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.15', 'unit' => 'em' );
	const H2_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H2_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H3_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Butler';
	const H3_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '42', 'unit' => 'px' );
	const H3_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';
	const H3_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H3_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H3_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H3_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.2', 'unit' => 'em' );
	const H3_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H3_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H4_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Butler';
	const H4_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '30', 'unit' => 'px' );
	const H4_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';
	const H4_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H4_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H4_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H4_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.25', 'unit' => 'em' );
	const H4_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H4_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H5_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const H5_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '20', 'unit' => 'px' );
	const H5_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';
	const H5_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H5_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H5_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H5_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.45', 'unit' => 'em' );
	const H5_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H5_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H6_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const H6_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '15', 'unit' => 'px' );
	const H6_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '400';
	const H6_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'uppercase';
	const H6_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H6_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H6_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.55', 'unit' => 'em' );
	const H6_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '2', 'unit' => 'px' );
	const H6_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Karla';
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const BUTTON_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const BUTTON_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const BUTTON_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.6', 'unit' => 'em' );
	const BUTTON_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '2', 'unit' => 'px' );
	const BUTTON_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Butler';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '30', 'unit' => 'px' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '300';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.35', 'unit' => 'em' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	/**
	 * Theme_Config constructor.
	 */
	public function __construct() {
		add_action( 'cmsmasters_first_setup', array( $this, 'first_setup_actions' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_default_assets' ), 8 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_default_assets' ), 8 );
	}

	/**
	 * Actions on first setup.
	 */
	public function first_setup_actions() {
		$cpt_support = get_option( 'elementor_cpt_support', array( 'post', 'page', 'e-landing-page' ) );

		if ( is_array( $cpt_support ) ) {
			if ( ! in_array( 'product', $cpt_support ) ) {
				$cpt_support[] = 'product';
			}

			if ( ! in_array( 'cmsms_doctor', $cpt_support ) ) {
				$cpt_support[] = 'cmsms_doctor';
			}

			if ( ! in_array( 'services', $cpt_support ) ) {
				$cpt_support[] = 'services';
			}
		}

		update_option( 'elementor_cpt_support', $cpt_support );
	}

	/**
	 * Enqueue default assets.
	 */
	public function enqueue_default_assets() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			wp_enqueue_style(
				'bella-beauty-default-fonts',
				$this->get_default_fonts(),
				array(),
				'1.0.0',
				'screen'
			);
		}

		if ( '' === Utils::get_active_kit() || ! did_action( 'elementor/loaded' ) ) {
			$default_styles = '.wp-block-widget-area h2.wp-block-heading,
			.widget h2 {
				font-family: var(--cmsmasters-h5-font-family);
				font-weight: var(--cmsmasters-h5-font-weight);
				font-style: var(--cmsmasters-h5-font-style);
				text-transform: var(--cmsmasters-h5-text-transform);
				text-decoration: var(--cmsmasters-h5-text-decoration);
				font-size: var(--cmsmasters-h5-font-size);
				line-height: var(--cmsmasters-h5-line-height);
				letter-spacing: var(--cmsmasters-h5-letter-spacing);
				word-spacing: var(--cmsmasters-h5-word-spacing);
			}

			.wp-block-button .wp-block-button__link {
				border-radius: 50px;
			}
 
			@media only screen and (max-width: 1024px) {
				:root {
					--e-global-typography-h1-font-size: 62px;
					--e-global-typography-h2-font-size: 48px;
					--e-global-typography-h3-font-size: 36px;
					--e-global-typography-h4-font-size: 24px;
					--e-global-typography-h5-font-size: 19px;
					--e-global-typography-h6-font-size: 12px;
					--e-global-typography-text-font-size: 16px;
					--e-global-typography-small-font-size: 14px;
					--e-global-typography-meta-font-size: 13px;
					--e-global-typography-taxonomy-font-size: 11px;
					--e-global-typography-button-font-size: 15px;
					--e-global-typography-accent-font-size: 15px;
					--e-global-typography-tertiary-font-size: 14px;
					--e-global-typography-blockquote-font-size: 26px;
				}

				body {
					--cmsmasters-main-content-padding-top: 80px !important;
					--cmsmasters-main-content-padding-bottom: 80px !important;
					--cmsmasters-single-meta-second-box-margin-bottom: 50px !important;
					--cmsmasters-single-media-box-margin-bottom: 80px !important;
					--cmsmasters-single-comments-box-margin-top: 80px !important;
					--cmsmasters-single-comments-items-hor-gap: 30px !important;
					--cmsmasters-single-nav-box-margin-top: 80px !important;
				}
			}

			@media only screen and (max-width: 767px) {
				:root {
					--e-global-typography-h1-font-size: 44px;
					--e-global-typography-h2-font-size: 36px;
					--e-global-typography-h3-font-size: 26px;
					--e-global-typography-h4-font-size: 20px;
					--e-global-typography-h5-font-size: 16px;
					--e-global-typography-h6-font-size: 10px;
					--e-global-typography-text-font-size: 15px;
					--e-global-typography-small-font-size: 13px;
					--e-global-typography-meta-font-size: 12px;
					--e-global-typography-taxonomy-font-size: 10px;
					--e-global-typography-button-font-size: 14px;
					--e-global-typography-accent-font-size: 14px;
					--e-global-typography-tertiary-font-size: 13px;
					--e-global-typography-blockquote-font-size: 20px;
				}

				body {
					--cmsmasters-archive-compact-media-width: 100%;
					--cmsmasters-archive-media-box-margin-right: 0;
					--cmsmasters-archive-media-box-margin-bottom: 40px;
					--cmsmasters-search-compact-media-width: 100%;
					--cmsmasters-search-media-box-margin-right: 0;
					--cmsmasters-search-media-box-margin-bottom: 40px;
				}
			}';

			wp_add_inline_style( 'bella-beauty-default-fonts', $default_styles );
		}
	}

	/**
	 * Get default fonts.
	 */
	public function get_default_fonts() {
		$fonts = array(
			'Karla' => array(
				'300',
				'400',
				'500',
				'700',
			),
			'Butler' => array(
				'300',
				'400',
			),
		);

		$families = array();

		foreach ( $fonts as $font => $weights ) {
			$families[] = str_replace( ' ', '+', $font ) . '%3A' . implode( '%2C', $weights );
		}

		return 'https://fonts.googleapis.com/css?family=' . implode( '%7C', $families );
	}

}
