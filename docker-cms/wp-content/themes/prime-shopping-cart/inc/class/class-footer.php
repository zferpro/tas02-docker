<?php
/**
 * Theme Footer Layout Class
 *
 * Handles:
 * - Footer wrapper
 * - Footer widgets
 * - Copyright section
 * - Back to top button
 *
 * @package prime-shopping-cart
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Prime_Shopping_Cart_Footer_Layout {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_hooks();
	}

	/**
	 * Register footer hooks.
	 *
	 * @return void
	 */
	private function register_hooks(): void {

		add_action('prime_shopping_cart_site_footer',array( $this, 'footer_container_before' ),5);
		add_action('prime_shopping_cart_site_footer',array( $this, 'footer_widgets' ),10);
		add_action('prime_shopping_cart_site_footer',array( $this, 'footer_info' ),80);
		add_action('prime_shopping_cart_site_footer',array( $this, 'footer_container_after' ),998);
		add_action('prime_shopping_cart_site_footer',array( $this, 'back_to_top_button' ),999);
	}

	/**
	 * Open footer wrapper.
	 *
	 * Developer Note:
	 * Starts the global footer container.
	 *
	 * @return void
	 */
	public function footer_container_before(): void {

		$html = '<footer id="colophon" class="site-footer">';

		$html = apply_filters(
			'prime_shopping_cart_footer_container_before_filter',
			$html
		);

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Render footer widgets.
	 *
	 * Developer Note:
	 * Displays footer widget columns.
	 *
	 * @return void
	 */
	public function footer_widgets(): void {

		if ( ! is_active_sidebar( 'footer-1' ) ) {
			return;
		}
		?>

		<div class="footer-widget-wrap">
			<div class="container">
				<div class="row prime-shopping-cart-flex">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Render footer copyright info.
	 *
	 * Developer Note:
	 * Displays copyright and theme credits.
	 *
	 * @return void
	 */
	public function footer_info(): void {

		$copyright = get_theme_mod(
			'copyright_text',
			''
		);

		if ( empty( $copyright ) ) {

			$copyright = sprintf(
				/* translators: 1: Current year, 2: Site name */
				esc_html__(
					'Copyright © %1$s %2$s. All rights reserved.',
					'prime-shopping-cart'
				),
				esc_html(
					date_i18n(
						_x(
							'Y',
							'copyright date format',
							'prime-shopping-cart'
						)
					)
				),
				esc_html( get_bloginfo( 'name' ) )
			);
		}

		$theme_credit = sprintf(
			/* translators: 1: Theme Name, 2: WordPress */
			esc_html__(
				'%1$s theme by aThemeArt. Powered by %2$s.',
				'prime-shopping-cart'
			),
			'<a href="' . esc_url( 'https://athemeart.com/downloads/prime-shopping-cart-pro/' ) . '" target="_blank" rel="noopener noreferrer">' .
				esc_html__( 'Prime Shopping Cart', 'prime-shopping-cart' ) .
			'</a>',
			'<a href="' . esc_url( 'https://wordpress.org/' ) . '" target="_blank" rel="nofollow noopener noreferrer">' .
				esc_html__( 'WordPress', 'prime-shopping-cart' ) .
			'</a>'
		);

		$html  = '<div class="footer-bottom">';
		$html .= '<div class="container">';
		$html .= '<div class="row">';
		$html .= '<div class="col-12 d-flex align-items-center">';

		$html .= '<div class="site-copyright">';
		$html .= apply_filters(
			'prime_shopping_cart_footer_copyright_filter',
			wp_kses_post( $copyright )
		);
		$html .= '<div class="site-credit">';
		$html .= wp_kses_post( $theme_credit );
		$html .= '</div>';
		$html .= '</div>';

		$html .= '<div class="social-links-wrap">';
		if( !empty($this->prime_shopping_cart_social_icons()) ):
		$html .= wp_kses(
		    $this->prime_shopping_cart_social_icons(),
		    $this->allowed_tags()
		);
		endif;
		$html .= '</div>';

		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Close footer wrapper.
	 *
	 * @return void
	 */
	public function footer_container_after(): void {

		$html = '</footer>';

		$html = apply_filters(
			'prime_shopping_cart_footer_container_after_filter',
			$html
		);

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Render back to top button.
	 *
	 * Developer Note:
	 * Floating scroll-to-top button.
	 *
	 * @return void
	 */
	public function back_to_top_button(): void {

		$html = '
			<a id="backToTop" class="ui-to-top" href="#">
				<i class="bi bi-arrow-up-short"></i>
			</a>
		';

		$html = apply_filters(
			'prime_shopping_cart_back_to_top_filter',
			$html
		);

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	* Display Theme Social Icons
	*/
	function prime_shopping_cart_social_icons() {

	    $socials = get_theme_mod( 'prime_shopping_cart_social', array() );
	    if ( empty( $socials ) || ! is_array( $socials ) ) {
	        return;
	    }

	    $html = '<div class="social-icons">';

	    foreach ( $socials as $icon => $url ) {

	        if ( empty( $url ) ) {
	            continue;
	        }

	        $label = ucfirst( str_replace( 'bi-', '', $icon ) );

	        $html .= sprintf(
	            '<a href="%1$s" target="_blank" rel="noopener noreferrer">
	                <i class="bi %2$s"></i>
	                <span class="screen-reader-text">%3$s</span>
	            </a>',
	            esc_url( $url ),
	            esc_attr( $icon ),
	            esc_html( $label )
	        );
	    }

	    $html .= '</div>';

	     return $html;
	}

	/**
	 * Allowed HTML tags.
	 *
	 * @return array
	 */
	private function allowed_tags(): array {

		return function_exists(
			'prime_shopping_cart_allowed_tags'
		)
			? prime_shopping_cart_allowed_tags()
			: array();
	}
}

/**
 * Initialize footer layout.
 */
$prime_shopping_cart_footer_layout = new Prime_Shopping_Cart_Footer_Layout();