<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
<0xC2><0xA0;*/

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5Z2JW4KT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php

if (
        ( ! function_exists( 'cmsmasters_template_do_location' ) || ! cmsmasters_template_do_location( 'footer' ) ) &&
        ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) )
) {
        get_template_part( 'template-parts/footer-widgets' );
        get_template_part( 'template-parts/footer' );
}

echo '<span class="cmsmasters-responsive-width"></span>';

wp_footer();
?>
</body>
</html>