<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package prime-shopping-cart
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
		if( !is_single() ){
			do_action( 'prime_shopping_cart_post_categories');
		}
        /**
        * Hook - the8-shop-dark_site_content_type.
        *
		* @hooked site_loop_heading - 10
        * @hooked render_meta_list	- 20
		* @hooked site_content_type - 30
        */
		$meta = array();
		if ( is_singular() ) :
				$meta = array();
		else :
			if( prime_shopping_cart_get_option('blog_meta_hide') != true ){
				
				$meta = array( 'date', 'author' );
			}
			$meta['content'] = prime_shopping_cart_get_option( 'blog_loop_content_type');
			$meta  	 = apply_filters( 'prime_shopping_cart_blog_meta', $meta);
		 endif;
		 do_action( 'prime_shopping_cart_site_content_type', $meta);
        ?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->