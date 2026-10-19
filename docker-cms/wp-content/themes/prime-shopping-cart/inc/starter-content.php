<?php
/**
 * The9 Store / Prime Shopping Cart Starter Content
 *
 * @link https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
 *
 * @package Prime Shopping Cart
 * @subpackage prime-shopping-cart
 * @since Prime Shopping Cart 1.0.0
 */

/**
 * Function to return the array of starter content for the theme.
 *
 * Passes it through the `prime_shopping_cart_get_starter_content` filter before returning.
 *
 * @since prime-shopping-cart 1.0.0
 *
 * @return array A filtered array of args for the starter_content.
 */
function prime_shopping_cart_get_starter_content() {

	$starter_content = array(

		/* -------------------------------------------------
		 * Widgets
		 * ------------------------------------------------- */
		'widgets' => array(
			'sidebar' => array(
				'search',
				'text_about',
				'text_business_info',
			),
			'footer-1' => array(
				'text_business_info',
				'text_about',
				'search',
			),
			'replace_icon' => array(
			    'fake_icons' => array(
			        'custom_html',
			        array(
			            'title'   => '',
			            'content' => '<ul class="header-icon d-flex justify-content-end align-items-center mb-0">
			                <li>
			                    <a class="cart-contents" href="#">
			                        <i class="bi bi-cart-check"></i>
			                        <span class="cart-meta">
			                            <span class="count label">0 items</span>
			                            <span class="amount label_2">£0.00</span>
			                        </span>
			                    </a>
			                </li>
			                <li>
			                    <a href="#">
			                        <i class="bi bi-person-circle"></i>
			                        <span class="icon_txt">
			                            <span class="label">Registration</span>
			                            <span class="label_2">Hello, Sign in</span>
			                        </span>
			                    </a>
			                </li>
			            </ul>',
			        ),
			    ),
			),
			// Custom slider / hero area (Custom HTML widget)
			'slider' => array(
				'my_text' => array(
					'custom_html',
					array(
						'title'   => '',
						'content' => '<div id="static_header_banner" class="header-img" style="background-image: url(' . esc_url( get_theme_file_uri( '/assets/image/custom-header.jpg' ) ) . '); background-attachment: scroll; background-size: cover; background-position: center center; height: 70vh;">
							<div class="content-text">
								<div class="container">
									<div class="site-header-text-wrap">
										<h1 class="page-title-text">Create. Reliable. Inspire.</h1>
										<p>Discover the perfect blend of simplicity and functionality with our easy eCommerce and blogging theme, designed for your upcoming online store. Elevate your online presence effortlessly!</p>
									</div>
								</div>
							</div>
						</div>',
					),
				),
			),
		),

		/* -------------------------------------------------
		 * Pages / Posts
		 * ------------------------------------------------- */
		'posts' => array(

			/* ===== Homepage ===== */
			'front' => array(
				'post_type'    => 'page',
				'post_title'   => esc_html_x( 'Homepage', 'Theme starter content', 'prime-shopping-cart' ),
				'post_content' => '<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide" style="margin-top:0px;margin-bottom:0px"><!-- wp:spacer {"height":"var:preset|spacing|xx-large"} -->
<div style="height:var(--wp--preset--spacing--xx-large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:group {"style":{"spacing":{"margin":{"top":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0px"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:cover {"url":"' . esc_url( get_theme_file_uri( '/assets/image/quick-patterns-1.webp' ) ) . '","dimRatio":20,"overlayColor":"quaternary","focalPoint":{"x":0.5,"y":0},"minHeight":705,"contentPosition":"bottom left","isDark":false,"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-left" style="min-height:724px"><span aria-hidden="true" class="wp-block-cover__background has-quaternary-background-color has-background-dim-20 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . esc_url( get_theme_file_uri( '/assets/image/quick-patterns-1.webp' ) ) . '" style="object-position:50% 0%" data-object-fit="cover" data-object-position="50% 0%"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"bottom":"var:preset|spacing|x-small","left":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--x-small);padding-left:var(--wp--preset--spacing--x-small)"><!-- wp:paragraph {"align":"left","placeholder":"Write title…","fontSize":"small"} -->
<p class="has-text-align-left has-small-font-size">Fresh Designs of Our Spring Collection</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">New Arrivals</h2>
<!-- /wp:heading -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--x-small)"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Shop Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group"><!-- wp:cover {"url":"' . esc_url( get_theme_file_uri( '/assets/image/quick-patterns-2.webp' ) ) . '","dimRatio":20,"overlayColor":"quaternary","focalPoint":{"x":0.5,"y":0.5},"minHeight":350,"contentPosition":"bottom left","isDark":false,"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"},"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-left" style="min-height:350px"><span aria-hidden="true" class="wp-block-cover__background has-quaternary-background-color has-background-dim-20 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . esc_url( get_theme_file_uri( '/assets/image/quick-patterns-2.webp' ) ) . '" style="object-position:50% 50%" data-object-fit="cover" data-object-position="50% 50%"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"bottom":"var:preset|spacing|x-small","left":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--x-small);padding-left:var(--wp--preset--spacing--x-small)"><!-- wp:paragraph {"align":"left","placeholder":"Write title…","fontSize":"small"} -->
<p class="has-text-align-left has-small-font-size">Comfort With Our latest Footwear Collection</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Footwear</h2>
<!-- /wp:heading -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--x-small)"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Shop Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
<!-- wp:cover {"url":"' . esc_url( get_theme_file_uri( '/assets/image/quick-patterns-3.webp' ) ) . '","dimRatio":20,"overlayColor":"quaternary","minHeight":350,"contentPosition":"bottom left","isDark":false,"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-left" style="min-height:350px"><span aria-hidden="true" class="wp-block-cover__background has-quaternary-background-color has-background-dim-20 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . esc_url( get_theme_file_uri( '/assets/image/quick-patterns-3.webp' ) ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"bottom":"var:preset|spacing|x-small","left":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--x-small);padding-left:var(--wp--preset--spacing--x-small)"><!-- wp:paragraph {"align":"left","placeholder":"Write title…","fontSize":"small"} -->
<p class="has-text-align-left has-small-font-size">Exclusive Selection of Imported</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Perfumes</h2>
<!-- /wp:heading -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--x-small)"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Shop Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
<!-- wp:spacer {"height":"var:preset|spacing|xx-large"} -->
<div style="height:var(--wp--preset--spacing--xx-large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
<!-- wp:columns -->
<div class="wp-block-columns" style="padding:30px; background:#fff; margin:20px"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Get Inspired</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Discover our carefully curated collections designed to elevate your everyday style. From timeless classics to the latest trends, find pieces that inspire confidence.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="' . esc_url( get_template_directory_uri() . '/assets/image/patterns-1.webp' ) . '" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->
<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="' . esc_url( get_template_directory_uri() . '/assets/image/patterns-2.webp' ) . '" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"var:preset|spacing|large"}}},"layout":{"type":"constrained"},"metadata":{"name":"Popular Categories"}} -->
<div class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--large)"><!-- wp:group {"style":{"spacing":{"blockGap":"8px","margin":{"bottom":"var:preset|spacing|small"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--small)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Explore Popular Categories</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Shop by our popular categories below</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"24px"}}}} -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"' . esc_url( get_template_directory_uri() ) . '/assets/image/quick-patterns-4.webp","dimRatio":0,"customOverlayColor":"#f8edda","minHeight":320,"minHeightUnit":"px","contentPosition":"bottom center","isDark":false,"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}},"border":{"radius":{"topLeft":"16px","topRight":"16px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-center" style="border-top-left-radius:16px;border-top-right-radius:16px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;min-height:320px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#f8edda"></span><img class="wp-block-cover__image-background" alt="" src="' . esc_url( get_template_directory_uri() ) . '/assets/image/quick-patterns-4.webp" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"color":{"background":"#ffffffe3"},"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#ffffffe3"><!-- wp:heading {"textAlign":"center","level":5} -->
<h5 class="wp-block-heading has-text-align-center">Men Collection</h5>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"spacing":{"padding":{"top":"8px"}}},"fontSize":"14"} -->
<p class="has-text-align-center has-14-font-size" style="padding-top:8px">12 Items</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"' . esc_url( get_template_directory_uri() ) . '/assets/image/quick-patterns-5.webp","dimRatio":0,"customOverlayColor":"#fae2e8","minHeight":320,"minHeightUnit":"px","contentPosition":"bottom center","isDark":false,"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}},"border":{"radius":{"topLeft":"16px","topRight":"16px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-center" style="border-top-left-radius:16px;border-top-right-radius:16px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;min-height:320px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#fae2e8"></span><img class="wp-block-cover__image-background" alt="" src="' . esc_url( get_template_directory_uri() ) . '/assets/image/quick-patterns-5.webp" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"color":{"background":"#ffffffe3"},"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#ffffffe3"><!-- wp:heading {"textAlign":"center","level":5} -->
<h5 class="wp-block-heading has-text-align-center">For Women</h5>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"spacing":{"padding":{"top":"8px"}}},"fontSize":"14"} -->
<p class="has-text-align-center has-14-font-size" style="padding-top:8px">10 Items</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"' . esc_url( get_template_directory_uri() ) . '/assets/image/quick-patterns-6.webp","dimRatio":0,"customOverlayColor":"#e4f3bb","minHeight":320,"minHeightUnit":"px","contentPosition":"bottom center","isDark":false,"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}},"border":{"radius":{"topLeft":"16px","topRight":"16px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-center" style="border-top-left-radius:16px;border-top-right-radius:16px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;min-height:320px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#e4f3bb"></span><img class="wp-block-cover__image-background" alt="" src="' . esc_url( get_template_directory_uri() ) . '/assets/image/quick-patterns-6.webp" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"color":{"background":"#ffffffe3"},"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#ffffffe3"><!-- wp:heading {"textAlign":"center","level":5} -->
<h5 class="wp-block-heading has-text-align-center">Accessories</h5>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"spacing":{"padding":{"top":"8px"}}},"fontSize":"14"} -->
<p class="has-text-align-center has-14-font-size" style="padding-top:8px">20 Items</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"' . esc_url( get_template_directory_uri() ) . '/assets/image/quick-patterns-2.webp","dimRatio":0,"customOverlayColor":"#d5ecfa","minHeight":320,"minHeightUnit":"px","contentPosition":"bottom center","isDark":false,"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}},"border":{"radius":{"topLeft":"16px","topRight":"16px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-center" style="border-top-left-radius:16px;border-top-right-radius:16px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;min-height:320px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#d5ecfa"></span><img class="wp-block-cover__image-background" alt="" src="' . esc_url( get_template_directory_uri() ) . '/assets/image/quick-patterns-2.webp" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"color":{"background":"#ffffffe3"},"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#ffffffe3"><!-- wp:heading {"textAlign":"center","level":5} -->
<h5 class="wp-block-heading has-text-align-center">Jackets &amp; Coats</h5>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"spacing":{"padding":{"top":"8px"}}},"fontSize":"14"} -->
<p class="has-text-align-center has-14-font-size" style="padding-top:8px">16 Items</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
			),

			/* ===== About Us ===== */
			'about' => array(
				'post_type'    => 'page',
				'post_title'   => esc_html_x( 'About Us', 'Theme starter content', 'prime-shopping-cart' ),
				'thumbnail'    => '{{image-about-us}}',
				'post_content' => '<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">About Our Store</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Welcome to <strong>The9 Store</strong> — your destination for quality products, thoughtful design, and exceptional customer service. We believe shopping should be simple, enjoyable, and inspiring.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Our Story</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Founded with a passion for great products and happy customers, we started as a small team with a big vision: to create an online store that feels personal, trustworthy, and stylish. Today we continue to carefully curate every item we offer.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Quality First</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Every product is selected for durability, design, and value. We work only with trusted suppliers.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Customer Care</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Your satisfaction is our priority. Our support team is always ready to help with any question.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Fast Shipping</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>We process and ship orders quickly so you can enjoy your new items as soon as possible.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:paragraph -->
<p>Thank you for choosing us. We look forward to being part of your shopping journey.</p>
<!-- /wp:paragraph -->',
			),

			/* ===== Contact ===== */
			'contact' => array(
				'post_type'    => 'page',
				'post_title'   => esc_html_x( 'Contact Us', 'Theme starter content', 'prime-shopping-cart' ),
				'post_content' => '<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Get in Touch</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Have a question about an order, a product, or anything else? We’d love to hear from you. Reach out using the details below or send us a message.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Contact Information</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong>Email:</strong> support@the9store.com<br>
<strong>Phone:</strong> +1 (555) 123-4567<br>
<strong>Hours:</strong> Mon – Fri, 9:00 AM – 6:00 PM</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>Address:</strong><br>
123 Commerce Street<br>
Suite 100<br>
New York, NY 10001<br>
United States</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Send a Message</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>You can use any contact form plugin (Contact Form 7, WPForms, Fluent Forms, etc.) here. Simply insert the form shortcode in this area after activating your preferred plugin.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><em>[contact-form-7 id="1" title="Contact form"]</em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:paragraph -->
<p>We usually reply within 24 hours on business days. Thank you for contacting The9 Store!</p>
<!-- /wp:paragraph -->',
			),

			/* ===== Blog (uses core default) ===== */
			'blog',
		),

		/* -------------------------------------------------
		 * Attachments (images)
		 * ------------------------------------------------- */
		'attachments' => array(
			'image-about-us' => array(
				'post_title' => _x( 'About Us', 'Theme starter content', 'prime-shopping-cart' ),
				'file'       => 'assets/image/header-image-contact.jpg', // relative to theme root
			),
			'logo' => array(
			    'post_title' => _x( 'Logo', 'Theme starter content', 'prime-shopping-cart' ),
			    'file'       => 'assets/image/prime-logo.png', // ← must match the real file
			),
		),

	
		/* -------------------------------------------------
		 * Theme Mods
		 * ------------------------------------------------- */
		'theme_mods' => array(
		    '__dialogue' => esc_html__( 'Your Trusted 24 Hours Service Provider!', 'prime-shopping-cart' ),

			// Individual social settings (matches your Customizer)
			'prime_shopping_cart_social[bi-facebook]'  => 'https://www.facebook.com/',
			'prime_shopping_cart_social[bi-twitter]'   => 'https://twitter.com/',
			'prime_shopping_cart_social[bi-pinterest]' => 'https://www.pinterest.com/',
			'prime_shopping_cart_social[bi-youtube]'   => 'https://www.youtube.com/',
			'prime_shopping_cart_social[bi-instagram]' => 'https://www.instagram.com/',

		    // Only useful if you also edit the header template
		    'show_header_icon' => '<ul class="header-icon d-flex justify-content-end align-items-center mb-0">
		        <li>
		            <a class="cart-contents" href="#">
		                <i class="bi bi-cart-check"></i>
		                <span class="cart-meta">
		                    <span class="count label">0 items</span>
		                    <span class="amount label_2">£0.00</span>
		                </span>
		            </a>
		        </li>
		        <li>
		            <a href="#">
		                <i class="bi bi-person-circle"></i>
		                <span class="icon_txt">
		                    <span class="label">Registration</span>
		                    <span class="label_2">Hello, Sign in</span>
		                </span>
		            </a>
		        </li>
		    </ul>',
		),

		/* -------------------------------------------------
		 * Options (Static front page)
		 * ------------------------------------------------- */
		'options' => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{front}}',
			'page_for_posts' => '{{blog}}',
			'custom_logo' 	 => '{{logo}}',
			'header_textcolor' => 'blank',
		),

		/* -------------------------------------------------
		 * Navigation Menus
		 * ------------------------------------------------- */
		'nav_menus' => array(
			'menu-1' => array(
				'name'  => __( 'Main Menu', 'prime-shopping-cart' ),
				'items' => array(
					'link_home',
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
			'topbar' => array(
				'name'  => __( 'Top Bar Menu', 'prime-shopping-cart' ),
				'items' => array(
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
		),
	);

	/**
	 * Filters the starter content array.
	 *
	 * @since the9-store 1.0.0
	 *
	 * @param array $starter_content Array of starter content.
	 */
	return apply_filters( 'prime_shopping_cart_get_starter_content', $starter_content );
}