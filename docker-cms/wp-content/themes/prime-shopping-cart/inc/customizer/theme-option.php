<?php 
/**
* Theme Options Panel.
*
* @package prime-shopping-cart
*/

$default = prime_shopping_cart_get_default_theme_options();
global $wp_customize;

// Add Theme Options Panel.
$wp_customize->add_panel( 'theme_option_panel',
	array(
		'title'      => esc_html__( 'Theme Options', 'prime-shopping-cart' ),
		'priority'   => 2,
		'capability' => 'edit_theme_options',
	)
);

$wp_customize->add_section(
    'topbar_section_settings',
    array(
        'title'       => esc_html__( 'General Settings', 'prime-shopping-cart' ),
        'priority'    => 10,
        'capability'  => 'edit_theme_options',
        'panel'       => 'theme_option_panel',
        'description' => sprintf(
            /* translators: %s: URL to the Prime Shopping Cart Pro page. */
            __(
                'Upgrade to <strong>Prime Shopping Cart Pro</strong> to unlock 100+ additional settings, Elementor add-ons, and advanced WooCommerce features. <a href="%s" target="_blank" rel="noopener">Get Now →</a>',
                'prime-shopping-cart'
            ),
            esc_url( 'https://athemeart.com/downloads/prime-shopping-cart-pro/' )
        ),
    )
);

