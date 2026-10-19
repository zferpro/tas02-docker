<?php
/**
 * Prime Shopping Cart functions and definitions
 *
 * @package Prime_Shopping_Cart
 */

defined('ABSPATH') || exit;

/**
 * Core theme functions
 */
require_once get_template_directory() . '/inc/theme-core.php';

/**
* TGM Plugins
*/
require get_template_directory() . '/inc/tgm/recommended-plugins.php';

/**
 * Header system (layout, hero, header UI)
 */
require_once get_template_directory() . '/inc/class/class-header.php';

/**
 * Body system (layout grid, container, sidebar)
 */
require_once get_template_directory() . '/inc/class/class-body.php';

/**
 * Post related features (related posts, enhancements)
 */
require_once get_template_directory() . '/inc/class/class-post-related.php';

/**
 * Template tags (meta, tags, helper functions)
 */
require_once get_template_directory() . '/inc/class/class-template-tags.php';

/**
 * Footer system (footer layout and structure)
 */
require_once get_template_directory() . '/inc/class/class-footer.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';
/**
* TGM Plugins
*/
//require get_template_directory() . '/inc/tgm/recommended-plugins.php';


/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
require get_template_directory() . '/inc/about-themes.php';