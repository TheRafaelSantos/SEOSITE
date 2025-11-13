<?php
namespace BellaBeautySpace\Kits\Settings\Elements;

use BellaBeautySpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Slider Bullets settings.
 */
class Slider_Bullets extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'slider_bullets';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Slider Bullets', 'bella-beauty' );
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
				'raw' => esc_html__( 'Used in: more posts, single post gallery, archive post gallery.', 'bella-beauty' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'type',
			array(
				'label' => esc_html__( 'Type', 'bella-beauty' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'normal' => esc_html__( 'Normal', 'bella-beauty' ),
					'dynamic' => esc_html__( 'Dynamic', 'bella-beauty' ),
					'numbered' => esc_html__( 'Numbered', 'bella-beauty' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'type' ),
					'normal'
				),
			)
		);

		$this->add_var_group_control( '', self::VAR_TYPOGRAPHY, array(
			'condition' => array(
				$this->get_control_id_parameter( '', 'type' ) => 'numbered',
			),
		) );

		$this->start_controls_tabs( 'states_tabs' );

		foreach ( array(
			'normal' => esc_html__( 'Normal', 'bella-beauty' ),
			'hover' => esc_html__( 'Hover', 'bella-beauty' ),
			'active' => esc_html__( 'Active', 'bella-beauty' ),
		) as $key => $label ) {
			$this->start_controls_tab(
				"states_{$key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"{$key}_colors_bg",
				array(
					'label' => esc_html__( 'Background', 'bella-beauty' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_bg" ) . ': {{VALUE}};',
					),
				)
			);

			$this->add_control(
				"{$key}_colors_text",
				array(
					'label' => esc_html__( 'Text', 'bella-beauty' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_text" ) . ': {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( '', 'type' ) => 'numbered',
					),
				)
			);

			$this->add_control(
				"{$key}_colors_bd",
				array(
					'label' => esc_html__( 'Border', 'bella-beauty' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_bd" ) . ': {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( '', 'border_border!' ) => 'none',
					),
				)
			);

			$this->add_var_group_control( $key, self::VAR_BOX_SHADOW );

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_var_group_control( '', self::VAR_BORDER, array(
			'fields_options' => array(
				'width' => array( 'label' => esc_html__( 'Border Width', 'bella-beauty' ) ),
			),
			'separator' => 'before',
			'exclude' => array( 'color' ),
		) );

		$this->add_control(
			'bd_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'bella-beauty' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'max' => 100,
						'min' => 0,
					),
					'%' => array(
						'max' => 50,
						'min' => 0,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'bd_radius' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'size',
			array(
				'label' => esc_html__( 'Size', 'bella-beauty' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'max' => 50,
						'min' => 1,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'size' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'spacing',
			array(
				'label' => esc_html__( 'Spacing', 'bella-beauty' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'spacing' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_heading_control',
			array(
				'label' => esc_html__( 'Container', 'bella-beauty' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'container_bg_color',
			array(
				'label' => esc_html__( 'Background', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_bg_color' ) . ': {{VALUE}};',
				),
			)
		);

		$this->add_var_group_control( 'container', self::VAR_BORDER );

		$this->add_control(
			'container_bd_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'bella-beauty' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_bd_radius' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label' => esc_html__( 'Padding', 'bella-beauty' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_padding_top' ) . ': {{TOP}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_padding_right' ) . ': {{RIGHT}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_padding_bottom' ) . ': {{BOTTOM}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_padding_left' ) . ': {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_margin',
			array(
				'label' => esc_html__( 'Margin', 'bella-beauty' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_margin_top' ) . ': {{TOP}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_margin_right' ) . ': {{RIGHT}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_margin_bottom' ) . ': {{BOTTOM}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_margin_left' ) . ': {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_jc',
			array(
				'label' => esc_html__( 'Horizontal Alignment', 'bella-beauty' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'bella-beauty' ),
						'icon' => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'bella-beauty' ),
						'icon' => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'bella-beauty' ),
						'icon' => 'eicon-h-align-right',
					),
				),
				'toggle' => true,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_jc' ) . ': {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_ai',
			array(
				'label' => esc_html__( 'Vertical Alignment', 'bella-beauty' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'bella-beauty' ),
						'icon' => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'bella-beauty' ),
						'icon' => ' eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'bella-beauty' ),
						'icon' => 'eicon-v-align-bottom',
					),
				),
				'toggle' => true,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_ai' ) . ': {{VALUE}};',
				),
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
