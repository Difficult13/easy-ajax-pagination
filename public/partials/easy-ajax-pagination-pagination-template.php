<?php

/**
 * Provide a pagination template for the plugin
 *
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}
?>

<?php
the_posts_pagination([
    'show_all'     => $args['pagination']['show_all'],
    'end_size'     => $args['pagination']['end_size'],
    'mid_size'     => $args['pagination']['mid_size'],
    'prev_next'    => $args['pagination']['prev_next'],
    'prev_text'    => $args['pagination']['prev_text'],
    'next_text'    => $args['pagination']['next_text'],
    'add_args'     => $args['pagination']['add_args'],
    'add_fragment' => $args['pagination']['add_fragment'],
    'screen_reader_text' => $args['pagination']['screen_reader_text']
]);
?>