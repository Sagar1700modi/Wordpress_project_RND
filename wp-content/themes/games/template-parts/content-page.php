<?php

/**
 * Template part for displaying page content
 * Optimized & portable version
 *
 * @package Games
 */

$fields = get_fields();
$custom_excerpt =  $fields['page_top_intro_content'] ?? '';
$show_sidebar = $fields['show_sidebar'] ?? 0;
$show_breadcrumbs_page = $fields['show_breadcrumbs_page'] ?? 0;
?>

<div class="page-wrapper">
    <!-- Site Content start -->
    <div class="main-content-wrapper">
        <?php
            if (function_exists('yoast_breadcrumb') &&  $show_breadcrumbs_page) {
                yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
            }
            ?>

            <h1><?php the_title(); ?></h1>
            <?php if ($custom_excerpt) : ?>
                <div class="post-exerpt">
                    <div class="desktop-open">
                        <div class="excerpt-toggle-wrapper">
                            <div class="post-excerpt" data-full-text="<?php
                                                                        preg_match('/<p[^>]*>(.*?)<\/p>/s', $custom_excerpt, $matches);
                                                                        $first_paragraph = isset($matches[1]) ? $matches[1] : '';
                                                                        echo esc_attr(wp_strip_all_tags($first_paragraph)); ?>">
                                <?php echo wp_kses_post($custom_excerpt); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Show More End -->

            <div class="author-published-wrapper">
                <!-- Publish and updated date Start -->
                <div class="published-last-update-wrapper">
                    <div class="published-date-wrapper">
                        <span class="label">
                            <?php esc_html_e('Published on', 'games'); ?>
                        </span>
                        <span class="date">
                            <?php echo esc_html(get_the_date('F j, Y')); ?>
                        </span>
                    </div>
                    <div class="last-update-wrapper">
                        <span class="label">
                            <?php esc_html_e('Last updated on', 'games'); ?>
                        </span>
                        <span class="date">
                            <?php echo esc_html(get_the_modified_date('F j, Y')); ?>
                        </span>
                    </div>
                </div>
                <!-- Publish and updated date End -->

                <!-- Author Start -->
                <?php
                $author_id   = get_post_field('post_author', get_the_ID());
                $author_name = get_the_author_meta('display_name', $author_id);
                $author_url  = get_author_posts_url($author_id);
                $author_avatar = get_avatar_url($author_id, ['size' => 80, 'default' => 'mystery']);
                $user = get_userdata($author_id);
                $author_role = '';

                if ( $user && ! empty($user->roles) ) {
                    $author_role = ucfirst($user->roles[0]); // Author, Editor, etc.
                }
                ?>
                <div class="author-wrapper">
                    <div class="author-img">
                        <a href="<?php echo esc_url($author_url); ?>">
                            <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>">
                        </a>
                    </div>
                    <div class="author-name">
                        <a href="<?php echo esc_url($author_url); ?>" class="author-name">
                            <?php echo esc_html($author_name); ?>
                        </a>
                        <div class="author-role">
                            <?php echo $author_role; ?>
                        </div>
                    </div>
                    
                </div>
                <!-- Author End -->

                <!-- Fact Author Start -->
                <?php
                $fact_author_id = $fields['fact_author'] ?? '';
                if ($fact_author_id) :
                    $fact_author_name   = get_the_author_meta('display_name', $fact_author_id);
                    $fact_author_url    = get_author_posts_url($fact_author_id);
                    $fact_author_avatar = get_avatar_url($fact_author_id, ['size' => 80, 'default' => 'mystery']);
                ?>
                    <div class="fact-checker-wrapper">
                        <div class="fact-checker-img">
                            <a href="<?php echo esc_url($fact_author_url); ?>">
                                <img src="<?php echo esc_url($fact_author_avatar); ?>" alt="<?php echo esc_attr($fact_author_name); ?>">
                            </a>
                        </div>
                        <div class="fact-checker-name-wrapper">
                            <div class="fact-checker-name">
                                <a href="<?php echo esc_url($fact_author_url); ?>" class="author-name">
                                    <?php echo esc_html($fact_author_name); ?>
                                </a>
                            </div>
                            <div class="author-checked">
                                <span class="author-checked"><?php esc_html_e('Fact Checked', 'games'); ?><i></i></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Fact Author Start -->
            </div>
            <!-- Affiliate content start -->
            <?php
            $option_discloser = function_exists('get_field') ? get_field('affiliate_disclosure', 'option') : '';
            $affiliate_content = $fields['affiliate_disclosure_content'] ?? '';
            if (!empty($affiliate_content) || !empty($option_discloser)) : ?>
                <div class="paragraph">
                    <?php echo !empty($affiliate_content) ? wp_kses_post($affiliate_content) : wp_kses_post($option_discloser); ?>
                </div>
            <?php endif; ?>
            <!-- Affiliate content end -->
        <?php
        the_content();
        wp_link_pages([
            'before' => '<div class="page-links">',
            'after'  => '</div>',
        ]);
        ?>
    </div>
    <!-- Site Content start -->
     
    <!-- Side bar start -->
    <?php
    if ($show_sidebar) {?>
    <div class="sidebar-wrapper">
        <?php dynamic_sidebar('default-page-sidebar');?>
    </div>
    <?php    
    }
    ?>
    <!-- Side bar end -->
</div>