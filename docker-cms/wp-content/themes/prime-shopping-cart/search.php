<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Prime_Shopping_Cart
 */

get_header();
/**
 * Current blog layout option.
 *
 * @var string
 */
$layout = prime_shopping_cart_get_option( 'blog_layout' );

//$column = ( $layout == 'full-container') ? 'col-md-4 col-sm-6 col-12' : 'col-md-6 col-sm-6 col-12';
$column = 'col-md-12';
/**
 * Open content wrapper.
 *
 * Hook:
 * - container_wrap_start()        Priority: 5
 * - container_wrap_column_start() Priority: 10
 */
do_action( 'prime_shopping_cart_container_wrap_start', esc_attr( $layout ) );

if ( have_posts() ) :

	// Accessible page title for blog posts page.
	if ( is_home() && ! is_front_page() ) :
		?>
		<header class="page-header screen-reader-text">
			<h1 class="page-title">
				<?php single_post_title(); ?>
			</h1>
		</header>
		<?php
	endif;

	/**
	 * Main Loop.
	 */
	echo "<div class='row'>";
	while ( have_posts() ) :

		the_post();

		/**
		* Run the loop for the search to output the results.
		* If you want to overload this in a child theme then include a file
		* called content-search.php and that will be used instead.
		*/
		echo "<div class='".esc_attr($column)."'>";
				get_template_part( 'template-parts/content', 'search' );
		echo "</div>";
	endwhile;
	echo "</div>";
	/**
	 * Posts navigation.
	 *
	 * Hook:
	 * - site_loop_navigation() Priority: 10
	 */
	do_action( 'prime_shopping_cart_loop_navigation');

else :

	/**
	 * No content found template.
	 */
	get_template_part( 'template-parts/content', 'none' );

endif;

/**
 * Close content wrapper.
 *
 * Hook:
 * - container_wrap_column_end() Priority: 5
 * - get_sidebar()               Priority: 10
 * - container_wrap_end()        Priority: 999
 */
do_action( 'prime_shopping_cart_container_wrap_end', esc_attr( $layout ) );

get_footer();
