<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Games
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">

    <?php
    $author_page_field = get_field('author_listing_page', 'option');

    if ( is_object( $author_page_field ) ) {
        $author_page_id = $author_page_field->ID;
    }
    elseif ( is_string( $author_page_field ) ) {
        $author_page_id = url_to_postid( $author_page_field );
    } else {
        $author_page_id = 0;
    }
    if ( $author_page_id && is_page( $author_page_id ) ) :

        include locate_template('/templates/template-author-listing.php');

    else :
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', 'page' );

            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile;

    endif;
    ?>

    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
