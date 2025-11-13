<?php
namespace BellaBeautySpace\Admin\Installer\Importer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Widgets handler class is responsible for different methods on importing "Elementor" plugin widgets with controls where need to replace ids.
 */
class Elementor_Widgets {

	/**
	 * Change widgets query control in posts with _elementor_data on import.
	 *
	 * @param array $element Elementor element.
	 * @param array $displayed_ids Displayed ids.
	 *
	 * @return array Elementor element.
	 */
	public static function change_import_displayed_ids( $element, $displayed_ids = array() ) {
		if ( empty( $element['widgetType'] ) ) {
			return $element;
		}

		$element = self::parse_query_widgets( $element, $displayed_ids );
		$element = self::parse_nav_menu_widget( $element, $displayed_ids );
		$element = self::parse_dynamic_tags( $element, $displayed_ids );

		return $element;
	}

	/**
	 * Parse query widgets.
	 *
	 * @param array $element Elementor element.
	 * @param array $displayed_ids Displayed ids.
	 *
	 * @return array Elementor element.
	 */
	private static function parse_query_widgets( $element, $displayed_ids = array() ) {
		$blog_widgets = array(
			'cmsmasters-blog-grid',
			'cmsmasters-blog-slider',
			'cmsmasters-blog-featured',
			'cmsmasters-ticker',
		);

		$give_wp_widgets = array(
			'cmsmasters-give-wp-donor-wall',
			'cmsmasters-give-wp-form-grid',
			'cmsmasters-give-wp-forms',
			'cmsmasters-give-wp-goal',
			'cmsmasters-give-wp-multi-form-goal',
			'cmsmasters-give-wp-totals',
		);

		$tribe_events_widgets = array(
			'cmsmasters-tribe-events-events-grid',
			'cmsmasters-tribe-events-events-slider',
		);

		$all_widgets = array_merge( $blog_widgets, $give_wp_widgets, $tribe_events_widgets );

		$all_widgets[] = 'cmsmasters-woo-products';
		$all_widgets[] = 'cmsmasters-woo-products-slider';
		
		if ( ! in_array( $element['widgetType'], $all_widgets, true ) ) {
			return $element;
		}
		
		$displayed_post_ids = ( isset( $displayed_ids['post_id'] ) ? $displayed_ids['post_id'] : array() );
		$displayed_taxonomy_ids = ( isset( $displayed_ids['taxonomy'] ) ? $displayed_ids['taxonomy'] : array() );

		$post_ids = self::rearrange_displayed_ids( $displayed_post_ids );
		$term_ids = self::rearrange_displayed_ids( $displayed_taxonomy_ids );

		$setting_prefix = 'query_';

		if ( in_array( $element['widgetType'], $blog_widgets, true ) ) {
			$setting_prefix = 'blog_';
		}

		if ( in_array( $element['widgetType'], $tribe_events_widgets, true ) ) {
			$setting_prefix = 'tribe-events_';
		}

		if ( in_array( $element['widgetType'], $give_wp_widgets, true ) ) {
			$setting_prefix = '';
		}

		$widget_fields = array(
			'posts_in' => 'post',
			'include_term_ids' => 'term',
			'filter_' . $setting_prefix . 'include_term_ids' => 'term',
			'exclude_term_ids' => 'term',
			'posts_not_in' => 'post',
			'selected_authors' => 'author',
			'fallback_posts_in' => 'post',
			'form_list' => 'post',
			'donor_list' => 'post',
			'cat_list' => 'term',
			'tag_list' => 'term',
		);

		foreach ( $widget_fields as $field_key => $ids_type ) {
			switch( $ids_type ) {
				case 'post':
					$replace_ids = $post_ids;

					break;
				case 'term':
					$replace_ids = $term_ids;

					break;
			}

			$setting_key = $setting_prefix . $field_key;

			if ( 'filter_' . $setting_prefix . 'include_term_ids' === $field_key ) {
				$setting_key = $field_key;
			}

			if (
				! isset( $element['settings'][ $setting_key ] ) ||
				empty( $element['settings'][ $setting_key ] )
			) {
				continue;
			}

			if ( 'author' === $ids_type ) {
				$element['settings'][ $setting_key ] = array();

				continue;
			}

			$element['settings'][ $setting_key ] = self::replace_widget_field_ids(
				$element['settings'][ $setting_key ],
				$replace_ids
			);
		}

		return $element;
	}

	/**
	 * Parse navigation menu widget.
	 *
	 * @param array $element Elementor element.
	 * @param array $displayed_ids Displayed ids.
	 *
	 * @return array Elementor element.
	 */
	private static function parse_nav_menu_widget( $element, $displayed_ids = array() ) {
		if (
			'cmsmasters-nav-menu' !== $element['widgetType'] ||
			! isset( $displayed_ids['taxonomy']['nav_menu'] ) ||
			empty( $displayed_ids['taxonomy']['nav_menu'] ) ||
			empty( $element['settings']['nav_menu'] )
		) {
			return $element;
		}

		$old_id = $element['settings']['nav_menu'];

		if ( isset( $displayed_ids['taxonomy']['nav_menu'][ $old_id ] ) ) {
			$element['settings']['nav_menu'] = strval( $displayed_ids['taxonomy']['nav_menu'][ $old_id ] );
		}

		return $element;
	}

