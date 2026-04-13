<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id = get_queried_object_id() ?: get_the_ID();
if ( ! $post_id ) {
    return;
}

$fields = get_fields();
$acf_title    =$fields[ 'slots_sidebar_title' ] ?? '';
$acf_slot_ids = $fields[ 'slots_sidebar_posts' ] ?? '';

if ( ! empty( $acf_slot_ids ) && is_array( $acf_slot_ids ) ) {
    $slot_ids = $acf_slot_ids;
    $heading = $acf_title;

} else {
    $providers = wp_get_post_terms( $post_id, 'sl-provider', [
        'fields' => 'all',
    ] );

    if ( empty( $providers ) || is_wp_error( $providers ) ) {
        return;
    }

    $provider_ids  = wp_list_pluck( $providers, 'term_id' );
    $provider_name = $providers[0]->name;
    sort( $provider_ids );
    $cache_key = 'ga_provider_slots_' . implode( '_', $provider_ids );
    $slot_ids = wp_cache_get( $cache_key, 'ga_slots' );

    if ( false === $slot_ids ) {

        $query = new WP_Query( [
            'post_type'              => 'slotsl',
            'post_status'            => 'publish',
            'posts_per_page'         => 8,
            'post__not_in'           => [ $post_id ],
            'fields'                 => 'ids',
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'tax_query'              => [
                [
                    'taxonomy' => 'sl-provider',
                    'field'    => 'term_id',
                    'terms'    => $provider_ids,
                ],
            ],
        ] );

        $slot_ids = $query->posts;

        wp_cache_set( $cache_key, $slot_ids, 'ga_slots', HOUR_IN_SECONDS );
    }

    if ( empty( $slot_ids ) ) {
        return;
    }
    $heading = ! empty( $acf_title ) ? $acf_title : sprintf(
        '%s %s',
        esc_html__( 'More Free games from', 'games' ),
        esc_html( $provider_name )
    );
}
?>
<span class="slots-sidebar-heading"><?php echo esc_html( $heading ); ?></span>
<div class="slot-gallery-wrapper">
<!-- Slots Gallery -->
<?php foreach ( $slot_ids as $slot_id ) :

    $image = get_post_meta( $slot_id, 'slimg', true );
    if ( ! $image ) {
        continue;
    }
?>
    <a href="<?php echo esc_url( get_permalink( $slot_id ) ); ?>" class="slot-image-link">
        <img
            src="<?php echo esc_url( $image ); ?>"
            alt="<?php echo esc_attr( wp_basename( $image, '.' . pathinfo( $image, PATHINFO_EXTENSION ) ) ); ?>"
            loading="lazy"
        >
    </a>
<?php endforeach; ?>
</div>