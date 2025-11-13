<?php
namespace BellaBeautySpace\Modules;

use BellaBeautySpace\Modules\CSS_Vars;
use BellaBeautySpace\Modules\Gutenberg;
use BellaBeautySpace\Modules\Swiper;
use BellaBeautySpace\Modules\Page_Preloader;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme modules.
 *
 * Main class for theme modules.
 */
class Modules {

	/**
	 * Theme modules constructor.
	 *
	 * Run modules for theme.
	 */
	public function __construct() {
		new CSS_Vars();

		new Swiper();

		new Gutenberg();

		new Page_Preloader();
	}

}
