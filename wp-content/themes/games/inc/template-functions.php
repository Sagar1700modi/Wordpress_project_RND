<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Games
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

// Enable SVG support
function allow_svg_uploads($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_uploads');

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Site Settings',
        'menu_title' => 'Site Settings',
        'menu_slug'  => 'theme-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ));
}

/** Register sidebar for posts */
function post_custom_sidebar()
{
    register_sidebar(array(
        'name'          => __('Post Sidebar', 'games'),
        'id'            => 'post-sidebar',
        'description'   => __('Widgets in this area will be shown on posts.', 'games'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'post_custom_sidebar');

/** Register sidebar for slots */
function slot_custom_sidebar()
{
    register_sidebar(array(
        'name'          => __('Slot Sidebar', 'games'),
        'id'            => 'slot-sidebar',
        'description'   => __('Widgets in this area will be shown on slots.', 'games'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'slot_custom_sidebar');

/** Register sidebar for default page */
function default_page_custom_sidebar()
{
    register_sidebar(array(
        'name'          => __('Default Page Sidebar', 'games'),
        'id'            => 'default-page-sidebar',
        'description'   => __('Widgets in this area will be shown on default page template.', 'games'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'default_page_custom_sidebar');

function register_footer_widgets()
{

    // Existing 4 footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => __('Footer Widget ' . $i, 'games'),
            'id'            => 'footer-widget-' . $i,
            'description'   => __('Footer widget area ' . $i, 'games'),
            'before_widget' => '<div id="%1$s" class="footer-widget footer-widget-' . $i . ' %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        ));
    }

    // Copyright widget
    register_sidebar(array(
        'name'          => __('Footer Copyright', 'games'),
        'id'            => 'footer-copyright',
        'description'   => __('Copyright section in the footer', 'games'),
        'before_widget' => '<div id="%1$s" class="footer-copyright %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="screen-reader-text">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'register_footer_widgets');


class Games_Menu_Walker extends Walker_Nav_Menu
{
    // Start element
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        // Correct has_children check using property set in display_element
        $has_children = !empty($item->has_children);
        if ($has_children) {
            $classes[] = 'has-submenu';
        }

        // Add custom menu layout class
        if (!empty($item->menu_layout)) {
            $classes[] = 'menu-layout-' . esc_attr($item->menu_layout);
        } else {
            // Add submenu-open-left for top-level items with children and no layout
            if ($depth === 0 && $has_children) {
                $classes[] = 'submenu-single';
            }
        }

        $class_names = implode(' ', array_map('esc_attr', $classes));
        $output .= '<li class="' . esc_attr($class_names) . '">';

        $atts  = '';
        $atts .= !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $atts .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
        $atts .= !empty($item->xfn)        ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $atts .= !empty($item->url)        ? ' href="' . esc_url($item->url) . '"' : '';

        $title = apply_filters('the_title', $item->title, $item->ID);

        $item_output  = $args->before;
        $item_output .= '<a' . $atts . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters(
            'walker_nav_menu_start_el',
            $item_output,
            $item,
            $depth,
            $args
        );
    }

    // Start submenu
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class=\"sub-nav-menu\">\n";
        $output .= "$indent\t<button class=\"btn mobile-back-button\"><i></i> Back</button>\n";
        $output .= "$indent\t<ul class=\"sub-menu\">\n";
    }

    // End submenu
    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent\t</ul>\n";
        $output .= "$indent</div>\n";
    }

    /**
     * Override display_element so that each item knows if it has children
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
{
    $id_field = $this->db_fields['id'];

    // Set has_children property
    if (isset($children_elements[$element->$id_field]) && !empty($children_elements[$element->$id_field])) {
        $element->has_children = true;
    } else {
        $element->has_children = false;
    }

    // Continue rendering
    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
}
}


add_action('wp_update_nav_menu_item', 'games_save_menu_item_fields', 10, 3);
function games_save_menu_item_fields($menu_id, $menu_item_db_id, $args)
{

    if (isset($_POST['menu_layout'][$menu_item_db_id])) {
        update_post_meta(
            $menu_item_db_id,
            '_menu_layout',
            sanitize_text_field($_POST['menu_layout'][$menu_item_db_id])
        );
    } else {
        delete_post_meta($menu_item_db_id, '_menu_layout');
    }
}
add_filter('wp_setup_nav_menu_item', 'games_load_menu_item_fields');
function games_load_menu_item_fields($item)
{
    $item->menu_layout = get_post_meta($item->ID, '_menu_layout', true);
    return $item;
}


add_action('wp_nav_menu_item_custom_fields', 'games_add_menu_item_fields', 10, 4);
function games_add_menu_item_fields($item_id, $item, $depth, $args)
{

    $layout = get_post_meta($item_id, '_menu_layout', true);
?>
    <p class="description description-wide">
        <strong>Menu Layout</strong><br>

        <label>
            <input type="radio" name="menu_layout[<?php echo $item_id; ?>]" value="3-col"
                <?php checked($layout, '3-col'); ?>>
            3 Columns
        </label><br>

        <label>
            <input type="radio" name="menu_layout[<?php echo $item_id; ?>]" value="4-col"
                <?php checked($layout, '4-col'); ?>>
            4 Columns
        </label><br>

        <label>
            <input type="radio" name="menu_layout[<?php echo $item_id; ?>]" value="full-width"
                <?php checked($layout, 'full-width'); ?>>
            Full Width
        </label>
    </p>
    <?php
}

function ga_live_search_ajax()
{
    $keyword = sanitize_text_field($_POST['keyword']);

    $post_types = get_post_types(array(
        'public' => true,
    ));

    if (($key = array_search('attachment', $post_types)) !== false) {
        unset($post_types[$key]);
    }

    $args = array(
        'post_type'      => $post_types,
        'posts_per_page' => 10,
        's'              => $keyword,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="live-search-items">';

        foreach ($query->posts as $post) {
            $post_type  = get_post_type($post);
            $type_label = ucfirst($post_type);

            echo '<a href="' . esc_url(get_permalink($post->ID)) . '" class="live-search-item">';
            echo '<span class="live-search-title">' . do_shortcode(wp_kses_post($post->post_title)) . '</span>';
            // echo ' <span class="live-search-type">[' . esc_html($type_label) . ']</span>';
            echo '</a>';
        }

        echo '</div>';
    } else {
        echo '<div class="live-search-no-results">' . esc_html__('No results found', 'gaming-america') . '</div>';
    }

    wp_die();
}

add_action('wp_ajax_ga_live_search', 'ga_live_search_ajax');
add_action('wp_ajax_nopriv_ga_live_search', 'ga_live_search_ajax');

/**
 * Register ACF Blocks
 */
add_action('acf/init', 'games_register_acf_blocks');

function games_register_acf_blocks()
{

    // Stop if ACF not active
    if (! function_exists('acf_register_block_type')) {
        return;
    }

    /**
     * Slots Sidebar Block
     */
    acf_register_block_type([
        'name'              => 'slots-sidebar-block',
        'title'             => __('Slots Sidebar Block'),
        'description'       => __('Displays related slots in sidebar'),
        'render_template'   => get_template_directory() . '/blocks/slots-sidebar-block.php',
        'category'          => 'widgets',
        'icon'              => 'admin-sidebar',
        'keywords'          => ['slots', 'sidebar'],
        'mode'              => 'preview',
        'supports'          => [
            'align' => false,
        ],
    ]);

    /**
     * Game Feature Block
     */
    acf_register_block_type([
        'name'              => 'game-feature-block',
        'title'             => __('Game Feature Block'),
        'description'       => __('Displays featured game information'),
        'render_template'   => get_template_directory() . '/blocks/game-feature-block.php',
        'category'          => 'layout',
        'icon'              => 'star-filled',
        'keywords'          => ['game', 'feature'],
        'mode'              => 'preview',
        'supports'          => [
            'align' => false,
        ],
    ]);

    /** TOC Block */
    acf_register_block_type([
        'name'              => 'toc-block',
        'title'             => __('TOC Block'),
        'description'       => __('Displays table of contents'),
        'render_template'   => get_template_directory() . '/blocks/sidebar-toc-block.php',
        'category'          => 'layout',
        'icon'              => 'list-view',
        'keywords'          => ['toc', 'table of contents'],
        'mode'              => 'preview',
        'supports'          => [
            'align' => false,
        ],
    ]);
}


function ga_load_more_authors()
{
    //$page = intval($_POST['page']) + 1;
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $per_page = 36;
    $offset = ($page - 1) * $per_page;
    $selected_roles = get_field('author_role', 'option');

    if (empty($selected_roles) || !is_array($selected_roles)) {
        $selected_roles = ['author'];
    }

    $total_authors = count(get_users([
        'role__in' => $selected_roles,
        'fields'   => 'ID',
    ]));

    $authors = get_users([
        'role__in' => $selected_roles,
        'number'   => $per_page,
        'offset'   => $offset,
        'orderby'  => 'display_name',
        'order'    => 'ASC',
    ]);

    if (empty($authors)) {
        wp_send_json_error([
            'html' => '',
            'last_page' => true
        ]);
    }

    ob_start();

    foreach ($authors as $user) :
        $user_id = $user->ID;
        $short_designation = get_field('short_designation', 'user_' . $user_id);
    ?>
        <div class="team-card">
            <div class="avatar">
                <div class="img"><?php echo get_avatar($user_id, 100); ?></div>
            </div>
            <div class="content">
                <h3 class="name">
                    <a href="<?php echo get_author_posts_url($user_id); ?>">
                        <?php echo esc_html($user->display_name); ?>
                    </a>
                </h3>
                <p class="role"><?php echo esc_html($short_designation); ?></p>
                <p class="articles"><?php echo count_user_posts($user_id, 'news'); ?> Articles</p>
            </div>
        </div>
<?php endforeach;

    $html = ob_get_clean();

    // Check if this is the last page
    $loaded_total = $offset + count($authors);
    $last_page = ($loaded_total >= $total_authors);

    wp_send_json_success([
        'html' => $html,
        'last_page' => $last_page,
        'returned_count' => count($authors)
    ]);
}
add_action('wp_ajax_ga_load_more_authors', 'ga_load_more_authors');
add_action('wp_ajax_nopriv_ga_load_more_authors', 'ga_load_more_authors');

/** author page breadcrumb */
add_filter('wpseo_breadcrumb_links', function ($links) {

    if (is_author()) {

        $author_id   = get_queried_object_id();
        $author_name = get_the_author_meta('display_name', $author_id);

        $author_page_url = get_field('author_listing_page', 'option');
        $author_page_id  = $author_page_url ? url_to_postid($author_page_url) : '';

        /*
         * Remove all crumbs after Home
         * (this removes "Archives for admin" etc.)
         */
        $new_links = [];

        if (!empty($links)) {
            $new_links[] = $links[0]; // Keep Home only
        }

        /*
         * Add Author Listing page
         */
        if ($author_page_id) {
            $new_links[] = [
                'url'  => get_permalink($author_page_id),
                'text' => get_the_title($author_page_id),
            ];
        }

        /*
         * Add current author (last breadcrumb)
         */
        $new_links[] = [
            'url'  => '',
            'text' => $author_name,
        ];

        return $new_links;
    }

    return $links;
});

// Add this to your functions.php to avoid 404s on author pages
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_author()) {
        $query->set('post_type', array('post'));
        $query->set('posts_per_page', 5);
    }
});
