<?php
/**
 * The template for displaying pages
 *
 * @package Courier_Pro
 */

get_header();
?>

<div class="container">
    <div class="row">
        <div class="col col-8">
            <?php
            while ( have_posts() ) {
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="card-image">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php } ?>
                    <div class="card-body">
                        <h1><?php the_title(); ?></h1>
                        <div class="page-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </article>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) {
                    comments_template();
                }
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
