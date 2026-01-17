<?php
/**
 * Courier Pro Theme Functions
 *
 * @package Courier_Pro
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Setup
 */
function courier_pro_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ) );

    // Register menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'courier-pro' ),
        'footer'  => esc_html__( 'Footer Menu', 'courier-pro' ),
    ) );

    // Load text domain
    load_theme_textdomain( 'courier-pro', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'courier_pro_setup' );

/**
 * Enqueue Styles and Scripts
 */
function courier_pro_enqueue_assets() {
    // Styles
    wp_enqueue_style( 'courier-pro-style', get_stylesheet_uri(), array(), '1.0.0' );
    wp_enqueue_style( 'courier-pro-responsive', get_template_directory_uri() . '/css/responsive.css', array(), '1.0.0' );

    // Scripts
    wp_enqueue_script( 'courier-pro-main', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true );
    wp_enqueue_script( 'courier-pro-tracking', get_template_directory_uri() . '/js/tracking.js', array(), '1.0.0', true );
    wp_enqueue_script( 'courier-pro-calculator', get_template_directory_uri() . '/js/calculator.js', array(), '1.0.0', true );
    wp_enqueue_script( 'courier-pro-booking', get_template_directory_uri() . '/js/booking.js', array(), '1.0.0', true );

    // Localize script
    wp_localize_script( 'courier-pro-main', 'courierData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'courier_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'courier_pro_enqueue_assets' );

/**
 * Register Widget Areas
 */
function courier_pro_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Primary Sidebar', 'courier-pro' ),
        'id'            => 'primary-sidebar',
        'description'   => esc_html__( 'Main sidebar', 'courier-pro' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area', 'courier-pro' ),
        'id'            => 'footer-widgets',
        'description'   => esc_html__( 'Footer widgets', 'courier-pro' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'courier_pro_widgets_init' );

/**
 * Custom Logo
 */
function courier_pro_custom_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $html = sprintf(
        '<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>',
        esc_url( home_url( '/' ) ),
        wp_get_attachment_image( $custom_logo_id, 'full' )
    );
    return $html;
}

/**
 * Get Custom Logo
 */
function courier_pro_get_custom_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        return wp_get_attachment_image( $custom_logo_id, 'full' );
    }
    return '<span class="logo-text">' . get_bloginfo( 'name' ) . '</span>';
}

/**
 * Excerpt Length
 */
function courier_pro_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'courier_pro_excerpt_length' );

/**
 * Excerpt More
 */
function courier_pro_excerpt_more( $more ) {
    return ' ...';
}
add_filter( 'excerpt_more', 'courier_pro_excerpt_more' );

/**
 * Add Body Classes
 */
function courier_pro_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'singular';
    }
    if ( is_home() ) {
        $classes[] = 'blog';
    }
    return $classes;
}
add_filter( 'body_class', 'courier_pro_body_classes' );

/**
 * Customize Excerpt Length
 */
function courier_pro_custom_excerpt( $length = 20 ) {
    return $length;
}

/**
 * Get Post Thumbnail
 */
function courier_pro_get_post_thumbnail( $size = 'medium' ) {
    if ( has_post_thumbnail() ) {
        return get_the_post_thumbnail( get_the_ID(), $size );
    }
    return '<img src="' . get_template_directory_uri() . '/images/placeholder.jpg" alt="' . get_the_title() . '">';
}

/**
 * Sanitize Text
 */
function courier_pro_sanitize_text( $text ) {
    return sanitize_text_field( $text );
}

/**
 * Sanitize HTML
 */
function courier_pro_sanitize_html( $html ) {
    return wp_kses_post( $html );
}

/**
 * Get Theme Option
 */
function courier_pro_get_option( $option, $default = '' ) {
    $value = get_option( 'courier_pro_' . $option, $default );
    return $value;
}

/**
 * Update Theme Option
 */
function courier_pro_update_option( $option, $value ) {
    return update_option( 'courier_pro_' . $option, $value );
}

/**
 * Include Template Parts
 */
function courier_pro_get_template_part( $slug, $name = null ) {
    $template = '';
    if ( ! empty( $name ) ) {
        $template = locate_template( array( "template-parts/{$slug}/{$name}.php" ) );
    }
    if ( empty( $template ) ) {
        $template = locate_template( array( "template-parts/{$slug}.php" ) );
    }
    if ( ! empty( $template ) ) {
        load_template( $template );
    }
}

/**
 * Pagination
 */
function courier_pro_pagination() {
    the_posts_pagination( array(
        'mid_size'           => 2,
        'prev_text'          => esc_html__( 'Previous', 'courier-pro' ),
        'next_text'          => esc_html__( 'Next', 'courier-pro' ),
        'screen_reader_text' => esc_html__( 'Posts navigation', 'courier-pro' ),
    ) );
}

/**
 * Comment Form Arguments
 */
function courier_pro_comment_form_args( $args ) {
    $args['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required="required"></textarea></p>';
    return $args;
}
add_filter( 'comment_form_defaults', 'courier_pro_comment_form_args' );

/**
 * Custom Admin CSS
 */
function courier_pro_admin_enqueue_scripts() {
    wp_enqueue_style( 'courier-pro-admin', get_template_directory_uri() . '/css/admin.css' );
}
add_action( 'admin_enqueue_scripts', 'courier_pro_admin_enqueue_scripts' );

/**
 * Include Plugin Compatibility
 */
if ( file_exists( get_template_directory() . '/inc/custom-post-types.php' ) ) {
    require_once get_template_directory() . '/inc/custom-post-types.php';
}

if ( file_exists( get_template_directory() . '/inc/custom-taxonomies.php' ) ) {
    require_once get_template_directory() . '/inc/custom-taxonomies.php';
}

if ( file_exists( get_template_directory() . '/inc/widgets.php' ) ) {
    require_once get_template_directory() . '/inc/widgets.php';
}

if ( file_exists( get_template_directory() . '/inc/template-tags.php' ) ) {
    require_once get_template_directory() . '/inc/template-tags.php';
}
