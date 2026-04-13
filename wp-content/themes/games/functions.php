<?php
/**
 * Games functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Games
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function games_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Games, use a find and replace
		* to change 'games' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'games', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'games' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'games_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'games_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function games_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'games_content_width', 640 );
}
add_action( 'after_setup_theme', 'games_content_width', 0 );


require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/template-acf-fields.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/slot-metas.php';
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';

/**
 * Enqueue scripts and styles.
 */
function games_scripts() {

    wp_enqueue_style(
        'games-style-bootstrap',
        get_template_directory_uri() . '/assets/css/bootstrap.min.css',
        array(),
        null
    );

    wp_enqueue_style(
        'games-style',
        get_template_directory_uri() . '/assets/css/style.css',
        array(),
        null
    );

    wp_enqueue_style(
        'games-responsive',
        get_template_directory_uri() . '/assets/css/responsive.css',
        array('games-style'),
        null
    );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/assets/js/custom-script.js', array('jquery'), null, true);

    wp_localize_script('custom-js', 'ga_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ga_ajax_nonce'),
    ]);

}
add_action( 'wp_enqueue_scripts', 'games_scripts' );

add_filter('file_mod_allowed', function($allowed, $context) {
  if ('download_language_pack' === $context) {
    return true;
  }
  return $allowed;
}, 10, 2);
 	
add_filter('wp_title', 'do_shortcode');
add_filter('the_title', 'do_shortcode');
add_filter('single_post_title', 'do_shortcode');
add_filter('widget_title', 'do_shortcode');
add_filter('wpseo_title', 'do_shortcode');
add_filter('wpseo_metadesc', 'do_shortcode');
add_filter('wpseo_opengraph_title', 'do_shortcode');
add_filter('wpseo_opengraph_desc', 'do_shortcode');
add_filter('wpseo_twitter_title', 'do_shortcode');
add_filter('wpseo_twitter_description', 'do_shortcode');
add_filter('the_excerpt', 'do_shortcode');
add_filter('get_the_excerpt', 'do_shortcode');

add_filter('wpseo_breadcrumb_single_link_info', function ($link_info) {
     $link_info['text'] = do_shortcode($link_info['text']);
     return $link_info;
});

function year_sc() {
     $year = strftime("%Y", date('U'));
     return $year;
}
add_shortcode('cur_year', 'year_sc');

function month_sc() {
        return date_i18n('F');
}
add_shortcode('cur_month', 'month_sc');


function games_rel_normalize_title( string $term ): string {
    $months = array(
        'january', 'february', 'march', 'april', 'may', 'june',
        'july', 'august', 'september', 'october', 'november', 'december',
    );

    $term = preg_replace( '/\b\d{4}\b/', '', $term );
    $term = preg_replace( '/\b(' . implode( '|', $months ) . ')\b/i', '', $term );
    $term = preg_replace( '/[^a-zA-Z0-9\x{00C0}-\x{024F}\s]/u', ' ', $term );
    $term = preg_replace( '/\s+/', ' ', $term );

    return trim( $term );
}

function acf_rel_title_search( array $args, array $field, int|string $post_id ): array {
    if ( empty( $args['s'] ) || ! is_string( $args['s'] ) ) {
        return $args;
    }

    global $wpdb;

    $raw_term         = sanitize_text_field( wp_unslash( $args['s'] ) );
    $raw_term         = gamingamerica_acf_rel_normalize_search_term( $raw_term );
    $post_types       = ! empty( $args['post_type'] ) ? (array) $args['post_type'] : array( 'page' );
    $valid_post_types = array_values( array_filter( $post_types, 'post_type_exists' ) );

    if ( empty( $valid_post_types ) ) {
        return $args;
    }

    $normalized       = gamingamerica_rel_normalize_title( $raw_term );
    $normalized_lower = strtolower( $normalized );
    $raw_lower        = strtolower( $raw_term );
    $words        = array_filter( explode( ' ', $normalized_lower ) );
    $first_words  = array_slice( array_values( $words ), 0, 3 );
    $like_prefilter = '%' . $wpdb->esc_like( implode( ' ', $first_words ) ) . '%';

    $type_in = implode( "','", array_map( 'esc_sql', $valid_post_types ) );

    $pages = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts}
            WHERE post_type IN ( '{$type_in}' )
            AND post_title LIKE %s
            LIMIT 500",
            $like_prefilter
        )
    );

    if ( empty( $pages ) ) {
        $pages = $wpdb->get_results(
            "SELECT ID, post_title FROM {$wpdb->posts}
            WHERE post_type IN ( '{$type_in}' )
            LIMIT 2000"
        );
    }

    $exact_ids = array();
    $norm_ids  = array();

    foreach ( $pages as $page ) {
        $db_normalized = gamingamerica_normalize_db_title( $page->post_title );

        if ( strpos( $db_normalized, $raw_lower ) !== false ) {
            $exact_ids[] = (int) $page->ID;
        } elseif ( strpos( $db_normalized, $normalized_lower ) !== false ) {
            $norm_ids[] = (int) $page->ID;
        }
    }

    $ordered_ids = array_unique( array_merge( $exact_ids, $norm_ids ) );

    if ( empty( $ordered_ids ) ) {
        return $args;
    }

    unset( $args['s'] );
    $args['post_status'] = 'any';
    $args['post__in']    = $ordered_ids;
    $args['orderby']     = 'post__in';

    return $args;
}

