<?php
add_action( 'add_meta_boxes', function () {

   add_meta_box(
        'slot_game_details',
        'Slot – Game Details',
        'slot_game_details_meta_box',
        'slotsl',
        'normal',
        'high'
    );

    add_meta_box(
        'slot_game_features',
        'Slot – Game Features',
        'slot_game_features_meta_box',
        'slotsl',
        'normal',
        'default'
    );
});

function slot_game_details_meta_box( $post ) {

    wp_nonce_field( 'save_slot_attrs', 'slot_attrs_nonce' );

    $slot_attrs = get_post_meta( $post->ID, 'slot_attrs', true );
    if ( ! is_array( $slot_attrs ) ) {
        $slot_attrs = [];
    }

    $fields = [
        'min_bet'             => 'Min Bet',
        'max_bet'             => 'Max Bet',
        'reels'               => 'Reels',
        'rows'                => 'Rows',
        'payline'             => 'Paylines',
        'rtp'                 => 'RTP (%)',
        'hit_frequency'       => 'Hit Frequency',
        'max_win_per_spin'    => 'Max Win / Spin',
        'max_win_probability' => 'Max Win Probability',
        'volatility'          => 'Volatility',
    ];

    echo '<table class="form-table">';

    foreach ( $fields as $key => $label ) {

        $value = $slot_attrs[ $key ] ?? '';

        echo '<tr>
            <th><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th>
            <td>
                <input type="text"
                       id="' . esc_attr( $key ) . '"
                       name="slot_attrs[' . esc_attr( $key ) . ']"
                       value="' . esc_attr( $value ) . '"
                       class="regular-text">
            </td>
        </tr>';
    }

    echo '</table>';
}


function slot_game_features_meta_box( $post ) {

    // List of features
    $features = [
        'configurable_winlines' => 'Configurable Winlines',
        'free_spins'            => 'Free Spins',
        'megaways'              => 'Megaways',
        'progressive_jackpot'   => 'Progressive Jackpot',
        'bonus_buy'             => 'Bonus Buy',
        'autoplay'              => 'Autoplay',
        'quickspin'             => 'Quickspin',
    ];

    echo '<table class="form-table">';

    foreach ( $features as $key => $label ) {

        // Get current saved value
        $saved_value = get_post_meta( $post->ID, $key, true );

        // If not saved yet, default to empty (so checkbox not checked)
        $checked = $saved_value === '1' ? '1' : '';

        echo '<tr>
            <th>' . esc_html( $label ) . '</th>
            <td>
                <label>
                    <input type="checkbox" name="' . esc_attr( $key ) . '" value="1" ' . checked( $checked, '1', false ) . ' />
                    Enabled
                </label>
            </td>
        </tr>';
    }

    echo '</table>';
}


add_action( 'save_post_slotsl', function ( $post_id ) {

    // Skip autosave, revision, or preview
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;
    if ( wp_is_post_autosave( $post_id ) ) return;

    // Permission check
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Nonce check
    if ( ! isset( $_POST['slot_attrs_nonce'] ) || ! wp_verify_nonce( $_POST['slot_attrs_nonce'], 'save_slot_attrs' ) ) return;

    // --------- SAVE SLOT ATTRS ---------
    if ( isset( $_POST['slot_attrs'] ) && is_array( $_POST['slot_attrs'] ) ) {
        $clean_attrs = [];
        foreach ( $_POST['slot_attrs'] as $key => $value ) {
            $value = trim( wp_unslash( $value ) );
            if ( $value !== '' ) {
                $clean_attrs[ $key ] = sanitize_text_field( $value );
            }
        }
        if ( ! empty( $clean_attrs ) ) {
            update_post_meta( $post_id, 'slot_attrs', $clean_attrs );
        }
    }

    // --------- SAVE GAME FEATURES (Checkboxes) ---------
    $features = [
        'configurable_winlines',
        'free_spins',
        'megaways',
        'progressive_jackpot',
        'bonus_buy',
        'autoplay',
        'quickspin',
    ];

    foreach ( $features as $feature ) {
        if ( isset( $_POST[ $feature ] ) ) {
            // Only update if user checked
            update_post_meta( $post_id, $feature, '1' );
        }
        // Do NOT delete meta if checkbox not present in $_POST
        // This preserves existing values in DB until user explicitly saves
    }

});





