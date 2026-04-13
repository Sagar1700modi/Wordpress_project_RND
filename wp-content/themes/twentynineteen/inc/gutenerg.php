<?php

/**
 *  Custom Gutenberg functions
 */

function alecaddd_gutenberg_default_colors()

{

    add_theme_support(
         
        'editor-color-palette',

        array(
          array (
              'name' => 'White colorT',
              'slug' => 'White',
              'color' => '#fff'
          )
            
        )
       
    );

}

add_action( 'init', 'alecaddd_gutenberg_default_colors');