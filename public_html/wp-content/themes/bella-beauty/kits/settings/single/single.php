<?php
namespace BellaBeautySpace\Kits\Settings\Single;

use BellaBeautySpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use BellaBeautySpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Single settings.
 */
class Single extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'single';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Single', 'bella-beauty' );
	}

	/**
	 * Get control ID prefix.
	 *
	 * Retrieve the control ID prefix.
	 *
	 * @return string Control ID prefix.
	 */
	protected static function get_control_id_prefix() {
		$toggle_name = self::get_toggle_name();

		return parent::get_control_id_prefix() . "_{$toggle_name}";
	}

	/**
	 * Register toggle controls.
	 *
	 * Registers the controls of the kit settings tab toggle.
	 */
	protected function register_toggle_controls() {
		$this->add_control(
			'notice',
			array(
				'raw' => esc_html__( "If you use an 'Singular' template, then the settings will not be applied, if you set the template to 'All Singular', then these settings will be hidden.", 'bella-beauty' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label' => esc_html__( 'Layout', 'bella-beauty' ),
				'label_block' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'l-sidebar' => array(
						'title' => esc_html__( 'Left', 'bella-beauty' ),
						'description' => esc_html__( 'Left Sidebar', 'bella-beauty' ),
					),
					'fullwidth' => array(
						'title' => esc_html__( 'Full', 'bella-beauty' ),
						'description' => esc_html__( 'Full Width', 'bella-beauty' ),
					),
					'r-sidebar' => array(
						'title' => esc_html__( 'Right', 'bella-beauty' ),
						'description' => esc_html__( 'Right Sidebar', 'bella-beauty' ),
					),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'layout' ),
					'r-sidebar'
				),
				'toggle' => false,
			)
		);

		$this->add_control(
			'elements_heading_control',
			array(
				'label' => esc_html__( 'Elements Order', 'bella-beauty' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'elements',
			array(
				'label_block' => true,
				'show_label' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => array(
					'media' => esc_html__( 'Media', 'bella-beauty' ),
					'title' => esc_html__( 'Title', 'bella-beauty' ),
					'meta_first' => esc_html__( 'Meta Data 1', 'bella-beauty' ),
					'meta_second' => esc_html__( 'Meta Data 2', 'bella-beauty' ),
					'content' => esc_html__( 'Content', 'bella-beauty' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'elements' ),
					array(
						'media',
						'title',
						'meta_first',
						'content',
						'meta_second',
					)
				),
				'multiple' => true,
			)
		);

		$this->add_control(
			'heading_visibility',
			array(
				'label' => esc_html__( 'Heading Visibility', 'bella-beauty' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'bella-beauty' ),
				'label_on' => esc_html__( 'Show', 'bella-beauty' ),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'heading_visibility' ),
					'yes'
				),
			)
		);

		$this->add_control(
			'blocks_heading_control',
			array(
				'label' => esc_html__( 'Blocks Order', 'bella-beauty' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'blocks',
			array(
				'label_block' => true,
				'show_label' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => array(
					'nav' => esc_html__( 'Posts Navigation', 'bella-beauty' ),
					'author' => esc_html__( 'Author Box', 'bella-beauty' ),
					'more_posts' => esc_html__( 'More Posts', 'bella-beauty' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'blocks' ),
					array(
						'nav',
						'author',
						'more_posts',
					)
				),
				'multiple' => true,
			)
		);

		$this->add_control(
			'apply_settings',
			array(
				'label_block' => true,
				'show_label' => false,
				'type' => Controls_Manager::BUTTON,
				'text' => esc_html__( 'Save & Reload', 'bella-beauty' ),
				'event' => 'cmsmasters:theme_settings:apply_settings',
				'separator' => 'before',
			)
		);
	}

}
