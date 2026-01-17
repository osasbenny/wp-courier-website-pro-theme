<?php
/**
 * The header for our theme
 *
 * @package Courier_Pro
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="site-branding">
                    <?php
                    if ( has_custom_logo() ) {
                        the_custom_logo();
                    } else {
                        ?>
                        <div class="logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <nav class="site-navigation">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'fallback_cb'    => 'wp_page_menu',
                    ) );
                    ?>
                </nav>

                <div class="header-actions">
                    <a href="<?php echo esc_url( home_url( '/track' ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'Track Shipment', 'courier-pro' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main id="main" class="site-main">
