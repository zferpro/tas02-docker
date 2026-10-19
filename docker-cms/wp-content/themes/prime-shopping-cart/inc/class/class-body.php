<?php
/**
 * Theme Body Layout Class
 *
 * Handles content container, main layout columns,
 * and sidebar rendering for frontend pages.
 *
 * @package prime-shopping-cart
 */
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Prime_Shopping_Cart_Body_Layout{

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_hooks();
	}

	/**
	 * Register theme hooks.
	 *
	 * @return void
	 */
	private function register_hooks(): void {
		add_action('prime_shopping_cart_container_wrap_start', array( $this, 'container_wrap_start' ), 5 );
		add_action('prime_shopping_cart_container_wrap_start', array( $this, 'container_wrap_column_start' ), 10 ); 
		add_action('prime_shopping_cart_container_wrap_end', array( $this, 'container_wrap_column_end' ), 5 ); 
		add_action('prime_shopping_cart_container_wrap_end', array( $this, 'render_sidebar' ), 10 );
		add_action('prime_shopping_cart_container_wrap_end', array( $this, 'container_wrap_end' ), 999 );
	}

	/**
	 * Open main container wrapper.
	 *
	 * Developer Note:
	 * - Starts content area and Bootstrap row layout.
	 *
	 * @return void
	 */
	public function container_wrap_start(): void {

		$html = '
			<div id="content" class="content-area container">
				<div class="row">
		';

		$html = apply_filters(
			'prime_shopping_container_wrap_start_filter',
			$html
		);

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Open main content column.
	 *
	 * Developer Note:
	 * - Controls content width based on layout type.
	 *
	 * @param string $layout Layout type.
	 * @return void
	 */
	public function container_wrap_column_start( string $layout = '' ): void {

		switch ( $layout ) {

			case 'sidebar-content':
				$layout_class = 'col-xl-8 col-md-8 col-12 order-2';
				break;

			case 'no-sidebar':
				$layout_class = 'col-md-10 offset-md-1';
				break;

			case 'full-container':
				$layout_class = 'col-md-12';
				break;

			default:
				$layout_class = 'col-xl-8 col-md-8 col-12 order-1';
		}

		$html = '<main id="main" class="site-main ' . esc_attr( $layout_class ) . '">
		';

		$html = apply_filters(
			'prime_shopping_container_wrap_column_start_filter',
			$html
		);

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Close main content column.
	 *
	 * @return void
	 */
	public function container_wrap_column_end(): void {

		$html = '</main>';

		$html = apply_filters(
			'prime_shopping_container_wrap_column_end_filter',
			$html
		);

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Close container wrapper.
	 *
	 * @return void
	 */
	public function container_wrap_end(): void {

		$html = '
				</div>
			</div>
		';

		$html = apply_filters(
			'prime_shopping_container_wrap_end_filter',
			$html
		);

		echo wp_kses( $html, $this->allowed_tags() );
	}


	/**
	 * Render sidebar layout.
	 *
	 * Developer Note:
	 * - Displays sidebar based on selected layout.
	 *
	 * @param string $layout Layout type.
	 * @return void
	 */
	public function render_sidebar( string $layout = '' ): void {
		switch ( $layout ) {
			case 'sidebar-content':
				$layout_class = 'col-xl-4 col-md-4 col-12 order-1 prime-shopping-cart-sidebar';
				break;
			case 'no-sidebar':
			case 'full-container':
				return;
			default:
				$layout_class = 'col-xl-4 col-md-4 col-12 order-2 prime-shopping-cart-sidebar';
		}
		?>
		<div class="<?php echo esc_attr( $layout_class ); ?>">
			<?php get_sidebar(); ?>
		</div>

		<?php
	}
	/**
	 * Allowed HTML tags for wp_kses().
	 *
	 * @return array
	 */
	private function allowed_tags(): array {

		return function_exists( 'prime_shopping_cart_allowed_tags' )
			? prime_shopping_cart_allowed_tags()
			: array();
	}
}

/**
 * Initialize layout class.
 */
$prime_shopping_body_layout = new Prime_Shopping_Cart_Body_Layout();