 <?php
$fields = get_fields();
$show_sidebar = get_field('show_sidebar_slots', 'option');

$slot_id = get_queried_object_id();
if (!$slot_id) {
    $slot_id = get_the_ID();
}

$content = get_post_field('post_content', $slot_id);
$first_shortcode = '';
$remaining_content = $content;

if (preg_match('/\[(\[?)([^\s\]]+)([^\]]*)\](?:([^\[]*?)\[\/\2\])?/s', $content, $matches)) {
    $first_shortcode = $matches[0];
    $remaining_content = str_replace($first_shortcode, '', $content);
}

// Basic post data
$title       = get_the_title($slot_id);

// Slot meta
$all_meta = get_post_meta($slot_id);
$slot_attrs = $all_meta['slot_attrs'][0] ?? null;
if ($slot_attrs && is_serialized($slot_attrs)) {
    $slot_attrs = unserialize($slot_attrs);
}

$min_bet    = $slot_attrs['min_bet'] ?? null;
$max_bet    = $slot_attrs['max_bet'] ?? null;
$reels      = $slot_attrs['reels'] ?? null;
$rows       = $slot_attrs['rows'] ?? null;
$paylines   = $slot_attrs['payline'] ?? null;
$rtp        = $slot_attrs['rtp'] ?? null;
$volatility = $slot_attrs['volatility'] ?? null;

// Slot rating
$rating = $all_meta['slotsl_rating'][0] ?? null;
if ($rating && is_serialized($rating)) {
    $rating = unserialize($rating);
}
$rating_value = $rating['rating'] ?? null;

/** custom post meta */
$custom_excerpt =  $fields['page_top_intro_content_slots'] ?? '';
?>
<?php
if ($first_shortcode) {
    echo do_shortcode($first_shortcode);
}
?>
<div class="page-wrapper page-wrapper-slot">
    <div class="main-content-wrapper">

    <?php
    if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
    }
    ?>
    <h1><?php echo esc_html($title); ?></h1>
    <?php if ($custom_excerpt) : ?>
        <div class="post-exerpt">
            <div class="desktop-open">
                <div class="excerpt-toggle-wrapper">
                    <!-- EXCERPT (initially collapsed) -->
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
        <!-- ===== Author & Meta ===== -->
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
        
        <!-- Fact Author Start -->
        <?php
        $fact_author_id = $fields['fact_author_slots'] ?? '';
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

        </div>        
        <!-- ===== Affiliate Disclosure (optional) ===== -->
        <?php
        $option_discloser = function_exists('get_field') ? get_field('affiliate_disclosure', 'option') : '';
        $affiliate_content = $fields['affiliate_disclosure_content'] ?? '';
        if ( !empty($affiliate_content) || !empty($option_discloser) ) : ?>
        <div class="paragraph">
            <?php echo !empty($affiliate_content) ? wp_kses_post($affiliate_content) : wp_kses_post($option_discloser); ?>
        </div>
        <?php endif; ?>

        <h2><?php esc_html_e('Slot Details', 'games'); ?></h2>

        <ul>
            <?php if ($reels || $rows): ?>
            <li><strong><?php esc_html_e('Reels × Rows:', 'games'); ?></strong> <?php echo esc_html(($reels && $rows) ? "{$reels} × {$rows}" : $reels); ?></li>
            <?php endif; ?>
            <?php if ($paylines): ?>
            <li><strong><?php esc_html_e('Paylines:', 'games'); ?></strong> <?php echo esc_html($paylines); ?></li>
            <?php endif; ?>
            <?php if ($rtp): ?>
            <li><strong><?php esc_html_e('RTP:', 'games'); ?></strong> <?php echo esc_html($rtp); ?>%</li>
            <?php endif; ?>
            <?php if ($volatility): ?>
            <li><strong><?php esc_html_e('Volatility:', 'games'); ?></strong> <?php echo esc_html($volatility); ?></li>
            <?php endif; ?>
            <?php if ($min_bet || $max_bet): ?>
            <li><strong><?php esc_html_e('Min / Max Bet:', 'games'); ?></strong><?php echo esc_html(($min_bet && $max_bet) ? "{$min_bet} / {$max_bet}" : $min_bet); ?></li>
            <?php endif; ?>
            <?php if ($rating_value): ?>
            <li><strong><?php esc_html_e('Rating:', 'games'); ?></strong> <?php echo esc_html(number_format((float)$rating_value, 1)); ?></li>
            <?php endif; ?>
        </ul>
        <?php 
        include get_template_directory() . '/blocks/game-feature-block.php';
        ?>
        <div class="site-main"><?php  echo apply_filters('the_content', $remaining_content);?></div>
    </div>
    <?php
    if ($show_sidebar) {?>
    <div class="sidebar-wrapper">
        <?php dynamic_sidebar('slot-sidebar');?>
    </div>
    <?php    
    }
    ?>

</div>


