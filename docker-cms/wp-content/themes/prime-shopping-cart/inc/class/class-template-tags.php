<?php
/**
 * Template Tags / Post Meta Renderer
 *
 * Handles author, date, category, comments, avatar, and tags output.
 *
 * @package prime-shopping-cart
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Prime_Shopping_Cart_Post_Meta {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'prime_shopping_cart_site_content_type', array( $this, 'render_tags_list' ),99 );
		add_action( 'prime_shopping_cart_site_content_type', array( $this, 'render_meta_list' ), 30, 2 );
		add_action( 'prime_shopping_cart_meta_info', array( $this, 'render_meta_list' ), 10, 2 );

		add_action( 'prime_shopping_cart_post_categories', array( $this, 'render_get_the_category_list' ), 10 );
	}
	/**
	 * Render post category list.
	 *
	 * - Displays assigned post categories in loop or single view.
	 * - Allows optional wrapper class for styling flexibility.
	 *
	 * @param string $wrapper_class Additional CSS class for wrapper.
	 * @return void
	 */
	public function render_get_the_category_list( string $wrapper_class = '' ): void {
		//if( is_single() ) return;
		?>
		<div class="prime-categories <?php echo esc_attr( $wrapper_class ); ?>">
			<?php echo wp_kses( get_the_category_list( ', ' ), $this->allowed_tags() ); ?>
		</div>
		<?php
	}
	/**
	 * Render meta list.
	 *
	 * @param array  $order Meta order.
	 * @param string $wrapper_class Extra wrapper class.
	 * @return void
	 */
	public function render_meta_list( $order, string $wrapper_class = '' ): void {

		if ( ! is_array( $order ) || empty( $order ) ) {
			return;
		}

		$order = $this->sanitize_order_array( $order );

		if ( empty( $order ) ) {
			return;
		}
		global $post;
		$author_id = get_post_field( 'post_author', get_the_ID() );
		$html  = '';
		$html .= $this->get_avatar( $order );

		$html .= '<ul class="entry-meta ' . esc_attr( $wrapper_class ) . '">';

		foreach ( $order as $meta ) {

			switch ( $meta ) {

				case 'author':
					$html .= '<li class="post-by">';
					$html .= '<span>' . esc_html__( 'By - ', 'prime-shopping-cart' ) . '</span>';
					$html .= '<a href="' . esc_url( get_author_posts_url( $author_id ) ) . '">';
					$html .= esc_html(get_the_author_meta( 'display_name', $author_id ));
					$html .= '</a>';
					$html .= '</li>';
					break;

				case 'date':
					$html .= '<li class="meta posted-on">';
					$html .= $this->get_time_tags();
					$html .= '</li>';
					break;

				case 'category':
					$html .= '<li class="meta category">';
					$html .= esc_html__( 'Posted in ','prime-shopping-cart' );
					$html .= get_the_category_list( ', ' );
					$html .= '</li>';
					break;

				case 'comments':
					$comments = $this->get_comments();

					if ( ! empty( $comments ) ) {
						$html .= '<li class="meta comments">' . $comments . '</li>';
					}
					break;
			}
		}

		$html .= '</ul>';

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Sanitize meta order array.
	 *
	 * @param array $order Meta order.
	 * @return array
	 */
	private function sanitize_order_array( array $order ): array {

		$allowed = array( 'avatar', 'author', 'date', 'category', 'comments' );

		return array_values(
			array_intersect( $order, $allowed )
		);
	}

	/**
	 * Render avatar if enabled.
	 *
	 * @param array $order Meta order.
	 * @return string
	 */
	private function get_avatar( array $order ): string {

		if ( ! in_array( 'avatar', $order, true ) ) {
			return '';
		}

		$user_id = get_the_author_meta( 'ID' );

		return sprintf(
			'<a href="%s" class="avatar_round">%s</a>',
			esc_url( get_author_posts_url( $user_id ) ),
			get_avatar( $user_id, 60 )
		);
	}

	/**
	 * Get comments link.
	 *
	 * @return string
	 */
	private function get_comments(): string {

		if ( ! comments_open() ) {
			return '';
		}

		$count = get_comments_number();

		if ( $count < 1 ) {
			return '';
		}

		$text = sprintf(
			/* translators: %s: number of comments */
			_n( '%s Comment', '%s Comments', $count, 'prime-shopping-cart' ),
			number_format_i18n( $count )
		);

		return sprintf(
			'<a href="%s">%s</a>',
			esc_url( get_comments_link() ),
			esc_html( $text )
		);
	}

	/**
	 * Render tags list.
	 *
	 * @return void
	 */
	public function render_tags_list(): void {

		$tags = get_the_tags();

		if ( empty( $tags ) || ! is_array( $tags ) || !is_single() ) {
			return;
		}

		$html = '<div class="prime-tags-list">';
		$html .= '<span>' . esc_html__( 'Tags:', 'prime-shopping-cart' ) . '</span>';

		foreach ( $tags as $tag ) {

			$html .= sprintf(
				'<a href="%s" class="%s" rel="tag">%s</a>',
				esc_url( get_tag_link( $tag->term_id ) ),
				esc_attr( $tag->slug ),
				esc_html( $tag->name )
			);
		}

		$html .= '</div>';

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Get time markup.
	 *
	 * @return string
	 */
	private function get_time_tags(): string {

		$published = sprintf(
			'<time class="entry-date published" datetime="%s">%s</time>',
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		$modified = '';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {

			$modified = sprintf(
				'<time class="updated" datetime="%s">%s</time>',
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);
		}

		return $published . $modified;
	}

	/**
	 * Allowed HTML tags.
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
 * Init class.
 */
$prime_shopping_cart_post_meta = new Prime_Shopping_Cart_Post_Meta();