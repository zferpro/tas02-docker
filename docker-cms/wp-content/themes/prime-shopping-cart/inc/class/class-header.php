<?php
/**
 * Theme Header Layout Class
 *
 * @package prime-shopping-cart
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Prime_Shopping_Cart_Header_Layout {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	private function register_hooks(): void {
		add_action('prime_shopping_cart_header_branding', array( $this, 'get_site_branding' ), 10 );

		add_action('prime_shopping_cart_header', array( $this, 'skip_to_content' ), 5 );
		add_action('prime_shopping_cart_header', array( $this, 'site_header_top_bar' ), 10 );
		add_action('prime_shopping_cart_header', array( $this, 'site_header_wrap_before' ), 10 );
		add_action('prime_shopping_cart_header', array( $this, 'header_logo_container' ), 20 );
		add_action('prime_shopping_cart_header', array( $this, 'get_site_navigation' ), 30 );
		add_action('prime_shopping_cart_header', array( $this, 'site_header_wrap_after' ), 99 );
		add_action('prime_shopping_cart_header', array( $this, 'site_hero_sections' ), 100 );
		
	}
	/**
	 * Site Header Top Bar
	 *
	 * Displays top bar with optional message and navigation menu.
	 * Controlled by theme option: __enable_topbar
	 * Uses WP menu location: topbar
	 *
	 * @return void
	 */
	function site_header_top_bar() {

		$dialogue = prime_shopping_cart_get_option( '__dialogue' );

		if ( ! has_nav_menu( 'topbar' ) && empty( $dialogue ) ) {
			return;
		}
		?>

		<div class="top-bar-wrap">
			<div class="container d-flex align-items-center">

				<div class="left-menu">
					<?php if ( ! empty( $dialogue ) ) : ?>
						<span class="dialogue"><?php echo esc_html( $dialogue ); ?></span>
					<?php endif; ?>
				</div>

				<div class="right-menu d-flex">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'topbar',
							'depth'          => 2,
							'menu_class'     => 'top-menu',
							'container'      => 'ul',
							'fallback_cb'    => 'prime_shopping_cart_navwalker::fallback_2',
						)
					);
					?>
				</div>

			</div>
		</div>

		<?php
	}
	/**
	 * Skip to content link for accessibility.
	 *
	 * Note:
	 * Helps keyboard and screen reader users bypass header/navigation
	 * and jump directly to the main content area (#primary).
	 */
	public function skip_to_content() : void{
		?>
		<a class="skip-link screen-reader-text" href="#content">
			<?php esc_html_e( 'Skip to content', 'prime-shopping-cart' ); ?>
		</a>
		<?php
	}
	/**
	 * Open site header wrapper markup.
	 *
	 * Developer Note:
	 * - Starts global site header container.
	 * - Used for sticky header and header layout styling.
	 *
	 * @return void
	 */
	function site_header_wrap_before() {

		$html = '<header id="masthead" class="site-header sticky-mode">';

		echo wp_kses( $html, $this->allowed_tags() );
	}

	/**
	 * Close site header wrapper markup.
	 *
	 * Developer Note:
	 * - Ends global site header container.
	 *
	 * @return void
	 */
	function site_header_wrap_after() {

		$html = '</header>';

		echo wp_kses( $html, $this->allowed_tags() );
	}
	/**
	 * Header Logo & Search Container
	 *
	 * Developer Note:
	 * - Fixed formatting and spacing issues.
	 * - Improved readability and WordPress coding standards.
	 * - Fixed text-domain consistency.
	 * - Escaped dynamic output properly.
	 * - Added safer WooCommerce checks.
	 *
	 * @return void
	 */
	public function header_logo_container(): void {
		?>
			<div class="container">
				<div class="row align-items-center">

					<div class="col-xl-3 col-lg-3 col-sm-4 col-12 text-left">
						<?php do_action( 'prime_shopping_cart_header_branding' ); ?>
					</div>

					<div class="col-xl-6 col-lg-6 col-sm-5 col-12 text-center ms-auto">
						<?php
						if ( class_exists( 'APSW_Product_Search_Finale_Class' ) ) {
							do_action( 'apsw_search_bar_preview' );
						} else {
							get_search_form();
						}
						?>
					</div>
					<?php 
					if ( is_active_sidebar( 'replace_icon' ) && is_customize_preview() && !class_exists( 'WooCommerce' ) ) {
						echo '<div class="col-xl-3 col-lg-3 col-sm-3 col-12 text-right rs-block">
							<div class="column d-flex align-items-center justify-content-end h-100">';
						dynamic_sidebar( 'replace_icon' );
						echo '</div></div>';
					}
					if ( class_exists( 'WooCommerce' ) ) : ?>
					<div class="col-xl-3 col-lg-3 col-sm-3 col-12 text-right rs-block">
							<div class="column d-flex align-items-center justify-content-end h-100">
							<?php
							$header_icons = get_theme_mod( 'show_header_icon' );

							?>
								<ul class="header-icon d-flex justify-content-end align-items-center mb-0">
									
									<li><?php prime_shopping_cart_woocommerce_cart_link(); 
										if ( ! WC()->cart->is_empty() ) :
										echo '<div class="dropdown-box">';
											the_widget(
												'WC_Widget_Cart',
													array( 'title' => '' )
											);
										echo '</div>';
										endif;
									?></li>
									
									<li>
										<a class="gs-tooltip-act" href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
											<i class="bi bi-person-circle"></i>
										<span class="icon_txt">
											<?php if ( is_user_logged_in() ) : ?>
												<span class="label"><?php esc_html_e( 'My', 'prime-shopping-cart' ); ?></span>
												<span class="label_2"><?php esc_html_e( 'Account', 'prime-shopping-cart' ); ?></span>
											<?php else : ?>
												<span class="label"><?php esc_html_e( 'Registration', 'prime-shopping-cart' ); ?></span>
												<span class="label_2"><?php esc_html_e( 'Hello, Sign in', 'prime-shopping-cart' ); ?></span>
											<?php endif; ?>
										</span>
										</a>

										<?php if ( is_user_logged_in() ) : ?>
											<ul class="prime-myaccount-endpoint">
												<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
													<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
														<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
															<?php echo esc_html( $label ); ?>
														</a>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>

									</li>
								</ul>

							</div>
						
					</div>
					<?php endif; ?>
				</div>
			</div>
		<?php
	}
	/**
	 * Render primary site navigation menu.
	 *
	 * Includes responsive navigation wrapper,
	 * close button for mobile menu, and custom nav walker support.
	 *
	 * @return void
	 */
	public function get_site_navigation(): void {
		?>
		<nav id="navbar" class="underline">
			<button class="prime-shop-responsive-navbar"><i class="bi bi-list"></i></button>
			<div id="aside-nav-wrapper" class="nav-wrap flex-grow-1">
				<button class="prime-shop-navbar-close"><i class="bi bi-x-lg"></i></button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'depth'          => 3,
					'menu_class'     => 'prime-main-menu navigation-menu',
					'container'      => 'ul',
					'walker'         => new prime_shopping_cart_navwalker(),
					'fallback_cb'    => 'prime_shopping_cart_navwalker::fallback',
				)
			);
			?>
		 	<div id="follow"></div>
			</div>
		</nav>

		<?php
	}
	/**
	* Get the Site logo
	*
	* @return HTML
	*/
	public function get_site_branding (){
		
		$html = '<div class="logo-wrap">';
		
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$html .= get_custom_logo();
		}else{
			$html .= '<h3><a href="'.esc_url( home_url( '/' ) ).'" rel="home" class="site-title">';
			$html .= get_bloginfo( 'name' );
			$html .= '</a></h3>';
		}
		
		$html .= '</div>';
		
		$html = apply_filters( 'get_site_branding_filter', $html );
		
		echo wp_kses( $html, $this->allowed_tags() );
		
	}
	/**
	 * Display hero/banner section based on current page context.
	 *
	 * Developer Note:
	 * - Loads homepage slider/banner on front page.
	 * - Uses featured image as hero background for singular posts/pages.
	 * - Supports WooCommerce shop/category headers.
	 * - Falls back to custom header image when no thumbnail exists.
	 *
	 * @return void
	 */
	public function site_hero_sections() {

		// Skip hero section on 404 page.
		if ( is_404() ) {
			return;
		}

		$is_custom_blog_home = is_home() && prime_shopping_cart_get_option( '__replace_blog_home' );

		// Homepage / Blog Hero Section.
		if ( ( is_front_page() && ! is_home() ) || $is_custom_blog_home ) {

			if ( is_active_sidebar( 'slider' ) ) {
				dynamic_sidebar( 'slider' );
			} else {
				echo wp_kses( $this->homepage_static_banner(), $this->allowed_tags() );
			}

			return;
		}

		// Default header image.
		$header_image = get_header_image();

		// Use featured image for singular pages/posts except WooCommerce products.
		if ( has_post_thumbnail() && is_singular() && ( ! function_exists( 'is_product' ) || ! is_product() ) ) {
			$header_image = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
		}
		?>

		<div id="static_header_banner"
			class="inner-page <?php echo ! empty( $header_image ) ? 'header-img' : 'none'; ?> "
			<?php if ( ! empty( $header_image ) ) : ?>
				style="background-image:url(<?php echo esc_url( $header_image ); ?>); background-size:cover; background-position:center center;"
			<?php endif; ?>
		>
			<div class="content-text">
				<div class="d-flex align-items-center text-start">
					<div class="site-header-text-wrap">
						<?php 
							if ( is_home() ) {
								if ( display_header_text() == true ){
									echo '<h1 class="page-title-text">';
									echo bloginfo( 'name' );
									echo '</h1>';
									echo '<p class="subtitle">';
									echo esc_html(get_bloginfo( 'description', 'display' ));
									echo '</p>';

								}
							}else if ( function_exists('is_shop') && is_shop() ){
								if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
									echo '<h1 class="page-title-text">';
									echo esc_html( woocommerce_page_title() );
									echo '</h1>';
								}
							}else if( function_exists('is_product_category') && is_product_category() ){
								echo '<h1 class="page-title-text">';
								echo esc_html( woocommerce_page_title() );
								echo '</h1>';
								echo '<p class="subtitle">';
								do_action( 'prime_shopping_cartarchive_description' );
								echo '</p>';
								
							}elseif ( is_singular() ) {
								echo '<h1 class="page-title-text">';
									echo single_post_title( '', false );
								echo '</h1>';
								if( is_singular('post') && prime_shopping_cart_get_option('single_meta_hide') != true ){
									$meta = array(  'date', 'author', 'category', 'comments' );
									do_action( 'prime_shopping_cart_meta_info', $meta);
								}
							} elseif ( is_archive() ) {
								the_archive_title( '<h1 class="page-title-text">', '</h1>' );
								the_archive_description( '<p class="archive-description subtitle">', '</p>' );
							} elseif ( is_search() ) {
								echo '<h1 class="title">';
									printf( /* translators:straing */ esc_html__( 'Search Results for: %s', 'prime-shopping-cart' ),  get_search_query() );
								echo '</h1>';
							} elseif ( is_404() ) {
								echo '<h1 class="display-1">';
									esc_html_e( 'Oops! That page can&rsquo;t be found.', 'prime-shopping-cart' );
								echo '</h1>';
							}
		               	?>
					</div>
					
					<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
						<?php
						if ( function_exists( 'bcn_display' ) ) {
							bcn_display();
						}
						?>
					</div>

				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Generate homepage static banner markup.
	 *
	 * Developer Note:
	 * - Displays custom homepage hero title/description.
	 * - Uses custom banner image from theme options.
	 * - Falls back to site title + tagline if no custom content exists.
	 *
	 * @return string|void
	 */
	function homepage_static_banner() {

		$img_url     = prime_shopping_cart_get_option( '__media_img_url' );
		$banner_title = prime_shopping_cart_get_option( '__banner_title' );
		$banner_desc  = prime_shopping_cart_get_option( '__banner_desc' );

		// Custom banner content.
		if ( ! empty( $banner_title ) || ! empty( $banner_desc ) ) {

			$html  = '<div id="static_header_banner" class="' . ( ! empty( $img_url ) ? 'header-img' : '' ) . '"';

			if ( ! empty( $img_url ) ) {
				$html .= ' style="background-image:url(' . esc_url( $img_url ) . '); background-size:cover; background-position:center center;"';
			}

			$html .= '>';

			$html .= '<div class="content-text">
						<div class="container">
							<div class="site-header-text-wrap">';

			if ( ! empty( $banner_title ) ) {
				$html .= '<h1 class="page-title-text">' . esc_html( $banner_title ) . '</h1>';
			}

			if ( ! empty( $banner_desc ) ) {
				$html .= '<div class="subtitle">' . esc_html( $banner_desc ) . '</div>';
			}

			$html .= '</div>
					</div>
				</div>
			</div>';

			return $html;
		}

		// Fallback banner.
		$header_image = get_header_image();
		?>

		<div id="static_header_banner"
			class="<?php echo ! empty( $header_image ) ? 'header-img' : ''; ?>"
			<?php if ( ! empty( $header_image ) ) : ?>
				style="background-image:url(<?php echo esc_url( $header_image ); ?>); background-size:cover; background-position:center center;"
			<?php endif; ?>
		>
			<div class="content-text">
				<div class="container">
					<div class="site-header-text-wrap">

						<?php if ( display_header_text() ) : ?>
							<h1 class="page-title-text"><?php bloginfo( 'name' ); ?></h1>
							<p class="subtitle">
								<?php echo esc_html( get_bloginfo( 'description', 'display' ) ); ?>
							</p>
						<?php endif; ?>

					</div>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Allowed HTML tags.
	 *
	 * @return array
	 */
	private function allowed_tags(): array {

		return function_exists( 'prime_shopping_cart_allowed_tags' )
			? prime_shopping_cart_allowed_tags()
			: [];
	}
}

/**
 * Global instance.
 *
 * Child themes can remove hooks like:
 * remove_action(
 *     'joyas_shop_site_header',
 *     [ $prime_shopping_cart_header_layout, 'render_top_bar' ],
 *     10
 * );
 */
$prime_shopping_cart_header_layout = new Prime_Shopping_Cart_Header_Layout();