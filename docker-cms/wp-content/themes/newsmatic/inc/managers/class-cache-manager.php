<?php
    /**
     * Cache Manager
     * 
     * @package Newsmatic
     * @since 1.4.7
     */
    namespace Newsmatic;

    use Newsmatic\CustomizerDefault as ND;

    if( ! class_exists( __NAMESPACE__ . '\\Cache_Manager' ) ) {
        /**
         * Cache_Manager class
         */
        class Cache_Manager {
            /**
             * Dynamic css transient
             * 
             * @since 1.4.7
             */
            private static $dynamic_css_transient = 'newsmatic_dynamic_css';

            /**
             * Constructor
             * 
             * @since 1.4.7
             */
            public function __construct() {
                /**
                 * Hook executes after value of a control changes
                 */
                add_action( 'customize_save_after', [ $this, 'clear_transient' ] );
                /**
                 * Hooks executes after value of a control changes and customizer resets
                 */
                add_action( "updated_option", [ $this, 'clear_transient' ] );
                /**
                 * Set transient
                 */
                add_action( 'init', [ $this, 'init' ] );
            }

            /**
             * Clear transient
             * 
             * @since 1.4.7
             */
            public function clear_transient() {
                $contexts = [ 'single', 'page', 'home', 'archive', 'search', '404', 'other' ];
                foreach ( $contexts as $ctx ) {
                    delete_transient( self::$dynamic_css_transient . '_' . $ctx );
                }
            }

            /**
             * Initialization
             * 
             * @since 1.4.7
             */
            public function init() {
                $this->get_dynamic_css();
            }
            
            /**
             * Get dynamic css
             * 
             * @since 1.4.7
             */
            public static function get_dynamic_css() {
                // Create a unique key per page context
                $key = self::$dynamic_css_transient . '_' . self::get_page_context();
                $cached = get_transient( $key );
                if ( $cached !== false ) {
                    return $cached;
                }
                $css = self::newsmatic_current_styles();
                set_transient( $key, $css, HOUR_IN_SECONDS );
                return $css;
            }

            /**
             * Get page context
             * 
             * @since 1.4.7
             */
            private static function get_page_context() {
                if ( is_single() )       return 'single';
                if ( is_page() )         return 'page';
                if ( is_home() )         return 'home';
                if ( is_archive() )      return 'archive';
                if ( is_search() )       return 'search';
                if ( is_404() )          return '404';
                return 'other';
            }

            /**
             * Generates the current changes in styling of the theme.
             * 
             * @package Online Newspaper Pro
             * @since 1.4.7
             */
            private static function newsmatic_current_styles() {
                ob_start();
                    // inline style call
                    $nPresetCode = function($var,$id) {
                        newsmatic_assign_preset_var($var,$id);
                    };
                    $nPresetCode( "--newsmatic-global-preset-color-1", "preset_color_1" );
                    $nPresetCode( "--newsmatic-global-preset-color-2", "preset_color_2" );
                    $nPresetCode( "--newsmatic-global-preset-color-3", "preset_color_3" );
                    $nPresetCode( "--newsmatic-global-preset-color-4", "preset_color_4" );
                    $nPresetCode( "--newsmatic-global-preset-color-5", "preset_color_5" );
                    $nPresetCode( "--newsmatic-global-preset-color-6", "preset_color_6" );
                    $nPresetCode( "--newsmatic-global-preset-color-7", "preset_color_7" );
                    $nPresetCode( "--newsmatic-global-preset-color-8", "preset_color_8" );
                    $nPresetCode( "--newsmatic-global-preset-color-9", "preset_color_9" );
                    $nPresetCode( "--newsmatic-global-preset-color-10", "preset_color_10" );
                    $nPresetCode( "--newsmatic-global-preset-color-11", "preset_color_11" );
                    $nPresetCode( "--newsmatic-global-preset-color-12", "preset_color_12" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-1", "preset_gradient_1" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-2", "preset_gradient_2" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-3", "preset_gradient_3" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-4", "preset_gradient_4" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-5", "preset_gradient_5" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-6", "preset_gradient_6" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-7", "preset_gradient_7" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-8", "preset_gradient_8" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-9", "preset_gradient_9" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-10", "preset_gradient_10" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-11", "preset_gradient_11" );
                    $nPresetCode( "--newsmatic-global-preset-gradient-color-12", "preset_gradient_12" );
                    if( ND\newsmatic_get_customizer_option('website_block_border_top_option') ) :
                        newsmatic_assign_var( "--theme-block-top-border-color", "website_block_border_top_color" );
                    endif;
                    $nBackgroundCode = function($identifier,$id) {
                        newsmatic_get_background_style($identifier,$id);
                    };
                    $nBackgroundCode('.newsmatic_font_typography .header-custom-button','header_custom_button_background_color_group');
                    $nBackgroundCode('.newsmatic_font_typography .header-custom-button:hover','header_custom_button_background_hover_color_group');
                    $nSpacingCode = function($identifier,$id, $property = 'padding') {
                        newsmatic_get_responsive_spacing_style($identifier,$id, $property);
                    };
                    $nTypoCode = function($identifier,$id) {
                        newsmatic_get_typo_style($identifier,$id);
                    };
                    $nTypoCode( "--site-title", 'site_title_typo' );
                    $nTypoCode( "--site-tagline", 'site_tagline_typo' );
                    newsmatic_site_logo_width_fnc("body .site-branding img.custom-logo", 'newsmatic_site_logo_width');
                    newsmatic_site_logo_width_fnc("body .site-footer .footer-site-logo img", 'bottom_footer_logo_width');
                    $nColorGroupCode = function($identifier,$id,$property='color') {
                        newsmatic_color_options_one($identifier,$id,$property);
                    };
                    $nColorCode = function($identifier,$id) {
                        newsmatic_text_color_var($identifier,$id);
                    };
                    $nColorCode('--menu-color','header_menu_color');
                    $nColorCode('--sidebar-toggle-color','header_sidebar_toggle_color');
                    $nColorCode('--search-color','header_search_icon_color');
                    newsmatic_get_background_style_var('--site-bk-color', 'site_background_color');
                    $nColorCode('--move-to-top-background-color','stt_background_color_group');
                    $nColorCode('--move-to-top-color','stt_color_group');
                    newsmatic_visibility_options('.ads-banner','header_ads_banner_responsive_option');
                    newsmatic_visibility_options('body #newsmatic-scroll-to-top.show','stt_responsive_option');
                    newsmatic_border_option('body .site-footer .row-one.full-width, body .site-footer .row-one .full-width','footer_top_border', 'border-top');
                    $nColorCode('--custom-btn-color','header_custom_button_color_group');
                    $nColorCode('--mode-toggle-color','light_mode_color');
                    $nColorCode('--mode-toggle-color-dark','dark_mode_color');
                    newsmatic_theme_color('--theme-color-red','theme_color');
                    $nColorCode('--footer-social-color','footer_social_icons_color');
                    newsmatic_box_shadow_styles( 'header_builder_box_shadow', 'body .site .site-header' );
                    newsmatic_category_colors_styles();
                    
                    // banner image border radius setting styles
                    $banner_slider_image_border_radius = ND\newsmatic_get_customizer_option( 'banner_slider_image_border_radius' );
                    $banner_slider_block_posts_image_border_radius = ND\newsmatic_get_customizer_option( 'banner_slider_block_posts_image_border_radius' );
                    if ( $banner_slider_image_border_radius) {
                        echo " #main-banner-section .main-banner-slider figure.post-thumb { border-radius: " . $banner_slider_image_border_radius['desktop'] . "px; } #main-banner-section .main-banner-slider .post-element{ border-radius: " . $banner_slider_image_border_radius['desktop'] . "px;}\n";
                        echo " @media (max-width: 769px){ #main-banner-section .main-banner-slider figure.post-thumb { border-radius: " . $banner_slider_image_border_radius['tablet']. "px; } #main-banner-section .main-banner-slider .post-element { border-radius: " . $banner_slider_image_border_radius['desktop'] . "px; } }\n";
                        echo " @media (max-width: 548px){ #main-banner-section .main-banner-slider figure.post-thumb  { border-radius: " . $banner_slider_image_border_radius['smartphone']. "px; } #main-banner-section .main-banner-slider .post-element { border-radius: " . $banner_slider_image_border_radius['desktop'] . "px; } }\n";
                    }

                    if ( $banner_slider_block_posts_image_border_radius) {
                        echo " #main-banner-section .main-banner-trailing-posts figure.post-thumb, #main-banner-section .banner-trailing-posts figure.post-thumb { border-radius: " . $banner_slider_block_posts_image_border_radius['desktop'] . "px } #main-banner-section .banner-trailing-posts .post-element { border-radius: " . $banner_slider_block_posts_image_border_radius['desktop'] . "px;}\n";

                        echo " @media (max-width: 769px){ #main-banner-section .main-banner-trailing-posts figure.post-thumb,
                        #main-banner-section .banner-trailing-posts figure.post-thumb { border-radius: " . $banner_slider_block_posts_image_border_radius['tablet']. "px } #main-banner-section .banner-trailing-posts .post-element { border-radius: " . $banner_slider_block_posts_image_border_radius['tablet'] . "px;} }\n";

                        echo " @media (max-width: 548px){ #main-banner-section .main-banner-trailing-posts figure.post-thumb,
                        #main-banner-section .banner-trailing-posts figure.post-thumb  { border-radius: " . $banner_slider_block_posts_image_border_radius['smartphone']. "px  } #main-banner-section .banner-trailing-posts .post-element { border-radius: " . $banner_slider_block_posts_image_border_radius['smartphone'] . "px;} }\n";
                    }

                    // archive page image setting styles
                    $archive_page_image_ratio = ND\newsmatic_get_customizer_option( 'archive_page_image_ratio' );
                    $archive_page_image_border_radius = ND\newsmatic_get_customizer_option( 'archive_page_image_border_radius' );
                    if ( $archive_page_image_ratio) {
                        echo " main.site-main .primary-content article figure.post-thumb-wrap { padding-bottom: calc( " . $archive_page_image_ratio['desktop']. " * 100% ) }\n";
                        echo " @media (max-width: 769px){ main.site-main .primary-content article figure.post-thumb-wrap { padding-bottom: calc( " . $archive_page_image_ratio['tablet']. " * 100% ) } }\n";
                        echo " @media (max-width: 548px){ main.site-main .primary-content article figure.post-thumb-wrap { padding-bottom: calc( " . $archive_page_image_ratio['smartphone']. " * 100% ) } }\n";
                    }
                    if ( $archive_page_image_border_radius) {
                        echo " main.site-main .primary-content article figure.post-thumb-wrap { border-radius: " . $archive_page_image_border_radius['desktop'] . "px}\n";
                        echo " @media (max-width: 769px){ main.site-main .primary-content article figure.post-thumb-wrap { border-radius: " . $archive_page_image_border_radius['tablet']. "px } }\n";
                        echo " @media (max-width: 548px){ main.site-main .primary-content article figure.post-thumb-wrap { border-radius: " . $archive_page_image_border_radius['smartphone']. "px  } }\n";
                    }

                    // front sections image settings styles
                    $full_width_blocks = ND\newsmatic_get_customizer_option( 'full_width_blocks' );
                    $full_width_blocks = json_decode( $full_width_blocks );
                    foreach( $full_width_blocks as $block ) : 
                        if( $block->option && isset( $block->uniqueId ) ) {
                            if( isset( $block->imageRatio ) ) {
                                echo "#block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->desktop . " * 100% ) }\n";
                                echo " @media (max-width: 769px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->tablet . " * 100% ) } }\n";
                                echo " @media (max-width: 548px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->smartphone . " * 100% ) }}\n";
                            }
                            if( isset( $block->imageRadius ) ) {
                                echo "#block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->desktop . "px }\n";
                                echo " @media (max-width: 769px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->tablet . "px } }\n";
                                echo " @media (max-width: 548px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->smartphone . "px } }\n";

                            }
                        }
                    endforeach;
                    
                    $leftc_rights_blocks = ND\newsmatic_get_customizer_option( 'leftc_rights_blocks' );
                    $leftc_rights_blocks = json_decode( $leftc_rights_blocks );
                    foreach( $leftc_rights_blocks as $block ) : 
                        if( $block->option && isset( $block->uniqueId ) ) {
                            if( isset( $block->imageRatio ) ) {
                                echo "#block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->desktop . " * 100% ) }\n";
                                echo " @media (max-width: 769px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->tablet . " * 100% ) } }\n";
                                echo " @media (max-width: 548px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->smartphone . " * 100% ) }}\n";
                            }
                            if( isset( $block->imageRadius ) ) {
                                echo "#block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->desktop . "px }\n";
                                echo " @media (max-width: 769px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->tablet . "px } }\n";
                                echo " @media (max-width: 548px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->smartphone . "px } }\n";
                            }
                        }
                    endforeach;

                    $lefts_rightc_blocks = ND\newsmatic_get_customizer_option( 'lefts_rightc_blocks' );
                    $lefts_rightc_blocks = json_decode( $lefts_rightc_blocks );
                    foreach( $lefts_rightc_blocks as $block ) : 
                        if( $block->option && isset( $block->uniqueId ) ) {
                            if( isset( $block->imageRatio ) ) {
                                echo "#block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->desktop . " * 100% ) }\n";
                                echo " @media (max-width: 769px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->tablet . " * 100% ) } }\n";
                                echo " @media (max-width: 548px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->smartphone . " * 100% ) }}\n";
                            }
                            if( isset( $block->imageRadius ) ) {
                                echo "#block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->desktop . "px }\n";

                                echo " @media (max-width: 769px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->tablet . "px } }\n";

                                echo " @media (max-width: 548px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->smartphone . "px } }\n";
                                
                            }
                        }
                    endforeach;

                    $bottom_full_width_blocks = ND\newsmatic_get_customizer_option( 'bottom_full_width_blocks' );
                    $bottom_full_width_blocks = json_decode( $bottom_full_width_blocks );
                    foreach( $bottom_full_width_blocks as $block ) : 
                        if( $block->option && isset( $block->uniqueId ) ) {
                            if( isset( $block->imageRatio ) ) {
                                echo "#block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->desktop . " * 100% ) }\n";
                                echo " @media (max-width: 769px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->tablet . " * 100% ) } }\n";
                                echo " @media (max-width: 548px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { padding-bottom: calc( " . $block->imageRatio->smartphone . " * 100% ) }}\n";
                            }
                            if( isset( $block->imageRadius ) ) {
                                echo "#block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->desktop . "px }\n";
                                echo " @media (max-width: 769px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->tablet . "px } }\n";
                                echo " @media (max-width: 548px){ #block--" . $block->uniqueId . " article figure.post-thumb-wrap { border-radius: " . $block->imageRadius->smartphone . "px } }\n";
                            }
                        }
                    endforeach;

                    $nBackgroundCode('.newsmatic_main_body .site-header .row-one.full-width, .newsmatic_main_body .site-header .row-one .full-width', 'top_header_background_color_group');
                    $nBackgroundCode('.newsmatic_main_body .site-header .row-two.full-width, .newsmatic_main_body .site-header .row-two .full-width','header_background_color_group');
                    $nBackgroundCode('.newsmatic_main_body .site-header .row-three.full-width, .newsmatic_main_body .site-header .row-three .full-width','header_menu_background_color_group');

                    $nBackgroundCode('body.newsmatic_main_body .site-footer .row-one.full-width, body.newsmatic_main_body .site-footer .row-one .full-width','footer_background_color_group');
                    $nBackgroundCode('body.newsmatic_main_body .site-footer .row-two.full-width, body.newsmatic_main_body .site-footer .row-two .full-width','bottom_footer_background_color_group');
                    $nBackgroundCode('body.newsmatic_main_body .site-footer .row-three.full-width, body.newsmatic_main_body .site-footer .row-three .full-width','footer_third_row_background');

                    newsmatic_border_option('body .site-header.layout--default','header_builder_border', 'border');
                    newsmatic_border_option('body .site-header .row-one.full-width, body .site-header .row-one .full-width','top_header_bottom_border', 'border-bottom');
                    newsmatic_border_option('body .site-header .row-two .bb-bldr-row:before','header_menu_top_border', 'border-bottom');
                    newsmatic_border_option('body .site-header .row-three .bb-bldr-row:before','header_menu_bottom_border', 'border-bottom');
                    newsmatic_border_option('body .site-footer .row-two.full-width, body .site-footer .row-two .full-width','footer_second_row_border', 'border-top');
                    newsmatic_border_option('body .site-footer .row-three.full-width, body .site-footer .row-three .full-width','footer_third_row_border', 'border-top');

                    $nSpacingCode( 'body .site-header .row-one.full-width, body .site-header .row-one .full-width' , 'header_first_row_padding', 'padding' );
                    $nSpacingCode( 'body .site-header .row-two.full-width, body .site-header .row-two .full-width' , 'header_second_row_padding', 'padding' );
                    $nSpacingCode( 'body .site-header .row-three.full-width, body .site-header .row-three .full-width' , 'header_third_row_padding', 'padding' );
                    $nSpacingCode( 'body .site-footer .row-one.full-width, body .site-footer .row-one .full-width' , 'footer_first_row_padding', 'padding' );
                    $nSpacingCode( 'body .site-footer .row-two.full-width, body .site-footer .row-two .full-width' , 'footer_second_row_padding', 'padding' );
                    $nSpacingCode( 'body .site-footer .row-three.full-width, body .site-footer .row-three .full-width' , 'footer_third_row_padding', 'padding' );

                    newsmatic_adjust_builder_border( 'header_builder' );
                    newsmatic_adjust_builder_border( 'responsive_header_builder' );

                    $nColorCode('--top-header-menu-color','top_header_menu_color');
                    $nColorGroupCode('body.newsmatic_main_body .site-header.layout--default .top-date-time, body.newsmatic_main_body .site-header.layout--default .top-date-time:after','top_header_datetime_color', 'color');
                    $nColorCode('--random-news-color','header_random_news_label_color');
                    $nColorCode('--newsletter-color','header_newsletter_label_color');
                    $nColorCode('--footer-text-color','footer_color');
                    $nColorGroupCode('.newsmatic_main_body .site-footer .site-info','bottom_footer_text_color');
                    $nColorCode('--top-header-social-color','top_header_social_icon_color');
                $current_styles = ob_get_clean();
                return apply_filters( 'newsmatic_current_styles', wp_strip_all_tags( self::newsmatic_minifyCSS( $current_styles ) ) );
            }

            /**
             * Minify dynamic css
             * 
             * @since 1.4.7
             */
            private static function newsmatic_minifyCSS( $css ) {
                // Remove comments
                $css = preg_replace( '!/\*.*?\*/!s', '', $css );
                // Remove space after colons
                $css = preg_replace( '/\s*:\s*/', ':', $css );
                // Remove whitespace
                $css = preg_replace( '/\s+/', ' ', $css );
                // Remove space before/after brackets and semicolons
                $css = preg_replace( '/\s*{\s*/', '{', $css );
                $css = preg_replace( '/\s*}\s*/', '}', $css );
                $css = preg_replace( '/\s*;\s*/', ';', $css );
                // Remove final semicolon in a block
                $css = preg_replace( '/;}/', '}', $css );
                // Trim the final output
                return trim( $css );
            }
        }
        new Cache_Manager();
    }