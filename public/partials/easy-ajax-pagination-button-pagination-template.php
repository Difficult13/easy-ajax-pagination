<?php

/**
 * Provide a button + pagination template for the plugin
 *
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}
?>

<div class="eap-container <?php echo esc_attr($args['class']); ?>" id="<?php echo esc_attr($args['id']); ?>">

    <?php echo wp_kses_post($args['pagination']); ?>

    <button class="eap-show-more-button">
        <span><?php echo esc_html($args['button_text']); ?></span>
        <img style="display: none" class="eap-loader" src="<?php echo esc_url($args['loader']); ?>">
    </button>
</div>