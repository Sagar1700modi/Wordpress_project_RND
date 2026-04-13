<?php
/**
 * Anywhere use theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Anywhere_use_theme
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
function anywhere_use_theme_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Anywhere use theme, use a find and replace
		* to change 'anywhere-use-theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'anywhere-use-theme', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'anywhere-use-theme' ),
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
			'anywhere_use_theme_custom_background_args',
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
add_action( 'after_setup_theme', 'anywhere_use_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function anywhere_use_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'anywhere_use_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'anywhere_use_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function anywhere_use_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'anywhere-use-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'anywhere-use-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'anywhere_use_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function anywhere_use_theme_scripts() {
	wp_enqueue_style( 'anywhere-use-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'anywhere-use-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'anywhere-use-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'anywhere_use_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * 1. Register the block and its "Attributes" (the database for your content)
 */
add_action( 'init', function() {
    register_block_type( 'custom/universal-dynamic-block', array(
        'attributes'      => array(
            'title'       => array( 'type' => 'string', 'default' => '' ),
            'description' => array( 'type' => 'string', 'default' => '' ),
            'imageUrl'    => array( 'type' => 'string', 'default' => '' ),
            'videoUrl'    => array( 'type' => 'string', 'default' => '' ),
            'linkUrl'     => array( 'type' => 'string', 'default' => '' ),
        ),
        'render_callback' => 'my_dynamic_render_callback',
    ) );
});

/**
 * 2. The Frontend Display (How it looks on your website)
 */
function my_dynamic_render_callback( $attr ) {
    ob_start(); ?>
    <div class="my-custom-block" style="border:1px solid #ddd; padding:20px; margin:20px 0;">
        <?php if ( !empty($attr['imageUrl']) ) : ?>
            <img src="<?php echo esc_url($attr['imageUrl']); ?>" style="max-width:100%;">
        <?php endif; ?>

        <?php if ( !empty($attr['title']) ) echo "<h2>" . esc_html($attr['title']) . "</h2>"; ?>
        <?php if ( !empty($attr['description']) ) echo "<p>" . esc_html($attr['description']) . "</p>"; ?>

        <?php if ( !empty($attr['videoUrl']) ) : ?>
            <video controls src="<?php echo esc_url($attr['videoUrl']); ?>" style="width:100%;"></video>
        <?php endif; ?>

        <?php if ( !empty($attr['linkUrl']) ) : ?>
            <a href="<?php echo esc_url($attr['linkUrl']); ?>" class="button">Click Here</a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * 3. Inject the Block Editor UI directly into the admin footer
 */
add_action( 'admin_footer', function() {
    ?>
    <script>
    (function(blocks, editor, components, element) {
        var el = element.createElement;
        var TextControl = components.TextControl;
        var TextareaControl = components.TextareaControl;

        blocks.registerBlockType('custom/universal-dynamic-block', {
            title: 'My Dynamic Multi-Block',
            icon: 'admin-generic',
            category: 'common',
            attributes: {
                title: { type: 'string' },
                description: { type: 'string' },
                imageUrl: { type: 'string' },
                videoUrl: { type: 'string' },
                linkUrl: { type: 'string' }
            },
            edit: function(props) {
                var attr = props.attributes;

                return el('div', { style: { padding: '20px', background: '#f9f9f9', border: '2px dashed #999' } },
                    el('h4', {}, 'Dynamic Block Editor'),
                    el(TextControl, { label: 'Title', value: attr.title, onChange: function(val) { props.setAttributes({title: val}) } }),
                    el(TextareaControl, { label: 'Description', value: attr.description, onChange: function(val) { props.setAttributes({description: val}) } }),
                    el(TextControl, { label: 'Image URL', value: attr.imageUrl, onChange: function(val) { props.setAttributes({imageUrl: val}) } }),
                    el(TextControl, { label: 'Video URL', value: attr.videoUrl, onChange: function(val) { props.setAttributes({videoUrl: val}) } }),
                    el(TextControl, { label: 'Link URL', value: attr.linkUrl, onChange: function(val) { props.setAttributes({linkUrl: val}) } })
                );
            },
            save: function() { return null; } // Dynamic blocks use the PHP callback for saving
        });
    })(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element);
    </script>
    <?php
});