<?php
/**
 * Template part for displaying posts
 * Optimized & portable version
 *
 * @package Games
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>" class="post-thumbnail">
            <?php the_post_thumbnail('large'); ?>
        </a>
    <?php endif; ?>

    <header class="entry-header">
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h2>
    </header>

</article>