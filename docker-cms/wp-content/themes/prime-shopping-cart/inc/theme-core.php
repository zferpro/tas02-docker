<?php
/**
 * Prime Shopping Cart functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Prime_Shopping_Cart
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function prime_shopping_cart_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Prime Shopping Cart, use a find and replace
		* to change 'prime-shopping-cart' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'prime-shopping-cart', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	add_theme_support( 'wp-block-styles' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'prime-shopping-cart' ),
			'topbar' => esc_html__( 'Topbar', 'prime-shopping-cart' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'prime_shopping_cart_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
	/*
	* Enable support for Post Formats.
	* See https://developer.wordpress.org/themes/functionality/post-formats/
	*/
	add_theme_support( 'post-formats', array(
		'image',
		'video',
		'gallery',
		'audio',
		'quote'
	) );

	if ( is_customize_preview() ) {
		require get_template_directory() . '/inc/starter-content.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		add_theme_support( 'starter-content', prime_shopping_cart_get_starter_content() );
	}
	
	add_theme_support('responsive-embeds');
	add_theme_support('align-wide'); 
}
add_action( 'after_setup_theme', 'prime_shopping_cart_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function prime_shopping_cart_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'prime_shopping_cart_content_width', 640 );
}
add_action( 'after_setup_theme', 'prime_shopping_cart_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function prime_shopping_cart_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'prime-shopping-cart' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'prime-shopping-cart' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s" data-aos="fade-up">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));

	register_sidebar( array(
		'name'          => esc_html__( 'Homepage Banner', 'prime-shopping-cart' ),
		'id'            => 'slider',
		'description'   => esc_html__( 'Add widgets here.', 'prime-shopping-cart' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'prime-shopping-cart' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Add widgets here.', 'prime-shopping-cart' ),
		'before_widget' => '<div id="%1$s" class="col-12 col-sm-6 col-md-4 widget %2$s" data-aos="fade-up">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	if ( is_customize_preview() && !class_exists( 'WooCommerce' ) ) {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Repalce My Account and Mini Cart', 'prime-shopping-cart' ),
			'id'            => 'replace_icon',
			'description'   => esc_html__( 'Add widgets here to replace the default My Account and Mini Cart icons in the header. If this widget area is empty, the default header icons will be displayed.', 'prime-shopping-cart' ),
			'before_widget' => '<div id="%1$s" class="col-12 col-sm-6 col-md-4 widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title screen-reader-text"><span>',
			'after_title'   => '</span></h3>',
		)
	);
	}
	
}
add_action( 'widgets_init', 'prime_shopping_cart_widgets_init' );

/**
 * Enqueue theme scripts and styles.
 *
 * Developer Note:
 * Child themes can override or dequeue assets using registered handles.
 */
function prime_shopping_cart_scripts() {
	wp_enqueue_style( 'prime-shopping-cart-google-fonts', 'https://fonts.googleapis.com/css?family=Josefin+Sans|Roboto+Flex&display=swap' );

	wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/vendors/bootstrap/css/bootstrap.css' ), array(), '5.3.8' );
	wp_style_add_data( 'bootstrap', 'rtl', 'replace' );
	
	wp_enqueue_style( 'normalize', get_theme_file_uri( '/assets/css/normalize.css' ), array(), '1.0.0' );
	wp_enqueue_style( 'bi-icons', get_theme_file_uri( '/vendors/bi-icons/bootstrap-icons.css' ), array(), '1.13.1' );
	wp_enqueue_style( 'scrollbar', get_theme_file_uri( '/vendors/scrollbar/simple-scrollbar.css' ), array(), '1.0.0' );
	wp_enqueue_style( 'owl-carousel', get_theme_file_uri( '/vendors/owl-carousel/assets/owl.carousel.css' ), array(), '2.3.4' );
	wp_enqueue_style( 'aos', get_theme_file_uri( '/vendors/aos-next/aos.css' ), array(), '1.0.0' );
	wp_enqueue_style( 'prime-shopping-cart-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'prime-shopping-cart-style', 'rtl', 'replace' );

	/*---------Load All of JS-----------------*/

	wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/vendors/bootstrap/js/bootstrap.js' ), 0, '3.3.7', true );
	wp_enqueue_script( 'scrollbar-js', get_theme_file_uri( '/vendors/scrollbar/simple-scrollbar.js' ), 0, '', true );
	wp_enqueue_script( 'customselect', get_theme_file_uri( '/vendors/customselect.js' ), 0, '', true );
	wp_enqueue_script( 'owl-carousel-js', get_theme_file_uri( '/vendors/owl-carousel/owl.carousel.js' ), 0, '', true );
	wp_enqueue_script( 'aos', get_theme_file_uri( '/vendors/aos-next/aos.js' ), 0, '', true );
	wp_enqueue_script( 'prime-shopping-cart-js', get_theme_file_uri( '/assets/js/prime-shopping-cart.js'), array('jquery','thickbox'), '1.0.0', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'prime_shopping_cart_scripts' );

/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Prime_Shopping_Cart
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses prime_shopping_cart_header_style()
 */
function prime_shopping_cart_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'prime_shopping_cart_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1000,
				'height'             => 250,
				'flex-height'        => true,
				'wp-head-callback'   => 'prime_shopping_cart_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'prime_shopping_cart_custom_header_setup' );

if ( ! function_exists( 'prime_shopping_cart_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see prime_shopping_cart_custom_header_setup().
	 */
	function prime_shopping_cart_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
				}
			<?php
			// If the user has set a custom color for the text use that.
		else :
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;


// Include custom style editor (TinyMCE visual editor)
function prime_shopping_cart_editor_styles() {
    add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'admin_init', 'prime_shopping_cart_editor_styles' );