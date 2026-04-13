<?php 
/**
 * Template Name: Slot Archive
 */
get_header();
?>
<div class="container">
    <div class="page-wrapper">
        <?php
        if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
        }
        $custom_excerpt = get_field('page_intro_content');
        ?>
        <div class="top-content text-center slot-top-text">
            <h1><?php the_title(); ?></h1>
        </div>
        <div class="slots-listing-wrapper">
            <?php the_content(); ?>
        </div>
        
    </div>
</div>
<?php
get_footer();
?>