<?php
/**
 * WooCommerce Compatibility
 *
 * Handles WooCommerce integration, layout wrappers,
 * custom hooks, scripts, and theme support.
 *
 * Developer Note:
 * This file removes several default WooCommerce hooks
 * and replaces them with theme-specific markup/layouts
 * for better frontend consistency and styling control.
 *
 * @package prime-shopping-cart
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*--------------------------------------------------------------
# WooCommerce Hook Cleanup
--------------------------------------------------------------*/

/**
 * Remove default WooCommerce layout elements.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

/**
 * Disable default WooCommerce headings.
 */
add_filter( 'woocommerce_show_page_title', '__return_false' );
add_filter( 'woocommerce_product_description_heading', '__return_false' );
add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );

/**
 * Reposition cross-sells.
 */
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 5 );

/**
 * Move archive description into custom hook.
 */
add_action( 'prime_shopping_cart_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

/*--------------------------------------------------------------
# Theme Support
--------------------------------------------------------------*/

/**
 * Register WooCommerce support.
 *
 * @return void
 */
function prime_shopping_cart_woocommerce_setup(): void {

	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 300,
			'single_image_width'    => 600,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'prime_shopping_cart_woocommerce_setup' );

/*--------------------------------------------------------------
# Assets
--------------------------------------------------------------*/

/**
 * Load WooCommerce styles/scripts.
 *
 * @return void
 */
function prime_shopping_cart_woocommerce_scripts(): void {

	wp_enqueue_style(
		'prime-shopping-cart-woocommerce-core',
		get_template_directory_uri() . '/assets/css/woocommerce-core.css',
		array(),
		_S_VERSION
	);

	wp_enqueue_style(
		'prime-shopping-cart-woocommerce-style',
		get_template_directory_uri() . '/woocommerce.css',
		array(),
		_S_VERSION
	);
	wp_style_add_data( 'prime-shopping-cart-woocommerce-style', 'rtl', 'replace' );
	/**
	 * WooCommerce star rating font.
	 */
	$font_path = esc_url( WC()->plugin_url() . '/assets/fonts/' );

	$inline_font = "
	@font-face {
		font-family: 'star';
		src: url('{$font_path}star.eot');
		src:
			url('{$font_path}star.eot?#iefix') format('embedded-opentype'),
			url('{$font_path}star.woff') format('woff'),
			url('{$font_path}star.ttf') format('truetype'),
			url('{$font_path}star.svg#star') format('svg');
		font-weight: normal;
		font-style: normal;
	}";

	wp_add_inline_style(
		'prime-shopping-cart-woocommerce-style',
		$inline_font
	);

	wp_enqueue_script(
		'prime-shopping-cart-woocommerce',
		get_theme_file_uri( '/assets/js/prime-shopping-cart-woocommerce.js' ),
		array(),
		_S_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'prime_shopping_cart_woocommerce_scripts' );

/**
 * Disable default WooCommerce styles.
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/*--------------------------------------------------------------
# Body Classes
--------------------------------------------------------------*/
/**
 * Add WooCommerce body class.
 *
 * @param array $classes Body classes.
 * @return array
 */
function prime_shopping_cart_woocommerce_active_body_class( array $classes ): array {

	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'prime_shopping_cart_woocommerce_active_body_class' );

/*--------------------------------------------------------------
# Product Layout
--------------------------------------------------------------*/

/**
 * Related products & upsells layout.
 *
 * @param array $args Product args.
 * @return array
 */
function prime_shopping_cart_woocommerce_related_products_args( array $args ): array {

	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	return wp_parse_args( $defaults, $args );
}
add_filter( 'woocommerce_output_related_products_args', 'prime_shopping_cart_woocommerce_related_products_args' );
add_filter( 'woocommerce_upsell_display_args', 'prime_shopping_cart_woocommerce_related_products_args' );

/**
 * Cross-sell columns.
 *
 * @return int
 */
function prime_shopping_cart_change_cross_sells_columns(): int {
	return 4;
}
add_filter( 'woocommerce_cross_sells_columns', 'prime_shopping_cart_change_cross_sells_columns' );

/*--------------------------------------------------------------
# Theme Wrappers
--------------------------------------------------------------*/

/**
 * Remove default WooCommerce wrappers.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'prime_shopping_cart_woocommerce_wrapper_before' ) ) {

	/**
	 * Open theme wrapper.
	 *
	 * @return void
	 */
	function prime_shopping_cart_woocommerce_wrapper_before(): void {

		$layout = is_product()
			? 'no-sidebar'
			: 'full-container';

		do_action( 'prime_shopping_cart_container_wrap_start', $layout );
	}
}
add_action( 'woocommerce_before_main_content', 'prime_shopping_cart_woocommerce_wrapper_before' );

if ( ! function_exists( 'prime_shopping_cart_woocommerce_wrapper_after' ) ) {

	/**
	 * Close theme wrapper.
	 *
	 * @return void
	 */
	function prime_shopping_cart_woocommerce_wrapper_after(): void {

		$layout = is_product()
			? 'no-sidebar'
			: 'full-container';

		do_action( 'prime_shopping_cart_container_wrap_end', $layout );
	}
}
add_action( 'woocommerce_after_main_content', 'prime_shopping_cart_woocommerce_wrapper_after' );

/*--------------------------------------------------------------
# AJAX Cart Fragments
--------------------------------------------------------------*/

if ( ! function_exists( 'prime_shopping_cart_woocommerce_cart_link_fragment' ) ) {

	/**
	 * Refresh cart fragment after AJAX add-to-cart.
	 *
	 * @param array $fragments AJAX fragments.
	 * @return array
	 */
	function prime_shopping_cart_woocommerce_cart_link_fragment( array $fragments ): array {

		ob_start();

		prime_shopping_cart_woocommerce_cart_link();

		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'prime_shopping_cart_woocommerce_cart_link_fragment' );

/*--------------------------------------------------------------
# Header Cart
--------------------------------------------------------------*/

if ( ! function_exists( 'prime_shopping_cart_woocommerce_cart_link' ) ) {

	/**
	 * Display mini cart link.
	 *
	 * @return void
	 */
	function prime_shopping_cart_woocommerce_cart_link(): void {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>">

			<i class="bi bi-cart-check"></i>

			<?php
			$item_count = WC()->cart->get_cart_contents_count();

			$item_text = sprintf(
				/* translators: %d: item count */
				_n( '%d item', '%d items', $item_count, 'prime-shopping-cart' ),
				$item_count
			);
			?>

			<span class="cart-meta">
				<span class="count label">
					<?php echo esc_html( $item_text ); ?>
				</span>

				<span class="amount label_2">
					<?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
				</span>
			</span>

		</a>
		<?php
	}
}

if ( ! function_exists( 'prime_shopping_cart_woocommerce_header_cart' ) ) {

	/**
	 * Display header mini-cart widget.
	 *
	 * @return void
	 */
	function prime_shopping_cart_woocommerce_header_cart(): void {

		$current_class = is_cart()
			? 'current-menu-item'
			: '';
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $current_class ); ?>">
				<?php prime_shopping_cart_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				the_widget(
					'WC_Widget_Cart',
					array(
						'title' => '',
					)
				);
				?>
			</li>
		</ul>
		<?php
	}
}
/*--------------------------------------------------------------
# Shop Toolbar
--------------------------------------------------------------*/

/**
 * Remove default WooCommerce result count.
 *
 * Developer Note:
 * The theme replaces the default WooCommerce
 * result counter with a custom toolbar layout.
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

if ( ! function_exists( 'prime_shopping_cart_toolbar_start' ) ) {

	/**
	 * Open shop toolbar wrapper.
	 *
	 * Developer Note:
	 * Creates the main toolbar container before
	 * shop loop controls and filters.
	 *
	 * @return void
	 */
	function prime_shopping_cart_toolbar_start(): void {

		echo '<div class="prime-shopping-cart-toolbar">';
	}

	add_action( 'woocommerce_before_shop_loop', 'prime_shopping_cart_toolbar_start', 20 );
}

