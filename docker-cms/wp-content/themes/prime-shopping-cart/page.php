<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Prime_Shopping_Cart
 */

get_header();

$layout = prime_shopping_cart_get_option( 'page_layout' );

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