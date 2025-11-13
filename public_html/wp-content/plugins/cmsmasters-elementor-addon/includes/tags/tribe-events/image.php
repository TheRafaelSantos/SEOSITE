<?php
namespace CmsmastersElementor\Tags\TribeEvents;

use CmsmastersElementor\Base\Traits\Base_Tag;
use CmsmastersElementor\Tags\TribeEvents\Traits\Tribe_Events_Group;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * CMSMasters event image.
 *
 * Retrieves the event image.
 *
 * @since 1.13.0
 */
class Image extends Data_Tag {

	use Base_Tag, Tribe_Events_Group;

	/**
	* Get tag name.
	*
	* Returns the name of the dynamic tag.
	*
	* @since 1.13.0
	*
	* @return string Tag name.
	*/
	public static function tag_name() {
		return 'event-image';
	}

	/**
	* Get tag image.
	*
	* Returns the image of the dynamic tag.
	*
	* @since 1.13.0
	*
	* @return string Tag image.
	*/
	public static function tag_title() {
		return __( 'Event Image', 'cmsmasters-elementor' );
	}

	/**
	* Get categories.
	*
	* Returns an array of dynamic tag categories.
	*
	* @since 1.13.0
	*
	* @return array Tag categories.
	*/
	public function get_categories() {
		return array( TagsModule::IMAGE_CATEGORY );
	}

	/**
	* Get value.
	*
	* Returns out the value of the dynamic data tag.
	*
	* @since 1.13.0
	*
	* @return array Tag value.
	*/
	public function get_value( array $options = array() ) {
		$event_data = tribe_get_event();

		if ( ! $event_data ) {
			return array();
		}

		$image_id = get_post_thumbnail_id( $event_data->ID );

		if ( $image_id ) {
			return array(
				'id' => $image_id,
				'url' => wp_get_attachment_image_src( $image_id, 'full' ),
			);
		}
	}

}
