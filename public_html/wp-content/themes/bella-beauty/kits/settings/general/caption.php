<?php
namespace BellaBeautySpace\Kits\Settings\General;

use BellaBeautySpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Caption settings.
 */
class Caption extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'caption';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Caption', 'bella-beauty' );
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
				'raw' => esc_html__( 'Used in: default caption, Gutenberg editor.', 'bella-beauty' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->start_controls_tabs( 'types_tabs' );

		foreach ( array(
			'outside' => esc_html__( 'Outside', 'bella-beauty' ),
			'inside' => esc_html__( 'Inside', 'bella-beauty' ),
		) as $key => $label ) {
			$this->start_controls_tab(
				"type_{$key}_tab",
				array( 'label' => $label )
			);

			$this->add_var_group_control( $key, self::VAR_TYPOGRAPHY );

			$this->add_control(
				"{$key}_colors_heading_control",
				array(
					'label' => esc_html__( 'Colors', 'bella-beauty' ),
					'type' => Controls_Manager::HEADING,
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
				)
			);

			$this->add_control(
				"{$key}_colors_link",
				array(
					'label' => esc_html__( 'Link', 'bella-beauty' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_link" ) . ': {{VALUE}};',
					),
				)
			);

			$this->add_control(
				"{$key}_colors_hover",
				array(
					'label' => esc_html__( 'Link Hover', 'bella-beauty' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_hover" ) . ': {{VALUE}};',
					),
				)
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
				"{$key}_colors_bd",
				array(
					'label' => esc_html__( 'Border', 'bella-beauty' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_bd" ) . ': {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( '', "{$key}_border_border!" ) => 'none',
					),
				)
			);

			$this->add_var_group_control( $key, self::VAR_BORDER, array(
				'fields_options' => array(
					'width' => array( 'label' => esc_html__( 'Border Width', 'bella-beauty' ) ),
				),
				'separator' => 'before',
				'exclude' => array( 'color' ),
			) );

			$this->add_control(
				"{$key}_bd_radius",
				array(
					'label' => esc_html__( 'Border Radius', 'bella-beauty' ),
					'type' => Controls_Manager::DIMENSIONS,
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
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_bd_radius" ) . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				"{$key}_padding",
				array(
					'label' => esc_html__( 'Padding', 'bella-beauty' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_padding_top" ) . ': {{TOP}}{{UNIT}};' .
							'--' . $this->get_control_prefix_parameter( '', "{$key}_padding_right" ) . ': {{RIGHT}}{{UNIT}};' .
							'--' . $this->get_control_prefix_parameter( '', "{$key}_padding_bottom" ) . ': {{BOTTOM}}{{UNIT}};' .
							'--' . $this->get_control_prefix_parameter( '', "{$key}_padding_left" ) . ': {{LEFT}}{{UNIT}};',
					),
				)
			);

			if ( 'outside' === $key ) {
				$this->add_responsive_control(
					"{$key}_gap",
					array(
						'label' => esc_html__( 'Gap', 'bella-beauty' ),
						'type' => Controls_Manager::SLIDER,
						'range' => array(
							'px' => array(
								'min' => 0,
								'max' => 50,
							),
							'%' => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'size_units' => array(
							'px',
							'%',
						),
						'selectors' => array(
							':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_gap" ) . ': {{SIZE}}{{UNIT}};',
						),
					)
				);
			}

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_control(
			'image_heading_control',
			array(
				'label' => esc_html__( 'Image with Caption', 'bella-beauty' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'image_notice',
			array(
				'raw' => esc_html__( 'Used for classic wp-caption image.', 'bella-beauty' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'image_colors_bg',
			array(
				'label' => esc_html__( 'Background', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'image_colors_bg' ) . ': {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'image_colors_bd',
			array(
				'label' => esc_html__( 'Border', 'bella-beauty' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'image_colors_bd' ) . ': {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'image_border_border!' ) => 'none',
				),
			)
		);

		$this->add_var_group_control( 'image', self::VAR_BORDER, array(
			'fields_options' => array(
				'width' => array( 'label' => esc_html__( 'Border Width', 'bella-beauty' ) ),
			),
			'exclude' => array( 'color' ),
		) );

		$this->add_control(
			'image_bd_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'bella-beauty' ),
				'type' => Controls_Manager::DIMENSIONS,
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
					':root' => '--' . $this->get_control_prefix_parameter( '', 'image_bd_radius' ) . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_padding',
			array(
				'label' => esc_html__( 'Padding', 'bella-beauty' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'image_padding_top' ) . ': {{TOP}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'image_padding_right' ) . ': {{RIGHT}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'image_padding_bottom' ) . ': {{BOTTOM}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'image_padding_left' ) . ': {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

}