/*Social Profile*/
$wp_customize->add_setting( '__dialogue',
    array(
        'default'           => $default['__dialogue'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( '__dialogue',
    array(
        'label'    => esc_html__( 'Dialogue', 'prime-shopping-cart' ),
        'section'  => 'topbar_section_settings',
        'type'     => 'text',
        
    )
);
   

	
// === Blog Settings Section ===
$wp_customize->add_section('theme_option_section_settings', array(
    'title'      => esc_html__('Blog Settings', 'prime-shopping-cart'),
    'priority'   => 100,
    'capability' => 'edit_theme_options',
    'panel'      => 'theme_option_panel',
));

/**
 * =========================================================
 * Blog Page Layout
 * =========================================================
 */
$wp_customize->add_setting('blog_layout', array(
    'default'           => isset($default['blog_layout']) ? $default['blog_layout'] : 'content-sidebar',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'prime_shopping_cartsanitize_select',
));

$wp_customize->add_control('blog_layout', array(
    'label'       => esc_html__('Blog Page Layout', 'prime-shopping-cart'),
    'description' => esc_html__('Choose the layout for blog, category, tag, and archive pages.', 'prime-shopping-cart'),
    'section'     => 'theme_option_section_settings',
    'type'        => 'select',
    'choices'     => array(
        'sidebar-content' => esc_html__('Sidebar Left / Content Right', 'prime-shopping-cart'),
        'content-sidebar' => esc_html__('Content Left / Sidebar Right', 'prime-shopping-cart'),
        'no-sidebar'      => esc_html__('No Sidebar', 'prime-shopping-cart'),
        'full-container'      => esc_html__('Full Container', 'prime-shopping-cart'),
    ),
));

/**
 * =========================================================
 * Archive Page Content
 * =========================================================
 */
$wp_customize->add_setting('blog_loop_content_type', array(
    'default'           => isset($default['blog_loop_content_type']) ? $default['blog_loop_content_type'] : 'excerpt',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'prime_shopping_cartsanitize_select',
));

$wp_customize->add_control('blog_loop_content_type', array(
    'label'       => esc_html__('Archive Page Content', 'prime-shopping-cart'),
    'description' => esc_html__('Choose what to display on blog and archive pages.', 'prime-shopping-cart'),
    'section'     => 'theme_option_section_settings',
    'type'        => 'select',
    'choices'     => array(
        'excerpt' => esc_html__('Show Excerpt', 'prime-shopping-cart'),
        'content' => esc_html__('Show Full Content', 'prime-shopping-cart'),
        'hide'    => esc_html__('Hide Content', 'prime-shopping-cart'),
    ),
));

/**
 * =========================================================
 * Hide Meta on Archive / Blog
 * =========================================================
 */
$wp_customize->add_setting('blog_meta_hide', array(
    'default'           => isset($default['blog_meta_hide']) ? $default['blog_meta_hide'] : false,
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'prime_shopping_cartsanitize_checkbox',
));

// === Hide Meta on Archive / Blog ===
$wp_customize->add_control('blog_meta_hide', array(
    'label'       => esc_html__('Hide Meta on Archive / Blog', 'prime-shopping-cart'),
    'description' => esc_html__('Hide post meta information such as author name, publish date, categories, tags, and comment count on blog and archive pages.', 'prime-shopping-cart'),
    'section'     => 'theme_option_section_settings',
    'type'        => 'checkbox',
));
/**
 * =========================================================
 * Hide Default Placeholder Image
 * =========================================================
 */
$wp_customize->add_setting('hide_default_placeholder', array(
    'default'           => isset($default['hide_default_placeholder']) ? $default['hide_default_placeholder'] : false,
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'prime_shopping_cartsanitize_checkbox',
));

$wp_customize->add_control('hide_default_placeholder', array(
    'label'       => esc_html__('Hide Default Placeholder Image', 'prime-shopping-cart'),
    'description' => esc_html__('Hide the default placeholder image when no featured image or product image is available.', 'prime-shopping-cart'),
    'section'     => 'theme_option_section_settings',
    'type'        => 'checkbox',
));
/**
 * =========================================================
 * Single Post Layout
 * =========================================================
 */
$wp_customize->add_setting('single_post_layout', array(
    'default'           => isset($default['single_post_layout']) ? $default['single_post_layout'] : 'content-sidebar',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'prime_shopping_cartsanitize_select',
));

$wp_customize->add_control('single_post_layout', array(
    'label'       => esc_html__('Single Post Layout', 'prime-shopping-cart'),
    'description' => esc_html__('Choose the layout for single blog post pages.', 'prime-shopping-cart'),
    'section'     => 'theme_option_section_settings',
    'type'        => 'select',
    'choices'     => array(
        'sidebar-content' => esc_html__('Sidebar Left / Content Right', 'prime-shopping-cart'),
        'content-sidebar' => esc_html__('Content Left / Sidebar Right', 'prime-shopping-cart'),
        'no-sidebar'      => esc_html__('No Sidebar', 'prime-shopping-cart'),
        'full-container'      => esc_html__('Full Container', 'prime-shopping-cart'),
    ),
));

/**
 * =========================================================
 * Hide Meta on Single Post
 * =========================================================
 */
$wp_customize->add_setting('single_meta_hide', array(
    'default'           => isset($default['single_meta_hide']) ? $default['single_meta_hide'] : false,
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'prime_shopping_cartsanitize_checkbox',
));

$wp_customize->add_control('single_meta_hide', array(
    'label'       => esc_html__('Hide Meta on Single Post', 'prime-shopping-cart'),
    'description' => esc_html__('Hide post meta information such as author name, publish date, categories, tags, and comment count on single blog post pages.', 'prime-shopping-cart'),
    'section'     => 'theme_option_section_settings',
    'type'        => 'checkbox',
));

/**
 * =========================================================
 * Customize Read More Button
 * =========================================================
 */
$wp_customize->add_setting('read_more_text', array(
    'default'           => isset($default['read_more_text']) ? $default['read_more_text'] : esc_html__('Read More', 'prime-shopping-cart'),
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control('read_more_text', array(
    'label'       => esc_html__('Customize "Read More" Button', 'prime-shopping-cart'),
    'description' => esc_html__('Enter custom text for the Read More button. Leave empty to hide the button.', 'prime-shopping-cart'),
    'section'     => 'theme_option_section_settings',
    'type'        => 'text',
));
		
/*Posts management section start */
$wp_customize->add_section( 'page_option_section_settings',
	array(
		'title'      => esc_html__( 'Page Layout Settings', 'prime-shopping-cart' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
		
	)
);

	
		/*Home Page Layout*/
		$wp_customize->add_setting( 'page_layout',
			array(
				'default'           => $default['blog_layout'],
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'prime_shopping_cartsanitize_select',
			)
		);
		$wp_customize->add_control( 'page_layout',
			array(
				'label'    => esc_html__( 'Page Layout', 'prime-shopping-cart' ),
				'section'  => 'page_option_section_settings',
				'description' => esc_html__( 'Choose between different layout options to be used as default', 'prime-shopping-cart' ),
				'choices'   => array(
					'sidebar-content'  => esc_html__('Sidebar on Left, Content on Right', 'prime-shopping-cart'),
					'content-sidebar'  => esc_html__('Content on Left, Sidebar on Right', 'prime-shopping-cart'),
                    'no-sidebar'      => esc_html__('No Sidebar', 'prime-shopping-cart'),
					'full-container'       => esc_html__('Full Width (No Sidebar)', 'prime-shopping-cart'),
					),
				'type'     => 'select',
				'priority' => 170,
			)
		);

// Footer Settings Section
$wp_customize->add_section( 'footer_section',
	array(
		'title'      => esc_html__( 'Footer Settings', 'prime-shopping-cart' ),
		'priority'   => 130,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
		
	)
);

// Copyright Text Setting
$wp_customize->add_setting( 'copyright_text',
	array(
		'default'           => $default['copyright_text'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'copyright_text',
	array(
		'label'       => esc_html__( 'Copyright Text', 'prime-shopping-cart' ),
		'section'     => 'footer_section',
		'description' => esc_html__( 'Customize the footer copyright message.', 'prime-shopping-cart' ),
		'type'        => 'textarea',
		'priority'    => 10,
	)
);

// Social options
$prime_shopping_cart_social = array(
    'bi-facebook'  => array( 'label' => esc_html__( 'Facebook URL', 'prime-shopping-cart' ) ),
    'bi-twitter'   => array( 'label' => esc_html__( 'Twitter URL', 'prime-shopping-cart' ) ),
    'bi-pinterest' => array( 'label' => esc_html__( 'Pinterest URL', 'prime-shopping-cart' ) ),
    'bi-youtube'   => array( 'label' => esc_html__( 'YouTube URL', 'prime-shopping-cart' ) ),
    'bi-instagram' => array( 'label' => esc_html__( 'Instagram URL', 'prime-shopping-cart' ) ),
);

// Loop
foreach ( $prime_shopping_cart_social as $key => $val ) {

    // Setting
    $wp_customize->add_setting(
        'prime_shopping_cart_social[' . $key . ']',
        array(
            'default'           => '',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    // Control
    $wp_customize->add_control(
        'prime_shopping_cart_social[' . $key . ']',
        array(
            'label'   => $val['label'],
            'section' => 'footer_section',
            'type'    => 'url',
            'priority'    => 20,
        )
    );
}		