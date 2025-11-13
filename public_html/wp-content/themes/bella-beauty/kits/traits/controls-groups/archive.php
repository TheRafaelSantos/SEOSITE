<?php
namespace BellaBeautySpace\Kits\Traits\ControlsGroups;

use BellaBeautySpace\Kits\Controls\Controls_Manager as CmsmastersControls;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Archive trait.
 *
 * Allows to use a group of controls for archive.
 */
trait Archive {

	/**
	 * Group of controls for archive.
	 *
	 * @param string $key Controls key.
	 * @param array $args Controls args.
	 */
	protected function controls_group_archive( $key = '', $args = array() ) {
		list(
			$condition,
			$conditions
		) = $this->get_controls_group_required_args( $args, array(
			'condition' => array(), // Controls condition
			'conditions' => array(), // Controls conditions
		) );

		$default_args = array(
			'condition' => $condition,
			'conditions' => $conditions,
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'layout' ),
			array_merge_recursive(
				$default_args,
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
						$this->get_control_name_parameter( $key, 'layout' ),
						'r-sidebar'
					),
					'toggle' => false,
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'type' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Type', 'bella-beauty' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'large' => esc_html__( 'Large', 'bella-beauty' ),
						'grid' => esc_html__( 'Grid', 'bella-beauty' ),
						'compact' => esc_html__( 'Compact', 'bella-beauty' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'type' ),
						'large'
					),
					'toggle' => false,
				)
			)
		);

		$default_large_args = array_merge_recursive(
			$default_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'type' ) => 'large' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'large_elements_heading_control' ),
			array_merge_recursive(
				$default_large_args,
				array(
					'label' => esc_html__( 'Elements Order', 'bella-beauty' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'large_elements' ),
			array_merge_recursive(
				$default_large_args,
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
						'more' => esc_html__( 'Read More', 'bella-beauty' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'large_elements' ),
						array(
							'media',
							'title',
							'meta_first',
							'content',
							'meta_second',
							'more',
						)
					),
					'multiple' => true,
				)
			)
		);

		$default_grid_args = array_merge_recursive(
			$default_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'type' ) => 'grid' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'grid_style' ),
			array_merge_recursive(
				$default_grid_args,
				array(
					'label' => esc_html__( 'Style', 'bella-beauty' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'fit-rows' => esc_html__( 'Fit Rows', 'bella-beauty' ),
						'masonry' => esc_html__( 'Masonry', 'bella-beauty' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_style' ),
						'masonry'
					),
					'toggle' => false,
					'separator' => 'before',
				)
			)
		);

		$this->add_responsive_control(
			$this->get_control_name_parameter( $key, 'grid_columns' ),
			array_merge_recursive(
				$default_grid_args,
				array(
					'label' => esc_html__( 'Columns', 'bella-beauty' ),
					'label_block' => true,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'1' => array(
							'title' => '1',
							'description' => esc_html__( 'One', 'bella-beauty' ),
						),
						'2' => array(
							'title' => '2',
							'description' => esc_html__( 'Two', 'bella-beauty' ),
						),
						'3' => array(
							'title' => '3',
							'description' => esc_html__( 'Three', 'bella-beauty' ),
						),
						'4' => array(
							'title' => '4',
							'description' => esc_html__( 'Four', 'bella-beauty' ),
						),
						'5' => array(
							'title' => '5',
							'description' => esc_html__( 'Five', 'bella-beauty' ),
						),
					),
					'desktop_default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_columns' ),
						'4'
					),
					'tablet_default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_columns_tablet' ),
						'2'
					),
					'mobile_default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_columns_mobile' ),
						'1'
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'grid_columns' ) . ': {{VALUE}};',
					),
					'toggle' => true,
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'grid_elements_heading_control' ),
			array_merge_recursive(
				$default_grid_args,
				array(
					'label' => esc_html__( 'Elements Order', 'bella-beauty' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'grid_elements' ),
			array_merge_recursive(
				$default_grid_args,
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
						'more' => esc_html__( 'Read More', 'bella-beauty' ),
					),
					'multiple' => true,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_elements' ),
						array(
							'media',
							'title',
							'meta_first',
							'content',
							'meta_second',
							'more',
						)
					),
				)
			)
		);

		$default_compact_args = array_merge_recursive(
			$default_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'type' ) => 'compact' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'media_heading_control' ),
			array_merge_recursive(
				$default_compact_args,
				array(
					'label' => esc_html__( 'Media', 'bella-beauty' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_media_visibility' ),
			array_merge_recursive(
				$default_compact_args,
				array(
					'label' => esc_html__( 'Visibility', 'bella-beauty' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
					'type' => Controls_Manager::SWITCHER,
					'label_off' => esc_html__( 'Hide', 'bella-beauty' ),
					'label_on' => esc_html__( 'Show', 'bella-beauty' ),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'compact_media_visibility' ),
						'yes'
					),
				)
			)
		);

		$default_compact_media_args = array_merge_recursive(
			$default_compact_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'compact_media_visibility' ) => 'yes' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_media_position' ),
			array_merge_recursive(
				$default_compact_media_args,
				array(
					'label' => esc_html__( 'Position', 'bella-beauty' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'icon' => 'eicon-h-align-left',
							'title' => esc_html__( 'Left', 'bella-beauty' ),
						),
						'right' => array(
							'icon' => 'eicon-h-align-right',
							'title' => esc_html__( 'Right', 'bella-beauty' ),
						),
					),
					'toggle' => false,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'compact_media_position' ),
						'left'
					),
				)
			)
		);

		$this->add_responsive_control(
			$this->get_control_name_parameter( $key, 'compact_media_width' ),
			array_merge_recursive(
				$default_compact_media_args,
				array(
					'label' => esc_html__( 'Width', 'bella-beauty' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( '%' ),
					'range' => array(
						'%' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'compact_media_width' ) . ': {{SIZE}}%;',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_vertical_alignment' ),
			array_merge_recursive(
				$default_compact_media_args,
				array(
					'label' => esc_html__( 'Vertical Alignment', 'bella-beauty' ),
					'label_block' => false,
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'flex-start' => array(
							'icon' => 'eicon-v-align-top',
							'title' => esc_html__( 'Top', 'bella-beauty' ),
						),
						'center' => array(
							'icon' => 'eicon-v-align-middle',
							'title' => esc_html__( 'Center', 'bella-beauty' ),
						),
						'flex-end' => array(
							'icon' => 'eicon-v-align-bottom',
							'title' => esc_html__( 'Bottom', 'bella-beauty' ),
						),
					),
					'toggle' => false,
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'compact_vertical_alignment' ) . ': {{VALUE}};',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_elements_heading_control' ),
			array_merge_recursive(
				$default_compact_args,
				array(
					'label' => esc_html__( 'Elements Order', 'bella-beauty' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_elements' ),
			array_merge_recursive(
				$default_compact_args,
				array(
					'label_block' => true,
					'show_label' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'bella-beauty' ),
					'type' => CmsmastersControls::SELECTIZE,
					'options' => array(
						'title' => esc_html__( 'Title', 'bella-beauty' ),
						'meta_first' => esc_html__( 'Meta Data 1', 'bella-beauty' ),
						'meta_second' => esc_html__( 'Meta Data 2', 'bella-beauty' ),
						'content' => esc_html__( 'Content', 'bella-beauty' ),
						'more' => esc_html__( 'Read More', 'bella-beauty' ),
					),
					'multiple' => true,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'compact_elements' ),
						array(
							'title',
							'meta_first',
							'content',
							'meta_second',
							'more',
						)
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'apply_settings' ),
			array_merge_recursive(
				$default_args,
				array(
					'label_block' => true,
					'show_label' => false,
					'type' => Controls_Manager::BUTTON,
					'text' => esc_html__( 'Save & Reload', 'bella-beauty' ),
					'event' => 'cmsmasters:theme_settings:apply_settings',
					'separator' => 'before',
				)
			)
		);
	}

}
