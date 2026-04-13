<?php
get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="page-wrapper">
            
            <?php 
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
            }
            if ( have_posts() ) : ?>

                <header class="archive-header">
                    <h1 class="archive-title">
                        <?php single_cat_title(); ?>
                    </h1>
                </header>

                <div class="category-posts">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('category-post-item'); ?>>

                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="post-date">
                                <?php echo get_the_date(); ?>
                            </div>

                        </article>

                    <?php endwhile; ?>

                </div>

                <?php the_posts_pagination(); ?>

            <?php else : ?>

                <p><?php esc_html_e( 'No posts found.', 'your-textdomain' ); ?></p>

            <?php endif; ?>
        </div>

    </div>
</main>

<?php
get_footer();