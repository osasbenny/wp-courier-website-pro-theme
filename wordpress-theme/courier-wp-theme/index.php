<?php
/**
 * The main template file
 *
 * @package Courier_Pro
 */

get_header();
?>

<div class="container">
    <div class="row">
        <div class="col col-8">
            <?php
            if ( have_posts() ) {
                while ( have_posts() ) {
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
                        <div class="card-body">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="post-meta">
                                <span class="post-date">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </span>
                                <span class="post-author">
                                    <?php esc_html_e( 'by', 'courier-pro' ); ?> <?php the_author(); ?>
                                </span>
                            </div>
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                <?php esc_html_e( 'Read More', 'courier-pro' ); ?>
                            </a>
                        </div>
                    </article>
                    <?php
                }
                courier_pro_pagination();
            } else {
                ?>
                <div class="card">
                    <div class="card-body">
                        <h2><?php esc_html_e( 'Nothing Found', 'courier-pro' ); ?></h2>
                        <p><?php esc_html_e( 'Sorry, no posts found.', 'courier-pro' ); ?></p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="col col-4">
            <?php
            if ( is_active_sidebar( 'primary-sidebar' ) ) {
                dynamic_sidebar( 'primary-sidebar' );
            }
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
