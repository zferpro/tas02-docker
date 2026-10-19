<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package prime_shopping_cart
 */

get_header();

$layout = prime_shopping_cart_get_option( 'single_post_layout' );

/**
 * Hook: prime_shopping_cart_container_wrap_start
 *
 * @hooked prime_shopping_cart_container_wrap_start - 5
 */
do_action( 'prime_shopping_cart_container_wrap_start', esc_attr( $layout ) );

if ( have_posts() ) :

	while ( have_posts() ) :
		the_post();

		/*
		 * Content template.
		 */
		get_template_part( 'template-parts/content', get_post_type() );

		/**
		 * Hook: prime_shopping_cart_single_post_navigation
		 *
		 * @hooked prime_shopping_cart_single_post_navigation
		 */
		do_action( 'prime_shopping_cart_single_post_navigation' );

		/*
		 * Load comments template.
		 */
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}

	endwhile;

endif;

/**
 * Hook: prime_shopping_cart_container_wrap_end
 *
 * @hooked prime_shopping_cart_container_wrap_end - 999
 */
do_action( 'prime_shopping_cart_container_wrap_end', esc_attr( $layout ) );

get_footer();