if ( ! function_exists( 'prime_shopping_cart_result_count' ) ) {

	/**
	 * Render custom product result count.
	 *
	 * Developer Note:
	 * Loads a custom template part instead of the
	 * default WooCommerce result counter markup.
	 *
	 * @return void
	 */
	function prime_shopping_cart_result_count(): void {

		get_template_part( 'woocommerce/result-count' );
	}

	add_action( 'woocommerce_before_shop_loop', 'prime_shopping_cart_result_count', 25 );
}

if ( ! function_exists( 'prime_shopping_cart_toolbar_end' ) ) {

	/**
	 * Close shop toolbar wrapper.
	 *
	 * @return void
	 */
	function prime_shopping_cart_toolbar_end(): void {

		echo '</div>';
	}

	add_action( 'woocommerce_before_shop_loop', 'prime_shopping_cart_toolbar_end', 40 );
}

if ( ! function_exists( 'prime_shopping_cart_loop_shop_per_page' ) ) {

	/**
	 * Set products per page.
	 *
	 * Developer Note:
	 * Allows visitors to dynamically control the
	 * number of products displayed per page using
	 * the `products-per-page` query parameter.
	 *
	 * Example:
	 * ?products-per-page=24
	 * ?products-per-page=all
	 *
	 * @return int
	 */
	function prime_shopping_cart_loop_shop_per_page(): int {

		$posts_per_page = isset( $_GET['products-per-page'] )
			? sanitize_text_field( wp_unslash( $_GET['products-per-page'] ) )
			: get_theme_mod( 'shopstore_woo_shop_posts_per_page', 12 );

		// Show all products.
		if ( 'all' === $posts_per_page ) {

			return (int) wp_count_posts( 'product' )->publish;
		}

		return absint( $posts_per_page );
	}

	add_filter( 'loop_shop_per_page', 'prime_shopping_cart_loop_shop_per_page', 20 );
}
/*--------------------------------------------------------------
# Product Loop
--------------------------------------------------------------*/

