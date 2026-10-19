<?php
/**
 * The template for displaying comments
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Prime_Shopping_Cart
 */

if ( post_password_required() ) {
	return;
}

$commenter = wp_get_current_commenter();
$req       = get_option( 'require_name_email' );
$consent   = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<div class="details-page-inner-box comment-meta">

			<h4 class="comments-title"><i class="bi bi-chat-right-dots"></i>

				<?php
				$comment_count = get_comments_number();

				if ( 1 === (int) $comment_count ) {

					printf(
						/* translators: %s: post title */
						esc_html__( 'One thought on “%s”', 'prime-shopping-cart' ),
						'<span>' . esc_html( get_the_title() ) . '</span>'
					);

				} else {

					printf(
						esc_html(
							/* translators: 1: comment count, 2: post title */
							_nx(
								'%1$s thought on “%2$s”',
								'%1$s thoughts on “%2$s”',
								$comment_count,
								'comments title',
								'prime-shopping-cart'
							)
						),
						esc_html( number_format_i18n( $comment_count ) ),
						'<span>' . esc_html( get_the_title() ) . '</span>'
					);
				}
				?>

			</h4>

			<?php the_comments_navigation(); ?>

			<ul class="comment-list">

				<?php
				wp_list_comments(
					array(
						'style'      => 'ul',
						'short_ping' => true,
						'callback'   => 'prime_shopping_cart_walker_comment',
					)
				);
				?>

			</ul>

			<?php the_comments_navigation(); ?>

			<?php if ( ! comments_open() ) : ?>

				<p class="no-comments">
					<?php esc_html_e( 'Comments are closed.', 'prime-shopping-cart' ); ?>
				</p>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php
	$args = array(

		'class_form' => 'row',

		'title_reply_before' => '<h4 class="comments-title"><i class="bi bi-chat-right-text"></i>',
		'title_reply_after'  => '</h4>',

		'comment_notes_before' =>
			'<p class="comment-notes col-12">' .
			esc_html__(
				'Your email address will not be published. Required fields are marked *.',
				'prime-shopping-cart'
			) .
			'</p>',

		'comment_field' =>
			'<div class="col-12">' .
			'<textarea id="comment" name="comment" cols="45" rows="8" required="required" placeholder="' .
			esc_attr__( 'Comment', 'prime-shopping-cart' ) .
			'"></textarea>' .
			'</div>',

		'submit_button' =>
			'<button type="submit" class=" theme-btn" id="%2$s"><span class="icon"><i class="bi bi-chat"></i></span>' .
			'<span class="text">%4$s</span>' .
			'</button>',

		'label_submit' => esc_html__( 'Post Comment', 'prime-shopping-cart' ),

		'fields' => apply_filters(
			'comment_form_default_fields',
			array(

				'author' =>
					'<label class="col-xl-6 col-lg-6 col-md-6 col-12">' .
					'<input id="author" name="author" type="text" value="' .
					esc_attr( $commenter['comment_author'] ) .
					'" placeholder="' .
					esc_attr__( 'Your Name', 'prime-shopping-cart' ) .
					'" ' .
					( $req ? 'required="required"' : '' ) .
					' />' .
					( $req ? '<span class="required">*</span>' : '' ).
					'</label>',

				'email' =>
					'<label class="col-xl-6 col-lg-6 col-md-6 col-12">' .
					'<input id="email" name="email" type="email" value="' .
					esc_attr( $commenter['comment_author_email'] ) .
					'" placeholder="' .
					esc_attr__( 'Your Email', 'prime-shopping-cart' ) .
					'" ' .
					( $req ? 'required="required"' : '' ) .
					' />' .
					( $req ? '<span class="required">*</span>' : '' ).
					'</label>',

				'url' =>
					'<label class="col-xl-12 col-lg-12 col-md-12 col-12">' .
					'<input id="url" name="url" type="url" value="' .
					esc_url( $commenter['comment_author_url'] ) .
					'" placeholder="' .
					esc_attr__( 'Website', 'prime-shopping-cart' ) .
					'" />' .
					'</label>',

				'cookies' =>
					'<p class="comment-form-cookies-consent col-12">' .
					'<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' .
					$consent .
					' />' .
					'<label for="wp-comment-cookies-consent">' .
					esc_html__(
						'Save my name, email, and website in this browser for the next time I comment.',
						'prime-shopping-cart'
					) .
					'</label>' .
					'</p>',
			)
		),
	);
	?>

	<div class="details-page-inner-box comment-form-wrap">

		<?php comment_form( $args ); ?>

	</div>

</div>