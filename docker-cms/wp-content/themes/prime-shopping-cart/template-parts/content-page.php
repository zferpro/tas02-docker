<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Prime_Shopping_Cart
 */

?>
<article data-aos="fade-up" id="post-<?php the_ID(); ?>" <?php post_class('entry-content'); ?>>
 	<?php
    /**
    * Hook - prime_shopping_cart_posts_blog_media.
    *
    * @hooked prime_shopping_cart_posts_formats_thumbnail - 10
    */
    do_action( 'prime_shopping_cart_posts_blog_media' );
    ?>
    <div class="post">
		<?php
        /**
        * Hook - the8-shop-dark_site_content_type.
        *
		* @hooked site_loop_heading - 10
        * @hooked render_meta_list	- 20
		* @hooked site_content_type - 30
        */
		$meta = array();
		 do_action( 'prime_shopping_cart_site_content_type', $meta);

		wp_link_pages(
			array(
				'before' => '<div class="page-links clearfix">',
				'after'  => '</div>',
			)
		);
        ?>
    </div>
    <?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'prime-shopping-cart' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>

		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->