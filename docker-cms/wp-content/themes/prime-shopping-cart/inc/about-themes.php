<?php

/*Add theme menu page*/
 
add_action('admin_menu', 'prime_admin_menu');

function prime_admin_menu() {
	
	$prime_page_title = esc_html__("Learn More About Prime Shopping Cart Pro",'prime-shopping-cart');
	add_theme_page($prime_page_title, $prime_page_title, 'edit_theme_options', 'prime_theme_info', 'prime_theme_info_page');
	
}
/*
**
** Premium Theme Feature Page
**
*/
function prime_theme_info_page(){
	if ( is_admin() ) {
		get_template_part('/inc/premium-screen/index');
		
	} 
}
function prime_admin_script($prime_hook){
	
	if($prime_hook != 'appearance_page_prime_theme_info') {
		return;
	} 
	wp_enqueue_style( 'prime-custom-css', get_template_directory_uri() .'/inc/premium-screen/pro-custom.css',array(),'1.0' );

}

add_action( 'admin_enqueue_scripts', 'prime_admin_script' );



if ( ! class_exists( 'prime_Admin' ) ) :

/**
 * prime_Admin Class.
 */
class prime_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( __CLASS__, 'hide_notices' ) );
		add_action( 'load-themes.php', array( $this, 'admin_notice' ) );
	}

	/**
	 * Add admin notice.
	 */
	public function admin_notice() {
		global $prime_pagenow;

		wp_enqueue_style( 'prime-message', get_template_directory_uri() . '/inc/premium-screen/message.css', array(), '1.0' );

		// Let's bail on theme activation.
		if ( 'themes.php' == $prime_pagenow && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
			update_option( 'prime_admin_notice_welcome', 1 );

		// No option? Let run the notice wizard again..
		} elseif( ! get_option( 'prime_admin_notice_welcome' ) ) {
			add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
		}
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {
		if ( isset( $_GET['prime-hide-notice'] ) && isset( $_GET['_prime_notice_nonce'] ) ) {
			if (
				! isset( $_GET['_prime_notice_nonce'] ) ||
				! wp_verify_nonce(
					sanitize_text_field( wp_unslash( $_GET['_prime_notice_nonce'] ) ),
					'prime_hide_notices_nonce'
				)
			) {
				wp_die(
					esc_html__( 'Action failed. Please refresh the page and retry.', 'prime-shopping-cart' )
				);
			}

			if ( ! current_user_can( 'manage_options' ) ) 
			/* translators: %s: plugin name. */{
				wp_die( esc_html__( 'Cheatin&#8217; huh?', 'prime-shopping-cart' ) );
			}

			$hide_notice = sanitize_text_field( wp_unslash( $_GET['prime-hide-notice'] ) );
			update_option( 'prime_admin_notice_' . $hide_notice, 1 );
		}
	}

	/**
	 * Show welcome notice.
	 */
	public function welcome_notice() {
		?>
		<div id="message" class="updated prime-message">
        
			<a class="cresta-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'activated' ), add_query_arg( 'prime-hide-notice', 'welcome' ) ), 'prime_hide_notices_nonce', '_prime_notice_nonce' ) ); ?>"></a>
            
			<p><?php printf(
				/* translators: %1$s: opening link tag, %2$s: closing link tag. */
				esc_html__( 'Welcome! Thank you for choosing Prime Shopping Cart. Unlock the full power of your store with the Pro version — advanced features, exclusive tools, and priority support await. %1$sDiscover Pro Features%2$s.', 'prime-shopping-cart' ),
				'<a href="' . esc_url( admin_url( 'themes.php?page=prime_theme_info' ) ) . '">',
				'</a>'
			); ?></p>
			
		</div>
		<?php
	}
}

endif;

return new prime_Admin();