<?php
/**
 * The template for displaying the footer
 *
 * @package Courier_Pro
 */

?>
    </main><!-- #main -->

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php esc_html_e( 'About Us', 'courier-pro' ); ?></h3>
                    <p>
                        <?php
                        $about_text = get_option( 'courier_pro_about_text', 'Professional courier and logistics services.' );
                        echo esc_html( $about_text );
                        ?>
                    </p>
                </div>

                <div class="footer-section">
                    <h3><?php esc_html_e( 'Quick Links', 'courier-pro' ); ?></h3>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/track' ) ); ?>"><?php esc_html_e( 'Track Shipment', 'courier-pro' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/calculator' ) ); ?>"><?php esc_html_e( 'Rate Calculator', 'courier-pro' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/booking' ) ); ?>"><?php esc_html_e( 'Book Shipment', 'courier-pro' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact Us', 'courier-pro' ); ?></a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3><?php esc_html_e( 'Services', 'courier-pro' ); ?></h3>
                    <ul>
                        <li><a href="#"><?php esc_html_e( 'Express Delivery', 'courier-pro' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Standard Delivery', 'courier-pro' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'International Shipping', 'courier-pro' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Bulk Shipping', 'courier-pro' ); ?></a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3><?php esc_html_e( 'Contact Info', 'courier-pro' ); ?></h3>
                    <p>
                        <strong><?php esc_html_e( 'Email:', 'courier-pro' ); ?></strong><br>
                        <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
                            <?php echo esc_html( get_option( 'admin_email' ) ); ?>
                        </a>
                    </p>
                    <p>
                        <strong><?php esc_html_e( 'Phone:', 'courier-pro' ); ?></strong><br>
                        <?php
                        $phone = get_option( 'courier_pro_phone', '+1 (555) 123-4567' );
                        echo esc_html( $phone );
                        ?>
                    </p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'courier-pro' ); ?></p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
