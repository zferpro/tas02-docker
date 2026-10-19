<?php 
    /**
     * File handles everything related too woocommerce compatibility
     * 
     * @package Newsmatic
     * @since 1.4.4
     */

    if( ! function_exists( 'newsmatic_woo_breadcrumb_wrapper_open' ) ) {
        /**
         * Add newsmatic container div before WooCommerce breadcrumb
         * 
         * @since 1.4.4
         */
        function newsmatic_woo_breadcrumb_wrapper_open() {
            echo '<div id="theme-content">';
            echo '<div class="newsmatic-container">';
            echo '<div class="row">';
            echo '<div class="newsmatic-breadcrumb-wrap">';
            if ( function_exists( 'woocommerce_breadcrumb' ) ) {
                woocommerce_breadcrumb();
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="site-main">';
            echo '<div class="newsmatic-container">';
            echo '<div class="row">';
            echo '<div class="primary-content">';
        }
        add_action( 'woocommerce_before_main_content', 'newsmatic_woo_breadcrumb_wrapper_open', 5 );
    }

    if( ! function_exists( 'newsmatic_woo_breadcrumb_wrapper_close' ) ) {
        /**
         * Add newsmatic container div before WooCommerce breadcrumb
         * 
         * @since 1.4.4
         */
        function newsmatic_woo_breadcrumb_wrapper_close() {
            echo '</div>';
        }
        add_action( 'woocommerce_sidebar', 'newsmatic_woo_breadcrumb_wrapper_close', 20 );
    }

    if( ! function_exists( 'newsmatic_woo_main_wrapper_close' ) ) {
        /**
         * Add newsmatic container div before WooCommerce breadcrumb
         * 
         * @since 1.4.4
         */
        function newsmatic_woo_main_wrapper_close() {
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        add_action( 'woocommerce_after_main_content', 'newsmatic_woo_main_wrapper_close', 70 );
    }

    if( ! function_exists( 'newsmatic_woo_sidebar_wrapper_open' ) ) {
        /**
         * Add newsmatic container div before sidebar
         * 
         * @since 1.4.4
         */
        function newsmatic_woo_sidebar_wrapper_open() {
            echo '</div>';
            echo '<div class="secondary-sidebar">';
        }
        add_action( 'woocommerce_after_main_content', 'newsmatic_woo_sidebar_wrapper_open', 40 );
    }

    if( ! function_exists( 'newsmatic_woo_sidebar_wrapper_close' ) ) {
        /**
         * Add newsmatic container div before sidebar
         * 
         * @since 1.4.4
         */
        function newsmatic_woo_sidebar_wrapper_close() {
            echo '</div>';
        }
        add_action( 'woocommerce_after_main_content', 'newsmatic_woo_sidebar_wrapper_close', 60 );
    }

    add_action( 'init', function() {
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 ); 
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 ); 
        add_action( 'woocommerce_after_main_content', 'woocommerce_get_sidebar', 50 ); 
    } );