	/**
	 * Parse dynamic tags.
	 *
	 * @param array $element Elementor element.
	 * @param array $displayed_ids Displayed ids.
	 *
	 * @return array Elementor element.
	 */
	private static function parse_dynamic_tags( $element, $displayed_ids = array() ) {
		foreach ( $element['settings'] as $setting_key => $setting_value ) {
			if ( '__dynamic__' === $setting_key && ! empty( $setting_value ) ) {
				$element['settings'][ $setting_key ] = self::change_import_dynamic_tags_ids( $setting_value, $displayed_ids );
			} elseif ( is_array( $setting_value ) ) {
				foreach ( $setting_value as $inner_setting_key => $inner_setting_value ) {
					if ( ! empty( $inner_setting_value['__dynamic__'] ) ) {
						$element['settings'][ $setting_key ][ $inner_setting_key ]['__dynamic__'] = self::change_import_dynamic_tags_ids( $inner_setting_value['__dynamic__'], $displayed_ids );
					}
				}
			}
		}

		return $element;
	}

	/**
	 * Change ids in dynamic tags on import.
	 *
	 * @param array $settings Settings.
	 * @param array $displayed_ids Displayed ids.
	 *
	 * @return array Changed settings.
	 */
	public static function change_import_dynamic_tags_ids( $settings = array(), $displayed_ids = array() ) {
		if ( empty( $settings ) ) {
			return $settings;
		}

		foreach ( $settings as $setting_key => $setting_value ) {
			if ( false === strpos( $setting_value, 'cmsmasters-site-internal-url' ) ) {
				continue;
			}

			preg_match( '/settings="(.*?)"/', $setting_value, $tag_setting_match );
			$tag_setting = urldecode( $tag_setting_match[1] );
			$tag_setting = json_decode( $tag_setting );

			if ( ! empty( $tag_setting->post_id ) ) {
				$displayed_post_ids = ( isset( $displayed_ids['post_id'] ) ? $displayed_ids['post_id'] : array() );

				$post_ids = self::rearrange_displayed_ids( $displayed_post_ids );

				$old_post_id = $tag_setting->post_id;

				if ( isset( $post_ids[ $old_post_id ] ) ) {
					$settings[ $setting_key ] = str_replace( 'post_id%22%3A%22' . $old_post_id, 'post_id%22%3A%22' . $post_ids[ $old_post_id ], $settings[ $setting_key ] );
				}
			}

			if ( ! empty( $tag_setting->taxonomy_id ) ) {
				$displayed_taxonomy_ids = ( isset( $displayed_ids['taxonomy'] ) ? $displayed_ids['taxonomy'] : array() );

				$taxonomy_ids = self::rearrange_displayed_ids( $displayed_taxonomy_ids );

				$old_taxonomy_id = $tag_setting->taxonomy_id;

				if ( isset( $taxonomy_ids[ $old_taxonomy_id ] ) ) {
					$settings[ $setting_key ] = str_replace( 'taxonomy_id%22%3A%22' . $old_taxonomy_id, 'taxonomy_id%22%3A%22' . $taxonomy_ids[ $old_taxonomy_id ], $settings[ $setting_key ] );
				}
			}
		}

		return $settings;
	}

	/**
	 * Rearrange displayed ids.
	 *
	 * @param array $displayed_ids Displayed ids.
	 *
	 * @return array Displayed ids.
	 */
	private static function rearrange_displayed_ids( $displayed_ids ) {
		$out_ids = array();

		foreach ( $displayed_ids as $ids ) {
			foreach ( $ids as $old_id => $new_id ) {
				$out_ids[ $old_id ] = $new_id;
			}
		}

		return $out_ids;
	}

	/**
	 * Replace widget field ids.
	 *
	 * @param array|string $old_val Old value.
	 * @param array $replace_ids Replace ids.
	 *
	 * @return array|string ids.
	 */
	private static function replace_widget_field_ids( $old_val, $replace_ids ) {
		if ( is_array( $old_val ) ) {
			$final_ids = array();

			foreach ( $old_val as $index => $old_id ) {
				if ( isset( $replace_ids[ $old_id ] ) ) {
					$final_ids[ $index ] = strval( $replace_ids[ $old_id ] );
				}
			}

			return $final_ids;
		}

		if ( isset( $replace_ids[ $old_val ] ) ) {
			return $replace_ids[ $old_val ];
		}

		return $old_val;
	}

}