/**
 * Set default product columns for shop archives.
 *
 * Developer Note:
 * Controls how many products appear per row
 * on WooCommerce archive and shop pages.
 *
 * @since 1.0.0
 * @return int
 */
function prime_shopping_cart_woocommerce_loop_columns(): int {
	return 4;
}
add_filter( 'loop_shop_columns', 'prime_shopping_cart_woocommerce_loop_columns' );


if ( ! function_exists( 'prime_shopping_cart_thumbnail_before' ) ) {

	/**
	 * Open product image wrapper.
	 *
	 * Developer Note:
	 * Creates a dedicated wrapper for product
	 * thumbnails, hover effects, badges, and actions.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_thumbnail_before(): void {
		echo '<div class="product-image">';
	}

	add_action(
		'woocommerce_before_shop_loop_item_title',
		'prime_shopping_cart_thumbnail_before',
		5
	);
}

/**
 * Reposition default WooCommerce hooks.
 */
add_action(
	'woocommerce_before_shop_loop_item_title',
	'woocommerce_template_loop_rating',
	10
);

if ( ! function_exists( 'prime_shopping_cart_loop_product_thumbnail' ) ) {

	/**
	 * Render product thumbnail with optional hover image.
	 *
	 * Developer Note:
	 * Displays the featured product image and
	 * automatically adds the first gallery image
	 * as a hover/secondary thumbnail if available.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_loop_product_thumbnail(): void {

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$attachment_ids = $product->get_gallery_image_ids();

		if ( ! empty( $attachment_ids[0] ) ) {

			$image_args = array(
				'class' => 'woo-entry-image-secondary',
				'alt'   => the_title_attribute(
					array(
						'echo' => false,
					)
				),
			);

			echo '<figure class="hover_hide">'. wp_kses_post(woocommerce_get_product_thumbnail()) . wp_get_attachment_image( $attachment_ids[0], 'woocommerce_thumbnail', false, $image_args ) .'</figure>';

		} else {

			echo '<figure class="product-thumbnail">';
				echo wp_kses_post( woocommerce_get_product_thumbnail() );
			echo '</figure>';
		}

		
	}

	add_action(
		'woocommerce_before_shop_loop_item_title',
		'prime_shopping_cart_loop_product_thumbnail',
		30
	);
}


if ( ! function_exists( 'prime_shopping_cart_thumbnail_after' ) ) {

	/**
	 * Close product image wrapper.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_thumbnail_after(): void {
		echo '</div>';
	}

	add_action(
		'woocommerce_before_shop_loop_item_title',
		'prime_shopping_cart_thumbnail_after',
		90
	);
}
if ( ! function_exists( 'prime_shopping_cart_shop_loop_content_before' ) ) {

	/**
	 * Open product content wrapper.
	 *
	 * Developer Note:
	 * Wraps title, price, rating, and buttons
	 * inside a dedicated content container.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_shop_loop_content_before(): void {
		echo '<div class="product-wrap">';
	}

	add_action(
		'woocommerce_shop_loop_item_title',
		'prime_shopping_cart_shop_loop_content_before',
		5
	);
}

if ( ! function_exists( 'prime_shopping_cart_shop_loop_item_title' ) ) {

	/**
	 * Render custom product title.
	 *
	 * Developer Note:
	 * Overrides the default WooCommerce loop title
	 * markup for improved styling flexibility.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_shop_loop_item_title(): void {

		$classes = apply_filters(
			'woocommerce_product_loop_title_classes',
			'woocommerce-loop-product__title entry-title'
		);

		printf(
			'<h3 class="%1$s"><a href="%2$s">%3$s</a></h3>',
			esc_attr( $classes ),
			esc_url( get_the_permalink() ),
			esc_html( get_the_title() )
		);
	}

	add_action(
		'woocommerce_shop_loop_item_title',
		'prime_shopping_cart_shop_loop_item_title',
		30
	);
}


if ( ! function_exists( 'prime_shopping_cart_shop_loop_content_after' ) ) {

	/**
	 * Close product content wrapper.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_shop_loop_content_after(): void {
		echo '</div>';
	}

	add_action(
		'woocommerce_after_shop_loop_item',
		'prime_shopping_cart_shop_loop_content_after',
		999
	);
}


if ( ! function_exists( 'prime_shopping_cart_before_quantity_input_field' ) ) {

	/**
	 * Render quantity increase button.
	 *
	 * Developer Note:
	 * Adds a custom plus button before the
	 * WooCommerce quantity input field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_before_quantity_input_field(): void {
		echo '<button type="button" class="qty-button qty-plus" aria-label="' . esc_attr__( 'Increase quantity', 'prime-shopping-cart' ) . '">';
		echo '<i class="bi bi-plus"></i>';
		echo '</button>';
	}

	add_action(
		'woocommerce_before_quantity_input_field',
		'prime_shopping_cart_before_quantity_input_field',
		10
	);
}


if ( ! function_exists( 'prime_shopping_cart_after_quantity_input_field' ) ) {

	/**
	 * Render quantity decrease button.
	 *
	 * Developer Note:
	 * Adds a custom minus button after the
	 * WooCommerce quantity input field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_after_quantity_input_field(): void {
		echo '<button type="button" class="qty-button qty-minus" aria-label="' . esc_attr__( 'Decrease quantity', 'prime-shopping-cart' ) . '">';
		echo '<i class="bi bi-dash"></i>';
		echo '</button>';
	}

	add_action(
		'woocommerce_after_quantity_input_field',
		'prime_shopping_cart_after_quantity_input_field',
		10
	);
}


/**
 * Single product sale countdown.
 */
/*add_action(
	'prime_shopping_cart_single_product_countdown',
	'prime_shopping_cart_single_product_countdown'
);*/


if ( ! function_exists( 'prime_shopping_cart_single_product_countdown' ) ) {

	/**
	 * Render sale countdown timer.
	 *
	 * Developer Note:
	 * Displays a countdown timer for products
	 * with an active scheduled sale end date.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prime_shopping_cart_single_product_countdown(): void {

		global $product;

		if (
			! $product instanceof WC_Product ||
			( ! $product->is_type( 'simple' ) && ! $product->is_type( 'variable' ) )
		) {
			return;
		}

		$sale_end = get_post_meta(
			$product->get_id(),
			'_sale_price_dates_to',
			true
		);

		if ( ! $product->is_on_sale() || empty( $sale_end ) ) {
			return;
		}

		$formatted_date = gmdate(
			'Y-m-d H:i:s',
			(int) $sale_end
		);

		?>
		<div class="sale-countdown-wrapper">
			<div
				id="sale-countdown"
				class="sale-countdown"
				data-end-date="<?php echo esc_attr( $formatted_date ); ?>"
				data-days="<?php esc_attr_e( 'Days', 'prime-shopping-cart' ); ?>"
				data-hours="<?php esc_attr_e( 'Hours', 'prime-shopping-cart' ); ?>"
				data-minutes="<?php esc_attr_e( 'Minutes', 'prime-shopping-cart' ); ?>"
				data-seconds="<?php esc_attr_e( 'Seconds', 'prime-shopping-cart' ); ?>"
			>
				<?php echo esc_html( $formatted_date ); ?>
			</div>
		</div>
		<?php
	}
}


if ( ! function_exists( 'prime_shopping_cart_product_categories' ) ) {
	/**
	 * Product Loop categorie.
	 *
	 * @return  void
	 */
	function prime_shopping_cart_product_categories() {
		global $product;
		echo '<div class="prime-categories">';
            echo wp_kses( wc_get_product_category_list( $product->get_id(), ', ', ), prime_shopping_cart_allowed_tags() );
        echo '</div>';
        
	}
}
add_action( 'woocommerce_shop_loop_item_title', 'prime_shopping_cart_product_categories', 20 );

/**
 * Display WooCommerce total sold count.
 *
 * Developer Note:
 * Uses WooCommerce built-in `total_sales` meta
 * to display how many units were sold.
 *
 * Example Output:
 * 120 Sold
 *
 * @since 1.0.0
 * @return void
 */
function prime_shopping_cart_product_sold_count(): void {

	global $product;
	// Validate product object.
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	// Get total sales count.
	$total_sales = (int) get_post_meta(
		$product->get_id(),
		'total_sales',
		true
	);

	// Hide if no sales yet.
	if ( $total_sales <= 0 ) {
		return;
	}

	printf(
		'<span class="product-sold-count">
			<i class="bi bi-fire"></i>
			%s
		</span>',
		esc_html(
			sprintf(
				/* translators: %s = total sold items */
				_n( '%s Sold', '%s Sold', $total_sales, 'prime-shopping-cart' ),
				number_format_i18n( $total_sales )
			)
		)
	);
}
add_action(
	'prime_shopping_cart_product_sold_count',
	'prime_shopping_cart_product_sold_count',
	10
);

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);


add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_filter('woocommerce_get_stock_html', '__return_empty_string');
/**
 * Custom single product add to cart button markup.
 *
 * Developer Note:
 * Replaces the default single product button HTML
 * with custom icon + text structure.
 *
 * @since 1.0.0
 */

add_filter(
	'woocommerce_loop_add_to_cart_link',
	'prime_shopping_cart_single_add_to_cart_button',
	10,
	2
);

function prime_shopping_cart_single_add_to_cart_button( $button, $product ) {

	if ( is_product() ) {

		$button_text = $product->single_add_to_cart_text();

		$button = sprintf(
			'<button type="submit" name="add-to-cart" value="%1$s" class="single_add_to_cart_button button alt theme-btn">
				<span class="icon">
					<i class="bi bi-cart-plus"></i>
				</span>
				<span class="text">%2$s</span>
			</button>',
			esc_attr( $product->get_id() ),
			esc_html( $button_text )
		);
	}

	return $button;
}


/**
 * Customize WooCommerce review form.
 *
 * Layout:
 * - Rating
 * - Name
 * - Email
 * - Review Text
 * - Cookie Consent
 * - Submit Button
 */
add_filter( 'woocommerce_product_review_comment_form_args', 'prime_shopping_cart_review_form' );

function prime_shopping_cart_review_form( $comment_form ) {

	$commenter = wp_get_current_commenter();
	$req       = (bool) get_option( 'require_name_email' );
	$consent   = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

	/**
	 * Form wrapper classes.
	 */
	$comment_form['class_form'] = 'row review-form';

	/**
	 * Reply title markup.
	 */
	$comment_form['title_reply_before'] = '<h4 class="comments-title"><i class="bi bi-star"></i> ';
	$comment_form['title_reply_after']  = '</h4>';

	/**
	 * Notes before form fields.
	 */
	$comment_form['comment_notes_before'] =
		'<p class="comment-notes col-12">' .
			esc_html__(
				'Your email address will not be published. Required fields are marked *.',
				'prime-shopping-cart'
			) .
		'</p>';

	$comment_form['comment_notes_after'] = '';

	/**
	 * Submit button.
	 */
	$comment_form['label_submit'] = esc_html__(
		'Submit Review',
		'prime-shopping-cart'
	);

	$comment_form['submit_button'] =
		'<button type="submit" class="theme-btn" id="%2$s">
			<span class="icon">
				<i class="bi bi-chat-square-text"></i>
			</span>
			<span class="text">%4$s</span>
		</button>';

	/**
	 * Build fields in desired order.
	 */
	$fields = array();

	/**
	 * Rating field.
	 *
	 * Added as the first field so it appears above
	 * Name and Email fields.
	 */
	if ( wc_review_ratings_enabled() ) {

		$fields['rating'] =
			'<div class="comment-form-rating col-12">
				<label for="rating">
					' . esc_html__( 'Your Rating', 'prime-shopping-cart' ) .
					( wc_review_ratings_required() ? ' <span class="required">*</span>' : '' ) . '
				</label>

				<select name="rating" id="rating" ' . ( wc_review_ratings_required() ? 'required' : '' ) . '>
					<option value="">' . esc_html__( 'Rate&hellip;', 'prime-shopping-cart' ) . '</option>
					<option value="5">' . esc_html__( 'Perfect', 'prime-shopping-cart' ) . '</option>
					<option value="4">' . esc_html__( 'Good', 'prime-shopping-cart' ) . '</option>
					<option value="3">' . esc_html__( 'Average', 'prime-shopping-cart' ) . '</option>
					<option value="2">' . esc_html__( 'Not that bad', 'prime-shopping-cart' ) . '</option>
					<option value="1">' . esc_html__( 'Very poor', 'prime-shopping-cart' ) . '</option>
				</select>
			</div>';
	}

	/**
	 * Author field.
	 */
	$fields['author'] =
		'<label class="col-xl-6 col-lg-6 col-md-6 col-12">
			<input
				id="author"
				name="author"
				type="text"
				value="' . esc_attr( $commenter['comment_author'] ) . '"
				placeholder="' . esc_attr__( 'Your Name', 'prime-shopping-cart' ) . '"
				' . ( $req ? 'required="required"' : '' ) . '
			/><span class="required">*</span>
		</label>';

	/**
	 * Email field.
	 */
	$fields['email'] =
		'<label class="col-xl-6 col-lg-6 col-md-6 col-12">
			<input
				id="email"
				name="email"
				type="email"
				value="' . esc_attr( $commenter['comment_author_email'] ) . '"
				placeholder="' . esc_attr__( 'Your Email', 'prime-shopping-cart' ) . '"
				' . ( $req ? 'required="required"' : '' ) . '
			/><span class="required">*</span>
		</label>';

	/**
	 * Cookie consent field.
	 */
	$fields['cookies'] =
		'<p class="comment-form-cookies-consent col-12">
			<input
				id="wp-comment-cookies-consent"
				name="wp-comment-cookies-consent"
				type="checkbox"
				value="yes"' . $consent . '
			/>
			<label for="wp-comment-cookies-consent">
				' . esc_html__(
					'Save my name, email, and website in this browser for the next time I review.',
					'prime-shopping-cart'
				) . '
			</label>
		</p>';

	$comment_form['fields'] = $fields;

	/**
	 * Review textarea.
	 *
	 * This appears after all fields above.
	 */
	$comment_form['comment_field'] =
		'<div class="col-12">
			<textarea
				id="comment"
				name="comment"
				cols="45"
				rows="8"
				required="required"
				placeholder="' . esc_attr__( 'Write your review', 'prime-shopping-cart' ) . '"
			></textarea><span class="required">*</span>
		</div>';

	return $comment_form;
}

