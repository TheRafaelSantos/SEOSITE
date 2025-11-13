<?php
namespace BellaBeautySpace\Kits\Settings\General;

use BellaBeautySpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Colors settings.
 */
class Colors extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the settings toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'colors';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Colors', 'bella-beauty' );
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
		$key = $this->get_css_var_prefix();

		$this->add_control(
			'text',
			array(
				'label' => esc_html__( 'Text', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => "--{$key}-text: {{VALUE}};",
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label' => esc_html__( 'Link', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => "--{$key}-link: {{VALUE}};",
				),
			)
		);

		$this->add_control(
			'hover',
			array(
				'label' => esc_html__( 'Link Hover', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => "--{$key}-hover: {{VALUE}};",
				),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label' => esc_html__( 'Heading', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => "--{$key}-heading: {{VALUE}};",
				),
			)
		);

		$this->add_control(
			'bg',
			array(
				'label' => esc_html__( 'Main Background', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => "--{$key}-bg: {{VALUE}};",
				),
			)
		);

		$this->add_control(
			'alternate',
			array(
				'label' => esc_html__( 'Alternate Background', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => "--{$key}-alternate: {{VALUE}};",
				),
			)
		);

		$this->add_control(
			'bd',
			array(
				'label' => esc_html__( 'Border', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => "--{$key}-bd: {{VALUE}};",
				),
			)
		);
	}

}
