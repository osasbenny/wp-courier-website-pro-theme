<?php
/**
 * The template for displaying 404 pages
 *
 * @package Courier_Pro
 */

get_header();
?>

<div class="container">
    <div class="row">
        <div class="col col-8">
            <div class="card">
                <div class="card-body text-center">
                    <h1><?php esc_html_e( '404', 'courier-pro' ); ?></h1>
                    <h2><?php esc_html_e( 'Page Not Found', 'courier-pro' ); ?></h2>
                    <p><?php esc_html_e( 'Sorry, the page you are looking for does not exist.', 'courier-pro' ); ?></p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'Go Home', 'courier-pro' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