/**
 * User Custom Avtar
 */
add_action('show_user_profile', 'author_image_meta_box');
add_action('edit_user_profile', 'author_image_meta_box');

function author_image_meta_box($user)
{

    $image_url = get_user_meta($user->ID, 'author_image_url', true);
    $default_avatar = get_avatar_url($user->ID, ['size' => 150]);

    $preview = $image_url ? esc_url($image_url) : esc_url($default_avatar);
?>
    <h2><?php _e('Author Profile Image', 'text-domain'); ?></h2>

    <table class="form-table">
        <tr>
            <th><label><?php _e('Author Image', 'text-domain'); ?></label></th>
            <td>
                <img id="author-image-preview" src="<?php echo $preview; ?>" style="width:150px;height:150px;border-radius:50%;object-fit:cover;display:block;margin-bottom:10px;">

                <input type="hidden" name="author_image_url" id="author_image_url" value="<?php echo esc_attr($image_url); ?>">

                <button type="button" class="button" id="upload-author-image">
                    <?php _e('Upload / Select Image', 'text-domain'); ?>
                </button>

                <button type="button" class="button" id="remove-author-image">
                    <?php _e('Remove', 'text-domain'); ?>
                </button>

                <p class="description">
                    <?php _e('Image is global and will work on all sites.', 'text-domain'); ?>
                </p>
            </td>
        </tr>
    </table>
<?php
}

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'profile.php' || $hook === 'user-edit.php') {
        wp_enqueue_media();
    }
});
add_action('admin_footer', function () {
?>
    <script>
        jQuery(function($) {

            let frame;

            $('#upload-author-image').on('click', function(e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Select Author Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    $('#author_image_url').val(attachment.url);
                    $('#author-image-preview').attr('src', attachment.url);
                });

                frame.open();
            });

            $('#remove-author-image').on('click', function() {
                $('#author_image_url').val('');
                $('#author-image-preview').attr('src', '<?php echo esc_js(get_avatar_url(get_current_user_id(), ['size' => 150])); ?>');
            });
        });
    </script>
<?php
});
add_action('personal_options_update', 'save_author_image_meta');
add_action('edit_user_profile_update', 'save_author_image_meta');

function save_author_image_meta($user_id)
{

    if (!current_user_can('edit_user', $user_id)) {
        return;
    }

    if (isset($_POST['author_image_url'])) {
        update_user_meta(
            $user_id,
            'author_image_url',
            esc_url_raw($_POST['author_image_url'])
        );
    }
}

add_filter('get_avatar_url', 'use_custom_author_image_as_avatar', 10, 3);
function use_custom_author_image_as_avatar($url, $id_or_email, $args)
{

    $user = false;

    if (is_numeric($id_or_email)) {
        $user = get_user_by('id', (int) $id_or_email);
    } elseif ($id_or_email instanceof WP_User) {
        $user = $id_or_email;
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
    }
	
    if (!$user) {
        return $url;
    }

    $custom_image = get_user_meta($user->ID, 'author_image_url', true);

    if ($custom_image) {
        return esc_url($custom_image);
    }
    return $url;
}

add_action( 'tgmpa_register', 'games_register_required_plugins' );

function games_register_required_plugins() {

    $plugins = array(

        array(
            'name'      => 'Advanced Custom Fields',
            'slug'      => 'advanced-custom-fields',
            'required'  => true,
        ),

        array(
            'name'      => 'Yoast SEO',
            'slug'      => 'wordpress-seo',
            'required'  => true,
        ),

    );

    $config = array(
        'id'           => 'games',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => false,
        'is_automatic' => true,
    );

    tgmpa( $plugins, $config );

}