/**
 * WooCommerce / WordPress comment callback for product reviews.
 *
 * Custom layout for single product reviews.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    Comment arguments.
 * @param int        $depth   Depth of comment.
 */
function prime_shopping_cart_review_callback( $comment, $args, $depth ) {

	
	$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
	?>

	<li <?php comment_class( empty( $args['has_children'] ) ? 'review shift' : 'review' ); ?>
		id="li-comment-<?php comment_ID(); ?>">

		<div id="comment-<?php comment_ID(); ?>" class="single-comment clearfix">

			<?php if ( ! empty( $args['avatar_size'] ) ) : ?>
				<?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'float-start' ) ); ?>
			<?php endif; ?>

			<div class="comment-content float-start">

				<h6 class="comment-author">
					<?php echo wp_kses_post( get_comment_author_link() ); ?>
				</h6>

				<div class="date d-flex">
					<?php
					printf(
						/* translators: 1: date, 2: time */
						esc_html__( '%1$s at %2$s', 'prime-shopping-cart' ),
						esc_html( get_comment_date() ),
						esc_html( get_comment_time() )
					);
					?>
				</div>

				<?php if ( wc_review_ratings_enabled() && $rating ) : ?>
					<div class="woocommerce-review-rating">
						<?php echo wc_get_rating_html( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="woocommerce-review-awaiting-approval">
						<?php esc_html_e( 'Your review is awaiting approval.', 'prime-shopping-cart' ); ?>
					</p>
				<?php endif; ?>

				<div class="comment-text">
					<?php comment_text(); ?>
				</div>

			</div>

		</div>

	</li>

	<?php
}
/**
 * Replace default WooCommerce review callback.
 */
add_filter( 'woocommerce_product_review_list_args', 'prime_shopping_cart_review_list_args' );

function prime_shopping_cart_review_list_args( $args ) {

	$args['callback']    = 'prime_shopping_cart_review_callback';
	$args['avatar_size'] = 80;
	$args['style']       = 'ul';

	return $args;
}
/**
 * Customize WooCommerce reviews heading.
 */
add_filter( 'woocommerce_reviews_title', 'prime_shopping_cart_reviews_title', 10, 3 );

function prime_shopping_cart_reviews_title( $reviews_title, $count, $product ) {

	$average = $product->get_average_rating();

	$title  = wc_get_rating_html( $average );
	$title .= sprintf(
		' <span class="review-count">%s</span>',
		sprintf(
			/* translators: 1: average rating (out of 5), 2: number of reviews */
			esc_html__( '%1$s out of 5 (%2$s Reviews)', 'prime-shopping-cart' ),
			$average,
			number_format_i18n( $count )
		)
	);

	return $title;
}
