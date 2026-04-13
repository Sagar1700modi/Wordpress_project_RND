<?php
get_header();
?>

<main id="primary" class="site-main">

    <div class="container">
        <div class="page-wrapper">
            <?php 
            // Breadcrumbs
            if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
            }
            $page_for_posts = get_option('page_for_posts');

            if ( $page_for_posts ) :
            ?>
                <h1 class="page-title">
                    <?php echo esc_html( get_the_title( $page_for_posts ) ); ?>
                </h1>
            <?php endif;
            
            // Check if there are posts
            if ( have_posts() ) : ?>

                <div class="blog-wrapper">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part('template-parts/content', 'blog'); ?>
                    <?php endwhile; ?>
                </div> <!-- .blog-wrapper -->

                <!-- Pagination -->
                <div class="custom-pagination">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => __('« Prev', 'games'),
                        'next_text' => __('Next »', 'games'),
                    ) );
                    ?>
                </div>

            <?php else : ?>

            <?php get_template_part('template-parts/content', 'none'); ?>

            <?php endif; ?>
        </div>
    </div> <!-- .container -->
</main><!-- #primary -->

<?php
get_sidebar();
get_footer();