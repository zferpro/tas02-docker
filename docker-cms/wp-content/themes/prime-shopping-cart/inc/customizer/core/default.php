<?php
/**
 * Default theme options.
 *
 * @package prime-shopping-cart
 */

if ( ! function_exists( 'prime_shopping_cart_get_default_theme_options' ) ) :

	/**
	 * Get default theme options
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function prime_shopping_cart_get_default_theme_options() {

		$defaults = array();
		
		
		/*Posts Layout*/
		$defaults['blog_layout']     				= 'full-container';
		$defaults['blog_loop_content_type']     	= 'hide';
		$defaults['blog_meta_hide']     			= false;
		$defaults['hide_default_placeholder']     	= false;

		
		$defaults['single_post_layout']     		= 'no-sidebar';
		$defaults['single_meta_hide']     			= false;
		$defaults['read_more_text']     			= esc_html__( 'Read More', 'prime-shopping-cart' );
		
		/*layout*/
		$defaults['copyright_text']					= esc_html__( 'Copyright © Your Company. All rights reserved.', 'prime-shopping-cart' );
		
		$defaults['index_hide_thumb']     			= false;
		
		
		/*Posts Layout*/
		$defaults['__primary_color']     			= '#6c757d';
		$defaults['__secondary_color']     			= '#d2a35c';


		$defaults['__media_url']     				= '';
		$defaults['__banner_title']     			= '';
		$defaults['__banner_desc']     				= '';
		$defaults['__dialogue']					    = '';
		// Pass through filter.
		$defaults = apply_filters( 'prime_shopping_cartfilter_default_theme_options', $defaults );

		return $defaults;

	}

endif;
