<?php
/**
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 11.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
    return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

if ( $rating_count > 0 ) : ?>

    <div class="woocommerce-product-rating d-flex align-items-center flex-wrap gap-2">

        <?php
        // Star rating HTML.
        echo wp_kses(
            wc_get_rating_html( $average, $rating_count ),
            prime_shopping_cart_allowed_tags()
        );
        ?>

        <?php if ( $review_count > 0 ) : ?>

            <div class="custom-product-rating">

                <?php
                printf(
                    /* translators: 1: average rating, 2: review count */
                    esc_html__( 'Rated %1$s out of 5 based on %2$d review(s)', 'prime-shopping-cart' ),
                    esc_html( number_format_i18n( $average, 1 ) ),
                    absint( $review_count )
                );
                ?>

            </div>

        <?php endif; ?>

    </div>

<?php endif; ?>