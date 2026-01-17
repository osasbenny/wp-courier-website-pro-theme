<?php
/**
 * The template for displaying the homepage
 *
 * @package Courier_Pro
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1><?php esc_html_e( 'Fast & Reliable Courier Services', 'courier-pro' ); ?></h1>
        <p><?php esc_html_e( 'Track your shipments in real-time and get instant quotes', 'courier-pro' ); ?></p>
        <div class="hero-buttons">
            <a href="<?php echo esc_url( home_url( '/track' ) ); ?>" class="btn btn-primary">
                <?php esc_html_e( 'Track Shipment', 'courier-pro' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url( '/booking' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'Book Now', 'courier-pro' ); ?>
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section alt">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Why Choose Us', 'courier-pro' ); ?></h2>
            <p><?php esc_html_e( 'We provide the best courier services with competitive rates', 'courier-pro' ); ?></p>
        </div>

        <div class="row">
            <div class="col col-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3>⚡ <?php esc_html_e( 'Fast Delivery', 'courier-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Express delivery options available for urgent shipments', 'courier-pro' ); ?></p>
                    </div>
                </div>
            </div>

            <div class="col col-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3>🔍 <?php esc_html_e( 'Real-time Tracking', 'courier-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Track your shipments with live updates and notifications', 'courier-pro' ); ?></p>
                    </div>
                </div>
            </div>

            <div class="col col-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3>💰 <?php esc_html_e( 'Competitive Rates', 'courier-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Get instant quotes and the best rates in the market', 'courier-pro' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Calculator Section -->
<section class="calculator-section">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Calculate Shipping Cost', 'courier-pro' ); ?></h2>
            <p><?php esc_html_e( 'Get an instant quote for your shipment', 'courier-pro' ); ?></p>
        </div>

        <div class="row">
            <div class="col col-8">
                <div class="card">
                    <div class="card-body">
                        <?php echo do_shortcode( '[courier_calculator]' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Blog Posts -->
<section class="blog-section alt">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Latest News', 'courier-pro' ); ?></h2>
            <p><?php esc_html_e( 'Stay updated with our latest news and updates', 'courier-pro' ); ?></p>
        </div>

        <div class="row">
            <?php
            $args = array(
                'posts_per_page' => 3,
                'post_type'      => 'post',
            );
            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    ?>
                    <div class="col col-4">
                        <div class="card">
                            <?php if ( has_post_thumbnail() ) { ?>
                                <div class="card-image">
                                    <?php the_post_thumbnail( 'medium' ); ?>
                                </div>
                            <?php } ?>
                            <div class="card-body">
                                <h3><?php the_title(); ?></h3>
                                <div class="post-meta">
                                    <span class="post-date"><?php echo esc_html( get_the_date() ); ?></span>
                                </div>
                                <p><?php the_excerpt(); ?></p>
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php esc_html_e( 'Read More', 'courier-pro' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            }
            ?>
        </div>
    </div>
</section>

<?php
get_footer();
