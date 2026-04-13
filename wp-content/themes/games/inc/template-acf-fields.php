<?php
/**
 * ACF Local Field Groups
 * (Field names unchanged – optimized structure only)
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( [
    'key'   => 'group_theme_options',
    'title' => 'Theme Options',

    'fields' => [
        [
            'key'       => 'tab_site_settings',
            'label'     => 'Site Settings',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'   => 'guide_block_page_affiliate_disclosure',
            'label' => 'Affiliate Disclosure Content For Slot',
            'name'  => 'affiliate_disclosure',
            'type'  => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 1,
        ],
        [
            'key'           => 'field_show_sidebar_posts',
            'label'         => 'Show Sidebar For Posts?',
            'name'          => 'show_sidebar_posts',
            'type'          => 'true_false',
            'instructions'  => 'Enable to display the sidebar on the post.',
            'default_value' => 1,
            'ui'            => 1,
        ],
        [
            'key'           => 'field_show_sidebar_slots',
            'label'         => 'Show Sidebar For Slots?',
            'name'          => 'show_sidebar_slots',
            'type'          => 'true_false',
            'instructions'  => 'Enable to display the sidebar on the Slots.',
            'default_value' => 1,
            'ui'            => 1,
        ],

        /** Author */
        [
            'key'       => 'tab_author_settings',
            'label'     => 'Author',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'   => 'field_author_page',
            'label' => 'Select Author Listing Page',
            'name'  => 'author_listing_page',
            'type'  => 'page_link',
            'post_type' => ('page'),
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'id',
        ],
        [
            'key'   => 'field_author_title',
            'label' => 'Title',
            'name'  => 'title',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_author_description',
            'label' => 'Description',
            'name'  => 'text',
            'type'  => 'textarea',
        ],
        [
            'key'=>  'field_author_posts',
            'label'=>  'Select Authors',
            'name'=>  'selected_authors',
            'type'=>  'user',
            //'role'=>  ['author', 'administrator', 'editor'], 
            'allow_null'=>  1,
            'multiple'=>  1,
            'return_format'=> 'id'
        ], 
        [
            'key'           => 'field_author_role',
            'label'         => 'Select Author Role To Show All Over The Site',
            'name'          => 'author_role',
            'type'          => 'select',       
            'choices'       => [
                'administrator' => 'Administrator',
                'editor'        => 'Editor',
                'author'        => 'Author',
                'contributor'   => 'Contributor',
                'subscriber'    => 'Subscriber',
            ],
            'multiple'      => 1,           
            'ui'            => 1,             
            'return_format' => 'value',        
            'instructions'  => 'Select the user role you want to show across the site',
        ],
    ],

    'location' => array(
        array(
            array(
                'param'    => 'options_page',
                'operator' => '==',
                'value'    => 'theme-settings', // Default ACF Options Page
            ),
        ),
    ),

    'menu_order'           => 0,
    'position'             => 'normal',
    'style'                => 'default',
    'label_placement'      => 'top',
    'instruction_placement'=> 'label',
    'active'               => true,
] );

/** Post Content acf Fields (POST) */
acf_add_local_field_group( array( 

    'key'   => 'group_post_content',
    'title' => 'Post Content',

    'fields' => array(
        array(
            'key'   => 'field_6989851eb4bb0',
            'label' => 'Page Top Intro Content',
            'name'  => 'page_top_intro_content',
            'type'  => 'wysiwyg',

            'tabs'         => 'all',
            'toolbar'      => 'full',
            'media_upload' => 1,
            'delay'        => 1,
        ),
        array(
            'key'           => 'page_fact_author',
            'label'         => 'Fact Author',
            'name'          => 'page_fact_author',
            'type'          => 'user',
            'role'          => array( 'author', 'editor', 'administrator' ),
            'allow_null'    => 1,
            'return_format' => 'id',
        ),
    ),

    'location' => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'post',
            ),
        ),
    ),

    'menu_order'          => 0,
    'position'            => 'normal',
    'style'               => 'default',
    'label_placement'     => 'top',
    'instruction_placement'=> 'label',
    'active'              => true,
) );


/** Post Content acf Fields **/
acf_add_local_field_group( array(
    'key'   => 'group_post_content_page',
    'title' => 'Page Content',

    'fields' => array(
        array(
            'key'           => 'guide_block_fact_author',
            'label'         => 'Fact Author',
            'name'          => 'fact_author',
            'type'          => 'user',
            'role'          => array( 'author', 'editor', 'administrator' ),
            'allow_null'    => 1,
            'return_format' => 'id',
        ),
        array(
            'key'   => 'field_6989851eb4bb0',
            'label' => 'Page Top Intro Content',
            'name'  => 'page_top_intro_content',
            'type'  => 'wysiwyg',
            'tabs'         => 'all',
            'toolbar'      => 'full',
            'media_upload' => 1,
            'delay'        => 1,
        ),
        array(
            'key'   => 'guide_block_page_affiliate_disclosure_content',
            'label' => 'Affiliate Disclosure Content',
            'name'  => 'affiliate_disclosure_content',
            'type'  => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 1,
        ),
        array(
            'key'           => 'field_show_sidebar',
            'label'         => 'Show Sidebar?',
            'name'          => 'show_sidebar',
            'type'          => 'true_false',
            'instructions'  => 'Enable to display the sidebar on this page.',
            'default_value' => 1,
            'ui'            => 1,
        ),
        array(
            'key'           => 'field_show_breadcrumb',
            'label'         => 'Show Breadcrumb?',
            'name'          => 'show_breadcrumbs_page',
            'type'          => 'true_false',
            'instructions'  => 'Enable to display the Breadcrumbs on this page.',
            'default_value' => 1,
            'ui'            => 1,
        ),
    ),

    'location' => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'page',
            ),
        ),
    ),

    'menu_order'          => 0,
    'position'            => 'normal',
    'style'               => 'default',
    'label_placement'     => 'top',
    'instruction_placement'=> 'label',
    'active'              => true,
) );

