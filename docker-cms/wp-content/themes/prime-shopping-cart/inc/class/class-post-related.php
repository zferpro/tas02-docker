<?php
/**
 * All POST Related Function
 *
 * Handles post content rendering, thumbnails, navigation,
 * author box, comment field ordering, and post format media.
 *
 * @package prime-shopping-cart
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Prime_Shopping_Cart_Post_Related {

	/**
	 * Constructor.
	 *
	 * Developer Note:
	 * Initializes the class and registers all hooks/actions/filters
	 * required for post-related frontend functionality.
	 */
	public function __construct() {
		$this->register_hooks();
	}

	/**
	 * Register theme hooks.
	 *
	 * Developer Note:
	 * Attaches all frontend actions and filters used by the theme
	 * for rendering post content, media, navigation, and comment form behavior.
	 *
	 * @return void
	 */
	private function register_hooks(): void {

		if ( ! is_admin() ) {

			// Adds post/page heading output in loop layouts.
			add_action( 'prime_shopping_cart_site_content_type', array( $this, 'site_loop_heading' ), 20 );

			// Outputs post content or excerpt based on theme settings.
			add_action( 'prime_shopping_cart_site_content_type', array( $this, 'site_content_type' ), 40 );
		}

		// Renders thumbnails/media for post formats.
		add_action( 'prime_shopping_cart_posts_blog_media', array( $this, 'render_thumbnail' ) );

		// Displays blog loop pagination/navigation.
		add_action( 'prime_shopping_cart_loop_navigation', array( $this, 'site_loop_navigation' ) );

		// Displays single post previous/next navigation.
		add_action( 'prime_shopping_cart_single_post_navigation', array( $this, 'single_post_navigation' ), 10 );

		// Replaces default "Read More" link for content.
		add_filter( 'the_content_more_link', array( $this, 'append_read_more_button' ) );

		// Replaces excerpt ending with custom button link.
		add_filter( 'excerpt_more', array( $this, 'append_read_more_button' ) );

		// Moves comment textarea to bottom of comment form.
		add_filter( 'comment_form_fields', array( $this, 'move_comment_field_to_bottom' ) );

		// Displays author bio after single post navigation.
		//add_action( 'prime_shopping_cart_single_post_navigation', array( $this, 'author_bio' ), 20 );
	}

	/**
	 * Render author bio section.
	 *
	 * Developer Note:
	 * Displays author avatar, author link,
	 * and author description on single posts.
	 *
	 * @return void
	 */
	function author_bio() {
	?>

		<div id="author-info">
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), '160', '' ); ?>
			</div>

			<div class="align-left">
				<h3><?php the_author_link(); ?></h3>

				<div class="author-description">
					<?php the_author_meta( 'description' ); ?>
				</div>
			</div>
		</div>

	<?php
	}

	/**
	 * Render post loop heading/title.
	 *
	 * Developer Note:
	 * Displays post titles in archive/blog/search loops.
	 * Links titles only in non-singular views.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function site_loop_heading( $args = array() ): void {

		// Skip pages.
		if ( is_page() ) {
			return;
		}
		// Ensure args is array.
		$args = is_array( $args ) ? $args : array();

		// Hide condition.
		if ( ! empty( $args['title'] ) && 'hide' === $args['title'] ) {
			return;
		}

		// Single view (no link).
		if ( is_singular() ) {
			//the_title( '<h2 class="entry-title">', '</h2>' );
			return;
		}

		// Loop view (linked title).
		the_title(
			'<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">',
			'</a></h2>'
		);
	}
	/**
	 * Render post content or excerpt based on theme settings.
	 *
	 * Developer Note:
	 * - Controls loop content type (excerpt/content).
	 * - Supports hide option via args.
	 * - Uses theme option filter for flexibility.
	 *
	 * @param array $args Optional arguments.
	 * @return void
	 */
	public function site_content_type( $args = array() ): void {

		// Ensure args is array.
		$args = is_array( $args ) ? $args : array();

		// Hide condition.
		if ( ! empty( $args['content'] ) && 'hide' === $args['content'] ) {
			return;
		}

		// Get content type (excerpt or full content).
		$type = prime_shopping_cart_get_option( 'blog_loop_content_type' );

		echo '<div class="content-wrap">';

		// Single pages always show full content.
		if ( is_single() || is_page() ) {

			the_content();

			echo '</div>';
			return;
		}

		// Archive / blog loop.
		if ( 'content' === $type ) {

			the_content();

		} else {

			$excerpt = get_the_excerpt();

			if ( ! empty( $excerpt ) ) {
				echo wp_kses_post( $excerpt );
			}
		}

		echo '</div>';
	}
	/**
	 * Custom excerpt "Read More" link.
	 *
	 * Developer Note:
	 * Appends a styled button below excerpts
	 * in archive/blog layouts.
	 *
	 * @param string $more Default excerpt more text.
	 * @return string
	 */
	public function append_read_more_button( $args = array() ) : string {

		// Do not modify in admin.
		if ( is_admin() ) {
			return '';
		}

		// Ensure args is array.
		$args = is_array( $args ) ? $args : array();

		// Hide condition.
		if ( empty( prime_shopping_cart_get_option( 'read_more_text' ) )  ) {
			return '';
		}
		$more = sprintf(
			'<div class="more-link">
				<a href="%1$s" class="link-btn theme-btn">
					<span class="icon"><i class="bi bi-arrow-right"></i></span>
					<span class="text">%2$s</span>
				</a>
			</div>',
			esc_url( get_permalink( get_the_ID() ) ),
			esc_html(
				prime_shopping_cart_get_option( 'read_more_text' )
			)
		);
		return $more;
	}

	/**
	 * Render single post previous/next navigation.
	 *
	 * Developer Note:
	 * Displays previous and next article links
	 * with optional featured image thumbnails.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function single_post_navigation() {

		$html = '<div class="single-prev-next d-flex">';

		$prevPost = get_previous_post();

		if ( $prevPost ) :

			$html .= '<div class="d-flex align-items-center col-md-6 gap-15">';

			$prevthumbnail = get_the_post_thumbnail( $prevPost->ID, array( 60, 60 ) );

			if ( $prevthumbnail ) {
				$html .= get_previous_post_link( '%link', $prevthumbnail ) ;
			}

			$html .= '<div><h5>' . esc_html__( 'Previous Article', 'prime-shopping-cart' ) . '</h5>';
			$html .= get_previous_post_link( '%link', '<span>%title</span>' );
			$html .= '</div>';

			$html .= '</div>';

		endif;

		$nextPost = get_next_post();

		if ( $nextPost ) :

			$html .= '<div class="d-flex align-items-center col-md-6 gap-15">';

			$html .= '<div class="ml-auto align-right"><h5>' . esc_html__( 'Next Article', 'prime-shopping-cart' ) . '</h5>';
			$html .= get_next_post_link( '%link', '<span>%title</span>' );
			$html .= '</div>';

			$nextthumbnail = get_the_post_thumbnail( $nextPost->ID, array( 60, 60 ) );

			if ( ! empty( $nextthumbnail ) ) {
				$html .= get_previous_post_link( '%link', $nextthumbnail ) ;
			}

			$html .= '</div>';

		endif;

		$html .= '</div>';

		$html = apply_filters(
			'prime_shopping_cart_single_post_navigation_filter',
			$html
		);

		echo wp_kses( $html, $this->allowed_tags() );
	}
	/**
	 * Render posts navigation.
	 *
	 * Developer Note:
	 * Supports default previous/next navigation
	 * and numeric pagination layouts.
	 *
	 * @param string $type Navigation type.
	 * @return void
	 */
	public function site_loop_navigation( string $type = '' ): void {

		// Get navigation type from theme setting.
		if ( empty( $type ) ) {

			$type = apply_filters(
				'prime_shopping_cart_loop_navigation_filter',
				get_theme_mod(
					'prime_shopping_cart_loop_navigation',
					'default'
				)
			);
		}

		// Default navigation.
		if ( 'default' === $type ) {

			echo '<nav class="posts-navigation-wrap">';

			the_posts_navigation(
				array(
					'prev_text' => sprintf(
						'<span class="icon"><i class="bi bi-chevron-double-left"></i></span><span class="nav-prev">%s</span>',
						esc_html__( 'Previous Posts', 'prime-shopping-cart' )
					),
					'next_text' => sprintf(
						'<span class="nav-next">%s</span><span class="icon"><i class="bi bi-chevron-double-right"></i></span>',
						esc_html__( 'Next Posts', 'prime-shopping-cart' )
					),
					'screen_reader_text' => esc_html__( 'Posts navigation', 'prime-shopping-cart' ),
				)
			);

			echo '</nav>';

			return;
		}

		// Numeric pagination.
		echo '<nav class="pagination-custom">';

		the_posts_pagination(
			array(
				'mid_size'           => 2,
				'end_size'           => 1,
				'type'               => 'list',
				'prev_text'          => '<i class="bi bi-chevron-left"></i>',
				'next_text'          => '<i class="bi bi-chevron-right"></i>',
				'screen_reader_text' => esc_html__( 'Posts pagination', 'prime-shopping-cart' ),
			)
		);

		echo '</nav>';
	}
	/**
	 * Move comment textarea field to bottom.
	 *
	 * Developer Note:
	 * Reorders comment form fields so the main
	 * comment textarea appears after name/email fields.
	 *
	 * @since 1.0.0
	 * @param array $fields Comment form fields.
	 * @return array
	 */
	function move_comment_field_to_bottom( $fields ) {

		$comment_field = $fields['comment'];
		$cookies_field = $fields['cookies'];

		unset( $fields['comment'] );
		unset( $fields['cookies'] );

		$fields['comment'] = $comment_field;
		$fields['cookies'] = $cookies_field;

		return $fields;
	}

	/**
	 * Render post thumbnail/media by post format.
	 *
	 * Developer Note:
	 * Dynamically loads gallery, audio, video,
	 * or default featured image layouts.
	 *
	 * @param string $formats Post format.
	 * @return void
	 */
	public function render_thumbnail( $formats = '' ) {

		if ( empty( $formats ) ) {
			$formats = get_post_format( get_the_ID() );
		}
		switch ( $formats ) {
			default:
				$this->get_image_thumbnail();
			break;

			case 'gallery':
				$this->get_gallery_thumbnail();
			break;

			case 'audio':
				$this->get_audio_thumbnail();
			break;

			case 'video':
				$this->get_video_thumbnail();
			break;
		}
	}

	/**
	 * Render gallery post format thumbnail.
	 *
	 * Developer Note:
	 * Supports both Gutenberg gallery blocks
	 * and classic WordPress gallery shortcodes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function get_gallery_thumbnail(){
		
		if ( is_singular() ) {
			return;
		}

		$post = get_post();

		if ( empty( $post ) ) {
			return;
		}

		$html = '';
		if( has_block('gallery', $post->post_content) ): 
			$html = '<div class="img-box">';
			$post_blocks = parse_blocks( $post->post_content );
			
			if( !empty( $post_blocks ) ):
				
				$html .= '<figure class="gallery-media prime-gallery-slider" data-slider_nav="yes">';
				foreach ( $post_blocks as $row  ):

					if( $row['blockName']=='core/gallery' ){
						
						foreach ( $row['innerBlocks'] as $item  ):
							if( !empty($item["attrs"]["id"]) ):

							   $link   = wp_get_attachment_url( $item["attrs"]["id"] );
							   $html  .= '<div class="item"><img src="' . esc_url( $link ) . '"  class="img-responsive" alt="' .esc_attr( get_the_title() ). '" title="' .esc_attr( get_the_title() ). '"  /></div>';
							endif;

						endforeach;
					}
					//
				endforeach;
				$html .= '</figure>';
				
			endif;
			$html .= '</div>';

		elseif ( get_post_gallery() ) :
			$html = '<div class="img-box">';
			$html .= '<i class="bi bi-images prime-icon"></i>';
			$html .= '<figure class="gallery-media fs-product-slider" data-slider_nav="yes">';
			
				$gallery = get_post_gallery( $post, false );
				$ids     = explode( ",", $gallery['ids'] );
				
				foreach( $ids as $id ) {
				
				   $link   = wp_get_attachment_url( $id );
				
				   $html  .= '<div class="item"><img src="' . esc_url( $link ) . '"  class="img-responsive" alt="' .esc_attr( get_the_title() ). '" title="' .esc_attr( get_the_title() ). '"  /></div>';
				
				} 
				
			$html .= '</figure>';
			$html .= '</div>';
		else: 
			
			$html .= $this->get_image_thumbnail();
			
		endif;	
		
		$html =  apply_filters( 'prime-shopping-cart-post-gallery', $html );
		
		echo wp_kses( $html, $this->allowed_tags() );
	}
	/**
	 * Render audio post format thumbnail/player.
	 *
	 * Developer Note:
	 * Extracts embedded audio players from post content
	 * and optionally overlays them on featured images.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function get_audio_thumbnail() {

		// Do not render custom media on singular views.
		if ( is_singular() ) {
			return;
		}

		$content = apply_filters( 'the_content', get_the_content() );

		$audio = array();
		$html  = '';

		$post_thumbnail_url = '';

		/**
		 * Developer Note:
		 * Skip extracting standalone audio embeds
		 * when WordPress playlist block/script exists.
		 */
		if ( false === strpos( $content, 'wp-playlist-script' ) ) {

			$audio = get_media_embedded_in_content(
				$content,
				array( 'audio' )
			);
		}

		/**
		 * Developer Note:
		 * Retrieve featured image URL
		 * for audio background overlay layout.
		 */
		if ( has_post_thumbnail() ) {

			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );

			$post_thumbnail_url = wp_get_attachment_url(
				$post_thumbnail_id
			);
		}

		/**
		 * Developer Note:
		 * Render first available audio embed.
		 */
		if ( ! empty( $audio ) ) {

			$html  = '<div class="img-box">';
			// Only render first audio embed.
			$audio_html = reset( $audio );

			if ( ! empty( $post_thumbnail_url ) ) {

				$html .= sprintf(
					'<figure style="background-image:url(\'%1$s\');" class="entry-audio embed-responsive embed-responsive-16by9">
						<div class="audio-center">%2$s</div>
					</figure>',
					esc_url( $post_thumbnail_url ),
					wp_kses( $audio_html, $this->allowed_tags() )
				);

			} else {

				$html .= wp_kses(
					$audio_html,
					$this->allowed_tags()
				);
			}

			$html .= '</div>';

		} else {

			/**
			 * Developer Note:
			 * Fallback to default featured image
			 * if no audio embed exists.
			 */
			$this->get_image_thumbnail();
			return;
		}

		/**
		 * Developer Note:
		 * Allow developers to modify
		 * final audio thumbnail markup.
		 */
		$html = apply_filters(
			'prime-shopping-cart_audio_thumbnail',
			$html
		);

		echo wp_kses(
			$html,
			$this->allowed_tags()
		);
	}

	/**
	 * Render video post format thumbnail/player.
	 *
	 * Developer Note:
	 * Extracts embedded videos from post content
	 * and outputs responsive video wrappers.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function get_video_thumbnail() {

		// Do not render custom media on singular views.
		if ( is_singular() ) {
			return;
		}

		$content = apply_filters(
			'the_content',
			get_the_content( get_the_ID() )
		);

		$video = array();
		$html  = '';

		/**
		 * Developer Note:
		 * Skip extracting video embeds
		 * if WordPress playlist script exists.
		 */
		if ( false === strpos( $content, 'wp-playlist-script' ) ) {

			$video = get_media_embedded_in_content(
				$content,
				array( 'video', 'object', 'embed', 'iframe' )
			);
		}

		/**
		 * Developer Note:
		 * Render first available video embed.
		 */
		if ( ! empty( $video ) ) {

			$html  = '<div class="img-box">';
			// Only display first video embed.
			$video_html = reset( $video );

			$html .= '<div class="entry-video embed-responsive embed-responsive-16by9">';
			$html .= wp_kses( $video_html, $this->allowed_tags() );
			$html .= '</div>';

			$html .= '</div>';

		} else {

			/**
			 * Developer Note:
			 * Fallback to featured image
			 * if no embedded video exists.
			 */
			$this->get_image_thumbnail();
			return;
		}

		/**
		 * Developer Note:
		 * Allow developers to filter
		 * final video thumbnail markup.
		 */
		$html = apply_filters(
			'prime-shopping-cart_video_thumbnail',
			$html
		);

		echo wp_kses(
			$html,
			$this->allowed_tags()
		);
	}

	/**
	 * Render default featured image thumbnail.
	 *
	 * Developer Note:
	 * Displays clickable featured image thumbnails
	 * for standard blog/archive post layouts.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function get_image_thumbnail() {

		$html = '';

		/**
		 * Developer Note:
		 * Render featured image thumbnail
		 * only on archive/blog loop views.
		 */
		if ( has_post_thumbnail() && ! is_singular() ) {

			$post_id = get_the_ID();

			$html  = '<div class="img-box">';
			$html .= sprintf(
				'<a href="%1$s" class="image-link">
					%2$s
				</a>',
				esc_url( get_permalink( $post_id ) ),
				get_the_post_thumbnail( $post_id, 'full' )
			);

			$html .= '</div>';
		}else{
			if( is_singular() ):
				$html .= '';
			else:
				if( prime_shopping_cart_get_option('hide_default_placeholder') != true ){
				$html .= '<div class="thumbnail-placeholder"></div>';
				}
			endif;
			
		}
		/**
		 * Developer Note:
		 * Allow developers to filter
		 * featured image thumbnail markup.
		 */
		$html = apply_filters(
			'prime-shopping-cart_image_thumbnail',
			$html
		);

		echo wp_kses(
			$html,
			$this->allowed_tags()
		);
	}
	/**
	 * Allowed HTML tags for wp_kses().
	 *
	 * Developer Note:
	 * Returns sanitized HTML tag list used
	 * for secure frontend HTML rendering.
	 *
	 * @return array
	 */
	private function allowed_tags(): array {

		return function_exists( 'prime_shopping_cart_allowed_tags' )
			? prime_shopping_cart_allowed_tags()
			: array();
	}
}

$prime_shopping_cart_post_related = new Prime_Shopping_Cart_Post_Related();