<?php
/**
 * Description: Displays selected or all authors
 */

// Get option field properly
$author_page_field = get_field('author_listing_page', 'option');

if ( is_numeric( $author_page_field ) ) {
    $author_page_id = (int) $author_page_field;
} elseif ( is_string( $author_page_field ) && ! empty( $author_page_field ) ) {
    $author_page_id = url_to_postid( $author_page_field );
} else {
    $author_page_id = 0;
}

// Only run author listing if this is the selected page
if ( $author_page_id && is_page( $author_page_id ) ) :

    $author_heading     = get_field('title', 'option') ?: 'Our Authors';
    $author_description = get_field('text', 'option') ?: 'Our authors deliver informed coverage.';
    $selected_authors   = get_field('selected_authors', 'option');
    $selected_roles     = get_field('author_role', 'option');

    if ( empty($selected_roles) || ! is_array($selected_roles) ) {
        $selected_roles = ['author'];
    }
    /**
     * Get Authors
     */
    if ( ! empty($selected_authors) ) {

        $authors = get_users([
            'include' => $selected_authors,
            'orderby' => 'display_name',
            'order'   => 'ASC',
        ]);

    } else {
        $authors = get_users([
            'role__in' => $selected_roles,
            'number'   => 36,
            'orderby'  => 'display_name',
            'order'    => 'ASC',
        ]);
    }

    if ( ! empty($authors) ) :

        global $wpdb;

        $author_ids = wp_list_pluck($authors, 'ID');
        $results    = [];

        if ( ! empty($author_ids) ) {

            $author_ids = array_map('absint', $author_ids);
            $ids_string = implode(',', $author_ids);

            $results = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT post_author, COUNT(*) as total
                    FROM {$wpdb->posts}
                    WHERE post_type = %s
                    AND post_status = 'publish'
                    AND post_author IN ($ids_string)
                    GROUP BY post_author",
                    'post'
                ),
                OBJECT_K
            );
        }
?>
<div class="page-wrapper author-list-page">

    <?php if ( function_exists('yoast_breadcrumb') ) : ?>
        <?php yoast_breadcrumb('<div class="breadcrumbs">','</div>'); ?>
    <?php endif; ?>

    <div class="top-content">
        <h1><?php echo esc_html($author_heading); ?></h1>
        <p><?php echo esc_html($author_description); ?></p>
    </div>

    <div class="team-grid">

        <?php foreach ( $authors as $user ) :
            $user_id     = $user->ID;
            $designation = get_field('short_designation', 'user_' . $user_id);
            $post_count  = isset($results[$user_id]) ? $results[$user_id]->total : 0;
            $post_count = count_user_posts($user_id, 'post'); 
        ?>

        <div class="team-card">

            <div class="avatar">
                <a href="<?php echo esc_url( get_author_posts_url($user_id) ); ?>">
                    <?php echo get_avatar($user_id, 150); ?>
                </a>
            </div>

            <div class="content">
                <h3 class="name">
                    <a href="<?php echo esc_url( get_author_posts_url($user_id) ); ?>">
                        <?php echo esc_html($user->display_name); ?>
                    </a>
                </h3>

                <?php if ( $designation ) : ?>
                    <p class="role"><?php echo esc_html($designation); ?></p>
                <?php endif; ?>

                <p><?php echo esc_html($post_count . ' Articles'); ?></p>
            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <?php if ( empty($selected_authors) ) : ?>
        <div class="btn-row">
            <button class="learn-more-btn" id="load-more-authors" data-page="1">
                <?php esc_html_e( 'Load More', 'gaming-america' ); ?>
            </button>
        </div>
    <?php endif; ?>

</div>

<?php
    endif;

else :
?>
<div class="container">
    <?php
    while ( have_posts() ) :
        the_post();
        get_template_part('template-parts/content', 'page');
    endwhile;
    ?>
</div>
<?php
endif;
?>