/** Slots acf field */
acf_add_local_field_group( array(
    'key'   => 'group_slotsl_content_page',
    'title' => 'Page Content',

    'fields' => array(
        array(
            'key'           => 'guide_block_fact_author_slot',
            'label'         => 'Fact Author',
            'name'          => 'fact_author_slots',
            'type'          => 'user',
            'role'          => array( 'author', 'editor', 'administrator' ),
            'allow_null'    => 1,
            'return_format' => 'id',
        ),
        array(
            'key'   => 'field_page_top_intro_content_slots',
            'label' => 'Page Top Intro Content',
            'name'  => 'page_top_intro_content_slots',
            'type'  => 'wysiwyg',
            'tabs'         => 'all',
            'toolbar'      => 'full',
            'media_upload' => 1,
            'delay'        => 1,
        ),
        array(
            'key'   => 'guide_block_page_affiliate_disclosure_content_slot',
            'label' => 'Affiliate Disclosure Content',
            'name'  => 'affiliate_disclosure_content_slots',
            'type'  => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 1,
        )
    ),

    'location' => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'slotsl',
            ),
        ),
    ),

    'menu_order'          => 0,
    'position'            => 'normal',
    'style'               => 'default',
    'label_placement'     => 'top',
    'instruction_placement'=> 'label',
    'active'              => true,
) );

/** Sidebr Slot block */
acf_add_local_field_group([
    'key' => 'group_slots_sidebar_block',
    'title' => 'Related Slots Sidebar',
    'fields' => [

        [
            'key' => 'field_slots_sidebar_title',
            'label' => 'Section Title',
            'name' => 'slots_sidebar_title',
            'type' => 'text',
            'instructions' => 'Enter the heading/title for this slots sidebar section',
            'required' => 1,
        ],
        [
            'key' => 'field_slots_sidebar_posts',
            'label' => 'Select Slots',
            'name' => 'slots_sidebar_posts',
            'type' => 'post_object',
            'post_type' => ['slotsl'],
            'return_format' => 'id',
            'multiple' => 1,
            'required' => 1,
        ]

    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slots-sidebar-block',
            ],
        ],
    ],
    'style' => 'default',
    'position' => 'normal',
    'hide_on_screen' => '',
]);

/** featured game block */
acf_add_local_field_group([
    'key'      => 'group_game-feature-block',
    'title'    => 'Game Feature Block',
    'category' => 'layout',
    'icon'     => 'star-filled',
    'keywords' => ['expert', 'rating', 'slots'],
    'fields'   => [
        [
            'key'       => 'block_game-feature-block',
            'label'     => 'Block: Game Feature',
            'name'      => 'game-feature-block_title',
            'type'      => 'message',
            'new_lines' => 'wpautop',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/game-feature-block',
            ],
        ],
    ],
]);

/** TOC block */
acf_add_local_field_group([
    'key'      => 'group_toc-block',
    'title'    => 'TOC Block',
    'category' => 'layout',
    'icon'     => 'star-filled',
    'keywords' => ['expert', 'rating', 'slots'],
    'fields'   => [
        [
            'key'       => 'block_toc-block',
            'label'     => 'Block: TOC',
            'name'      => 'game-feature-block_title',
            'type'      => 'message',
            'new_lines' => 'wpautop',
        ],
        [
            'key'           => 'field_toc_main_heading',
            'label'         => 'TOC Heading',
            'name'          => 'toc_main_heading',
            'type'          => 'text',
            'instructions' => 'Enter Heading to show at the top of TOC',
        ],
        [
            'key'           => 'field_toc_headings',
            'label'         => 'Headings to include',
            'name'          => 'toc_headings',
            'type'          => 'select',
            'instructions' => 'Select which heading levels should appear in the TOC.',
            'choices'       => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
            ],
            'multiple'      => 1,
            'ui'            => 1,
            'required'      => 1,
            'return_format' => 'value',
        ],
        [
            'key'           => 'field_toc_custom_classes',
            'label'         => 'Target Class Names',
            'name'          => 'toc_class_names',
            'type'          => 'text',
            'instructions' => 'Enter class names separated by commas (e.g. content, post-content, article-body).',
            'placeholder'   => 'content, post-content',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/toc-block',
            ],
        ],
    ],
]);