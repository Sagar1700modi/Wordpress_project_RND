<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id = get_queried_object_id();
if ( ! $post_id ) {
    $post_id = get_the_ID();
}

if ( ! $post_id || ! is_numeric( $post_id ) ) {
    return;
}

/**
 * Feature mapping (NORMAL post meta keys)
 */
$feature_map = [
    'Configurable Winlines' => 'configurable_winlines',
    'Free Spins'            => 'free_spins',
    'Megaways'              => 'megaways',
    'Progressive Jackpot'   => 'progressive_jackpot',
    'Bonus Features'        => 'bonus_buy',
    'Autoplay'              => 'autoplay',
    'Quickspin'             => 'quickspin',
];

$features = [];

foreach ( $feature_map as $label => $meta_key ) {

    $value = get_post_meta( $post_id, $meta_key, true );

    $features[] = [
        'title'     => $label,
        'available' => ! empty( $value ), 
    ];
}
?>


<span >
    <?php echo esc_html__( 'Game Features', 'games' ); ?>
</span>

<ul>
    <?php foreach ( $features as $feature ) : ?>
        <li>
            <span class="feature-status">
                <?php echo $feature['available'] ? 'Yes' : 'No'; ?>
            </span>
            <span class="feature-title">
                <?php echo esc_html( $feature['title'] ); ?>
            </span>
        </li>
    <?php endforeach; ?>

</ul>

